<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\AsaasCobranca;
use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AsaasClient
 *
 * @property int|null $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $asaas_cobranca_id
 * @property string|null $costumer_id
 * @property string|null $name
 * @property string|null $cpfCnpj
 * @property string|null $email
 * @property int|null $student_id
 * @property int|null $service_id
 *
 * @property AsaasCobranca $asaas_cobranca
 * @property Servico|null $servico
 *
 * @package App\Models\Base
 */
class AsaasClient extends Model
{
	protected $table = 'asaas_clients';

	protected $casts = [
//		'student_id' => 'int',
//		'service_id' => 'int'
	];

	protected $fillable = [
		'asaas_cobranca_id',
		'costumer_id',
		'name',
		'cpfCnpj',
		'email',
		'student_id',
		'service_id'
	];

	public function asaas_cobranca()
	{
		return $this->belongsTo(AsaasCobranca::class);
	}

	public function servico()
	{
		return $this->belongsTo(Servico::class, 'service_id');
	}

    public function student()
    {
        return $this->hasOne(\App\Models\Student::class, 'id', 'student_id');
    }
}
