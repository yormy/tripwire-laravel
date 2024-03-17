<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\AgentFailedEvent;
use Yormy\TripwireLaravel\Services\RequestSource;

class Agent extends BaseWire
{
    public const NAME = 'agent';

    public function isAttack($patterns): bool
    {
        $agents = $this->config->tripwires();
        if (empty($agents)) {
            return false;
        }

        $violations = [];

        $browsers = $agents['browsers'];
        $currentBrowser = RequestSource::getBrowser();
        if ($currentBrowser && $this->isFilterAttack($currentBrowser, $browsers)) {
            $violations[] = $currentBrowser;
        }

        $platforms = $agents['platforms'];
        $currentPlatform = RequestSource::getPlatform();
        if ($currentPlatform && $this->isFilterAttack($currentPlatform, $platforms)) {
            $violations[] = $currentPlatform;
        }

        $devicesBlocked = $agents['devices']['block'];
        if ($blocked = $this->isDeviceBlocked($devicesBlocked)) {
            $violations[] = $blocked;
        }

        if ($maliciousAgent = $this->isMaliciousAgent()) {
            $violations[] = $maliciousAgent;
        }

        if (! empty($violations)) {
            $this->attackFound($violations);
        }

        return ! empty($violations);
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new AgentFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

    /**
     * @psalm-return array<never, never>|string|null
     */
    protected function isMaliciousAgent(): array|string|null
    {
        $agent = RequestSource::getUserAgent();

        if (empty($agent) || ($agent === '-') || strstr($agent, '<?')) {
            return [];
        }

        $patterns = [
            '@"feed_url@',
            '@}__(.*)|O:@',
            '@J?Simple(p|P)ie(Factory)?@',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $agent, $matches)) {
                return $matches[0];
            }
        }

        return null;
    }

    private function isDeviceBlocked(array $devices): ?string
    {
        if (RequestSource::isPhone()) {
            if (in_array('PHONE', $devices)) {
                return 'PHONE';
            }
        }

        if (RequestSource::isMobile()) {
            if (in_array('MOBILE', $devices)) {
                return 'MOBILE';
            }
        }

        if (RequestSource::isTablet()) {
            if (in_array('TABLET', $devices)) {
                return 'TABLET';
            }
        }

        if (RequestSource::isDesktop()) {
            if (in_array('DESKTOP', $devices)) {
                return 'DESKTOP';
            }
        }

        return null;
    }
}
