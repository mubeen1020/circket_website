<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectModule
 * 
 * @property int $id
 * @property string $module
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class ProjectModule extends Model
{
	protected $table = 'project_modules';

	protected $casts = [
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'module',
		'createdby',
		'isActive'
	];
}
