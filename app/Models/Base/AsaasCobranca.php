<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AsaasCobranca
 *
 * @property int|null $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $asaas_client_id
 * @property string|null $customer
 * @property string|null $billingType
 * @property float|null $value
 * @property Carbon|null $dueDate
 * @property string $paymentLink
 * @property string|null $status
 * @property string|null $invoiceUrl
 * @property Carbon $paymentDate
 * @property int|null $service_id
 *
 * @property Servico|null $servico
 *
 * @package App\Models\Base
 */
class AsaasCobranca extends Model
{
	protected $table = 'asaas_cobrancas';

	protected $casts = [
		'asaas_client_id' => 'int',
		'value' => 'float',
		'dueDate' => 'datetime',
		'paymentDate' => 'datetime',
		'service_id' => 'int'
	];

	protected $fillable = [
		'asaas_client_id',
		'customer',
		'billingType',
		'value',
		'dueDate',
		'paymentLink',
		'status',
		'invoiceUrl',
		'paymentDate',
		'service_id',
        "id_charge",
	];

	public function servico()
	{
		return $this->belongsTo(Servico::class, 'service_id');
	}
    public function asaas_client()
    {
        return $this->hasOne(AsaasClient::class, 'id','asaas_client_id');
    }


}
