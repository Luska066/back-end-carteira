@extends('asaas_clients.layout')

@section('asaasClients.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('asaas_clients.index', compact([])) }}"> Asaas Clients</a></li>
                    <li class="breadcrumb-item">@lang('Edit Asaas Client') #{{$asaasClient->id}}</li>
                </ol>
            </div>
            <div class="card-body">
                <form action="{{ route('asaas_clients.update', compact('asaasClient')) }}" method="POST" class="m-0 p-0">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
        <label for="asaas_cobranca_id" class="form-label">Asaas Cobranca:</label>
        <div class="d-flex flex-row align-items-center justify-content-between">
    <select name="asaas_cobranca_id" id="asaas_cobranca_id" class="form-control form-select flex-grow-1" >
        <option value="">Select Asaas Cobranca</option>
        @foreach($asaasCobrancas as $asaasCobranca)
            <option value="{{ $asaasCobranca->id }}" {{ @old('asaas_cobranca_id', $asaasClient->asaas_cobranca_id) == $asaasCobranca->id ? "selected" : "" }}>{{ $asaasCobranca->customer }}</option>
        @endforeach
    </select>

    <a class="btn btn-light text-nowrap" href="{{implode('/', ['','asaasCobrancas','create'])}}"><i class="fa fa-plus-circle"></i> New</a>
</div>
        @if($errors->has('asaas_cobranca_id'))
			<div class='error small text-danger'>{{$errors->first('asaas_cobranca_id')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="costumer_id" class="form-label">Costumer:</label>
        <input type="text" name="costumer_id" id="costumer_id" class="form-control" value="{{@old('costumer_id', $asaasClient->costumer_id)}}" required/>
        @if($errors->has('costumer_id'))
			<div class='error small text-danger'>{{$errors->first('costumer_id')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" name="name" id="name" class="form-control" value="{{@old('name', $asaasClient->name)}}" required/>
        @if($errors->has('name'))
			<div class='error small text-danger'>{{$errors->first('name')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="cpfCnpj" class="form-label">Cpfcnpj:</label>
        <input type="text" name="cpfCnpj" id="cpfCnpj" class="form-control" value="{{@old('cpfCnpj', $asaasClient->cpfCnpj)}}" required/>
        @if($errors->has('cpfCnpj'))
			<div class='error small text-danger'>{{$errors->first('cpfCnpj')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" name="email" id="email" class="form-control" value="{{@old('email', $asaasClient->email)}}" required/>
        @if($errors->has('email'))
			<div class='error small text-danger'>{{$errors->first('email')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="student_id" class="form-label">Student:</label>
        <div class="d-flex flex-row align-items-center justify-content-between">
    <select name="student_id" id="student_id" class="form-control form-select flex-grow-1" required>
        <option value="">Select Student</option>
        @foreach($students as $student)
            <option value="{{ $student->id }}" {{ @old('student_id', $asaasClient->student_id) == $student->id ? "selected" : "" }}>{{ $student->name }}</option>
        @endforeach
    </select>

    <a class="btn btn-light text-nowrap" href="{{route('students.create', compact([]))}}"><i class="fa fa-plus-circle"></i> New</a>
</div>
        @if($errors->has('student_id'))
			<div class='error small text-danger'>{{$errors->first('student_id')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="service_id" class="form-label">Service:</label>
        <input type="number" name="service_id" id="service_id" class="form-control" value="{{@old('service_id', $asaasClient->service_id)}}" required/>
        @if($errors->has('service_id'))
			<div class='error small text-danger'>{{$errors->first('service_id')}}</div>
		@endif
    </div>

                    </div>
                    <div class="card-footer">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <a href="{{ route('asaas_clients.index', []) }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">@lang('Update Asaas Client')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
