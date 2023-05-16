<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SystemUserTypeAccessmodule
 * 
 * @property int $id
 * @property int $module_id
 * @property int $usertype_id
 * @property int $canAdd
 * @property int $canView
 * @property int $canDelete
 * @property int $canEdit
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SystemUserTypeAccessmodule extends Model
{
	protected $table = 'system_user_type_accessmodules';

	protected $casts = [
		'module_id' => 'int',
		'usertype_id' => 'int',
		'canAdd' => 'int',
		'canView' => 'int',
		'canDelete' => 'int',
		'canEdit' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'module_id',
		'usertype_id',
		'canAdd',
		'canView',
		'canDelete',
		'canEdit',
		'createdby',
		'isActive'
	];
}
