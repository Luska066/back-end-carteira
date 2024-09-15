@extends('students.layout')

@section('students.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('students.index', compact([])) }}"> Students</a></li>
                    <li class="breadcrumb-item">@lang('Edit Student') #{{$student->id}}</li>
                </ol>
            </div>
            <div class="card-body">

                <form action="{{ route('students.update', compact('student')) }}" method="POST" enctype="multipart/form-data" class="m-0 p-0">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <img style="border-radius: 10px" src="{{\Illuminate\Support\Facades\Storage::url($student->image_url)}}">
                            <input  type="file" name="image" id="image" class=" form-control"
                                   value="{{@old('image', $student->name)}}" required/>
                            @if($errors->has('image'))
                                <div class='error small text-danger'>{{$errors->first('image')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input disabled type="text" name="name" id="name" class="form-control"
                                   value="{{@old('name', $student->name)}}" required/>
                            @if($errors->has('name'))
                                <div class='error small text-danger'>{{$errors->first('name')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input disabled type="email" name="email" id="email" class="form-control"
                                   value="{{@old('email', $student->email)}}" required/>
                            @if($errors->has('email'))
                                <div class='error small text-danger'>{{$errors->first('email')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="nome_completo" class="form-label">Nome Completo:</label>
                            <input disabled type="text" name="nome_completo" id="nome_completo" class="form-control"
                                   value="{{@old('nome_completo', $student->nome_completo)}}" required/>
                            @if($errors->has('nome_completo'))
                                <div class='error small text-danger'>{{$errors->first('nome_completo')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="client_id" class="form-label">Client:</label>
                            <input disabled type="number" name="client_id" id="client_id" class="form-control"
                                   value="{{@old('client_id', $student->client_id)}}"/>
                            @if($errors->has('client_id'))
                                <div class='error small text-danger'>{{$errors->first('client_id')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="carteira_id" class="form-label">Carteira:</label>
                            <div class="d-flex flex-row align-items-center justify-content-between">
                                <select  disabled  name="carteira_id" id="carteira_id" class="form-control form-select flex-grow-1"
                                        required>
                                    <option value="">Select Carteira</option>
                                    @foreach($carteiras as $carteira)
                                        <option
                                            value="{{ $carteira->id }}" {{ @old('carteira_id', $student->carteira_id) == $carteira->id ? "selected" : "" }}>{{ $carteira->student_id == $student->id ? $carteira->uuid : "Ops ocorreu um erro" }} </option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('carteira_id'))
                                <div class='error small text-danger'>{{$errors->first('carteira_id')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="image_url" class="form-label">Image Url:</label>
                            <input  disabled  type="text" name="image_url" id="image_url" class="form-control"
                                   value="{{@old('image_url', $student->image_url)}}"/>
                            @if($errors->has('image_url'))
                                <div class='error small text-danger'>{{$errors->first('image_url')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="cpf" class="form-label">Cpf:</label>
                            <input disabled type="text" name="cpf" id="cpf" class="form-control"
                                   value="{{@old('cpf', $student->cpf)}}"/>
                            @if($errors->has('cpf'))
                                <div class='error small text-danger'>{{$errors->first('cpf')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="matricula" class="form-label">Matricula:</label>
                            <input disabled type="text" name="matricula" id="matricula" class="form-control"
                                   value="{{@old('matricula', $student->matricula)}}"/>
                            @if($errors->has('matricula'))
                                <div class='error small text-danger'>{{$errors->first('matricula')}}</div>
                            @endif
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <a href="{{ route('students.index', []) }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">@lang('Update Student')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h2>Asaas Client</h2>
            </div>
            @php
                $student_asaas_client = $student->asaas_client()->orderBy('id','desc')->first()
            @endphp
            <div class="card-body">
                <div class="mb-3">
                    <label for="image_url" class="form-label">Image Url:</label>
                    <input  disabled type="text" name="image_url" id="image_url" class="form-control"
                           value="{{@old('image_url', $student_asaas_client->costumer_id)}}"/>
                </div>
                <div class="mb-3">
                    <label for="image_url" class="form-label">Image Url:</label>
                    <input  disabled type="text" name="image_url" id="image_url" class="form-control"
                           value="{{@old('image_url', $student_asaas_client->cpfCnpj)}}"/>
                </div>
                <div class="mb-3">
                    <label for="image_url" class="form-label">Image Url:</label>
                    <input  disabled type="text" name="image_url" id="image_url" class="form-control"
                           value="{{@old('image_url', $student_asaas_client->email)}}"/>
                </div>
                <div>
                    @php
                        $heads = [
                            'Id da Cobrança Asaas',
                            'Customer',
                            ['label' => 'Forma de Pagamento', 'width' => 50],
                            ['label' => 'Valor', 'no-export' => true, 'width' => 50],
                            ['label' => 'Data de Vencimento', 'no-export' => true, 'width' => 5],
                            ['label' => 'Status', 'no-export' => true, 'width' => 20],
                            ['label' => 'Boleto', 'no-export' => true, 'width' => 20],
                            ['label' => 'Ações', 'no-export' => true, 'width' => 5],
                        ];


                        $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                                          <i class="fa fa-lg fa-fw fa-trash"></i>
                                      </button>';
                        $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                                           <i class="fa fa-lg fa-fw fa-eye"></i>
                                       </button>';

                       $cobranca_asaas_client = $student_asaas_client->asaas_cobranca()->get();
                        $tableRow = [];
                        foreach ($cobranca_asaas_client as $dataCobranca){
                            $vizualizar = "<a href='".$dataCobranca->id."' class='btn btn-xs btn-primary  mx-1 shadow' title='Details'>
                                    VIZUALIZAR
                                       </a>";
                            $btnEdit = "<div id='refresh_$dataCobranca->id' onclick='refreshPage($dataCobranca->id)' class='btn btn-xs btn-default text-primary mx-1 shadow' title='Edit'>
                                        <i id='spin_$dataCobranca->id' class='fa fa-lg fa-fw fa-sync-alt'></i>
                                    </div>";
                            $actions = ' <div data-label="Actions:" class="text-nowrap">
                                   <a href="'.route('asaas_cobrancas.show',$dataCobranca->id).'" type="button"
                                   class="btn btn-primary btn-sm me-1">View</a>
                            </div>';
                            $tableRow[] = [
                                $dataCobranca->id_charge,
                                $dataCobranca->asaas_client()->first()->costumer_id,
                                $dataCobranca->billingType ,
                                $dataCobranca->value,
                                \Carbon\Carbon::make($dataCobranca->dueDate)->format('d/m/Y H:i:s') ?? "Sem data de vencimento",
                                $dataCobranca->status ,
                                $dataCobranca->invoiceUrl ,
                                $actions
                            ];
                        }
                        $config = [
                            'data' => $tableRow,
                            'order' => [[1, 'asc']],
                            "paging" => true,
                        ];
                    @endphp
                    <div class="m-3">
                        <x-adminlte-datatable id="table4" hoverable :heads="$heads" head-theme="light" striped
                                              :config="$config">
                            @foreach($config['data'] as $row)
                                <tr>
                                    @foreach($row as $cell)
                                        <td>{!! $cell !!}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h2>Carteiras</h2>
            </div>
            <div class="card-body">
                @if($student->carteira()->get()->count() > 0)
                    @php
                        $heads = [
                            'Uuid',
                            'Matricula',
                            ['label' => 'Código de acesso da carteira', 'width' => 50],
                            ['label' => 'Nome do Curso', 'no-export' => true, 'width' => 50],
                            ['label' => 'Data de Expiração', 'no-export' => true, 'width' => 5],
                            ['label' => 'Status', 'no-export' => true, 'width' => 20],
                            ['label' => 'Ações', 'no-export' => true, 'width' => 5],
                        ];


                        $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                                          <i class="fa fa-lg fa-fw fa-trash"></i>
                                      </button>';
                        $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                                           <i class="fa fa-lg fa-fw fa-eye"></i>
                                       </button>';

                       $cobrancas = $student->carteira()->get();

                        $tableRow = [];
                        foreach ($cobrancas as $dataCobranca){
                            $vizualizar = "<a href='".$dataCobranca->id."' class='btn btn-xs btn-primary  mx-1 shadow' title='Details'>
                                    VIZUALIZAR
                                       </a>";
                            $btnEdit = "<div id='refresh_$dataCobranca->id' onclick='refreshPage($dataCobranca->id)' class='btn btn-xs btn-default text-primary mx-1 shadow' title='Edit'>
                                        <i id='spin_$dataCobranca->id' class='fa fa-lg fa-fw fa-sync-alt'></i>
                                    </div>";
                            $actions = ' <div data-label="Actions:" class="text-nowrap">
                                   <a href="'.route('carteiras.edit',$carteira->id).'" type="button"
                                   class="btn btn-primary btn-sm me-1">Edit</a>
                            </div>';
                            $tableRow[] = [
                                $dataCobranca->uuid,
                                $dataCobranca->matricula,
                                $dataCobranca->passCode ,
                                $dataCobranca->nome_curso,
                                $dataCobranca->expiredAt ?? "Sem data de vencimento",
                                strtoupper($dataCobranca->status) ,
                                $actions
                            ];
                        }
                        $config = [
                            'data' => $tableRow,
                            'order' => [[1, 'asc']],
                            "paging" => true,
                        ];
                    @endphp
                    <div class="m-3">
                        <x-adminlte-datatable id="table3" hoverable :heads="$heads" head-theme="light" striped
                                              :config="$config">
                            @foreach($config['data'] as $row)
                                <tr>
                                    @foreach($row as $cell)
                                        <td>{!! $cell !!}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>

                    </div>
                @else
                    <h4>Esse estudante não tem carteiras</h4>
                @endif
            </div>
        </div>
@endsection
