<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DomainSubscription
 * 
 * @property int $id
 * @property int $domain_id
 * @property int $plan_id
 * @property Carbon $subscriptionstartdate
 * @property Carbon $subscriptionenddate
 * @property int $createdby
 * @property int $isActive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class DomainSubscription extends Model
{
	protected $table = 'domain_subscriptions';

	protected $casts = [
		'domain_id' => 'int',
		'plan_id' => 'int',
		'subscriptionstartdate' => 'datetime',
		'subscriptionenddate' => 'datetime',
		'createdby' => 'int',
		'isActive' => 'int'
	];

	protected $fillable = [
		'domain_id',
		'plan_id',
		'subscriptionstartdate',
		'subscriptionenddate',
		'createdby',
		'isActive'
	];
}
