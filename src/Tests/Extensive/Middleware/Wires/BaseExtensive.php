<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class BaseExtensive extends TestCase
{
    use TripwireTestTrait;

    protected array $accepting = [];

    protected array $violations = [];

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->violations = file($this->violationsDataFile);

        // ignore commented out with #
        foreach ($this->violations as $index => $violation) {
            if (str_starts_with($violation, '##') || strlen(trim($violation)) === 0) {
                unset($this->violations[$index]);
            } else {
                $this->violations[$index] = str_replace(PHP_EOL, '', $this->violations[$index]);
            }
        }
        $this->tripwire = $this->tripwireClass::NAME;

        parent::__construct($name, $data, $dataName);
    }

    public function assertAccept(string $accept): void
    {
        $this->setConfig();

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire($accept);

        $this->assertNotLogged($startCount);

        $this->assertEquals('next', $result);
    }

    public function assertBlock(string $violation): void
    {
        $this->setConfig();

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire($violation);

        $this->assertNotEquals('next', $result, 'Should block but did not');
        $this->assertEquals(409, $result->getStatusCode());

        $this->assertLogAddedToDatabase($startCount);
    }
}
