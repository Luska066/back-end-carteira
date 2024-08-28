@extends('carteiras.layout')

@section('carteiras.content')
    <style>
        @keyframes animationSpin {
            to {
                transform: rotate(0);
            }

            from {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('carteiras.index', compact([])) }}"> Carteiras</a>
                    </li>
                    <li class="breadcrumb-item">@lang('Edit Carteira') #{{$carteira->id}}</li>
                </ol>
            </div>
            <div class="card-body">
                <form action="{{ route('carteiras.update', compact('carteira')) }}" method="POST" class="m-0 p-0">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Estudante:</label>
                            <div class="d-flex flex-row align-items-center justify-content-between">
                                <h5>Id do estudante : {{$carteira->student()->first()->id}}</h5>
                            </div>
                            @if($errors->has('student_id'))
                                <div class='error small text-danger'>{{$errors->first('student_id')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="nomeAluno" class="form-label">Nomealuno:</label>
                            <input type="text" name="nomeAluno" id="nomeAluno" class="form-control"
                                   value="{{@old('nomeAluno', $carteira->nomeAluno)}}" required/>
                            @if($errors->has('nomeAluno'))
                                <div class='error small text-danger'>{{$errors->first('nomeAluno')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="matricula" class="form-label">Matricula:</label>
                            <input type="text" name="matricula" id="matricula" class="form-control"
                                   value="{{@old('matricula', $carteira->matricula)}}" required/>
                            @if($errors->has('matricula'))
                                <div class='error small text-danger'>{{$errors->first('matricula')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="passCode" class="form-label">Passcode:</label>
                            <input type="text" name="passCode" id="passCode" class="form-control"
                                   value="{{@old('passCode', $carteira->passCode)}}" required/>
                            @if($errors->has('passCode'))
                                <div class='error small text-danger'>{{$errors->first('passCode')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="data_nascimento" class="form-label">Data Nascimento:</label>
                            <input type="text" disabled name="data_nascimento" id="data_nascimento" class="form-control"
                                   value="{{@old('data_nascimento', \Carbon\Carbon::make($carteira->data_nascimento)->format("d/m/Y"))}}"/>
                            @if($errors->has('data_nascimento'))
                                <div class='error small text-danger'>{{$errors->first('data_nascimento')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="nome_curso" class="form-label">Nome Curso:</label>
                            <input type="text" name="nome_curso" id="nome_curso" class="form-control"
                                   value="{{@old('nome_curso', $carteira->nome_curso)}}" required/>
                            @if($errors->has('nome_curso'))
                                <div class='error small text-danger'>{{$errors->first('nome_curso')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="dataInicioCurso" class="form-label">Datainiciocurso:</label>
                            <input type="text" disabled name="dataInicioCurso" id="dataInicioCurso" class="form-control"
                                   value="{{@old('dataInicioCurso', $carteira->dataInicioCurso != null ? \Carbon\Carbon::make($carteira->dataInicioCurso)->format("d/m/Y") : "Sem data de inicio do curso")}}"/>
                            @if($errors->has('dataInicioCurso'))
                                <div class='error small text-danger'>{{$errors->first('dataInicioCurso')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="dataFimCurso" class="form-label">Datafimcurso:</label>
                            <input type="text" disabled name="dataFimCurso" id="dataFimCurso" class="form-control"
                                   value="{{@old('dataFimCurso', $carteira->dataFimCurso != null ? \Carbon\Carbon::make($carteira->dataFimCurso)->format("d/m/Y") : "Sem data de fim do curso")}}"/>
                            @if($errors->has('dataFimCurso'))
                                <div class='error small text-danger'>{{$errors->first('dataFimCurso')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="carteiraPdfUrl" class="form-label">Carteirapdfurl:</label>
                            <input type="text" name="carteiraPdfUrl" id="carteiraPdfUrl" class="form-control"
                                   value="{{@old('carteiraPdfUrl', $carteira->carteiraPdfUrl)}}"/>
                            @if($errors->has('carteiraPdfUrl'))
                                <div class='error small text-danger'>{{$errors->first('carteiraPdfUrl')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="expiredAt" class="form-label">Data de expiração da carteira:</label>
                            <input type="text" disabled name="expiredAt" id="expiredAt" class="form-control"
                                   value="{{@old('expiredAt', $carteira->expiredAt != null ? \Carbon\Carbon::make($carteira->expiredAt)->format("d/m/Y") : "Sem data de Expiração")}}"/>

                            @if($errors->has('expiredAt'))
                                <div class='error small text-danger'>{{$errors->first('expiredAt')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="expiredAt" class="form-label">Url de acesso da carteira:</label>
                            <input type="text" disabled name="expiredAt" id="expiredAt" class="form-control"
                                   value="{{ENV('HOST_FRONT').$carteira->uuid}}"/>

                            @if($errors->has('expiredAt'))
                                <div class='error small text-danger'>{{$errors->first('expiredAt')}}</div>
                            @endif
                        </div>
                    </div>
            <div>
                <div class="d-flex flex-row align-items-center justify-content-between">
                    <a href="{{ route('carteiras.index', []) }}" class="btn btn-light">Cancel</a>
                </div>
            </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="p-3 d-flex flex-row  align-items-center justify-content-between">
            <h2>Cobrança</h2>
            <button type="submit" class="btn btn-primary">@lang('Gerar PDF')</button>

        </div>
        {{-- Setup data for datatables --}}
        @php
            $heads = [
                'Id Charge Asaas',
                'Customer Id',
                ['label' => 'Forma de Pagamento', 'width' => 10],
                ['label' => 'Valor', 'no-export' => true, 'width' => 5],
                ['label' => 'Data de Vencimento', 'no-export' => true, 'width' => 5],
                ['label' => 'Vizualizar Boleto de Pagamento', 'no-export' => true, 'width' => 20],
                ['label' => 'Status', 'no-export' => true, 'width' => 5],
                ['label' => 'Atualizar Status', 'no-export' => true, 'width' => 5],
            ];


            $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                              <i class="fa fa-lg fa-fw fa-trash"></i>
                          </button>';
            $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                               <i class="fa fa-lg fa-fw fa-eye"></i>
                           </button>';

           $cobrancas = $carteira->cobranca()->get();
            $tableRow = [];
            foreach ($cobrancas as $dataCobranca){
                $vizualizar = "<a href='".$dataCobranca->invoiceUrl."' class='btn btn-xs btn-primary  mx-1 shadow' title='Details'>
                        VIZUALIZAR
                           </a>";
                $btnEdit = "<div id='refresh_$dataCobranca->id' onclick='refreshPage($dataCobranca->id)' class='btn btn-xs btn-default text-primary mx-1 shadow' title='Edit'>
                            <i id='spin_$dataCobranca->id' class='fa fa-lg fa-fw fa-sync-alt'></i>
                        </div>";
                $tableRow[] = [
                    $dataCobranca->id_charge,
                    $dataCobranca->asaas_client()->first()->costumer_id,
                    $dataCobranca->billingType,
                    $dataCobranca->value,
                    $dataCobranca->dueDate,
                    $vizualizar,
                    $dataCobranca->status,
                    $btnEdit
                ];
            }
            $config = [
                'data' => $tableRow,
                'order' => [[1, 'asc']],
                "paging" => true,
            ];
        @endphp
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
    </div>
    </div>

    <script>
        function refreshPage(dataId) {

            const refreshButton = document.getElementById(`spin_${dataId}`);
            refreshButton.style.animationName = "animationSpin";
            refreshButton.style.animationDuration = "1s";
            refreshButton.style.animationDirection = "reverse";
            refreshButton.style.animationTimingFunction = "linear";
            refreshButton.style.animationIterationCount = "infinite";
            axios.put(`/asaas_cobranca/${dataId}/asaas/service`)
                .then((response) => {
                    if(!response.data.success){
                        refreshButton.style.animationName = "none";
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Não foi possível atualizar cobrança",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    if(response.data.success){
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Cobrança atualizada com sucesso",
                            text:"Em poucos instantes a página será recarregada!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000)
                    }
                })
                .catch(erro => {
                    refreshButton.style.animationName = "none";
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Não foi possível atualizar cobrança",
                        showConfirmButton: false,
                        timer: 1500
                    });
                })
                .finally(() => {

                })

        }
    </script>
@endsection
