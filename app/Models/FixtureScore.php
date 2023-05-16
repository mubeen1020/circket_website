<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FixtureScore
 * 
 * @property int $id
 * @property int $fixture_id
 * @property int $inningnumber
 * @property int $overnumber
 * @property int $ballnumber
 * @property int $playerId
 * @property int $bowlerId
 * @property string $balltype
 * @property int $runs
 * @property int $isout
 * @property int $isfour
 * @property int $issix
 * @property int $issuperoverball
 * @property int $createdby
 * @property int $domain_id
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class FixtureScore extends Model
{
	protected $table = 'fixture_scores';

	protected $casts = [
		'fixture_id' => 'int',
		'inningnumber' => 'int',
		'overnumber' => 'int',
		'ballnumber' => 'int',
		'playerId' => 'int',
		'bowlerId' => 'int',
		'runs' => 'int',
		'isout' => 'int',
		'isfour' => 'int',
		'issix' => 'int',
		'issuperoverball' => 'int',
		'createdby' => 'int',
		'domain_id' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'fixture_id',
		'inningnumber',
		'overnumber',
		'ballnumber',
		'playerId',
		'bowlerId',
		'balltype',
		'runs',
		'isout',
		'isfour',
		'issix',
		'issuperoverball',
		'createdby',
		'domain_id',
		'isActive'
	];
}
