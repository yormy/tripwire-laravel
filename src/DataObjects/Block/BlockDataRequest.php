<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Block;

class BlockDataRequest extends BlockData
{
    public function __construct(
        public string $blocked_ip,
        public ?string $internal_comments,
    ) {
    }
}
