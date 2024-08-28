<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Carteira
 *
 * @property int|null $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $student_id
 * @property Student|null $student
 * @property string|null $nomeAluno
 * @property string|null $matricula
 * @property string|null $passCode
 * @property Carbon|null $data_nascimento
 * @property string|null $nome_curso
 * @property Carbon $dataInicioCurso
 * @property Carbon $dataFimCurso
 * @property string $carteiraPdfUrl
 * @property Carbon $expiredAt
 *
 * @package App\Models\Base
 */
class Carteira extends Model
{
	protected $table = 'carteiras';

	protected $casts = [
		'student_id' => 'int',
		'data_nascimento' => 'datetime',
		'dataInicioCurso' => 'datetime',
		'dataFimCurso' => 'datetime',
		'expiredAt' => 'datetime'
	];

	protected $fillable = [
		'student_id',
		'nomeAluno',
		'uuid',
		'matricula',
		'passCode',
		'data_nascimento',
		'nome_curso',
		'dataInicioCurso',
		'dataFimCurso',
		'carteiraPdfUrl',
		'expiredAt',
        "asaas_cobranca_id",
        "url_access_card"
	];

    public function student(){
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function cobranca(){
        return $this->hasMany(AsaasCobranca::class, 'id','asaas_cobranca_id');
    }
}
