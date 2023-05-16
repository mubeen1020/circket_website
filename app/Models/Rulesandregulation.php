<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Rulesandregulation
 * 
 * @property int $id
 * @property string $content
 * @property int $year
 * @property int $domain_id
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Rulesandregulation extends Model
{
	protected $table = 'rulesandregulations';

	protected $casts = [
		'year' => 'int',
		'domain_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'content',
		'year',
		'domain_id',
		'createdby',
		'isActive'
	];
}
