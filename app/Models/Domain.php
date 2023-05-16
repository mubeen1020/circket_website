<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Domain
 * 
 * @property int $id
 * @property string $domain
 * @property string $url
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Domain extends Model
{
	protected $table = 'domains';

	protected $casts = [
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'domain',
		'url',
		'createdby',
		'isActive'
	];
}
