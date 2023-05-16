<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PlanFeature
 * 
 * @property int $id
 * @property string $detail
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PlanFeature extends Model
{
	protected $table = 'plan_features';

	protected $casts = [
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'detail',
		'createdby',
		'isActive'
	];
}
