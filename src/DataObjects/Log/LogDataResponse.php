<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Log;

use Carbon\CarbonImmutable;
use Yormy\TripwireLaravel\Models\TripwireLog;

class LogDataResponse extends LogData
{

    /**
     * @param array<string> $status
     * @param array<string> $method
     */
    public function __construct(
        public string $xid,
        public string $event_code,
        public int $event_score,
        public string $event_violation,
        public string $event_comment,
        public string $ip,
        public ?string $user_xid,
        public ?string $user_firstname,
        public ?string $user_lastname,
        public ?string $user_email,
        public ?string $url,
        public ?string $relative_url,
        public ?string $referer,
        public ?string $request,
        public ?string $user_agent,
        public CarbonImmutable $created_at,
        public ?CarbonImmutable $deleted_at,
        public ?int $tripwire_block_id,
        public ?string $header,
        public ?string $robot_crawler,
        public ?string $trigger_data,
        public ?string $trigger_rule,
        public ?string $browser_fingerprint,
        public bool $ignore,
        public ?string $rowstyle,
        public ?string $block_xid,
        public array $status,
        public array $method,
    ) {
    }

    public static function fromModel(TripwireLog $model): self
    {
        $constuctorData = self::constructorData($model);

        return new static(...$constuctorData);
    }

    protected static function constructorData(TripwireLog $model): array
    {
        $fieldId = config('tripwire.user_fields.id');
        $fieldFirstname = config('tripwire.user_fields.firstname');
        $fieldLastname = config('tripwire.user_fields.lastname');
        $fieldEmail = config('tripwire.user_fields.email');

        $relativeUrl = $model->url;
        $parsed = parse_url($model->url);
        if (isset($parsed['path'])) {
            $relativeUrl = $parsed['path'];
        }

        return [
            $model->xid,
            $model->event_code,
            $model->event_score,
            $model->event_violation,
            $model->event_comment,

            $model->ip,

            self::getUserField($model, $fieldId),
            self::getUserField($model, $fieldFirstname),
            self::getUserField($model, $fieldLastname),
            self::getUserField($model, $fieldEmail),

            $model->url,
            $relativeUrl,

            $model->referer,
            $model->request,
            $model->user_agent,

            CarbonImmutable::parse($model->created_at),
            CarbonImmutable::parse($model->deleted_at),

            $model->tripwire_block_id,
            $model->header,
            $model->robot_crawler,

            $model->trigger_data,
            $model->trigger_rule,
            $model->browser_fingerprint,

            (bool) $model->ignore,
            $model->deleted_at ? 'deleted' : '',
            self::getBlockXid($model),
            ...self::decorateWithStatus($model),
            ...self::decorateWithMethod($model),
        ];
    }

    protected static function getUserField(TripwireLog $model, string $field): ?string
    {
        if ($model->relationLoaded('user')) {
            if ($model->user) {
                return $model->user[$field];
            }
        }

        return null;
    }

    protected static function getBlockXid(TripwireLog $model): ?string
    {
        if ($model->relationLoaded('block') && $model->tripwire_block_id) {
            if ($model->user) {
                return $model->block->xid;
            }
        }

        return null;
    }

    private static function decorateWithStatus(TripwireLog $model): array
    {
        $scoreMediumThreshold = 20;
        $scoreHighThreshold = 50;

        $status = [];
        if ($model->event_score >= $scoreMediumThreshold) {
            $status = [
                'key' => 'medium',
                'nature' => 'warning',
                'text' => __('tripwire::logitem.score.medium'),
            ];
        }

        if ($model->event_score >= $scoreHighThreshold) {
            $status = [
                'key' => 'high',
                'nature' => 'danger',
                'text' => __('tripwire::logitem.score.high'),
            ];
        }

        $data = [];
        $data['status'] = $status;

        return $data;
    }

    private static function decorateWithMethod(TripwireLog $model): array
    {
        $nature = [];
        $method = $model->method;
        if ($method === 'DELETE') {
            $nature = 'DANGER';
        }
        if ($method === 'GET') {
            $nature = 'SUCCESS';
        }
        if ($method === 'POST' || $method === 'PATCH' || $method === 'PUT') {
            $nature = 'WARNING';
        }

        $status = [
            'key' => $method,
            'nature' => $nature,
            'text' => $method,
        ];

        $data = [];
        $data['method'] = $status;

        return $data;
    }
}
