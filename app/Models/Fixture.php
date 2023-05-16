<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Fixture
 * 
 * @property int $id
 * @property int $tournament_id
 * @property int $group_id
 * @property int $team_id_a
 * @property int $team_id_b
 * @property string $match_description
 * @property Carbon $match_startdate
 * @property Carbon $match_enddate
 * @property Carbon $match_starttime
 * @property Carbon $match_endtime
 * @property int $numberofover
 * @property int $overlimit
 * @property int $ground_id
 * @property int $toss_winning_team_id
 * @property int $first_inning_team_id
 * @property int $second_inning_team_id
 * @property int $running_inning
 * @property int|null $front_batsman
 * @property int|null $runner_batsman
 * @property int|null $bowler
 * @property int $winning_team_id
 * @property int $lossing_team_id
 * @property int $manofmatch_player_id
 * @property int $is_tie_match
 * @property string $match_result_description
 * @property int $domain_id
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Fixture extends Model
{
	protected $table = 'fixtures';

	protected $casts = [
		'tournament_id' => 'int',
		'group_id' => 'int',
		'team_id_a' => 'int',
		'team_id_b' => 'int',
		'match_startdate' => 'datetime',
		'match_enddate' => 'datetime',
		'match_starttime' => 'datetime',
		'match_endtime' => 'datetime',
		'numberofover' => 'int',
		'overlimit' => 'int',
		'ground_id' => 'int',
		'toss_winning_team_id' => 'int',
		'first_inning_team_id' => 'int',
		'second_inning_team_id' => 'int',
		'running_inning' => 'int',
		'front_batsman' => 'int',
		'runner_batsman' => 'int',
		'bowler' => 'int',
		'winning_team_id' => 'int',
		'lossing_team_id' => 'int',
		'manofmatch_player_id' => 'int',
		'is_tie_match' => 'int',
		'domain_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'tournament_id',
		'group_id',
		'team_id_a',
		'team_id_b',
		'match_description',
		'match_startdate',
		'match_enddate',
		'match_starttime',
		'match_endtime',
		'numberofover',
		'overlimit',
		'ground_id',
		'toss_winning_team_id',
		'first_inning_team_id',
		'second_inning_team_id',
		'running_inning',
		'front_batsman',
		'runner_batsman',
		'bowler',
		'winning_team_id',
		'lossing_team_id',
		'manofmatch_player_id',
		'is_tie_match',
		'match_result_description',
		'domain_id',
		'createdby',
		'isActive'
	];
}
