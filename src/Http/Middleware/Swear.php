<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Yormy\TripwireLaravel\Observers\Events\SwearFailedEvent;
use Yormy\TripwireLaravel\Repositories\LogRepository;

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

    protected function attackFound(array $violations): void
    {
        // log
        // take action
        $attackScore = $this->getAttackScore();
//        event(new SwearFailedEvent(
//            attackScore: $attackScore,
//            violations: $violations
//        ));

        $logRepository = new LogRepository();
        //$logRepository->get();

        $score = $logRepository->scoreViolationsWithin(200, ['1', 'test', 'SWEAR']);
        dd($score);


        // determine if action is needed based on log
        //ie if > 3 then action
        // place / update tarpit
        // tarpit on ip / user/ browser ?
        // no details of logs is needed as that is in the log itself
        // add tarpit id to log to indicate that the punishment has been taken effect on these log items


        // count action is based on what ?
        // same violation? or calculated of itme
        // ie 1x xss 1x sql 1x swear?

        // action to take inline

        // maar het geblocked zijn zie je pas in next req
    }
}
