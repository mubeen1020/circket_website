<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Group
 * 
 * @property int $id
 * @property string $name
 * @property int $domain_id
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Group extends Model
{
	protected $table = 'groups';

	protected $casts = [
		'domain_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'name',
		'domain_id',
		'createdby',
		'isActive'
	];
}
