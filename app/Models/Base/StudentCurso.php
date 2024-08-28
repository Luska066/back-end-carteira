<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StudentCurso
 *
 * @property int|null $id
 * @property int|null $student_id
 * @property int|null $nomeCurso
 * @property int|null $dataInicioCurso
 * @property int|null $dataFimCurso
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Student|null $student
 *
 * @package App\Models\Base
 */
class StudentCurso extends Model
{
	protected $table = 'student_cursos';

	protected $casts = [
		'student_id' => 'int',
	];

	protected $fillable = [
		'student_id',
		'nomeCurso',
		'dataInicioCurso',
		'dataFimCurso'
	];

	public function student()
	{
		return $this->belongsTo(Student::class);
	}
}
