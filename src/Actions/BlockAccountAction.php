<?php

namespace Yormy\TripwireLaravel\Actions;

use Yormy\TripwireLaravel\Actions\Interfaces\ActionInterface;

class BlockAccountAction implements ActionInterface
{
    public static function exec(): void
    {
        //event(new TripwireBlockedAccount()); ?
        dd('block account');
    }
}
