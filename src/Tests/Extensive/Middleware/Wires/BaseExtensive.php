<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

abstract class BaseExtensive extends TestCase
{
    use TripwireTestTrait;

    protected string $tripwire;

    protected string $tripwireClass;

    // @phpstan-ignore-next-line
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->tripwire = $this->tripwireClass::NAME;

        parent::__construct($name);
    }

    /**
     * @return array<string>
     */
    protected static function loadFile(string $filename): array
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
