<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Tests;

class TestConfig
{
    public static function setup(): void
    {
        config(['tripwire' => require __DIR__.'/../../config/tripwire.php']);
        config(['tripwire_wires' => require __DIR__.'/../../config/tripwire_wires.php']);
        config(['app.key' => 'base64:yNmpwO5YE6xwBz0enheYLBDslnbslodDqK1u+oE5CEE=']);
        config(['mail.default' => 'log']);
    }
}
