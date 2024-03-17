<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Actions\Interfaces;

interface ActionInterface
{
    public static function exec(): void;
}
