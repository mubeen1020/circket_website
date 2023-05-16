<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tournament
 * 
 * @property int $id
 * @property string $name
 * @property Carbon $tournamentstartdate
 * @property Carbon $tournamentenddate
 * @property string $description
 * @property int $season_id
 * @property int $isgroup
 * @property int $domain_id
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Tournament extends Model
{
	protected $table = 'tournaments';

	protected $casts = [
		'tournamentstartdate' => 'datetime',
		'tournamentenddate' => 'datetime',
		'season_id' => 'int',
		'isgroup' => 'int',
		'domain_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'name',
		'tournamentstartdate',
		'tournamentenddate',
		'description',
		'season_id',
		'isgroup',
		'domain_id',
		'createdby',
		'isActive'
	];
}
