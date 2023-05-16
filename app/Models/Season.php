<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Season
 * 
 * @property int $id
 * @property string $name
 * @property Carbon $seasonstartdate
 * @property Carbon $seasonenddate
 * @property string $description
 * @property int $domain_id
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Season extends Model
{
	protected $table = 'seasons';

	protected $casts = [
		'seasonstartdate' => 'datetime',
		'seasonenddate' => 'datetime',
		'domain_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'name',
		'seasonstartdate',
		'seasonenddate',
		'description',
		'domain_id',
		'createdby',
		'isActive'
	];
}
