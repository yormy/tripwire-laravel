<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BlockCollection extends ResourceCollection
{
    public $collects = BlockResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    {
        return parent::toArray($request);
    }
}
