<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LogCollection extends ResourceCollection
{
    public $collects = LogResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\Support\Arrayable|\JsonSerializable|array
     */
    public function toArray($request): array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    {
        return parent::toArray($request);
    }
}
