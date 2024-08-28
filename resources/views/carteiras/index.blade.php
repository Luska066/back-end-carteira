@extends('carteiras.layout')

@section('carteiras.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <ol class="breadcrumb m-0 p-0 flex-grow-1 mb-2 mb-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('carteiras.index', compact([])) }}"> Carteiras</a>
                    </li>
                </ol>

                <form action="{{ route('carteiras.index', []) }}" method="GET" class="m-0 p-0">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm me-2" name="search"
                               placeholder="Search Carteiras..." value="{{ request()->search }}">
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
                        <th role='columnheader'>Student</th>
                        <th role='columnheader'>Nomealuno</th>
                        <th role='columnheader'>Matricula</th>
                        <th role='columnheader'>Passcode</th>
                        <th role='columnheader'>Data Nascimento</th>
                        <th role='columnheader'>Nome Curso</th>
                        <th role='columnheader'>Datainiciocurso</th>
                        <th role='columnheader'>Datafimcurso</th>
                        <th role='columnheader'>Carteirapdfurl</th>
                        <th role='columnheader'>Expiredat</th>
                        <th scope="col" data-label="Actions">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($carteiras as $carteira)
                        <tr>
                            <td data-label="Student"><a href="{{route('students.show', $carteira->student_id ?: 0)}}"
                                                        class="text-dark">{{$carteira?->student?->name ?: "(blank)"}}</a>
                            </td>
                            <td data-label="Nomealuno">{{ $carteira->nomeAluno ?: "(blank)" }}</td>
                            <td data-label="Matricula">{{ $carteira->matricula ?: "(blank)" }}</td>
                            <td data-label="Passcode">{{ $carteira->passCode ?: "(blank)" }}</td>
                            <td data-label="Data Nascimento">{{ $carteira->data_nascimento ?: "(blank)" }}</td>
                            <td data-label="Nome Curso">{{ $carteira->nome_curso ?: "(blank)" }}</td>
                            <td data-label="Datainiciocurso">{{ $carteira->dataInicioCurso ?: "(blank)" }}</td>
                            <td data-label="Datafimcurso">{{ $carteira->dataFimCurso ?: "(blank)" }}</td>
                            <td data-label="Carteirapdfurl">{{ $carteira->carteiraPdfUrl ?: "(blank)" }}</td>
                            <td data-label="Expiredat">{{ $carteira->expiredAt ?: "(blank)" }}</td>

                            <td data-label="Actions:" class="text-nowrap">
                                <a href="{{route('carteiras.show', $carteira->id)}}" type="button"
                                   class="btn btn-primary btn-sm me-1">@lang('Show')</a>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-light dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="true"><i class="fa fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item"
                                               href="{{route('carteiras.edit', compact('carteira'))}}">@lang('Edit')</a>
                                        </li>
                                        <li>
                                            <form action="{{route('carteiras.destroy', compact('carteira'))}}"
                                                  method="POST" style="display: inline;" class="m-0 p-0">
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

                {{ $carteiras->withQueryString()->links() }}
            </div>
            <div class="text-center my-2">
                <a href="{{ route('carteiras.create', []) }}" class="btn btn-primary"><i
                        class="fa fa-plus"></i> @lang('ServiceCreateChargesAsaasClient new Carteira')</a>
            </div>
        </div>
    </div>
@endsection
