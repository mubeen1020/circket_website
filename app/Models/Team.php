<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 * 
 * @property int $id
 * @property string $name
 * @property string $owner
 * @property string $isclub
 * @property string $clubname
 * @property string $address
 * @property string $town
 * @property string $state
 * @property string $country
 * @property string $email
 * @property int $domain_id
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Team extends Model
{
	protected $table = 'teams';

	protected $casts = [
		'domain_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'name',
		'owner',
		'isclub',
		'clubname',
		'address',
		'town',
		'state',
		'country',
		'email',
		'domain_id',
		'createdby',
		'isActive'
	];
}
