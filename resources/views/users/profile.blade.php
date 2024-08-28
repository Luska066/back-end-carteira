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
    <div class="container">
        <div class="card d-flex align-items-center justify-content-center">
            <div class="mb-3 mt-5 d-flex flex-column align-items-center justify-content-center">
{{--                $users->adminlte_image()--}}
                <label id="imagem-url-perfil" style="background-image: url('{{$users->adminlte_image()}}')" for="image_url" class="form-label">
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
            <div class="mb-3 col-6">
                <label for="name" class="form-label">Nome:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{@old('name', $users->name)}}" required/>
                @if($errors->has('name'))
                    <div class='error small text-danger'>{{$errors->first('name')}}</div>
                @endif
            </div>
            <div class="text-center my-2">
                <a href="{{ route('users.create', []) }}" class="btn btn-primary"><i
                        class="fa fa-plus"></i> @lang('ServiceCreateChargesAsaasClient new User')</a>
            </div>
        </div>
    </div>
@endsection
