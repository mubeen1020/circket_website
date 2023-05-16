<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BonusPoint
 * 
 * @property int $id
 * @property string $detail
 * @property int $season_id
 * @property int $tournament_id
 * @property int $isbonuspoint
 * @property string $point
 * @property int $createdby
 * @property int $domain_id
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class BonusPoint extends Model
{
	protected $table = 'bonus_points';

	protected $casts = [
		'season_id' => 'int',
		'tournament_id' => 'int',
		'isbonuspoint' => 'int',
		'createdby' => 'int',
		'domain_id' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'detail',
		'season_id',
		'tournament_id',
		'isbonuspoint',
		'point',
		'createdby',
		'domain_id',
		'isActive'
	];
}
