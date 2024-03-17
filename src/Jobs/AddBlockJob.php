<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Yormy\TripwireLaravel\Observers\Events\Blocked\TripwireBlockedBrowserEvent;
use Yormy\TripwireLaravel\Observers\Events\Blocked\TripwireBlockedEvent;
use Yormy\TripwireLaravel\Observers\Events\Blocked\TripwireBlockedIpEvent;
use Yormy\TripwireLaravel\Observers\Events\Blocked\TripwireBlockedUserEvent;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Yormy\TripwireLaravel\Repositories\LogRepository;

class AddBlockJob implements ShouldBeEncrypted, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $ipAddress,
        private readonly ?int $userId,
        private readonly ?string $userType,
        private readonly int $withinMinutes,
        private readonly int $thresholdScore,
        private readonly int $penaltySeconds,
        private readonly bool $trainingMode = false
    ) {
    }

    public function handle(): void
    {
        $this->blockIfNeeded();
    }

    protected function blockIfNeeded(): ?Model
    {
        $punishableTimeframe = $this->withinMinutes;

        $sum = $this->getSumViolationScore($punishableTimeframe);

        if ($sum->maxScore >= $this->thresholdScore) {
            $blockRepository = new BlockRepository();
            $blockItem = $blockRepository->add(
                penaltySeconds: $this->penaltySeconds,
                ipAddress: $sum->ipAddress,
                userId: $sum->userId,
                userType: $sum->userType,
                browserFingerprint: $sum->browserFingerprint,
                responseJson : 'todo',
                responseHtml : 'todo',
                ignore: $this->trainingMode
            );

            if (! $this->trainingMode) {
                event(new TripwireBlockedEvent(
                    ipAddress: $sum->ipAddress,
                    userId: $sum->userId,
                    userType: $sum->userType,
                    browserFingerprint: $sum->browserFingerprint,
                ));
            }

            event(new TripwireBlockedIpEvent($sum->ipAddress));
            $sum->violationsByIp->update(['tripwire_block_id' => $blockItem->id]);

            if ($sum->violationsByUser) {
                $sum->violationsByUser->update(['tripwire_block_id' => $blockItem->id]);
                if (! $this->trainingMode) {
                    event(new TripwireBlockedUserEvent($sum->userId, $sum->userType));
                }
            }

            if ($sum->violationsByBrowser) {
                $sum->violationsByBrowser->update(['tripwire_block_id' => $blockItem->id]);
                if (! $this->trainingMode) {
                    event(new TripwireBlockedBrowserEvent($sum->browserFingerprint));
                }
            }

            return $blockItem;
        }

        return null;
    }

    /**
     * @param array<string> $violations
     */
    private function getSumViolationScore(int $punishableTimeframe, array $violations = []): \StdClass
    {
        $logRepository = new LogRepository();

        $violationsByIp = $logRepository->queryViolationsByIp($punishableTimeframe, $this->ipAddress, $violations);
        $scoreByIp = $violationsByIp->sum('event_score');

        $scoreByUser = 0;

        $violationsByUser = null;
        if ($this->userId) {
            $violationsByUser = $logRepository->queryViolationsByUser($punishableTimeframe, $this->userId, $this->userType, $violations);
            $scoreByUser = $violationsByUser->sum('event_score');
        }

        $requestSourceClass = config('tripwire.services.request_source');
        $browserFingerprint = $requestSourceClass::getBrowserFingerprint();
        $scoreByBrowser = 0;
        $violationsByBrowser = null;
        if ($browserFingerprint) {
            $violationsByBrowser = $logRepository->queryViolationsByBrowser($punishableTimeframe, $browserFingerprint, $violations);
            $scoreByBrowser = $violationsByBrowser->sum('event_score');
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
