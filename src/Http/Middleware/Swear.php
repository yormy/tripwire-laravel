<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Yormy\TripwireLaravel\Observers\Events\SwearFailedEvent;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Yormy\TripwireLaravel\Repositories\LogRepository;
use Yormy\TripwireLaravel\Services\IpAddress;
use Yormy\TripwireLaravel\Services\RequestSource;
use Yormy\TripwireLaravel\Services\User;

class Swear  extends Middleware
{
    public function getPatterns()
    {
        $patterns = [];

        if (! $words = $this->config->words) {
            return $patterns;
        }

        foreach ((array) $words as $word) {
            $patterns[] = '#\b' . $word . '\b#i';
        }

        return $patterns;
    }

    protected function blockIfNeeded()
    {
        $logRepository = new LogRepository();

        $punishableTimeframe = (int)$this->config->punish['within_minutes'];
        $ipAddress = IpAddress::get($this->request);

        $violationsByIp = $logRepository->queryViolationsByIp($punishableTimeframe, ['test', 'SWEAR'], $ipAddress);
        $scoreByIp = $violationsByIp->get()->sum('event_score');

        $userId = User::getId($this->request);
        $userType = User::getType($this->request);
        $scoreByUser = 0;

        $violationsByUser = null;
        if ($userId) {
            $violationsByUser = $logRepository->queryViolationsByUser($punishableTimeframe, ['test', 'SWEAR'],  $userId, $userType);
            $scoreByUser = $violationsByUser->get()->sum('event_score');
        }

        $browserFingerprint = (new RequestSource())->getBrowserFingerprint();
        $scoreByBrowser = 0;
        $violationsByBrowser = null;
        if ($browserFingerprint) {
            $violationsByBrowser = $logRepository->queryViolationsByBrowser($punishableTimeframe, ['test', 'SWEAR'],  $browserFingerprint);
            $scoreByBrowser = $violationsByBrowser->get()->sum('event_score');
        }

        $maxScore = max($scoreByIp, $scoreByUser, $scoreByBrowser);
        if ($maxScore > (int)$this->config->punish['score']) {
            $blockRepository = new BlockRepository();
            $penaltySeconds = 5; // from config
            $blockItem = $blockRepository->add(
                penaltySeconds: $penaltySeconds,
                ipAddress: $ipAddress,
                userId: $userId,
                userType: $userType,
                browserFingerprint: $browserFingerprint,
            );

            $violationsByIp->update(['tripwire_block_id' => $blockItem->id]);
            if (!$violationsByUser) {
                $violationsByUser->update(['tripwire_block_id' => $blockItem->id]);
            }

            if ($violationsByBrowser) {
                $violationsByBrowser->update(['tripwire_block_id' => $blockItem->id]);
            }

            return $blockItem;
        }

        return null;
    }


    protected function attackFound(array $violations): void
    {
        $attackScore = $this->getAttackScore();

        event(new SwearFailedEvent(
            attackScore: $attackScore,
            violations: $violations
        ));

        $this->blockIfNeeded();

    }
}
