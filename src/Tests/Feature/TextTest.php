<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class TextTest extends TestCase
{
    use TripwireTestTrait;

    protected string $tripwire ='text';

    protected $tripwireClass = Text::class;

    protected array $violations = [
        'aaa',
        '\x00',
        'k'
    ];

    /**
     * @test
     */
    public function respond_as_code_expects_code()
    {
        $this->setConfig();

        foreach ($this->violations as $violation) {
            $startCount = TripwireLog::count();

            $result = $this->triggerTripwire($violation);

            $this->assertLogAddedToDatabase($startCount);

            $this->assertEquals($result->getStatusCode(), 409);
        }
    }



}
