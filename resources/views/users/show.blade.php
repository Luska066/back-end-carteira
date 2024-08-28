@extends('users.layout')

@section('users.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('users.index', compact([])) }}"> Users</a></li>
                    <li class="breadcrumb-item">@lang('User') #{{$user->id}}</li>
                </ol>

                <a href="{{ route('users.index', []) }}" class="btn btn-light"><i class="fa fa-caret-left"></i> Back</a>
            </div>

            <div class="card-body">
                <table class="table table-striped">
    <tbody>
    <tr>
        <th scope="row">ID:</th>
        <td>{{$user->id}}</td>
    </tr>
            <tr>
            <th scope="row">Name:</th>
            <td>{{ $user->name ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Email:</th>
            <td>{{ $user->email ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Email Verified At:</th>
            <td>{{ $user->email_verified_at ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Password:</th>
            <td>{{ $user->password ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Remember Token:</th>
            <td>{{ $user->remember_token ?: "(blank)" }}</td>
        </tr>
                <tr>
            <th scope="row">Created at</th>
            <td>{{Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i:s')}}</td>
        </tr>
        <tr>
            <th scope="row">Updated at</th>
            <td>{{Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i:s')}}</td>
        </tr>
        </tbody>
</table>

            </div>

            <div class="card-footer d-flex flex-column flex-md-row align-items-center justify-content-end">
                <a href="{{ route('users.edit', compact('user')) }}" class="btn btn-info text-nowrap me-1"><i class="fa fa-edit"></i> @lang('Edit')</a>
                <form action="{{ route('users.destroy', compact('user')) }}" method="POST" class="m-0 p-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger text-nowrap"><i class="fa fa-trash"></i> @lang('Delete')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
