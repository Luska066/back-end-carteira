@extends('asaas_cobrancas.layout')

@section('asaasCobrancas.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <ol class="breadcrumb m-0 p-0 flex-grow-1 mb-2 mb-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('asaas_cobrancas.index', compact([])) }}"> Asaas Cobrancas</a></li>
                </ol>

                <form action="{{ route('asaas_cobrancas.index', []) }}" method="GET" class="m-0 p-0">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm me-2" name="search" placeholder="Search Asaas Cobrancas..." value="{{ request()->search }}">
                        <span class="input-group-btn">
                            <button class="btn btn-info btn-sm" type="submit"><i class="fa fa-search"></i> @lang('Go!')</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-striped table-responsive table-hover">
    <thead role="rowgroup">
    <tr role="row">
                    <th role='columnheader'>Asaas Client</th>
                    <th role='columnheader'>Customer</th>
                    <th role='columnheader'>Billingtype</th>
                    <th role='columnheader'>Value</th>
                    <th role='columnheader'>Duedate</th>
                    <th role='columnheader'>Paymentlink</th>
                    <th role='columnheader'>Status</th>
                    <th role='columnheader'>Invoiceurl</th>
                    <th role='columnheader'>Paymentdate</th>
                    <th role='columnheader'>Service</th>
                <th scope="col" data-label="Actions">Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($asaasCobrancas as $asaasCobranca)
        <tr>
                            <td data-label="Asaas Client"><a href="{{route('asaas_clients.show', $asaasCobranca->asaas_client_id ?: 0)}}" class="text-dark">{{$asaasCobranca?->asaas_client?->name ?: "(blank)"}}</a></td>
                            <td data-label="Customer">{{ $asaasCobranca->customer ?: "(blank)" }}</td>
                            <td data-label="Billingtype">{{ $asaasCobranca->billingType ?: "(blank)" }}</td>
                            <td data-label="Value">{{ $asaasCobranca->value ?: "(blank)" }}</td>
                            <td data-label="Duedate">{{ $asaasCobranca->dueDate ?: "(blank)" }}</td>
                            <td data-label="Paymentlink">{{ $asaasCobranca->paymentLink ?: "(blank)" }}</td>
                            <td data-label="Status">{{ $asaasCobranca->status ?: "(blank)" }}</td>
                            <td data-label="Invoiceurl">{{ $asaasCobranca->invoiceUrl ?: "(blank)" }}</td>
                            <td data-label="Paymentdate">{{ $asaasCobranca->paymentDate ?: "(blank)" }}</td>
                            <td data-label="Service">{{ $asaasCobranca->service_id ?: "(blank)" }}</td>

            <td data-label="Actions:" class="text-nowrap">
                                   <a href="{{route('asaas_cobrancas.show', compact('asaasCobranca'))}}" type="button" class="btn btn-primary btn-sm me-1">@lang('Show')</a>
<div class="btn-group btn-group-sm">
    <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{route('asaas_cobrancas.edit', compact('asaasCobranca'))}}">@lang('Edit')</a></li>
        <li>
            <form action="{{route('asaas_cobrancas.destroy', compact('asaasCobranca'))}}" method="POST" style="display: inline;" class="m-0 p-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="dropdown-item">@lang('Delete')</button>
            </form>
        </li>
    </ul>
</div>

                            </td>
        </tr>
    @endforeach
    </tbody>
</table>

                {{ $asaasCobrancas->withQueryString()->links() }}
            </div>
            <div class="text-center my-2">
                <a href="{{ route('asaas_cobrancas.create', []) }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('ServiceCreateChargesAsaasClient new Asaas Cobranca')</a>
            </div>
        </div>
    </div>
@endsection
