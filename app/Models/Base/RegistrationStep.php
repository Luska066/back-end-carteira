<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Step;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RegistrationStep
 * 
 * @property int|null $id
 * @property int|null $id_step
 * @property int|null $id_student
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Step|null $step
 * @property Student|null $student
 *
 * @package App\Models\Base
 */
class RegistrationStep extends Model
{
	protected $table = 'registration_steps';

	protected $casts = [
		'id_step' => 'int',
		'id_student' => 'int'
	];

	protected $fillable = [
		'id_step',
		'id_student'
	];

	public function step()
	{
		return $this->belongsTo(Step::class, 'id_step');
	}

	public function student()
	{
		return $this->belongsTo(Student::class, 'id_student');
	}
}
