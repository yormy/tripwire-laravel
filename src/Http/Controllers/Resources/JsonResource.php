<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

class JsonResource extends BaseJsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->withoutWrapping();
    }

    protected function formatDate(?Carbon $date): string
    {
        if (!$date) {
            return '';
        }

        return $date
            ->clone()
            ->addMinutes(config('tripwire.datetime.offset', 0))
            ->format(config('tripwire.datetime.format', 'YYYY-MM-DD'));
    }
}
