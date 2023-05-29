<?php

namespace Yormy\TripwireLaravel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Yormy\TripwireLaravel\Actions\AnonymizeWithoutModel;
use Yormy\TripwireLaravel\Events\ModelsEncrypted;
use Yormy\TripwireLaravel\Traits\Anonymizable;
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;

/**
 * @psalm-suppress UndefinedThisPropertyFetch
 *
 */
class EncryptDbCommand extends Command
{
    protected $signature = 'db:encrypt
                                {--model=* : Class names of the models to be anonymized}
                                {--key= : Encryption key}
                                {--pretend : Display the number of encrypted records found instead of actioning on them}';

    protected $description = 'Ecnrypt all models';

    private float $startTime;

    /**
     * The console components factory.
     *
     * @var \Illuminate\Console\View\Components\Factory
     *
     * @internal This property is not meant to be used or overwritten outside the framework.
     */
    protected $components;

    /**
     * @psalm-suppress MissingReturnType
     */
    public function handle(Dispatcher $events)
    {
        if (empty($encryptionKey = $this->option('key'))) {
            $this->components->error('No encryption key set use: ciphersweet:generate to generate a key and then specify with --key= ');
            die();
        }

        $this->startTime = microtime(true);

        $models = $this->getAllModels();

        if ($models->isEmpty()) {
            $this->components->info('No encryptable models found.');

            return null;
        }

        if ($this->option('pretend')) {
            /**
             * @psalm-suppress MixedArgument
             */
            $models->each(fn ($model) => $this->pretendToEncrypt($model));
            return null;
        }

        $encrypting = [];
        $events->listen(ModelsEncrypted::class, function (ModelsEncrypted $event) use (&$encrypting) {
            /**
             * @var string[] $encrypting
             */
            if (! in_array($event->model, $encrypting)) {
                $encrypting[] = $event->model;
            }

            $this->components->twoColumnDetail($event->model, "{$event->count} records ({$event->durationInSeconds} seconds)");
        });

        /**
         * @psalm-suppress MixedArgument
         */
        $models->each(fn ($model) => $this->encryptModel($model));

        $events->forget(ModelsEncrypted::class);

        $durationInMinutes = round((microtime(true) - $this->startTime)/60, 1);
        $this->components->twoColumnDetail('TOTAL DURATION', "{$durationInMinutes} minutes");

        $this->call('cache:clear');

        return null;
    }


    protected function encryptModel(string $model): void
    {
        $startTime = microtime(true);

        $encryptionKey = $this->option('key');

        try {
            $instance = $this->getClass($model);
        } catch (\Throwable) {
            return; // if the class cannot be created (ie abstract class) just skip it
        }

        $this->call('ciphersweet:encrypt', [
            'model' => $model,
            'newKey' => $encryptionKey
        ]);

        $durationInSeconds = round(microtime(true) - $startTime, 0);

        event(new ModelsEncrypted($model, $instance->count(), $durationInSeconds));
    }

    private function getAllModels(): Collection
    {
        $models = config('ciphersweet-ext.models');

        $allowed = collect();
        foreach ($models as $model) {
            if (
                $this->classExists($model) &&
                $this->isEncryptable($model)
            ) {
                $allowed->add($model);
            }
        }

        return $allowed;
    }


    protected function classExists(string $model)
    {
        return class_exists($model);
    }


    protected function isEncryptable(string $model): bool
    {
        $uses = class_uses_recursive($model);

        return in_array(UsesCipherSweet::class, $uses);
    }

    protected function pretendToEncrypt(string $model): void
    {
        try {
            $instance = $this->getClass($model);
        } catch (\Throwable) {
            return; // if the class cannot be created (ie abstract class) just skip it
        }

        /**
         * @psalm-suppress MixedMethodCall
         */
        $count = $instance->count();

        if ($count === 0) {
            $this->components->twoColumnDetail($model, "no action");
        } else {
            $this->components->twoColumnDetail($model, "{$count} records will be encrypted");
        }
    }

    /**
     * @psalm-suppress InvalidStringClass
     */
    private function getClass(string $name): Model
    {
        /**
         * @var Model
         */
        return new $name();
    }

}
