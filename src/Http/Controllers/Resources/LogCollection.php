<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LogCollection extends ResourceCollection
{
    public $collects = LogResource::class;

}
