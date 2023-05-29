<?php

namespace Yormy\TripwireLaravel\Traits;

use Mexion\BedrockUsers\Helpers\CheckHoneypot;

trait Honeypot
{
    public function checkHoneypot($request, array $honeypots = ['firstname']): void
    {
        $checkHoneypot = new CheckHoneypot($request);

        foreach ($honeypots as $honeypot) {
            $honeypotValues[] = $request->input($honeypot);
        }

        $checkHoneypot->handle(['honeypots' => $honeypotValues], function () {
            //
        });
    }
}
