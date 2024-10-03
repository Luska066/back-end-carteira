<?php

namespace App\Http\Controllers;

use App\Enums\Steps;
use App\Helpers\StringUtils;
use App\Models\AsaasCobranca;
use App\Models\Carteira;
use App\Models\RegistrationStep;
use App\Models\Step;
use App\Models\Student;
use App\Models\StudentCurso;
use App\Models\User;
use App\Services\Asaas\EnumObjectAsaas\BillingType;
use App\Services\Asaas\Services\ServiceConsultQrCodeCharge;
use App\Services\Asaas\Services\ServiceCreateChargesAsaasClient;
use App\Services\ExecuteService;
use Carbon\Carbon;
use http\Env;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Termwind\renderUsing;

class AuthFrontEndApplicationController extends Controller
{
    public function preLoginAction(Request $request)
    {

        try {
            $request->validate([
                "cpf" => "required",
                "data_nascimento" => "required",
            ], [
                "required" => "O campo :attribute deve ser preenchido",
            ]);

            if (!empty(Student::where([
                "cpf" => $request->cpf,
                "data_nascimento" => $request->data_nascimento
            ])->first())) {
                return [
                    "success" => true,
                    "message" => "Estudante encontrado!"
                ];
            }
            $studentController = new StudentController();
            $response_api = $studentController->consultarEstudanteAPI($request);
            return [
                "success" => true,
                "data" => $response_api["data"]
            ];
        } catch (\Exception $e) {
            return [
                "success" => false,
                "message" => "Erro Desconhecido!"
            ];
        }
    }

    public function verify(Request $request)
    {
        return [
            "success" => auth()->check(),
        ];
    }

    public function getSteps(Request $request)
    {
        try {
            $registration_step = RegistrationStep::where('id_student', $request->user()->getStudent()->id)->orderBy('id_step', 'desc')->first();

            $step = Step::query()->where('id', '>', $registration_step->id_step)->orderBy('id', 'asc')->first();

            return [
                "success" => true,
                "step" => $step,
            ];
        } catch (\Exception $exception) {

            return [
                "success" => false,
                "message" => "Ocorreu um erro interno."
            ];
        }
    }


    public function getStudentUuid(Request $request){
        return $request->user()->getStudent()->carteira()->first()->uuid;
    }

    public function getCard(Request $request){
        return [
            "student" => $request->user()->getStudent(),
            "card" => $request->user()->getStudent()->carteira()->first()
        ];
    }

    public function getStepsByUser(User $user)
    {
        try {
            $registration_step = RegistrationStep::where('id_student', $user->getStudent()->id)->get();

            $step = Step::query()->whereNotIn('id', $registration_step->pluck('id_step'))->orderBy('id', 'asc')->first();

            return [
                "success" => true,
                "step" => $step
            ];
        } catch (\Exception $exception) {
            return [
                "success" => false,
                "message" => "Ocorreu um erro interno."
            ];
        }
    }

    public function updateImage(Request $request)
    {
        try {
            $path = $request->file('image_perfil')->store('public/images');
            $user = $request->user()->getStudent();
            if (!empty($user->image_url)) {
                Storage::delete($user->image_url);
                $user->image_url = null;
                $user->save();
                Log::info("Imagem deletada com sucesso");
            }
            $user->image_url = $path;
            $user->save();
            RegistrationStep::query()->updateOrCreate([
                'id_student' => $request->user()->getStudent()->id,
                'id_step' => Step::where('name', Steps::FOTO->value)->first()->id,
            ], [
                'id_step' => Step::where('name', Steps::FOTO->value)->first()->id,
                'id_student' => $user->id,
            ]);
            return [
                "success" => true,
                "data" => $this->getSteps($request)
            ];
        } catch (\Exception $exception) {
            return [
                "success" => false,
                "message" => "Ocorreu um erro ao salvar a imagem."
            ];
        }
    }

