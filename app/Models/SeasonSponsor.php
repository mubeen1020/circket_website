<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SeasonSponsor
 * 
 * @property int $id
 * @property string $season_id
 * @property string $sponsor_id
 * @property int $createdby
 * @property int $domain_id
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SeasonSponsor extends Model
{
	protected $table = 'season_sponsors';

	protected $casts = [
		'createdby' => 'int',
		'domain_id' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'season_id',
		'sponsor_id',
		'createdby',
		'domain_id',
		'isActive'
	];
}
