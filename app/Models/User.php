<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string $fullname
 * @property int $system_user_type_id
 * @property Carbon $dob
 * @property string $contacta
 * @property string $contactb
 * @property string $address
 * @property string $email
 * @property string $password
 * @property string $agencyid
 * @property int $domain_id
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $remember_token
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';

	protected $casts = [
		'system_user_type_id' => 'int',
		'dob' => 'datetime',
		'domain_id' => 'int',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'fullname',
		'system_user_type_id',
		'dob',
		'contacta',
		'contactb',
		'address',
		'email',
		'password',
		'agencyid',
		'domain_id',
		'createdby',
		'isActive',
		'remember_token'
	];
}
