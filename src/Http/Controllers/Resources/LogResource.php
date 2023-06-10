<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Resources;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.getUserFromActionXid
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
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
            'created_at' => $this->created_at,
//
//
//
//
//            'current_plan' => $this->currentPlan(),
//            'sessions_count' => $this->sessions_count ?? 0,
//            'avatar_url' => url('shared/images/modules/bedrock-users/profile/member/member_avatar_01.png'),
        ];
//
//        $dates = $this->getDates([
//            'created_at',
//            'email_verified_at',
//            'phone_verified_at'
//        ]);

        return array_merge($fields);
    }
}
