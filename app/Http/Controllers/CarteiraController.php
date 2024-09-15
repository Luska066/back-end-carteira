<?php

namespace App\Http\Controllers;

use App\Enums\Steps;
use App\Models\AsaasCobranca;
use App\Models\RegistrationStep;
use App\Models\Step;
use App\Models\Student;
use App\Models\StudentCurso;
use App\Services\Asaas\EnumObjectAsaas\BillingType;
use App\Services\Asaas\Services\ServiceCreateChargesAsaasClient;
use App\Services\ExecuteService;
use Illuminate\Http\Request;

use App\Models\Carteira;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CarteiraController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Carteira::class, 'carteira');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $carteiras = Carteira::query();


        if (!empty($request->search)) {
            $carteiras->where('nomeAluno', 'like', '%' . $request->search . '%');
        }

        $carteiras = $carteiras->paginate(10);

        return view('carteiras.index', compact('carteiras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $students = \App\Models\Student::where('carteira_id',null)->get();

        return view('carteiras.create', compact('students'));
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
            "student_id" => "required",
            "course_id" => "required",
        ]);
        DB::beginTransaction();
        $student = Student::find($request->student_id);
        $course = StudentCurso::find($request->course_id);

        try {
            $carteira = Carteira::query()->updateOrCreate([
                'student_id' => $student->id,
                'nomeAluno' => $student->name,
                'matricula' => $student->matricula,
                'data_nascimento' => $student->data_nascimento,
                'nome_curso' => $course->nomeCurso,
                'dataInicioCurso' => $course->dataInicioCurso,
            ],[
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
            $student->carteira_id = $carteira->id ;
            $carteira->asaas_cobranca_id = $asaas_cobranca->id;
            $carteira->save();
            $student->save();
            RegistrationStep::query()->updateOrCreate([
                'id_student' => $student->id,
            ],[
                'id_step'=>Step::where('name',Steps::FOTO->value)->first()->id,
                'id_student' => $student->id,
            ]);

            $student->carteira_id = $response['externalReference']? DB::commit(): DB::rollBack();
            DB::commit();
            return redirect()->route('carteiras.index', [])->with('success', __('Carteira created successfully.'));
        } catch (\Throwable $e) {
            DB::rollback();

            return redirect()->route('carteiras.create', [])->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Carteira $carteira
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Carteira $carteira)
    {

        return view('carteiras.show', compact('carteira'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Carteira $carteira
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Carteira $carteira)
    {

        $students = \App\Models\Student::where('user_id', auth()->id())->get();

        return view('carteiras.edit', compact('carteira', 'students'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Carteira $carteira)
    {

        $request->validate(["student_id" => "required", "nomeAluno" => "required", "matricula" => "required", "passCode" => "required", "data_nascimento" => "required", "nome_curso" => "required"]);

        try {
            $carteira->student_id = $request->student_id;
            $carteira->nomeAluno = $request->nomeAluno;
            $carteira->matricula = $request->matricula;
            $carteira->passCode = $request->passCode;
            $carteira->data_nascimento = $request->data_nascimento;
            $carteira->nome_curso = $request->nome_curso;
            $carteira->dataInicioCurso = $request->dataInicioCurso;
            $carteira->dataFimCurso = $request->dataFimCurso;
            $carteira->carteiraPdfUrl = $request->carteiraPdfUrl;
            $carteira->expiredAt = $request->expiredAt;
            $carteira->save();

            return redirect()->route('carteiras.index', [])->with('success', __('Carteira edited successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('carteiras.edit', compact('carteira'))->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Carteira $carteira
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Carteira $carteira)
    {

        try {
            $carteira->delete();

            return redirect()->route('carteiras.index', [])->with('success', __('Carteira deleted successfully'));
        } catch (\Throwable $e) {
            return redirect()->route('carteiras.index', [])->with('error', 'Cannot delete Carteira: ' . $e->getMessage());
        }
    }


}
