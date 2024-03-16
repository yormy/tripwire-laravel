<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Block;

use Carbon\CarbonImmutable;

use Illuminate\Support\Carbon;

class BlockDataResponse extends BlockData
{
    public function __construct(
        public string $xid,
        public bool $ignore,
        public ?string $type,
        public ?string $reasons,
        public string $blocked_ip,
        public CarbonImmutable | null $deleted_at,

        public ?string $blocked_user_xid,
        public ?string $blocked_user_firstname,
        public ?string $blocked_user_lastname,
        public ?string $blocked_user_email,

        public ?string $blocked_browser_fingerprint,
        public int $blocked_repeater,
        public ?string $internal_comments,
        public bool $manually_blocked,
        public bool $persistent_block,
        public CarbonImmutable $blocked_until,
        public CarbonImmutable $created_at,
        public ?string $rowstyle,

        public array $status,
    ) {
    }

    protected static function constructorData($model): array
    {
        $fieldId = config('tripwire.user_fields.id');
        $fieldFirstname = config('tripwire.user_fields.firstname');
        $fieldLastname = config('tripwire.user_fields.lastname');
        $fieldEmail = config('tripwire.user_fields.email');

        return [
            $model->xid,
            (bool)$model->ignore,

            $model->type,
            $model->reasons,
            $model->blocked_ip,
            CarbonImmutable::parse($model->deleted_at),

            self::getUserField($model, $fieldId),
            self::getUserField($model, $fieldFirstname),
            self::getUserField($model, $fieldLastname),
            self::getUserField($model, $fieldEmail),
            $model->blocked_browser_fingerprint,
            (int)$model->blocked_repeater,

            $model->internal_comments,
            (bool)$model->manually_blocked,
            (bool)$model->persistent_block,

            CarbonImmutable::parse($model->blocked_until),
            CarbonImmutable::parse($model->created_at),
            $model->deleted_at ? 'deleted' : '',

            ...self::decorateWithStatus($model)
        ];
    }

    private static function decorateWithStatus($model): array
    {
        $status = [];
        if ($model->blocked_until >= Carbon::now()) {
            $status = [
                'key' => 'active',
                'nature' => 'danger',
                'text' => __('tripwire::logitem.block_active')
            ];
        }

        $data['status'] = $status;

        return $data;
    }

    protected static function getUserField($model, $field): ?string
    {
        $fieldId = config('tripwire.user_fields.id');
        if ($model->relationLoaded('user')) {
            if ($model->user) {
                return $model->user[$fieldId];
            };
        }
        return null;
    }

    public static function fromModel($model): self
    {
        $constuctorData = self::constructorData($model);

        return new static(...$constuctorData);
    }
}
