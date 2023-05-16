<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Country
 * 
 * @property int $id
 * @property string $name
 * @property int $createdby
 * @property int $domain_id
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Country extends Model
{
	protected $table = 'countries';

	protected $casts = [
		'createdby' => 'int',
		'domain_id' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'name',
		'createdby',
		'domain_id',
		'isActive'
	];
}