    public function createCard(Request $request)
    {

        DB::beginTransaction();
        $student = $request->user()->getStudent();
        if($student == null){
            throw new \Exception("Estudante não encontrado");
        }
        Log::info('student',[$student]);
        $course = StudentCurso::where('student_id', $student->id)->first();
        Log::info('course',[$course]);
        try {
            $carteira = Carteira::query()->updateOrCreate([
                'student_id' => $student->id,
                'matricula' => $student->matricula,
                'nome_curso' => $course->nomeCurso,
            ], [
                "uuid" => Str::uuid(),
                'student_id' => $student->id,
                'nomeAluno' => $student->name,
                'matricula' => $student->matricula,
                'passCode' => Str::random(6),
                'data_nascimento' => $student->data_nascimento,
                'nome_curso' => $course->nomeCurso,
                'dataInicioCurso' => $course->dataInicioCurso,
                'dataFimCurso' => $course->dataFimCurso,
            ]);
            Log::info("carteira");
            $asaas_cobranca = AsaasCobranca::where([
                'asaas_client_id' => $student->asaas_client->id,
                'billingType' => BillingType::PIX->value,
            ])->get();
            if ($asaas_cobranca->count() > 0) {
                RegistrationStep::query()->updateOrCreate([
                    'id_student' => $student->id,
                    'id_step' => Step::where('name', Steps::PAGAMENTO->value)->first()->id,
                ], [
                    'id_step' => Step::where('name', Steps::PAGAMENTO->value)->first()->id,
                    'id_student' => $student->id,
                ]);
                Log::info("Cobrança já existe", [
                    "student" => $student,
                    "course" => $course,
                    "asaas_cobranca" => $asaas_cobranca
                ]);
                return [
                    "success" => true,
                    "message" => 301,
                    "data" => $this->getSteps($request)
                ];
            }
            $service_create_charges_asaas_client = new ServiceCreateChargesAsaasClient($student, $carteira->id);
            $execute_service = new ExecuteService($service_create_charges_asaas_client);
            $response = $execute_service->request();
            $asaas_cobranca = AsaasCobranca::create([
                "id_charge" => $response["id"],
                'asaas_client_id' => $student->asaas_client->id,
                'billingType' => BillingType::PIX->value,
                'value' => ENV('VALUE_CARD'),
                'dueDate' => $response["dueDate"],
                'paymentLink' => $response["paymentLink"],
                'status' => $response["status"],
                'invoiceUrl' => $response["invoiceUrl"],
                'paymentDate' => $response["paymentDate"],
                'service_id' => $response["consult_id"],
            ]);
            $asaas_client = $student->asaas_client()->first();
            $asaas_client->asaas_cobranca_id = $asaas_cobranca->id;
            $asaas_client->save();
            $student->carteira_id = $carteira->id;
            $carteira->asaas_cobranca_id = $asaas_cobranca->id;
            $carteira->save();
            $student->save();
            RegistrationStep::query()->updateOrCreate([
                'id_student' => $student->id,
                'id_step' => Step::where('name', Steps::PAGAMENTO->value)->first()->id,
            ], [
                'id_step' => Step::where('name', Steps::PAGAMENTO->value)->first()->id,
                'id_student' => $student->id,
            ]);
            $student->carteira_id = $response['externalReference'] ? DB::commit() : DB::rollBack();

            DB::commit();
            Log::info("Carteira criada com sucesso", [
                "student" => $student,
                "course" => $course,
                "carteira" => $carteira,
                "asaas_cobranca" => $asaas_cobranca
            ]);
            return [
                "success" => true,
                "message" => "Carteira criada com sucesso!",
                "data" => $this->getSteps($request)
            ];
        } catch (\Throwable $e) {
            DB::rollback();
            Log::info("Erro ao criar carteira", [
                "error" => $e->getMessage(),
                "line" => $e->getLine(),
                "file" => $e->getFile(),
            ]);
            return [
                "success" => false,
                "message" => "Erro ao Criar Carteira"
            ];
        }
    }

    public function consultQrCode(Request $request)
    {
        $asaas_charge = $request->user()->getStudent()->asaas_client()->first()->asaas_cobranca()->first();
        $service_consult_qr_code = new ServiceConsultQrCodeCharge($asaas_charge);
        $execute_service = new ExecuteService($service_consult_qr_code);
        $response = $execute_service->request();
        if ($response == false) {
            throw new \Exception('Erro ao consultar QrCode');
        } else if ($response["success"] === true) {
            return [
                "success" => true,
                "data" => $response["encodedImage"]
            ];
        }
    }

    public function websocketGetIdentify(Request $request)
    {
        return $request->user()->id;
    }

