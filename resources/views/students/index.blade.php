@extends('students.layout')

@section('students.content')
    <div class="container-fluid d-flex align-items-center justify-content-center mt-4">
        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h2>Estudantes</h2>
            </div>
            @php
                $heads = [
                    'Nome',
                    ['label' => 'CPF', 'no-export' => true, 'width' => 5],
                    'Email',
                    ['label' => 'Matricula', 'no-export' => true, 'width' => 20],
                    ['label' => 'Data Nascimento', 'no-export' => true, 'width' => 20],
                    ['label' => 'Client Asaas Id', 'width' => 10],
                    ['label' => 'Carteira', 'no-export' => true, 'width' => 5],
                    ['label' => 'Ações', 'no-export' => true, 'width' => 5],
                ];


                $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                                  <i class="fa fa-lg fa-fw fa-trash"></i>
                              </button>';
                $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                                   <i class="fa fa-lg fa-fw fa-eye"></i>
                               </button>';
                $tableRow = [];
                foreach ($students as $student){
                    $asaas_client_id = $student->asaas_client()->get()->count() > 0 ? $student->asaas_client()->first()->costumer_id : "Sem conta asaas";
                    $vizualizar = "<a href='".$student->invoiceUrl."' class='btn btn-xs btn-primary  mx-1 shadow' title='Details'>
                            VIZUALIZAR
                               </a>";
                    $btnEdit = "<div id='refresh_$student->id' onclick='refreshPage($student->id)' class='btn btn-xs btn-default text-primary mx-1 shadow' title='Edit'>
                                <i id='spin_$student->id' class='fa fa-lg fa-fw fa-sync-alt'></i>
                            </div>";
                    $actions = ' <div data-label="Actions:" class="text-nowrap">
                                <a href="'.route("students.show", $student->id).'" type="button"
                                   class="btn btn-primary btn-sm me-1">Show</a>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-light dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item"
                                               href="'.route("students.edit", $student->id).'">Edit</a>
                                        </li>
                                        <li>
                                            <form action="'.route('students.destroy', $student->id).'"
                                                  method="POST" style="display: inline;" class="m-0 p-0">
                                                <button type="submit" class="dropdown-item">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                            </td>';
                    $tableRow[] = [
                        $student->name,
                        $student->cpf,
                        $student->email,
                        $student->matricula,
                        $student->data_nascimento,
                        $asaas_client_id,
                        $student->carteira()->first()->uuid ?? "Sem carteira ate o momento!",
                        $actions
                    ];
                }
                $config = [
                    'data' => $tableRow,
                    'order' => [[1, 'asc']],
                    "paging" => true,
                ];
            @endphp
            <div class="card-body">
{{--                <table class="table table-striped table-responsive-lg table-hover">--}}
{{--                    <thead role="rowgroup">--}}
{{--                    <tr role="row">--}}
{{--                        <th role='columnheader'>Image</th>--}}
{{--                        <th role='columnheader'>Name</th>--}}
{{--                        <th role='columnheader'>Email</th>--}}
{{--                        <th role='columnheader'>Nome Completo</th>--}}
{{--                        <th role='columnheader'>Client</th>--}}
{{--                        <th role='columnheader'>Carteira</th>--}}
{{--                        <th role='columnheader'>Cpf</th>--}}
{{--                        <th role='columnheader'>Matricula</th>--}}
{{--                        <th scope="col" data-label="Actions">Actions</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($students as $student)--}}
{{--                        <tr class="align-center">--}}
{{--                            <td data-label="Image Url">--}}
{{--                                <img--}}
{{--                                    src="{{\Illuminate\Support\Facades\Storage::url($student->image_url)}}"--}}
{{--                                />--}}
{{--                            </td>--}}
{{--                            <td data-label="Name">{{ $student->name ?: "(blank)" }}</td>--}}
{{--                            <td data-label="Email">{{ $student->email ?: "(blank)" }}</td>--}}
{{--                            <td data-label="Nome Completo">{{ $student->nome_completo ?: "(blank)" }}</td>--}}
{{--                            <td data-label="Client">{{ $student->client_id ?: "(blank)" }}</td>--}}
{{--                            <td data-label="Carteira">--}}
{{--                                <a href="{{route('carteiras.show', $student->carteira_id ?: 0)}}" class="text-dark">--}}
{{--                                    {{$student?->carteira?->nomeAluno ?: "(blank)"}}--}}
{{--                                </a>--}}
{{--                            </td>--}}
{{--                            <td data-label="Cpf">{{ $student->cpf ?: "(blank)" }}</td>--}}
{{--                            <td data-label="Matricula">{{ $student->matricula ?: "(blank)" }}</td>--}}

{{--                            <td data-label="Actions:" class="text-nowrap">--}}
{{--                                <a href="{{route('students.show', compact('student'))}}" type="button"--}}
{{--                                   class="btn btn-primary btn-sm me-1">@lang('Show')</a>--}}
{{--                                <div class="btn-group btn-group-sm">--}}
{{--                                    <button type="button" class="btn btn-light dropdown-toggle"--}}
{{--                                            data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i>--}}
{{--                                    </button>--}}
{{--                                    <ul class="dropdown-menu">--}}
{{--                                        <li><a class="dropdown-item"--}}
{{--                                               href="{{route('students.edit', compact('student'))}}">@lang('Edit')</a>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <form action="{{route('students.destroy', compact('student'))}}"--}}
{{--                                                  method="POST" style="display: inline;" class="m-0 p-0">--}}
{{--                                                @csrf--}}
{{--                                                @method('DELETE')--}}
{{--                                                <button type="submit" class="dropdown-item">@lang('Delete')</button>--}}
{{--                                            </form>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}

{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
                <div class="m-3">
                    <x-adminlte-datatable id="table3" hoverable :heads="$heads" head-theme="light" striped :config="$config">
                        @foreach($config['data'] as $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td>{!! $cell !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
{{--                {{ $students->withQueryString()->links() }}--}}
            </div>
            <div class="text-center my-2">
                <a href="{{ route('students.create', []) }}" class="btn btn-primary"><i
                        class="fa fa-plus"></i> @lang('ServiceCreateChargesAsaasClient new Student')</a>
            </div>
        </div>
    </div>
@endsection
