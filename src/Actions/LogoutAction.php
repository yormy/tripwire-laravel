<?php

namespace Yormy\TripwireLaravel\Actions;

use Yormy\TripwireLaravel\Actions\Interfaces\ActionInterface;

class LogoutAction implements ActionInterface
{
    public static function exec(): void
    {
        abort('do logoout, to implement'); //todo
    }}
