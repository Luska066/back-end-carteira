@extends('asaas_cobrancas.layout')

@section('asaasCobrancas.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('asaas_cobrancas.index', compact([])) }}"> Asaas Cobrancas</a></li>
                    <li class="breadcrumb-item">@lang('ServiceCreateChargesAsaasClient new')</li>
                </ol>
            </div>

            <div class="card-body">
                <form action="{{ route('asaas_cobrancas.store', []) }}" method="POST" class="m-0 p-0">
                    <div class="card-body">
                        @csrf
                        <div class="mb-3">
        <label for="asaas_client_id" class="form-label">Asaas Client:</label>
        <div class="d-flex flex-row align-items-center justify-content-between">
    <select name="asaas_client_id" id="asaas_client_id" class="form-control form-select flex-grow-1" required>
        <option value="">Select Asaas Client</option>
        @foreach($asaasClients as $asaasClient)
            <option value="{{ $asaasClient->id }}" {{ @old('asaas_client_id') == $asaasClient->id ? "selected" : "" }}>{{ $asaasClient->name }}</option>
        @endforeach
    </select>

    <a class="btn btn-light text-nowrap" href="{{implode('/', ['','asaasClients','create'])}}"><i class="fa fa-plus-circle"></i> New</a>
</div>
        @if($errors->has('asaas_client_id'))
			<div class='error small text-danger'>{{$errors->first('asaas_client_id')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="customer" class="form-label">Customer:</label>
        <input type="text" name="customer" id="customer" class="form-control" value="{{@old('customer')}}" required/>
        @if($errors->has('customer'))
			<div class='error small text-danger'>{{$errors->first('customer')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="billingType" class="form-label">Billingtype:</label>
        <input type="text" name="billingType" id="billingType" class="form-control" value="{{@old('billingType')}}" required/>
        @if($errors->has('billingType'))
			<div class='error small text-danger'>{{$errors->first('billingType')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="value" class="form-label">Value:</label>
        <input type="number" name="value" id="value" class="form-control" value="{{@old('value')}}" required/>
        @if($errors->has('value'))
			<div class='error small text-danger'>{{$errors->first('value')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="dueDate" class="form-label">Duedate:</label>
        <input type="date" name="dueDate" id="dueDate" class="form-control" value="{{@old('dueDate')}}" required/>
        @if($errors->has('dueDate'))
			<div class='error small text-danger'>{{$errors->first('dueDate')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="paymentLink" class="form-label">Paymentlink:</label>
        <input type="text" name="paymentLink" id="paymentLink" class="form-control" value="{{@old('paymentLink')}}" />
        @if($errors->has('paymentLink'))
			<div class='error small text-danger'>{{$errors->first('paymentLink')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status:</label>
        <input type="text" name="status" id="status" class="form-control" value="{{@old('status')}}" required/>
        @if($errors->has('status'))
			<div class='error small text-danger'>{{$errors->first('status')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="invoiceUrl" class="form-label">Invoiceurl:</label>
        <input type="text" name="invoiceUrl" id="invoiceUrl" class="form-control" value="{{@old('invoiceUrl')}}" required/>
        @if($errors->has('invoiceUrl'))
			<div class='error small text-danger'>{{$errors->first('invoiceUrl')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="paymentDate" class="form-label">Paymentdate:</label>
        <input type="date" name="paymentDate" id="paymentDate" class="form-control" value="{{@old('paymentDate')}}" />
        @if($errors->has('paymentDate'))
			<div class='error small text-danger'>{{$errors->first('paymentDate')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="service_id" class="form-label">Service:</label>
        <input type="number" name="service_id" id="service_id" class="form-control" value="{{@old('service_id')}}" required/>
        @if($errors->has('service_id'))
			<div class='error small text-danger'>{{$errors->first('service_id')}}</div>
		@endif
    </div>

                    </div>

                    <div class="card-footer">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <a href="{{ route('asaas_cobrancas.index', []) }}" class="btn btn-light">@lang('Cancel')</a>
                            <button type="submit" class="btn btn-primary">@lang('ServiceCreateChargesAsaasClient new Asaas Cobranca')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
