<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Resources;

class BlockResource extends JsonResource
{
    public function toArray($request): array
    {
        $fields = [
            'xid' => $this->xid,
            'ignore' => $this->ignore,
            'type' => $this->type,
            'reasons' => $this->reasons,

            'blocked_ip' => $this->blocked_ip,
            'blocked_user_id' => $this->blocked_user_id,
            'blocked_user_type' => $this->blocked_user_type,
            'blocked_browser_fingerprint' => $this->blocked_browser_fingerprint,
            'blocked_repeater' => $this->blocked_repeater,

            'internal_comments' => $this->internal_comments,
            'manually_blocked' => $this->manually_blocked,
            'persistent_block' => $this->persistent_block,
            'blocked_until' => $this->formatDate($this->blocked_until),
            'created_at' => $this->formatDate($this->created_at),
        ];

        return $fields;
    }
}
