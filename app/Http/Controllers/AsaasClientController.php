<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\Asaas\Services\ServiceCreateAsaasClient;
use App\Services\ExecuteService;
use Illuminate\Http\Request;

use App\Models\AsaasClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AsaasClientController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(AsaasClient::class, 'asaasClient');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $asaasClients = AsaasClient::query();

        $asaasClients->with('asaas_cobranca');
        //        $asaasClients->with('student');

        if (!empty($request->search)) {
            $asaasClients->where('name', 'like', '%' . $request->search . '%');
        }

        $asaasClients = $asaasClients->paginate(10);

        return view('asaas_clients.index', compact('asaasClients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $asaasCobrancas = \App\Models\AsaasCobranca::all();
        $students = \App\Models\Student::where('client_id', null)->get();

        return view('asaas_clients.create', compact('asaasCobrancas', 'students'));
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
            "student_id" => "required|unique:asaas_clients,student_id",
        ]);

        try {
            $student = Student::where('id', $request->student_id)->first();
            !empty($student) ? Log::info('Estudante encontrado com sucesso!', ["estudante" => $student]) : Log::info('Não foi possível encontrar o estudante');
            $asaas_service = new ServiceCreateAsaasClient($student);
            $execute_service = new ExecuteService($asaas_service);
            $response = $execute_service->request();
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
            DB::commit();
            return redirect()->route('asaas_clients.index', [])->with('success', __('Asaas Client created successfully.'));
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('asaas_clients.create', [])->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param \App\Models\AsaasClient $asaasClient
     *
     * @return \Illuminate\Http\Response
     */
    public function show(AsaasClient $asaasClient)
    {

        return view('asaas_clients.show', compact('asaasClient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AsaasClient $asaasClient
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(AsaasClient $asaasClient)
    {

        $asaasCobrancas = \App\Models\AsaasCobranca::all();
        $students = \App\Models\Student::where('user_id', auth()->id())->get();

        return view('asaas_clients.edit', compact('asaasClient', 'asaas_cobrancas', 'students'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AsaasClient $asaasClient)
    {

        $request->validate(["costumer_id" => "required", "name" => "required", "cpfCnpj" => "required", "email" => "required", "student_id" => "required|unique:asaas_clients,student_id,$asaasClient->id", "service_id" => "required"]);

        try {
            $asaasClient->asaas_cobranca_id = $request->asaas_cobranca_id;
            $asaasClient->costumer_id = $request->costumer_id;
            $asaasClient->name = $request->name;
            $asaasClient->cpfCnpj = $request->cpfCnpj;
            $asaasClient->email = $request->email;
            $asaasClient->student_id = $request->student_id;
            $asaasClient->service_id = $request->service_id;
            $asaasClient->save();

            return redirect()->route('asaas_clients.index', [])->with('success', __('Asaas Client edited successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('asaas_clients.edit', compact('asaasClient'))->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AsaasClient $asaasClient
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AsaasClient $asaasClient)
    {

        try {
            $asaasClient->delete();

            return redirect()->route('asaas_clients.index', [])->with('success', __('Asaas Client deleted successfully'));
        } catch (\Throwable $e) {
            return redirect()->route('asaas_clients.index', [])->with('error', 'Cannot delete Asaas Client: ' . $e->getMessage());
        }
    }


}
