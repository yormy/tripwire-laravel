<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Resources;

class LogResource extends JsonResource
{
    public function toArray($request): array
    {

        $domain = parse_url($this->url)['host'];
        $relativeURl = strtolower($this->url);
        $relativeURl = str_replace('https://', '', $relativeURl);
        $relativeURl = str_replace('http://', '', $relativeURl);
        $relativeURl = str_replace($domain, '', $relativeURl);

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
            'relative_url' => $relativeURl,
            'method' => $this->method,
            'referer' => $this->referer,
            'request' => $this->request,
            'user_agent' => $this->user_agent,
            'created_at' => $this->formatDate($this->created_at),

            'tripwire_block_id' => $this->tripwire_block_id,

            'header' => $this->header,
            'robot_crawler' => $this->robot_crawler,
            'trigger_data' => $this->trigger_data,
            'trigger_rule' => $this->trigger_rule,
            'browser_fingerprint' => $this->browser_fingerprint,
            'ignore' => (bool)$this->ignore,
        ];

        return $fields;
    }
}
