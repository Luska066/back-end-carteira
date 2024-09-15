<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\AsaasClient;
use App\Models\Carteira;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Student
 *
 * @property int|null $id
 * @property string|null $name
 * @property int|null $user_id
 * @property string|null $email
 * @property string|null $nome_completo
 * @property int $client_id
 * @property int|null $carteira_id
 * @property string $image_url
 * @property string $cpf
 * @property string $matricula
 *
 * @property User|null $user
 * @property AsaasClient $asaas_client
 * @property Carteira|null $carteira
 *
 * @package App\Models\Base
 */
class Student extends Model
{
	protected $table = 'students';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'client_id' => 'int',
		'carteira_id' => 'int'
	];

	protected $fillable = [
		'name',
		'user_id',
		'email',
		'nome_completo',
		'client_id',
		'carteira_id',
		'image_url',
		'cpf',
		'matricula',
        'data_nascimento',
        'pdf_url'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function asaas_client()
	{
		return $this->belongsTo(AsaasClient::class, 'id','student_id',"client_id");
	}

	public function carteira()
	{
		return $this->belongsTo(Carteira::class);
	}
}
