<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateAccepts extends Command
{
    /**
     * @var string
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $signature = 'generate:accepts';

    /**
     * @var string
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $description = 'Generate a file with random text that should be accepted';

    public function handle(): int
    {
        $locales = [
            'en_GB',
            'de_DE',
            'es_ES',
            'ar_SA',
            'ru_RU',
        ];

        foreach ($locales as $locale) {
            $accepts = $this->buildAccept($locale, 500);
            $contents = implode(PHP_EOL, $accepts);

            $filename = "AcceptsData-{$locale}.txt";
            Storage::disk('local')->put($filename, $contents);
        }

        return Command::SUCCESS;
    }

    public function buildText(string $locale): string
    {
        $name = fake($locale)->lastName();
        $realText = fake($locale)->realText(200); // characters

        return $name.'-'.$realText;
    }

    /**
     * @return array<string>
     */
    private function buildAccept(string $locale, int $lines = 2): array
    {
        $accepts = [];
        for ($i = 1; $i <= $lines; $i++) {
            $accepts[] = $this->buildText($locale);
        }

        return $accepts;
    }
}
