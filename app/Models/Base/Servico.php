<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Servico
 *
 * @property int|null $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $url
 * @property string $method
 * @property string|null $service_type
 * @property int $service_id
 * @property string $payload
 * @property string $response
 * @property int $exec
 * @property int $run_by_administrator
 *
 * @package App\Models\Base
 */
class Servico extends Model
{
	protected $table = 'servicos';

	protected $casts = [
		'service_id' => 'int',
		'exec' => 'int',
		'run_by_administrator' => 'int'
	];

	protected $fillable = [
		'url',
		'method',
		'service_type',
		'service_id',
		'payload',
		'response',
		'exec',
		'run_by_administrator',
        'user_id',
	];
}
