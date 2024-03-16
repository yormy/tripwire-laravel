<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Block;

use Carbon\CarbonImmutable;

use Illuminate\Support\Carbon;

class BlockDataRequest extends BlockData
{
    public function __construct(
        public string $blocked_ip,
        public ?string $internal_comments,
    ) {
    }

}
