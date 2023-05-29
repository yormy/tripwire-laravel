<?php

namespace Yormy\TripwireLaravel\Actions;

use Yormy\TripwireLaravel\Actions\Interfaces\ActionInterface;

class LogoutAction implements ActionInterface
{
    public static function exec(): void
    {
        dd('do logoout, to implement');
    }
}
