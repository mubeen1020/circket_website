<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Player
 * 
 * @property int $id
 * @property string $fullname
 * @property string $eoclid
 * @property string $contact
 * @property string $gender
 * @property Carbon $dob
 * @property string $address
 * @property string $town
 * @property string $state
 * @property string $country
 * @property string|null $occupation
 * @property string|null $employer
 * @property string $email
 * @property int $domain_id
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Player extends Model
{
	protected $table = 'players';

	protected $casts = [
		'dob' => 'datetime',
		'domain_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'fullname',
		'eoclid',
		'contact',
		'gender',
		'dob',
		'address',
		'town',
		'state',
		'country',
		'occupation',
		'employer',
		'email',
		'battingStyle',
		'bowlingstyle',
		'domain_id',
		'createdby',
		'isActive'
	];
}
