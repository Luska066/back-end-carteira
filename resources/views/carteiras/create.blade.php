@extends('carteiras.layout')

@section('carteiras.content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('carteiras.index', compact([])) }}"> Carteiras</a>
                    </li>
                    <li class="breadcrumb-item">@lang('ServiceCreateChargesAsaasClient new')</li>
                </ol>
            </div>

            <div class="card-body">
                <form action="{{ route('carteiras.store', []) }}" method="POST" class="m-0 p-0">
                    <div id="card-body" class="card-body">
                        @csrf
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Estudante :</label>
                            <div class="d-flex flex-row align-items-center justify-content-between">
                                <select name="student_id" id="student_id" class="form-control form-select flex-grow-1"
                                        required>
                                    <option value="">Select Student</option>
                                    @foreach($students as $student)
                                        <option
                                            value="{{ $student->id }}" {{ @old('student_id') == $student->id ? "selected" : "" }}>{{ $student->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('student_id'))
                                <div class='error small text-danger'>{{$errors->first('student_id')}}</div>
                            @endif
                        </div>

                    </div>

                    <div class="card-footer">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <a href="{{ route('carteiras.index', []) }}" class="btn btn-light">@lang('Cancel')</a>
                            <button type="submit" class="btn btn-primary">@lang('ServiceCreateChargesAsaasClient new Carteira')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("student_id").addEventListener('change', function (event) {
            const value = event.target.value;
            let data = null
            axios
                .get(`/find/courses/${value}`)
                .then(res => {
                    data = res.data
                }).finally(() => {

            const element = `
                 <div class="mb-3">
                    <label for="course_id" class="form-label">Cursos:</label>
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <select name="course_id" id="course_id" class="form-control form-select flex-grow-1" required>
                         ${data.map(courses => {
                console.log(courses)
                             return `
                                <option value="${courses.id}">
                                    ${courses.nomeCurso}
                                </option>}`
                        })}
                        </select>
                    </div>
                     @if($errors->has('course_id'))
                        <div class='error small text-danger'>{{$errors->first('course_id')}}</div>
                     @endif
                        </div>`
                document.getElementById("card-body").innerHTML += element
            })
        });

    </script>
@endsection
