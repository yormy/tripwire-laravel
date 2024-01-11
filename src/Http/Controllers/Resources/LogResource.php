<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Resources;

class LogResource extends JsonResource
{
    public function toArray($request): array
    {
        $fieldId = config('tripwire.user_fields.id');
        $fieldFirstname = config('tripwire.user_fields.firstname');
        $fieldLastname = config('tripwire.user_fields.lastname');
        $fieldEmail = config('tripwire.user_fields.email');

        $relativeUrl = $this->url;
        $parsed = parse_url($this->url);
        if (isset($parsed['path'])) {
            $relativeUrl = $parsed['path'];
        }

        $fields = [
            'xid' => $this->xid,
            'event_code' => $this->event_code,
            'event_score' => $this->event_score,
            'event_violation' => $this->event_violation,
            'event_comment' => $this->event_comment,
            'ip' => $this->ip,
            'user_xid' => $this->whenLoaded('user', fn () => $fieldId? $this->user[$fieldId] : null, null),
            'user_firstname' => $this->whenLoaded('user', fn () => $fieldId? $this->user[$fieldFirstname] : null, null),
            'user_lastname' => $this->whenLoaded('user', fn () => $fieldId? $this->user[$fieldLastname] : null, null),
            'user_email' => $this->whenLoaded('user', fn () => $fieldId? $this->user[$fieldEmail] : null, null),
            'url' => $this->url,
            'relative_url' => $relativeUrl,
            'method' => $this->method,
            'referer' => $this->referer,
            'request' => $this->request,
            'user_agent' => $this->user_agent,
            'created_at' => $this->formatDate($this->created_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'tripwire_block_id' => $this->tripwire_block_id,

            'header' => $this->header,
            'robot_crawler' => $this->robot_crawler,
            'trigger_data' => $this->trigger_data,
            'trigger_rule' => $this->trigger_rule,
            'browser_fingerprint' => $this->browser_fingerprint,
            'ignore' => (bool)$this->ignore,
            'rowstyle' => $this->deleted_at ? 'deleted' : '',
            'block_xid' => $this->relationLoaded('block') && $this->tripwire_block_id ? $this->block->xid : null,
        ];

        return $fields;
    }
}
