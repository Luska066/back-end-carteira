<?php

namespace App\Models;

use App\Models\Base\StudentCurso as BaseStudentCurso;
use Illuminate\Http\Request;

class StudentCurso extends BaseStudentCurso
{
    public function buscarCursoComBaseNoEstudante(Request $request,Student $student){
        return $this->where('student_id',$student->id)->get();
    }
}
