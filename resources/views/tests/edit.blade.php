@extends('tests.layout')

@section('test.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ implode('/', ['','tests']) }}"> Test</a></li>
                    <li class="breadcrumb-item">@lang('Edit Test') #{{$test->id}}</li>
                </ol>
            </div>
            <div class="card-body">
                <form action="{{ route('tests.update', compact('test')) }}" method="POST" class="m-0 p-0">
                    @method('PUT')
                    @csrf
                    <div class="card-body">

                    </div>
                    <div class="card-footer">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <a href="{{ route('tests.index', []) }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">@lang('Update Test')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
