@extends('users.layout')

@section('users.content')
    <style>
        #imagem-url-perfil{
            width: 200px;
            height: 200px;
            border:1px solid darkgrey;
            border-radius: 200px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
    <div class="container-fluid ">
        <div class="card py-5 d-flex align-items-center justify-content-center">
            <div class="d-flex  flex-column align-items-center justify-content-center" style="width: 800px">
                <h1>Alterar Senha</h1>
                <div class="mb-3 col-6">
                    <label for="name" class="form-label">Senha Atual:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{@old('name', $users->name)}}" required/>
                    @if($errors->has('name'))
                        <div class='error small text-danger'>{{$errors->first('name')}}</div>
                    @endif
                </div>
                <div class="mb-3 col-6">
                    <label for="name" class="form-label">Nova Senha:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{@old('name', $users->name)}}" required/>
                    @if($errors->has('name'))
                        <div class='error small text-danger'>{{$errors->first('name')}}</div>
                    @endif
                </div>
                <div class="mb-3 col-6">
                    <label for="name" class="form-label">Confirmar nova senha:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{@old('name', $users->name)}}" required/>
                    @if($errors->has('name'))
                        <div class='error small text-danger'>{{$errors->first('name')}}</div>
                    @endif
                </div>
                <div class="text-center my-2 border col-6">
                    <a href="{{ route('users.create', []) }}" class="btn col-12 btn-dark"><i
                            class="fa fa-plus"></i> @lang('Alterar senha')</a>
                </div>
            </div>
        </div>
    </div>
@endsection
