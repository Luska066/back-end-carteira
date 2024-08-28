@extends('asaas_cobrancas.layout')

@section('asaasCobrancas.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('asaas_cobrancas.index', compact([])) }}"> Asaas Cobrancas</a></li>
                    <li class="breadcrumb-item">@lang('Asaas Cobranca') #{{$asaasCobranca->id}}</li>
                </ol>

                <a href="{{ route('asaas_cobrancas.index', []) }}" class="btn btn-light"><i class="fa fa-caret-left"></i> Back</a>
            </div>

            <div class="card-body">
                <table class="table table-striped">
    <tbody>
    <tr>
        <th scope="row">ID:</th>
        <td>{{$asaasCobranca->id}}</td>
    </tr>
            <tr>
            <th scope="row">Asaas Client:</th>
            <td><a href="{{route('asaas_clients.show', $asaasCobranca->asaas_client_id ?: 0)}}" class="text-dark">{{$asaasCobranca?->asaas_client?->name ?: "(blank)"}}</a></td>
        </tr>
            <tr>
            <th scope="row">Customer:</th>
            <td>{{ $asaasCobranca->asaas_client()->first()->costumer_id ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Billingtype:</th>
            <td>{{ $asaasCobranca->billingType ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Value:</th>
            <td>{{ $asaasCobranca->value ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Duedate:</th>
            <td>{{ $asaasCobranca->dueDate ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Paymentlink:</th>
            <td>{{ $asaasCobranca->paymentLink ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Status:</th>
            <td>{{ $asaasCobranca->status ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Invoiceurl:</th>
            <td>{{ $asaasCobranca->invoiceUrl ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Paymentdate:</th>
            <td>{{ $asaasCobranca->paymentDate ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Service:</th>
            <td>{{ $asaasCobranca->service_id ?: "(blank)" }}</td>
        </tr>
                <tr>
            <th scope="row">Created at</th>
            <td>{{Carbon\Carbon::parse($asaasCobranca->created_at)->format('d/m/Y H:i:s')}}</td>
        </tr>
        <tr>
            <th scope="row">Updated at</th>
            <td>{{Carbon\Carbon::parse($asaasCobranca->updated_at)->format('d/m/Y H:i:s')}}</td>
        </tr>
        </tbody>
</table>

            </div>

            <div class="card-footer d-flex flex-column flex-md-row align-items-center justify-content-end">

                <button type="submit" class="btn btn-primary text-nowrap"><i class="fa fa-sync-alt"></i> @lang('Atualizar Cobranca')</button>
            </div>
        </div>
    </div>
@endsection
