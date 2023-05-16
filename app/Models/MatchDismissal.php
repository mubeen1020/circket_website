<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MatchDismissal
 * 
 * @property int $id
 * @property int $fixture_id
 * @property int $fixturescores_id
 * @property int $inningnumber
 * @property int $dismissal_id
 * @property int $outplayer_id
 * @property int $outbyplayer_id
 * @property int $createdby
 * @property int $domain_id
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MatchDismissal extends Model
{
	protected $table = 'match_dismissals';

	protected $casts = [
		'fixture_id' => 'int',
		'fixturescores_id' => 'int',
		'inningnumber' => 'int',
		'dismissal_id' => 'int',
		'outplayer_id' => 'int',
		'outbyplayer_id' => 'int',
		'createdby' => 'int',
		'domain_id' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'fixture_id',
		'fixturescores_id',
		'inningnumber',
		'dismissal_id',
		'outplayer_id',
		'outbyplayer_id',
		'createdby',
		'domain_id',
		'isActive'
	];
}
