<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BlockCollection extends ResourceCollection
{
    public $collects = BlockResource::class;
}
