<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

class HoneypotService
{
    /**
     * @param array<string> $honeypots
     *
     * @return array<string>
     */
    public static function checkFalseValues(\Illuminate\Http\Request $request, array $honeypots = []): array
    {
        $violations = [];
        foreach ($honeypots as $honeypotName) {
            $honeypotContent = $request->get($honeypotName);
            if ($honeypotContent) {
                $violations[] = "{$honeypotName}={$honeypotContent}";
            }
        }

        return $violations;
    }
}
