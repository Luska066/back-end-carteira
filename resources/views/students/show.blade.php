@extends('students.layout')

@section('students.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('students.index', compact([])) }}"> Students</a></li>
                    <li class="breadcrumb-item">@lang('Student') #{{$student->id}}</li>
                </ol>

                <a href="{{ route('students.index', []) }}" class="btn btn-light"><i class="fa fa-caret-left"></i> Back</a>
            </div>

            <div class="card-body">
                <table class="table table-striped">
    <tbody>
    <tr>
        <th scope="row">ID:</th>
        <td>{{$student->id}}</td>
    </tr>
            <tr>
            <th scope="row">Name:</th>
            <td>{{ $student->name ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Email:</th>
            <td>{{ $student->email ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Nome Completo:</th>
            <td>{{ $student->nome_completo ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Client:</th>
            <td>{{ $student->client_id ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Carteira:</th>
            <td><a href="{{route('carteiras.show', $student->carteira_id ?: 0)}}" class="text-dark">{{$student?->carteira?->nomeAluno ?: "(blank)"}}</a></td>
        </tr>
            <tr>
            <th scope="row">Image Url:</th>
            <td>{{ $student->image_url ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Cpf:</th>
            <td>{{ $student->cpf ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Matricula:</th>
            <td>{{ $student->matricula ?: "(blank)" }}</td>
        </tr>
            </tbody>
</table>

            </div>

            <div class="card-footer d-flex flex-column flex-md-row align-items-center justify-content-end">
                <a href="{{ route('students.edit', compact('student')) }}" class="btn btn-info text-nowrap me-1"><i class="fa fa-edit"></i> @lang('Edit')</a>
                <form action="{{ route('students.destroy', compact('student')) }}" method="POST" class="m-0 p-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger text-nowrap"><i class="fa fa-trash"></i> @lang('Delete')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
