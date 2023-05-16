<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PlanConfiguration
 * 
 * @property int $id
 * @property int $plan_id
 * @property int $feature_id
 * @property string $detail
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PlanConfiguration extends Model
{
	protected $table = 'plan_configurations';

	protected $casts = [
		'plan_id' => 'int',
		'feature_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'plan_id',
		'feature_id',
		'detail',
		'createdby',
		'isActive'
	];
}
