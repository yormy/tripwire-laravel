<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Block;

use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Yormy\TripwireLaravel\Models\TripwireBlock;

final class BlockDataResponse extends BlockData
{
    /**
     * @param array<string> $status
     */
    public function __construct(
        public string $xid,
        public bool $ignore,
        public ?string $type,
        public ?string $reasons,
        public string $blocked_ip,
        public ?CarbonImmutable $deleted_at,
        public ?string $blocked_user_xid,
        public ?string $blocked_user_firstname,
        public ?string $blocked_user_lastname,
        public ?string $blocked_user_email,
        public ?string $blocked_browser_fingerprint,
        public int $blocked_repeater,
        public ?string $internal_comments,
        public bool $manually_blocked,
        public bool $persistent_block,
        public ?CarbonImmutable $blocked_until,
        public CarbonImmutable $created_at,
        public ?string $rowstyle,
        public array $status,
    ) {
    }

    public static function fromModel(TripwireBlock $model): self
    {
        $constuctorData = self::constructorData($model);

        return new static(...$constuctorData);
    }

    /**
     * @return array<string>
     */
    protected static function constructorData(TripwireBlock $model): array
    {
        $fieldId = config('tripwire.user_fields.id');
        $fieldFirstname = config('tripwire.user_fields.firstname');
        $fieldLastname = config('tripwire.user_fields.lastname');
        $fieldEmail = config('tripwire.user_fields.email');

        return [
            $model->xid,
            (bool) $model->ignore,

            $model->type,
            $model->reasons,
            $model->blocked_ip,
            $model->deleted_at ? CarbonImmutable::parse($model->deleted_at) : null,

            self::getUserField($model, $fieldId),
            self::getUserField($model, $fieldFirstname),
            self::getUserField($model, $fieldLastname),
            self::getUserField($model, $fieldEmail),
            $model->blocked_browser_fingerprint,
            (int) $model->blocked_repeater,

            $model->internal_comments,
            (bool) $model->manually_blocked,
            (bool) $model->persistent_block,

            $model->blocked_until ? CarbonImmutable::parse($model->blocked_until) : null,
            CarbonImmutable::parse($model->created_at),
            $model->deleted_at ? 'deleted' : '',

            ...self::decorateWithStatus($model),
        ];
    }

    protected static function getUserField(TripwireBlock $model, string $field): ?string
    {
        if ($model->relationLoaded('user')) {
            if ($model->user) {
                return $model->user[$field];
            }
        }

        return null;
    }

    /**
     * @return array<string>
     */
    private static function decorateWithStatus(TripwireBlock $model): array
    {
        $status = [];
        if ($model->blocked_until >= Carbon::now()) {
            $status = [
                'key' => 'active',
                'nature' => 'danger',
                'text' => __('tripwire::logitem.block_active'),
            ];
        }

        $data = [];
        $data['status'] = $status;

        return $data;
    }
}
