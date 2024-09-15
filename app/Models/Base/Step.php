<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Step
 * 
 * @property int|null $id
 * @property string|null $name
 * @property string|null $redirect_uri
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models\Base
 */
class Step extends Model
{
	use SoftDeletes;
	protected $table = 'steps';

	protected $fillable = [
		'name',
		'redirect_uri'
	];
}
