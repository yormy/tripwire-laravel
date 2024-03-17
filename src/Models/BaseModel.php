<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Yormy\Xid\Models\Traits\Xid;

class BaseModel extends Model
{
    use QueryCacheable;
    use SoftDeletes;
    use Xid;

    // Cachables
    /**
     * @var int $cacheFor
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    public $cacheFor = 4 * 60 * 60; // cache time, in seconds

    /**
     * @var bool $flushCacheOnUpdate
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected static $flushCacheOnUpdate = true;
}
