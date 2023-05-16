<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ExtraRun
 * 
 * @property int $id
 * @property string $detail
 * @property string $runs
 * @property int $createdby
 * @property int $domain_id
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class ExtraRun extends Model
{
	protected $table = 'extra_runs';

	protected $casts = [
		'createdby' => 'int',
		'domain_id' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'detail',
		'runs',
		'createdby',
		'domain_id',
		'isActive'
	];
}
