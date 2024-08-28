@extends('students.layout')

@section('students.content')
    <style>
        #imagem-url-perfil{
            width: 200px;
            height: 200px;
            border:1px solid darkgrey;
            border-radius: 200px;
            background-image: url("https://voxnews.com.br/wp-content/uploads/2017/04/unnamed.png");
            background-position: center;
            background-size: contain;
        }
    </style>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('students.index', compact([])) }}"> Students</a></li>
                    <li class="breadcrumb-item">@lang('ServiceCreateChargesAsaasClient new')</li>
                </ol>
            </div>

            <div class="card-body">
                <form action="{{ route('students.store', []) }}" enctype='multipart/form-data' method="POST" class="m-0 p-0">
                    <div class="card-body"  >
                        @csrf
                        <div class="mb-3 d-flex flex-column align-items-center justify-content-center">
                            <label id="imagem-url-perfil" for="image_url" class="form-label">
                            </label>
                            <label  for="image_url" class="form-label">
                                Selecione a imagem
                            </label>
                            <input type="file" style="display: none" name="image_url" id="image_url" class="form-control" value="{{@old('name')}}"
                                   />
                            @if($errors->has('image_url'))
                                <div class='error small text-danger'>{{$errors->first('image_url')}}</div>
                            @endif
                        </div>


                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{@old('name')}}"
                                   required/>
                            @if($errors->has('name'))
                                <div class='error small text-danger'>{{$errors->first('name')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{@old('email')}}"
                                   required/>
                            @if($errors->has('email'))
                                <div class='error small text-danger'>{{$errors->first('email')}}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="cpf" class="form-label">Cpf:</label>
                            <input type="text" maxlength="14" name="cpf" id="cpf" class="form-control" value="{{@old('cpf')}}"/>
                            @if($errors->has('cpf'))
                                <div class='error small text-danger'>{{$errors->first('cpf')}}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="data_nascimento" class="form-label">Data nascimento:</label>
                            <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" value="{{@old('data_nascimento')}}"/>
                            @if($errors->has('data_nascimento'))
                                <div class='error small text-danger'>{{$errors->first('data_nascimento')}}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Senha:</label>
                            <input type="password" name="password" id="password" class="form-control"
                                   value="{{@old('password')}}" required/>
                            @if($errors->has('password'))
                                <div class='error small text-danger'>{{$errors->first('password')}}</div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <a href="{{ route('students.index', []) }}" class="btn btn-light">@lang('Cancel')</a>
                            <button type="submit" class="btn btn-primary">@lang('ServiceCreateChargesAsaasClient new Student')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function($){
            $("#cpf").mask('###.###.###.##', {numericInput: true});
        });
    </script>
@endsection
