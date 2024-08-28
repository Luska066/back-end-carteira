@extends('asaas_clients.layout')

@section('asaasClients.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('asaas_clients.index', compact([])) }}"> Asaas
                            Clients</a></li>
                    <li class="breadcrumb-item">@lang('ServiceCreateChargesAsaasClient new')</li>
                </ol>
            </div>

            <div class="card-body">
                <form action="{{ route('asaas_clients.store', []) }}" method="POST" class="m-0 p-0">
                    <div class="card-body">
                        @csrf
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student:</label>
                            <div class="d-flex flex-row align-items-center justify-content-between">
                                <select name="student_id" id="student_id" class="form-control form-select flex-grow-1"
                                        required>
                                    <option value="">Select Student</option>
                                    @foreach($students as $student)
                                        <option
                                            value="{{ $student->id }}" {{ @old('student_id') == $student->id ? "selected" : "" }}>{{ $student->name }}</option>
                                    @endforeach
                                </select>

                                <a class="btn btn-light text-nowrap" href="{{route('students.create', compact([]))}}"><i
                                        class="fa fa-plus-circle"></i> New</a>
                            </div>
                            @if($errors->has('student_id'))
                                <div class='error small text-danger'>{{$errors->first('student_id')}}</div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <a href="{{ route('asaas_clients.index', []) }}" class="btn btn-light">@lang('Cancel')</a>
                            <button type="submit" class="btn btn-primary">@lang('ServiceCreateChargesAsaasClient new Asaas Client')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
