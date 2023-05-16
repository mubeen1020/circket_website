<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Umpire
 * 
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $contact
 * @property int $is_certified
 * @property int $level
 * @property int $domain_id
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Umpire extends Model
{
	protected $table = 'umpires';

	protected $casts = [
		'is_certified' => 'int',
		'level' => 'int',
		'domain_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'name',
		'address',
		'contact',
		'is_certified',
		'level',
		'domain_id',
		'createdby',
		'isActive'
	];
}
