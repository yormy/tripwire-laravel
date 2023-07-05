<?php

namespace Yormy\TripwireLaravel\Services;

class HoneypotService
{
    public static function checkFalseValues(\Illuminate\Http\Request $request, array $honeypots = []): array
    {
        $violations = [];
        foreach ($honeypots as $honeypotName) {
            $honeypotContent = $request->get($honeypotName);
            if ($honeypotContent) {
                $violations[] = "$honeypotName=$honeypotContent";
            }
        }

        return $violations;
    }
}
