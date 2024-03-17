<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Actions;

use Yormy\TripwireLaravel\Actions\Interfaces\ActionInterface;

class BlockAccountAction implements ActionInterface
{
    public static function exec(): void
    {
        //event(new TripwireBlockedAccount()); ?
        abort('block account');
    }
}
