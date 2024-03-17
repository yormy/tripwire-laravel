<?php

namespace Yormy\TripwireLaravel\Actions;

use Yormy\TripwireLaravel\Actions\Interfaces\ActionInterface;

class BlockIpAction implements ActionInterface
{
    public static function exec(): void
    {
        abort('blockIP');
    }
}
