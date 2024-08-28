<?php

namespace App\Http\Controllers;

use App\Services\Asaas\EnumObjectAsaas\BillingType;
use App\Services\Asaas\Services\ServiceConsultChargeAsaas;
use App\Services\Asaas\Services\ServiceCreateChargesAsaasClient;
use App\Services\ExecuteService;
use Illuminate\Http\Request;

use App\Models\AsaasCobranca;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AsaasCobrancaController extends Controller
{

    public function __construct()
    {
        //		$this->authorizeResource(AsaasCobranca::class, 'asaasCobranca');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $asaasCobrancas = AsaasCobranca::query();

        $asaasCobrancas->with('asaas_client');

        if (!empty($request->search)) {
            $asaasCobrancas->where('customer', 'like', '%' . $request->search . '%');
        }

        $asaasCobrancas = $asaasCobrancas->paginate(10);

        return view('asaas_cobrancas.index', compact('asaasCobrancas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $asaasClients = \App\Models\AsaasClient::all();

        return view('asaas_cobrancas.create', compact('asaas_clients'));
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

        $request->validate(["asaas_client_id" => "required", "customer" => "required", "billingType" => "required", "value" => "required", "dueDate" => "required", "status" => "required", "invoiceUrl" => "required", "service_id" => "required"]);

        try {

            $asaasCobranca = new AsaasCobranca();
            $asaasCobranca->asaas_client_id = $request->asaas_client_id;
            $asaasCobranca->customer = $request->customer;
            $asaasCobranca->billingType = $request->billingType;
            $asaasCobranca->value = $request->value;
            $asaasCobranca->dueDate = $request->dueDate;
            $asaasCobranca->paymentLink = $request->paymentLink;
            $asaasCobranca->status = $request->status;
            $asaasCobranca->invoiceUrl = $request->invoiceUrl;
            $asaasCobranca->paymentDate = $request->paymentDate;
            $asaasCobranca->service_id = $request->service_id;
            $asaasCobranca->save();

            return redirect()->route('asaas_cobrancas.index', [])->with('success', __('Asaas Cobranca created successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('asaas_cobrancas.create', [])->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AsaasCobranca $asaasCobranca
     *
     * @return \Illuminate\Http\Response
     */
    public function show(AsaasCobranca $asaasCobranca)
    {

        return view('asaas_cobrancas.show', compact('asaasCobranca'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AsaasCobranca $asaasCobranca
     *
     * @return \Illuminate\Http\Response
     */
    public function asaasCobrancaConsultServiceAsaas(Request $request, AsaasCobranca $cobranca)
    {
        DB::beginTransaction();
        try {
            if ($cobranca->dueDate < now()) {
                $student = $cobranca->asaas_client()->first();
                $student = $student->student()->first();
                $carteira = $student->carteira()->first();
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
                $student->asaas_client()->first()->asaas_cobranca_id = $asaas_cobranca->id;
                $student->asaas_cobranca_id = $asaas_cobranca->id;
                $carteira->asaas_cobranca_id = $asaas_cobranca->id;
                $carteira->status = "ativo";
                $carteira->expiredAt = now()->addMonth();
                $carteira->save();
                $student->save();
                $student->carteira_id = $response['externalReference']? DB::commit(): DB::rollBack();
            } else {
                $result_atualizacao_cobranca = $this->atualizarCobrancaAsaas($cobranca);
                if (!$result_atualizacao_cobranca) {
                    throw new \Exception("Erro ao Atualizar Cobrança");
                }
                Log::info("Cobrança atualizada com sucesso");
                return [
                    "success" => true,
                    "message" => "Cobrança atualizada com sucesso"
                ];
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }


    }

    private function atualizarCobrancaAsaas(AsaasCobranca $cobranca){
        try{
            $student = $cobranca->asaas_client()->first();
            $student = $student->student()->first();
            $carteira = $student->carteira()->first();
            $service_consult_charge_asaas = new ServiceConsultChargeAsaas($cobranca);
            Log::info("Cobrança a ser atualizada",[
                "cobranca" => $cobranca
            ]);
            $execute_service = new ExecuteService($service_consult_charge_asaas);

            $response = $execute_service->request();
            Log::info("ExecuteService Result",[
            "response" => $response
            ]);
            $asaas_cobranca = AsaasCobranca::where("id_charge", $response["id"])->first();
            if(empty($asaas_cobranca)){
                Log::info("Cobrança Não encontrada",[]);
                throw new \Exception("Cobrança não encontrada");
            }
            Log::info("Cobrança Encontrada",[
                "response" => $asaas_cobranca
            ]);
            if (
                $asaas_cobranca->id === $cobranca->id &&
                $asaas_cobranca->asaas_client()->first()->id === $asaas_cobranca->asaas_client_id &&
                $asaas_cobranca->asaas_client()->first()->id === $cobranca->asaas_client_id
            ) {
                $asaas_cobranca->update([
                    'billingType' => BillingType::PIX->value,
                    'value' => ENV('VALUE_CARD'),
                    'dueDate' => $response["dueDate"],
                    'paymentLink' => $response["paymentLink"],
                    'status' => $response["status"],
                    'invoiceUrl' => $response["invoiceUrl"],
                    'paymentDate' => $response["paymentDate"],
                    'service_id' => $response["consult_id"],
                ]);
                $student->asaas_client()->first()->asaas_cobranca_id = $asaas_cobranca->id;
                $carteira->asaas_cobranca_id = $asaas_cobranca->id;
                $carteira->status = "ativo";
                $carteira->save();
                $student->save();
                $student->carteira_id = $response['externalReference']? DB::commit(): DB::rollBack();
            }else{
                return false;
            }
            return true;
        }catch (\Exception $e){
            Log::info("Erro ao atualizar", [
                "error" => $e->getMessage()
            ]);
            return false;
        }

    }

    public function edit(AsaasCobranca $asaasCobranca)
    {

        $asaasClients = \App\Models\AsaasClient::all();

        return view('asaas_cobrancas.edit', compact('asaasCobranca', 'asaas_clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AsaasCobranca $asaasCobranca)
    {

        $request->validate(["asaas_client_id" => "required", "customer" => "required", "billingType" => "required", "value" => "required", "dueDate" => "required", "status" => "required", "invoiceUrl" => "required", "service_id" => "required"]);

        try {
            $asaasCobranca->asaas_client_id = $request->asaas_client_id;
            $asaasCobranca->customer = $request->customer;
            $asaasCobranca->billingType = $request->billingType;
            $asaasCobranca->value = $request->value;
            $asaasCobranca->dueDate = $request->dueDate;
            $asaasCobranca->paymentLink = $request->paymentLink;
            $asaasCobranca->status = $request->status;
            $asaasCobranca->invoiceUrl = $request->invoiceUrl;
            $asaasCobranca->paymentDate = $request->paymentDate;
            $asaasCobranca->service_id = $request->service_id;
            $asaasCobranca->save();

            return redirect()->route('asaas_cobrancas.index', [])->with('success', __('Asaas Cobranca edited successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('asaas_cobrancas.edit', compact('asaasCobranca'))->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AsaasCobranca $asaasCobranca
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AsaasCobranca $asaasCobranca)
    {

        try {
            $asaasCobranca->delete();

            return redirect()->route('asaas_cobrancas.index', [])->with('success', __('Asaas Cobranca deleted successfully'));
        } catch (\Throwable $e) {
            return redirect()->route('asaas_cobrancas.index', [])->with('error', 'Cannot delete Asaas Cobranca: ' . $e->getMessage());
        }
    }


}
