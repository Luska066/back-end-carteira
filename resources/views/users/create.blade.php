@extends('users.layout')

@section('users.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('users.index', compact([])) }}"> Users</a></li>
                    <li class="breadcrumb-item">@lang('ServiceCreateChargesAsaasClient new')</li>
                </ol>
            </div>

            <div class="card-body">
                <form action="{{ route('users.store', []) }}" method="POST" class="m-0 p-0">
                    <div class="card-body">
                        @csrf
                        <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" name="name" id="name" class="form-control" value="{{@old('name')}}" required/>
        @if($errors->has('name'))
			<div class='error small text-danger'>{{$errors->first('name')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" name="email" id="email" class="form-control" value="{{@old('email')}}" required/>
        @if($errors->has('email'))
			<div class='error small text-danger'>{{$errors->first('email')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="email_verified_at" class="form-label">Email Verified At:</label>
        <input type="email" name="email_verified_at" id="email_verified_at" class="form-control" value="{{@old('email_verified_at')}}" />
        @if($errors->has('email_verified_at'))
			<div class='error small text-danger'>{{$errors->first('email_verified_at')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" name="password" id="password" class="form-control" value="{{@old('password')}}" required/>
        @if($errors->has('password'))
			<div class='error small text-danger'>{{$errors->first('password')}}</div>
		@endif
    </div>
    <div class="mb-3">
        <label for="remember_token" class="form-label">Remember Token:</label>
        <input type="text" name="remember_token" id="remember_token" class="form-control" value="{{@old('remember_token')}}" />
        @if($errors->has('remember_token'))
			<div class='error small text-danger'>{{$errors->first('remember_token')}}</div>
		@endif
    </div>

                    </div>

                    <div class="card-footer">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <a href="{{ route('users.index', []) }}" class="btn btn-light">@lang('Cancel')</a>
                            <button type="submit" class="btn btn-primary">@lang('ServiceCreateChargesAsaasClient new User')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
