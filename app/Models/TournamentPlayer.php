<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TournamentPlayer
 * 
 * @property int $id
 * @property int $tournament_id
 * @property int $team_id
 * @property int $player_id
 * @property int $domain_id
 * @property int $createdby
 * @property Carbon|null $updated_at
 * @property Carbon|null $created_at
 *
 * @package App\Models
 */
class TournamentPlayer extends Model
{
	protected $table = 'tournament_players';

	protected $casts = [
		'tournament_id' => 'int',
		'team_id' => 'int',
		'player_id' => 'int',
		'domain_id' => 'int',
		'createdby' => 'int'
	];

	protected $fillable = [
		'tournament_id',
		'team_id',
		'player_id',
		'domain_id',
		'createdby'
	];
}
