<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Database\Factories;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\Xid\Services\XidService;

class TripwireBlockFactory extends Factory
{
    /**
     * @var string $model
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $model = TripwireBlock::class;

    /**
     * @return array<string>
     */
    public function definition(): array
    {
        return [
            'xid' => XidService::generate(),
            'ignore' => rand(0, 10) === 0 ? true : false,
            'type' => 'Hacky?', // what is this
            'reasons' => json_encode('???'),
            'blocked_ip' => $this->faker->ipv4,
            'blocked_browser_fingerprint' => 'blocked fingerprint',
            'blocked_repeater' => rand(0, 100),
            'internal_comments' => 'internal comment',
            'manually_blocked' => rand(0, 5) === 0 ? true : false,
            'persistent_block' => rand(0, 10) === 0 ? true : false,
            'blocked_until' => $this->faker->dateTimeBetween,
            'created_at' => $this->faker->date,
            'deleted_at' => rand(0, 10) === 0 ? $this->faker->date : null,
        ];
    }

    public function forUser(Authenticatable $user): Factory
    {
        /**
         * @param array<string> $attributes
         *
         * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
         */
        return $this->state(function (array $attributes) use ($user) {
            return [
                'blocked_user_id' => $user->id,
                'blocked_user_type' => $user::class,
            ];
        });
    }

    public function ignore(): Factory
    {
        /**
         * @param array<string> $attributes
         *
         * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
         */
        return $this->state(function (array $attributes) {
            return [
                'ignore' => true,
            ];
        });
    }
}
