<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TeamPlayer
 * 
 * @property int $id
 * @property int $team_id
 * @property int $player_id
 * @property int $iscaptain
 * @property int $domain_id
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class TeamPlayer extends Model
{
	protected $table = 'team_players';

	protected $casts = [
		'team_id' => 'int',
		'player_id' => 'int',
		'iscaptain' => 'int',
		'domain_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'team_id',
		'player_id',
		'iscaptain',
		'domain_id',
		'createdby',
		'isActive'
	];
}
