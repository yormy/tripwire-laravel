<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class BaseExtensive extends TestCase
{
    use TripwireTestTrait;

    protected array $accepts = [];

    protected array $violations = [];

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        if (isset($this->violationsDataFile)) {
            $this->violations = $this->loadFile($this->violationsDataFile);
        }

        if (isset($this->acceptsDataFile)) {
            $this->accepts = $this->loadFile($this->acceptsDataFile);
        }

        $this->tripwire = $this->tripwireClass::NAME;

        parent::__construct($name, $data, $dataName);
    }

    private function loadFile(string $filename): array
    {
        $data = file($filename);

        // ignore commented out with #
        foreach ($data as $index => $value) {
            if (str_starts_with($value, '##') || strlen(trim($value)) === 0) {
                unset($data[$index]);
            } else {
                $data[$index] = str_replace(PHP_EOL, '', $data[$index]);
            }
        }

        return $data;
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
