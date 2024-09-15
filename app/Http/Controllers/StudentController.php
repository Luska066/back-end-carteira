<?php

namespace App\Http\Controllers;

use App\Enums\CargoUser;
use App\Enums\Steps;
use App\Models\AsaasClient;
use App\Models\RegistrationStep;
use App\Models\Servico;
use App\Models\Step;
use App\Models\StudentCurso;
use App\Models\User;
use App\Services\Asaas\Services\ServiceCreateAsaasClient;
use App\Services\ExecuteService;
use App\Services\FaculdadeAPI;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{

    public function __construct()
    {
        //        $this->authorizeResource(Student::class, 'student');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $students = Student::all();

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|unique:students,email",
            "cpf" => "required|unique:students,cpf",
            "data_nascimento" => "required",
            "image_url" => "required|file"
        ], [
            "required" => "O campo :attribute deve ser preenchido",
            "unique" => "O campo :attribute deve ser único",
            "file" => "O campo :attribute deve ser um arquivo",
        ]);
        try {
            $response_api = $this->consultarEstudanteAPI($request);
            //            DB::beginTransaction();
            if (!$response_api) {
                throw new \Exception("Erro ao consultar a API");
            }
            $user_exists = User::query()->where([
                "name" => $response_api["data"]["NomeAluno"],
                "email" => $request->email
            ])->first();
            if (!empty($user_exists)) {
                Log::info("Usuário ja cadadstrado no sistema");
                $this->criarEstudanteESeusCursos($user_exists, $request, $response_api);
            }
            if (empty($user_exists)) {
                $user_created = User::query()->create([
                    "name" => $response_api["data"]["NomeAluno"],
                    "email" => $request->email,
                    "password" => Hash::make($request->password),
                    "cargo" => CargoUser::STUDENT->value
                ]);
                if (!empty($user_created)) {
                    $this->criarEstudanteESeusCursos($user_created, $request, $response_api);
                } else {
                    Log::error("Erro ao cadastrar Estudante");
                    throw new \Exception("Erro ao cadastrar Estudante");
                }
            }
            //            DB::commit();
            return redirect()->route('students.index', [])->with('success', __('Student created successfully.'));
        } catch (\Throwable $e) {
            //            DB::rollBack();
            return redirect()->route('students.create', [])->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function criarEstudanteESeusCursos($user_created, $request, $response_api)
    {
        $student_data = [
            'name' => $user_created->name,
            'user_id' => $user_created->id,
            'email' => $user_created->email,
            'nome_completo' => $user_created->name,
            'client_id' => null,
            'carteira_id' => null,
            'image_url' => $request->file('image_url')->store('/public/images') ?? null,
            'cpf' => "" . $response_api["data"]["Matricula"],
            'matricula' => "" . $response_api["data"]["Matricula"],
            'data_nascimento' => $response_api["data"]["DataNascimento"],
        ];
        $student_verify = Student::query()->where([
            'name' => $user_created->name,
            'user_id' => $user_created->id,
        ])->first();
        if (!empty($student_verify)) {
            Log::error("Estudante ja cadastrado no sistema");
            throw new \Exception("Estudante ja cadastrado no sistema", 500);
        }
        $student = Student::query()->create($student_data);
        $cursos = $response_api["data"]["Cursos"];
        foreach ($cursos as $curso) {
            StudentCurso::query()->updateOrCreate([
                'student_id' => $student->id,
                'nomeCurso' => $curso["NomeCurso"],
            ], [
                'student_id' => $student->id,
                'nomeCurso' => $curso["NomeCurso"],
                'dataInicioCurso' => $curso["DataInicioCurso"],
                'dataFimCurso' => $curso["DataFimCurso"],
            ]);
        }
        RegistrationStep::query()->updateOrCreate([
            'id_student' => $student->id,
        ],[
            'id_step'=>Step::where('name',Steps::CADASTRO->value)->first()->id,
            'id_student' => $student->id,
        ]);

    }

    public function criarEstudanteESeusCursosAPI($user_created, $request)
    {
        try {

            $student_data = [
                'name' => $user_created->name,
                'user_id' => $user_created->id,
                'email' => $user_created->email,
                'nome_completo' => $user_created->name,
                'client_id' => null,
                'carteira_id' => null,
                'image_url' => null,
                'cpf' => "" . $request->Matricula,
                'matricula' => "" . $request->Matricula,
                'data_nascimento' => $request->DataNascimento,
            ];
            $student_verify = Student::query()->where([
                'name' => $user_created->name,
                'user_id' => $user_created->id,
            ])->first();
            if (!empty($student_verify)) {
                Log::error("Estudante ja cadastrado no sistema");
                throw new \Exception("Estudante ja cadastrado no sistema", 500);
            }
            $student = Student::query()->create($student_data);

            StudentCurso::query()->updateOrCreate([
                'student_id' => $student->id,
                'nomeCurso' => $request->NomeCurso,
            ], [
                'student_id' => $student->id,
                'nomeCurso' => $request->NomeCurso,
                'dataInicioCurso' => $request->DataInicioCurso,
                'dataFimCurso' => $request->DataFimCurso,
            ]);

            DB::commit();
            return $student;
        } catch (\Exception $e) {
            Log::info("error ao Criar Estudante e Curso", ["error" => $e->getMessage()]);

        }
    }

    public function criarUserEstudanteeContaAsaas(Request $request)
    {
        $request->validate([
            "NomeCurso" => "required",
            "DataInicioCurso" => "required",
            "Matricula" => "required",
            "DataNascimento" => "required",
            "email" => "required",
            "password" => "required|min:8",
        ]);

        DB::beginTransaction();

        try {
            $user_exists = User::query()->where([
                "name" => $request->NomeAluno,
            ])->first();
            if (!empty($user_exists)) {
                Log::info("Usuário ja cadastrado no sistema");
                RegistrationStep::query()->updateOrCreate([
                    'id_student' => $user_exists->getStudent()->id,
                ],[
                    'id_step'=>Step::where('name',Steps::CADASTRO->value)->first()->id,
                    'id_student' => $user_exists->getStudent()->id,
                ]);
                return [
                    "success" => false,
                    "redirect_url" => "/login",
                    "message" => "Usuário já cadastrado no sistema",
                ];
            }
            $user_created = User::query()->create([
                "name" => $request->NomeAluno,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "cargo" => CargoUser::STUDENT->value
            ]);
            $student = $this->criarEstudanteESeusCursosAPI($user_created, $request);
            !empty($student) ? Log::info('Estudante encontrado com sucesso!', ["estudante" => $student]) : Log::info('Não foi possível encontrar o estudante');
            $asaas_service = new ServiceCreateAsaasClient($student);
            $execute_service = new ExecuteService($asaas_service);
            $response = $execute_service->request();
            Log::info('response execute service :', [$response]);

            DB::beginTransaction();

            $asaas_client = AsaasClient::create([
                'costumer_id' => $response["id"],
                'name' => $response["name"],
                'cpfCnpj' => $response["cpfCnpj"],
                'email' => $response["email"],
                'student_id' => $student->id,
                'service_id' => $response["consult_id"]
            ]);

            $student->client_id = $asaas_client->id;
            $student->save();
            RegistrationStep::query()->updateOrCreate([
                'id_student' => $student->id,
            ],[
                'id_step'=>Step::where('name',Steps::CADASTRO->value)->first()->id,
                'id_student' => $student->id,
            ]);
            DB::commit();

            return [
                "success" => true,
                "message" => "Conta criada com sucesso!",
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info("Erro Criar Estudante API:", ["error" => $e->getMessage()]);
            return [
                "success" => false,
                "message" => "Erro ao criar conta...",
                "error" => $e->getMessage(),
            ];
        }
    }

    public function atualizarEstudanteESeusCursos($user_created, $request, $response_api)
    {
        return null;
    }

    public function consultarEstudanteAPI(Request $request)
    {
        try {
            Log::info("Consultando usuários na Faculdade", [
                "method" => __METHOD__,
                "request" => $request->all(),
            ]);
            $faculdade_api = new FaculdadeAPI(
                $request->name,
                Carbon::make($request->data_nascimento)->format('Y-m-d'),
                $request->cpf
            );
            $method = "GET";
            $response = $faculdade_api->request($method, "");
            Log::info("Resultado da consulta na Faculdade", [
                "method" => $method,
                "response" => $response,
            ]);
            $responseDecode = json_decode($response, true);
            if (!$responseDecode["success"]) {
                Log::error("Erro ao encontrar aluno!", [
                    "method" => $method,
                    "response" => $response,
                ]);
                Servico::create([
                    'url' => $faculdade_api->getBaseUri(),
                    'method' => $method,
                    'user_id' => request()->user()->id,
                    'service_type' => "App\Models\Student",
                    'service_id',
                    'payload',
                    'response' => $response,
                    'exec' => false,
                    'run_by_administrator' => true
                ]);
                throw new \Exception("Erro ao consultar a Faculdade", 404);
            }
            Log::info("Aluno encontrado com sucesso na faculdade!", [
                "method" => $method,
                "response" => $response,
            ]);
            Servico::create([
                'url' => $faculdade_api->getBaseUri(),
                'method' => $method,
                'user_id' => request()->user()->id ?? null,
                'service_type' => "App\Models\Student",
                'service_id',
                'payload',
                'response' => $response,
                'exec' => true,
                'run_by_administrator' => true
            ]);
            return json_decode($response, true);
        } catch (\Exception $exception) {

            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Student $student
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Student $student
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {

        $carteiras = \App\Models\Carteira::all();

        return view('students.edit', compact('student', 'carteiras'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {

        $path = $request->file('image')->store('public/images');
        try {

            $student->image_url = $path;
            $student->save();
            sleep(2);
            $generate = new AuthFrontEndApplicationController();
            $result = $generate->generateNewCard($student);
            return redirect()->route('students.index', [])->with('success', __('Student edited successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('students.edit', compact('student'))->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Student $student
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {

        try {
            $student->delete();

            return redirect()->route('students.index', [])->with('success', __('Student deleted successfully'));
        } catch (\Throwable $e) {
            return redirect()->route('students.index', [])->with('error', 'Cannot delete Student: ' . $e->getMessage());
        }
    }


}
