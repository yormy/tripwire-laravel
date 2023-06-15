<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\RfiFailedEvent;
use Jenssegers\Agent\Agent;

class Rfi extends BaseChecker
{
    public const NAME = 'rfi';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new RfiFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

    public function prepareInput($value): string
    {
        $exceptions = $this->config->guards['allow'];
        $domain = $this->request->getHost();

        $exceptions[] = 'http://' . $domain;
        $exceptions[] = 'https://' . $domain;
        $exceptions[] = 'http://&';
        $exceptions[] = 'https://&';

        return str_replace($exceptions, '', $value);
    }


    protected function matchAdditional($value): ?string
    {
        $contents = @file_get_contents($value);

        if (!empty($contents)) {
            if ($match = strstr($contents, '<?php')) {
                return $match;
            }
        }

        return null;
    }
}
