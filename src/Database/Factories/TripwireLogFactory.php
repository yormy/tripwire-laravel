<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Database\Factories;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\Xid\Services\XidService;

class TripwireLogFactory extends Factory
{
    protected $model = TripwireLog::class;

    public function definition(): array
    {
        $eventCode = [
            'LFI',
            'SQLI',
            'XSS',
            'custom',
        ];

        $eventViolation = [
            'example.malicious',
            '%252e%252f',
            '.ssh/id_rsa',
            '%uff1cscript',

        ];

        $requests = [
            '{"foo":"example.malicious"}',
            '{"foo":"http:\/\/ex.com\/index.php?page=%252e%252e%252fetc%252fpasswd"}',
            '{"foo":"<IMG SRC=jav&#x0A;ascript:alert(<WBR>>"}',
        ];

        $triggerData = [
            '-example.malicious-localhost-Symfony',
            '-http://ex.com/index.php?page=%252e%252e%252fetc%252fpasswd-localhost-Symfony',
            '><img%20src%3D%26%23x6a;%26%23x61;%26%23x76;%26%23x61',

        ];

        $triggerRule = [
            '#example.malicious#iUu',
            '#(%252e%252f|zip://|php://|file=expect:|http:%252f%252|data://text/plain;|php:expect://)#iUu',
            '#(([<¼]|(&lt;)|%3C|%BC)|([<¼]|(&lt;)|%3C|%BC)[\+\s\x00]*scrscriptipt|%253c|%252F|([<¼]|(&lt;)|%3C|%BC)[\+\s\x00]*script|1script3|1/script3|([<¼]|(&lt;)|%3C|%BC)[\+\s\x00]*[;/][\+\s\x00]*script|([<¼]|(&lt;)|%3C|%BC)[\+\s\x00]*[;/][\+\s\x00]*scrscriptipt|\xc0|\xBC)#iUu',
        ];

        return [
            'xid' => XidService::generate(),
            'ignore' => false,
            'event_code' => $this->faker->randomElement($eventCode),
            'event_score' => rand(0, 100),
            'event_violation' => $this->faker->randomElement($eventViolation),
            'event_comment' => 'a comment',
            'ip' => $this->faker->ipv4,
            'ips' => '["121.121.121.123", "121.121.121.123"]',
            'url' => $this->faker->url,
            'method' => $this->faker->randomElement(['GET', 'POST', 'PUT', 'DELETE', 'PATCH']),
            'referer' => 'referer',
            'header' => '["localhost"]',
            'request' => $this->faker->randomElement($requests),
            'trigger_data' => $this->faker->randomElement($triggerData),
            'trigger_rule' => $this->faker->randomElement($triggerRule),
            'user_agent' => $this->faker->userAgent,
            'robot_crawler' => 'robot crawler',
            'browser_fingerprint' => 'fingerprint'.rand(0, 99),
            'created_at' => $this->faker->date(),
            'deleted_at' => rand(0, 10) === 0 ? $this->faker->date() : null,
            'tripwire_block_id' => null,
        ];
    }

    public function forUser(Authenticatable $user): Factory
    {
        /**
         * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
         */
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
                'user_type' => $user::class,
            ];
        });
    }

    public function ignore(): Factory
    {
        /**
         * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
         */
        return $this->state(function (array $attributes) {
            return [
                'ignore' => true,
            ];
        });
    }

    public function forBlock(TripwireBlock $block): Factory
    {
        /**
         * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
         */
        return $this->state(function (array $attributes) use ($block) {
            return [
                'tripwire_block_id' => $block->id,
            ];
        });
    }
}
