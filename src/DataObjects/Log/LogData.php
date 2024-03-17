<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Log;

use Spatie\LaravelData\Data;

class LogData extends Data
{
    public static function examples(): array
    {
        $data['xid'] = '123123!24';

        $data['event_code'] = '';
        $data['event_score'] = 10;
        $data['event_violation'] = '';
        $data['event_comment'] = '';

        $data['ip'] = '1.1.1.1';

        $data['user_xid'] = '25151';
        $data['user_firstname'] = 'Joe';
        $data['user_lastname'] = 'Bounty';
        $data['user_email'] = 'welcome@example.com';

        $data['url'] = 'https://localhost.com';
        $data['relative_url'] = '/index';

        $data['referer'] = '';
        $data['request'] = '';
        $data['user_agent'] = '';

        $data['created_at'] = '2024-12-12 10:10:10';
        $data['deleted_at'] = '2024-12-12 10:10:10';

        $data['tripwire_block_id'] = 21;
        $data['header'] = '';
        $data['robot_crawler'] = '';
        $data['trigger_data'] = '';

        $data['trigger_rule'] = '';
        $data['browser_fingerprint'] = '';

        $data['ignore'] = false;
        $data['rowstyle'] = 'deleted';

        $data['block_xid'] = '2312412';

        $data['status'] = [
            'key' => 'some key',
            'nature' => 'danger',
            'text' => 'high',
        ];

        $data['method'] = [
            'key' => 'delete',
            'nature' => 'danger',
            'text' => 'delete',
        ];

        return $data;
    }

    public static function descriptions(): array
    {
        $data['xid'] = 'Internal id';

        $data['event_code'] = '';
        $data['event_score'] = 'the actual severity';
        $data['event_violation'] = '';
        $data['event_comment'] = '';

        $data['ip'] = '';

        $data['user_xid'] = 'Id of the user';
        $data['user_firstname'] = '';
        $data['user_lastname'] = '';
        $data['user_email'] = '';

        $data['url'] = 'the triggering url';
        $data['relative_url'] = '';

        $data['referer'] = '';
        $data['request'] = '';
        $data['user_agent'] = '';

        $data['created_at'] = '';
        $data['deleted_at'] = '';

        $data['tripwire_block_id'] = 'ID of the associated block';
        $data['header'] = '';
        $data['robot_crawler'] = '';
        $data['trigger_data'] = '';

        $data['trigger_rule'] = '';
        $data['browser_fingerprint'] = '';

        $data['ignore'] = 'is ignored';
        $data['rowstyle'] = 'frontend coloring';

        $data['block_xid'] = '';

        $data['status'] = [
            'key' => 'some key',
            'nature' => 'danger',
            'text' => 'high',
        ];

        $data['method'] = [
            'key' => 'delete',
            'nature' => 'danger',
            'text' => 'delete',
        ];

        return $data;
    }
}