    public function jaExecuteiPagamento(Request $request)
    {
        try {
            Log::info("Etapa Registrada com sucesso");
            return [
                "success" => true,
                "message" => "Etapa concluída com sucesso.",
                "data" => $this->getSteps($request)
            ];
        } catch (\Exception $e) {
            Log::info('Erro ao tentar marcar que executou o pagemento', [
                "user" => $request->user(),
                "exception" => $e->getMessage()
            ]);
            return [
                "success" => false,
                "message" => "Ocorreu um erro ao marcar a etapa como concluída."
            ];
        }
    }

    public function downloadCard(Request $request)
    {
        try {
            $student = $request->user()->getStudent();

            $card = $student->carteira()->first();
            $card->expiredAt = now()->addYear();
            $card->save();

            $filePath = Storage::path($student->image_url);
            $filename = $student->cpf . '.pdf';
            $path = '/carteira_pdf/' . $filename;


            $fields = [
                'nome' => $student->name,
                'cpf' => StringUtils::formatCpf($student->cpf),
                'matricula' => StringUtils::formatCpf($student->cpf),
                'data_nascimento' => Carbon::make($student->data_nascimento)->format('Y-m-d'),
                'curso' => $card->nome_curso,
                'data_inicio' => Carbon::make($card->dataInicioCurso)->format('Y-m-d'),
                'validade' => now()->addYear()->format('Y-m-d'),
                'hash_carteirinha' => $card->uuid,
                'link_carteirinha' => Env('URL_FRONT_END_USER', 'http://localhost:5173/student-id/validate/details/') . $card->uuid,
                'senha_carteirinha' => $card->passCode,
            ];
            //
            if (!empty($card->carteiraPdfUrl)) {
                Log::info("Carteira PDF já existe.");
                return [
                    "success" => true,
                    "message" => "Carteira PDF já existe.",
                    "data" => Env('APP_URL') . Storage::url($path)
                ];
            }

            // Lembre-se de substituir 'http://localhost:1458/gerar-carteira' pelo endereço do seu backend
            $response = Http::asMultipart()->attach(
                'foto',             // Nome do campo do arquivo
                file_get_contents($filePath), // Conteúdo do arquivo
                basename($filePath)  // Nome original do arquivo
            )->post(Env('URL_MICROSSERVICE').'/gerar-carteira', $fields);


            Storage::delete($path);
            // Salve o conteúdo do PDF no Storage
            Storage::put('public/' . $path, $response->body());

            $card->carteiraPdfUrl = '/storage' . $path;
            $card->save();
            RegistrationStep::query()->updateOrCreate([
                'id_student' => $student->id,
                'id_step'=>Step::where('name',Steps::AGUARDANDOPAGAMENTO->value)->first()->id,
            ],[
                'id_step'=>Step::where('name',Steps::AGUARDANDOPAGAMENTO->value)->first()->id,
                'id_student' => $student->id,
            ]);

            // Retorne a URL do arquivo salvo
            return [
                "success" => true,
                "message" => "Carteira gerada com sucesso.",
                "data" => Env('APP_URL') . Storage::url($path)
            ];

        } catch (\Exception $exc) {
            Log::info('Erro ao tentar gerar carteira', [
                "user" => $request->user(),
                "exception" => $exc->getMessage()
            ]);
            return [
                "success" => false,
                "message" => "Ocorreu um erro ao gerar a carteira."
            ];
        }
    }

