<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TournamentGroup
 * 
 * @property int $id
 * @property int $tournament_id
 * @property int $group_id
 * @property int $team_id
 * @property int $domain_id
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class TournamentGroup extends Model
{
	protected $table = 'tournament_groups';

	protected $casts = [
		'tournament_id' => 'int',
		'group_id' => 'int',
		'team_id' => 'int',
		'domain_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'tournament_id',
		'group_id',
		'team_id',
		'domain_id',
		'createdby',
		'isActive'
	];
}
