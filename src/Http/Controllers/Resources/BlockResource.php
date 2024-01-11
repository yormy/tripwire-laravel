<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Resources;

class BlockResource extends JsonResource
{
    public function toArray($request): array
    {
        $fieldId = config('tripwire.user_fields.id');
        $fieldFirstname = config('tripwire.user_fields.firstname');
        $fieldLastname = config('tripwire.user_fields.lastname');
        $fieldEmail = config('tripwire.user_fields.email');

        $fields = [
            'xid' => $this->xid,
            'ignore' => $this->ignore,
            'type' => $this->type,
            'reasons' => $this->reasons,

            'blocked_ip' => $this->blocked_ip,
            'blocked_user_xid' => $this->whenLoaded('user', fn () => $fieldId? $this->user[$fieldId] : null, null),
            'blocked_user_firstname' => $this->whenLoaded('user', fn () => $fieldId? $this->user[$fieldFirstname] : null, null),
            'blocked_user_lastname' => $this->whenLoaded('user', fn () => $fieldId? $this->user[$fieldLastname] : null, null),
            'blocked_user_email' => $this->whenLoaded('user', fn () => $fieldId? $this->user[$fieldEmail] : null, null),
            'blocked_browser_fingerprint' => $this->blocked_browser_fingerprint,
            'blocked_repeater' => $this->blocked_repeater,

            'internal_comments' => $this->internal_comments,
            'manually_blocked' => (bool)$this->manually_blocked,
            'persistent_block' => (bool)$this->persistent_block,
            'blocked_until' => $this->formatDate($this->blocked_until),
            'created_at' => $this->formatDate($this->created_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'rowstyle' => $this->deleted_at ? 'deleted' : ''
        ];

        return $fields;
    }
//
//    private function getUser()
//    {
//        $user = UserResolver::getMemberById($this->blocked_user_id);
//        if (!$user) {
//            $user = UserResolver::getAdminById($this->blocked_user_id);
//        }
//    }
}