    public function masterGenerateCard(Request $request,Student $student)
    {
        try {
            $card = $student->carteira()->first();
            $card->expiredAt = $card->expiredAt != null ? now()->addYear() : $card->expiredAt;
            $card->save();

            $filePath = Storage::path($student->image_url);
            $filename = $student->cpf . '.pdf';
            $path = '/carteira_pdf/' . $filename;


            $fields = [
                'nome' => $student->name,
                'cpf' => StringUtils::formatCpf($student->cpf),
                'matricula' => StringUtils::formatCpf($student->cpf),
                'data_nascimento' => Carbon::make($student->data_nascimento)->format('Y-m-d'),
                'curso' => $card->nome_curso,
                'data_inicio' => Carbon::make($card->dataInicioCurso)->format('Y-m-d'),
                'validade' => now()->addYear()->format('Y-m-d'),
                'hash_carteirinha' => $card->uuid,
                'link_carteirinha' => Env('URL_FRONT_END_USER', 'http://localhost:5173/student-id/validate/details/') . $card->uuid,
                'senha_carteirinha' => $card->passCode,
            ];
            //

            // Lembre-se de substituir 'http://localhost:1458/gerar-carteira' pelo endereço do seu backend
            $response = Http::asMultipart()->attach(
                'foto',             // Nome do campo do arquivo
                file_get_contents($filePath), // Conteúdo do arquivo
                basename($filePath)  // Nome original do arquivo
            )->post('http://localhost:1458/gerar-carteira', $fields);


            Storage::delete($path);
            // Salve o conteúdo do PDF no Storage
            Storage::put('public/' . $path, $response->body());

            $card->carteiraPdfUrl = '/storage' . $path;
            $card->save();
            RegistrationStep::query()->updateOrCreate([
                'id_student' => $student->id,
                'id_step'=>Step::where('name',Steps::AGUARDANDOPAGAMENTO->value)->first()->id,
            ],[
                'id_step'=>Step::where('name',Steps::AGUARDANDOPAGAMENTO->value)->first()->id,
                'id_student' => $student->id,
            ]);
            return redirect()->back()->with('success' , 'Carteira gerada com sucesso!');
        }catch (\Exception $exc){
            dd($exc->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erro ao gerar carteira']);
        }
    }


    public function generateNewCard(Student $student)
    {
        try {
            $student = $student;

            $card = $student->carteira()->first();
            $card->expiredAt = now()->addYear();
            $card->save();

            $filePath = Storage::path($student->image_url);
            $filename = $student->cpf . '.pdf';
            $path = '/carteira_pdf/' . $filename;


            $fields = [
                'nome' => $student->name,
                'cpf' => StringUtils::formatCpf($student->cpf),
                'matricula' => StringUtils::formatCpf($student->cpf),
                'data_nascimento' => Carbon::make($student->data_nascimento)->format('Y-m-d'),
                'curso' => $card->nome_curso,
                'data_inicio' => Carbon::make($card->dataInicioCurso)->format('Y-m-d'),
                'validade' => now()->addYear()->format('Y-m-d'),
                'hash_carteirinha' => $card->uuid,
                'link_carteirinha' => Env('URL_FRONT_END_USER', 'http://localhost:5173/student-id/validate/details/') . $card->uuid,
                'senha_carteirinha' => $card->passCode,
            ];
            //

            // Lembre-se de substituir 'http://localhost:1458/gerar-carteira' pelo endereço do seu backend
            $response = Http::asMultipart()->attach(
                'foto',             // Nome do campo do arquivo
                file_get_contents($filePath), // Conteúdo do arquivo
                basename($filePath)  // Nome original do arquivo
            )->post('http://localhost:1458/gerar-carteira', $fields);


            Storage::delete($path);
            // Salve o conteúdo do PDF no Storage
            Storage::put('public/' . $path, $response->body());

            $card->carteiraPdfUrl = '/storage' . $path;
            $card->save();
            RegistrationStep::query()->updateOrCreate([
                'id_student' => $student->id,
                'id_step'=>Step::where('name',Steps::AGUARDANDOPAGAMENTO->value)->first()->id,
            ],[
                'id_step'=>Step::where('name',Steps::AGUARDANDOPAGAMENTO->value)->first()->id,
                'id_student' => $student->id,
            ]);

            // Retorne a URL do arquivo salvo
            return [
                "success" => true,
                "message" => "Carteira gerada com sucesso.",
                "data" => Env('APP_URL') . Storage::url($path)
            ];

        } catch (\Exception $exc) {
            Log::info('Erro ao tentar gerar carteira', [
                "user" => auth()->user(),
                "exception" => $exc->getMessage()
            ]);
            return [
                "success" => false,
                "message" => "Ocorreu um erro ao gerar a carteira."
            ];
        }
    }

    public function downloadArchivePdf(Request $request)
    {
        try {
            $student = $request->user()->getStudent();

            $card = $student->carteira()->first();
            $card->expiredAt = now()->addYear();
            $card->save();

            $filePath = Storage::path($student->image_url);
            $filename = $student->cpf . '.pdf';
            $path = 'storage/carteira_pdf/' . $filename;

            return response()->download($path);

        } catch (\Exception $exc) {
            Log::info('Erro ao tentar gerar carteira', [
                "user" => $request->user(),
                "exception" => $exc->getMessage()
            ]);
            return [
                "success" => false,
                "message" => "Ocorreu um erro ao gerar a carteira."
            ];
        }
    }

}
