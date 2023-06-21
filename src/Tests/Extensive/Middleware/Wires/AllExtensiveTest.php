<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Xss;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;
use Faker\Factory as Faker;

class AllExtensiveTest extends TestCase
{
    protected $tripwireClass = Xss::class;
    public string $tripwire ='xss';

    protected array $accepts = [
        'saaaaaaa',
    ];

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->buildAccept();

        parent::__construct($name, $data, $dataName);
    }

    /**
     * @test
     *
     * @group aaa
     *
     * @dataProvider accepts
     */
    public function should_accept($accept): void
    {
        $this->setConfig();

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire($accept);

        $this->assertNotLogged($startCount);

        $this->assertEquals('next', $result);
    }


    private function buildAccept()
    {
        $locales = [
            'en_GB',
            'de_DE',
            'es_ES',
            'ar_SA',
            'ru_RU'
        ];

        foreach ($locales as $locale) {
            $faker = Faker::create($locale);
            for ($i = 1; $i <= 10; $i++) {
                $this->accepts[] = $this->buildText($faker);
            }
        }

        $this->formatProvider();
    }

    public function buildText($faker): string
    {

        $name = $faker->lastName();
        $realText =$faker->realText(50); // characters

        $text = $name. '-'. $realText;
        return $text;
    }

    public function formatProvider()
    {
        foreach ($this->accepts as $index => $accept) {
            $this->accepts[$index] = str_replace(PHP_EOL, '', $this->accepts[$index]);
        }
    }

    private function makeSentence(): string
    {
        return fake()->sentence();
    }

    protected function setConfig(): void
    {
        $settings = ['code' => 409];
        config(["tripwire_wires.$this->tripwire.enabled" => true]);
        config(["tripwire_wires.$this->tripwire.methods" => ['*']]);
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => $settings]);
    }

    protected function triggerTripwire(string $input)
    {
        $request = $this->app->request;
        $request->query->set('foo', $input);

        $wire = new $this->tripwireClass($request);

        return $wire->handle($request, $this->getNextClosure());
    }

    protected function assertNotLogged($startCount): void
    {
        $this->assertEquals($startCount, TripwireLog::count());
    }

    public function accepts(): array
    {
        $providerArray = [];
        foreach ($this->accepts as $accept) {
            $providerArray[$accept] = [$accept];
        }

        return $providerArray;
    }

}
