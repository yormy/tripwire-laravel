<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Resources;

class LogResource extends JsonResource
{
    public function toArray($request): array
    {
        $fields = [
            'xid' => $this->xid,
            'event_code' => $this->event_code,
            'event_score' => $this->event_score,
            'event_violation' => $this->event_violation,
            'event_comment' => $this->event_comment,
            'ip' => $this->ip,
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'url' => $this->url,
            'method' => $this->method,
            'referer' => $this->referer,
            'request' => $this->request,
            'user_agent' => $this->user_agent,
            'created_at' => $this->formatDate($this->created_at),
        ];

        return $fields;
    }
}
