<?php

namespace App\Http\Controllers;

use App\Enums\CargoUser;
use App\Models\Servico;
use App\Models\StudentCurso;
use App\Models\User;
use App\Services\ExecuteService;
use App\Services\FaculdadeAPI;
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
                $this->criarEstudanteESeusCursos($user_exists,$request,$response_api);
            }
            if (empty($user_exists)) {
                $user_created = User::query()->create([
                    "name" => $response_api["data"]["NomeAluno"],
                    "email" => $request->email,
                    "password" => Hash::make($request->password),
                    "cargo" => CargoUser::STUDENT->value
                ]);
                if (!empty($user_created)) {
                   $this->criarEstudanteESeusCursos($user_created,$request,$response_api);
                }else{
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

    public function criarEstudanteESeusCursos($user_created,$request,$response_api){
        $student_data = [
            'name' => $user_created->name,
            'user_id' => $user_created->id,
            'email' => $user_created->email,
            'nome_completo' => $user_created->name,
            'client_id' => null,
            'carteira_id' => null,
            'image_url' => $request->file('image_url')->store('/public/images'),
            'cpf' => "".$response_api["data"]["Matricula"],
            'matricula' => "".$response_api["data"]["Matricula"],
            'data_nascimento' => $response_api["data"]["DataNascimento"],
        ];
        $student_verify = Student::query()->where([
            'name' => $user_created->name,
            'user_id' => $user_created->id,
        ])->first();
        if(!empty($student_verify)){
            Log::error("Estudante ja cadastrado no sistema");
            throw new \Exception("Estudante ja cadastrado no sistema",500);
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
    }

    public function atualizarEstudanteESeusCursos($user_created,$request,$response_api){
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
                "PAMELA ALINE DA SILVA SOUZA",
                "1987-10-18",
                "35862893830"
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
                'user_id' => request()->user()->id,
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

        $request->validate(["name" => "required", "email" => "required|unique:students,email,$student->id", "nome_completo" => "required", "carteira_id" => "required"]);

        try {
            $student->name = $request->name;
            $student->email = $request->email;
            $student->nome_completo = $request->nome_completo;
            $student->client_id = $request->client_id;
            $student->carteira_id = $request->carteira_id;
            $student->image_url = $request->image_url;
            $student->cpf = $request->cpf;
            $student->matricula = $request->matricula;
            $student->save();

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
