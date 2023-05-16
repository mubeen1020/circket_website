<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SystemUserType
 * 
 * @property int $id
 * @property string $usertype
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SystemUserType extends Model
{
	protected $table = 'system_user_types';

	protected $casts = [
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'usertype',
		'createdby',
		'isActive'
	];
}
