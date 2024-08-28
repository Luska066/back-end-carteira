@extends('carteiras.layout')

@section('carteiras.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('carteiras.index', compact([])) }}"> Carteiras</a></li>
                    <li class="breadcrumb-item">@lang('Carteira') #{{$carteira->id}}</li>
                </ol>

                <a href="{{ route('carteiras.index', []) }}" class="btn btn-light"><i class="fa fa-caret-left"></i> Back</a>
            </div>

            <div class="card-body">
                <table class="table table-striped">
    <tbody>
    <tr>
        <th scope="row">ID:</th>
        <td>{{$carteira->id}}</td>
    </tr>
            <tr>
            <th scope="row">Student:</th>
            <td><a href="{{route('students.show', $carteira->student_id ?: 0)}}" class="text-dark">{{$carteira?->student?->name ?: "(blank)"}}</a></td>
        </tr>
            <tr>
            <th scope="row">Nomealuno:</th>
            <td>{{ $carteira->nomeAluno ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Matricula:</th>
            <td>{{ $carteira->matricula ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Passcode:</th>
            <td>{{ $carteira->passCode ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Data Nascimento:</th>
            <td>{{ $carteira->data_nascimento ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Nome Curso:</th>
            <td>{{ $carteira->nome_curso ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Datainiciocurso:</th>
            <td>{{ $carteira->dataInicioCurso ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Datafimcurso:</th>
            <td>{{ $carteira->dataFimCurso ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Carteirapdfurl:</th>
            <td>{{ $carteira->carteiraPdfUrl ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Expiredat:</th>
            <td>{{ $carteira->expiredAt ?: "(blank)" }}</td>
        </tr>
                <tr>
            <th scope="row">Created at</th>
            <td>{{Carbon\Carbon::parse($carteira->created_at)->format('d/m/Y H:i:s')}}</td>
        </tr>
        <tr>
            <th scope="row">Updated at</th>
            <td>{{Carbon\Carbon::parse($carteira->updated_at)->format('d/m/Y H:i:s')}}</td>
        </tr>
        </tbody>
</table>

            </div>

            <div class="card-footer d-flex flex-column flex-md-row align-items-center justify-content-end">
                <a href="{{ route('carteiras.edit', compact('carteira')) }}" class="btn btn-info text-nowrap me-1"><i class="fa fa-edit"></i> @lang('Edit')</a>
                <form action="{{ route('carteiras.destroy', compact('carteira')) }}" method="POST" class="m-0 p-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger text-nowrap"><i class="fa fa-trash"></i> @lang('Delete')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
