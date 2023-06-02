<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\RfiFailedEvent;
use Jenssegers\Agent\Agent;

class Rfi extends BaseChecker
{

    protected function attackFound(array $violations): void
    {
        event(new RfiFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }

    public function prepareInput($value)
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
