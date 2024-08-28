@extends('asaas_clients.layout')

@section('asaasClients.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('asaas_clients.index', compact([])) }}"> Asaas Clients</a></li>
                    <li class="breadcrumb-item">@lang('Asaas Client') #{{$asaasClient->id}}</li>
                </ol>

                <a href="{{ route('asaas_clients.index', []) }}" class="btn btn-light"><i class="fa fa-caret-left"></i> Back</a>
            </div>

            <div class="card-body">
                <table class="table table-striped">
    <tbody>
    <tr>
        <th scope="row">ID:</th>
        <td>{{$asaasClient->id}}</td>
    </tr>
            <tr>
            <th scope="row">Asaas Cobranca:</th>
            <td><a href="{{route('asaas_cobrancas.show', $asaasClient->asaas_cobranca_id ?: 0)}}" class="text-dark">{{$asaasClient?->asaas_cobranca?->customer ?: "(blank)"}}</a></td>
        </tr>
            <tr>
            <th scope="row">Costumer:</th>
            <td>{{ $asaasClient->costumer_id ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Name:</th>
            <td>{{ $asaasClient->name ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Cpfcnpj:</th>
            <td>{{ $asaasClient->cpfCnpj ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Email:</th>
            <td>{{ $asaasClient->email ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Student:</th>
            <td><a href="{{route('students.show', $asaasClient->student_id ?: 0)}}" class="text-dark">{{$asaasClient?->student?->name ?: "(blank)"}}</a></td>
        </tr>
            <tr>
            <th scope="row">Service:</th>
            <td>{{ $asaasClient->service_id ?: "(blank)" }}</td>
        </tr>
                <tr>
            <th scope="row">Created at</th>
            <td>{{Carbon\Carbon::parse($asaasClient->created_at)->format('d/m/Y H:i:s')}}</td>
        </tr>
        <tr>
            <th scope="row">Updated at</th>
            <td>{{Carbon\Carbon::parse($asaasClient->updated_at)->format('d/m/Y H:i:s')}}</td>
        </tr>
        </tbody>
</table>

            </div>

            <div class="card-footer d-flex flex-column flex-md-row align-items-center justify-content-end">
                <a href="{{ route('asaas_clients.edit', compact('asaasClient')) }}" class="btn btn-info text-nowrap me-1"><i class="fa fa-edit"></i> @lang('Edit')</a>
                <form action="{{ route('asaas_clients.destroy', compact('asaasClient')) }}" method="POST" class="m-0 p-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger text-nowrap"><i class="fa fa-trash"></i> @lang('Delete')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
