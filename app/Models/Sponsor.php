<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sponsor
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $website
 * @property int $createdby
 * @property int $domain_id
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Sponsor extends Model
{
	protected $table = 'sponsors';

	protected $casts = [
		'createdby' => 'int',
		'domain_id' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'name',
		'email',
		'website',
		'createdby',
		'domain_id',
		'isActive'
	];
}
