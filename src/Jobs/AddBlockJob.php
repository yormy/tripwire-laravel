<?php

namespace Yormy\TripwireLaravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Yormy\TripwireLaravel\Observers\Events\RefererFailedEvent;
use Yormy\TripwireLaravel\Observers\Events\TripwireBlockedBrowserEvent;
use Yormy\TripwireLaravel\Observers\Events\TripwireBlockedIpEvent;
use Yormy\TripwireLaravel\Observers\Events\TripwireBlockedUserEvent;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Yormy\TripwireLaravel\Repositories\LogRepository;

class AddBlockJob implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $ipAddress,
        private readonly int $userId,
        private readonly string $userType,
        private readonly int $withinMinutes,
        private readonly int $thresholdScore,
        private readonly int $penaltySeconds,
        private readonly bool $trainingMode = false
    )
    { }

    public function handle()
    {
        $this->blockIfNeeded();
    }

    protected function blockIfNeeded()
    {
        $punishableTimeframe = $this->withinMinutes;

        $sum = $this->getSumViolationScore($punishableTimeframe);

        if ($sum->maxScore > $this->thresholdScore) {
            $blockRepository = new BlockRepository();
            $blockItem = $blockRepository->add(
                penaltySeconds: $this->penaltySeconds,
                ipAddress: $sum->ipAddress,
                userId: $sum->userId,
                userType: $sum->userType,
                browserFingerprint: $sum->browserFingerprint,
                ignore: $this->trainingMode
            );

            $sum->violationsByIp->update(['tripwire_block_id' => $blockItem->id]);
            event(new TripwireBlockedIpEvent($sum->ipAddress));

            if (!$sum->violationsByUser) {
                $sum->violationsByUser->update(['tripwire_block_id' => $blockItem->id]);
                event(new TripwireBlockedUserEvent($sum->userId, $sum->userType));
            }

            if ($sum->violationsByBrowser) {
                $sum->violationsByBrowser->update(['tripwire_block_id' => $blockItem->id]);
                event(new TripwireBlockedBrowserEvent($sum->browserFingerprint));
            }

            return $blockItem;
        }

        return null;
    }


    private function getSumViolationScore(int $punishableTimeframe, array $violations = []):\StdClass
    {
        $logRepository = new LogRepository();

        $violationsByIp = $logRepository->queryViolationsByIp($punishableTimeframe, $this->ipAddress, $violations);
        $scoreByIp = $violationsByIp->get()->sum('event_score');

        $scoreByUser = 0;

        $violationsByUser = null;
        if ($this->userId) {
            $violationsByUser = $logRepository->queryViolationsByUser($punishableTimeframe, $this->userId, $this->userType, $violations);
            $scoreByUser = $violationsByUser->get()->sum('event_score');
        }

        $requestSourceClass = config('tripwire.services.request_source');
        $browserFingerprint =$requestSourceClass::getBrowserFingerprint();
        $scoreByBrowser = 0;
        $violationsByBrowser = null;
        if ($browserFingerprint) {
            $violationsByBrowser = $logRepository->queryViolationsByBrowser($punishableTimeframe, $browserFingerprint, $violations);
            $scoreByBrowser = $violationsByBrowser->get()->sum('event_score');
        }

        $result = new \StdClass();
        $result->maxScore = max($scoreByIp, $scoreByUser, $scoreByBrowser);
        $result->ipAddress = $this->ipAddress;
        $result->userId = $this->userId;
        $result->userType = $this->userType;
        $result->browserFingerprint = $browserFingerprint;
        $result->violationsByIp = $violationsByIp;
        $result->violationsByUser = $violationsByUser;
        $result->violationsByBrowser = $violationsByBrowser;

        return $result;
    }
}
