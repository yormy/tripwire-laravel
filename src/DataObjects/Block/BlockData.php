<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Block;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class BlockData extends Data
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @return array<string, array<int,string>>
     */
    public static function rules(ValidationContext $context): array
    {
        $rules = [];

        $rules['blocked_ip'] = ['required', 'string', 'min:6'];
        $rules['internal_comments'] = ['required', 'string'];

        return $rules;
    }

    /**
     * @return array<string>
     */
    public static function examples(): array
    {
        $data = [];

        $data['xid'] = '123123!24';
        $data['ignore'] = true;
        $data['type'] = '?';
        $data['reasons'] = [];
        $data['blocked_ip'] = '198.12.13.100';
        $data['blocked_user_xid'] = '1234';
        $data['blocked_user_firstname'] = 'Joe';
        $data['blocked_user_lastname'] = 'Bounty';
        $data['blocked_user_email'] = 'welcome@example.com';
        $data['blocked_browser_fingerprint'] = 'safafasfasfag';
        $data['blocked_repeater'] = 4;
        $data['internal_comments'] = 'Internal comments';
        $data['manually_blocked'] = false;
        $data['persistent_block'] = false;
        $data['blocked_until'] = '2024-12-12 10:10:10';
        $data['created_at'] = '2024-12-12 10:10:10';
        $data['rowstyle'] = 'deleted';
        $data['status'] = [
            'key' => 'some key',
            'nature' => 'danger',
            'text' => 'blocked',
        ];

        return $data;
    }

    /**
     * @return array<string>
     */
    public static function descriptions(): array
    {
        $data = [];

        $data['xid'] = 'internal number';
        $data['ignore'] = 'This block will be ignored';
        $data['type'] = '?';
        $data['reasons'] = '?';
        $data['blocked_ip'] = 'The blocked ip';
        $data['blocked_user_xid'] = 'the id user that is blocked';
        $data['blocked_user_firstname'] = 'The user details';
        $data['blocked_user_lastname'] = 'The user details';
        $data['blocked_user_email'] = 'The user details';
        $data['blocked_browser_fingerprint'] = 'The browser fingerprint';
        $data['blocked_repeater'] = 'how many offences';
        $data['internal_comments'] = 'Just internal comments';
        $data['manually_blocked'] = 'This ip was manually blocked';
        $data['persistent_block'] = 'this block cannot be easily resetted';
        $data['blocked_until'] = 'Till when is this blocked';
        $data['created_at'] = '';
        $data['rowstyle'] = 'helpers for the frontend';

        $data['status'] = [
            'key' => 'some key',
            'nature' => 'color helpers for the frontend',
            'text' => '',
        ];

        return $data;
    }
}
