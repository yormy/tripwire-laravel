<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace Yormy\TripwireLaravel\Models{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel withoutTrashed()
 */
	class BaseModel extends \Eloquent {}
}

namespace Yormy\TripwireLaravel\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Yormy\TripwireLaravel\Models\TripwireLog> $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $user
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock byBrowser(string $browserFingerprint)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock byIp(string $ipAddress)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock byUserId(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock byUserType(string $userType)
 * @method static \Yormy\TripwireLaravel\Database\Factories\TripwireBlockFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock notIgnore()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock query()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock withinDays(int $days)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock withoutTrashed()
 */
	class TripwireBlock extends \Eloquent {}
}

namespace Yormy\TripwireLaravel\Models{
/**
 * 
 *
 * @property-read \Yormy\TripwireLaravel\Models\TripwireBlock|null $block
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $user
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog byBrowser(string $browserFingerprint)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog byIp(string $ipAddress)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog byUserId(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog byUserType(string $userType)
 * @method static \Yormy\TripwireLaravel\Database\Factories\TripwireLogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog types(array $codes)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog within(int $minutes)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog withoutTrashed()
 */
	class TripwireLog extends \Eloquent {}
}

