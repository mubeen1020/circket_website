<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ground
 * 
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $city_id
 * @property int $domain_id
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Ground extends Model
{
	protected $table = 'grounds';

	protected $casts = [
		'city_id' => 'int',
		'domain_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'name',
		'address',
		'city_id',
		'domain_id',
		'createdby',
		'isActive'
	];
}
