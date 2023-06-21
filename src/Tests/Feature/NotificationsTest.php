<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Responses;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Notifications\Notifiable;
use Yormy\TripwireLaravel\Notifications\UserBlockedNotification;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\BlockTestTrait;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class NotificationsTest extends TestCase
{
    use BlockTestTrait;
    use TripwireTestTrait;

    private string $tripwire = 'text';

    protected $tripwireClass = Text::class;

    const BLOCK_CODE = 401;

    const TRIPWIRE_TRIGGER = 'HTML-RESPONSE-TEST';

    /**
     * @test
     *
     * @group tripwire-notifications
     */
    public function Block_added_Disabled_notifications(): void
    {
        $this->setConfig();
        Notification::fake();
        $this->triggerBlock();
        Notification::assertSentTimes(UserBlockedNotification::class, 0);
    }

    /**
     * @test
     *
     * @group tripwire-notifications
     */
    public function Block_added_Send_notification_mail(): void
    {
        $this->setConfig();
        $mailSettings = [
            'enabled' => true,
            'name' => 'Tripwire',
            'from' => 'Tripwire@system.com',
            'to' => 'Tripwire@system.com',
            'template_plain' => 'tripwire-laravel::email_plain',
            'template_html' => 'tripwire-laravel::email',
        ];
        config(["tripwire.notifications.mail" => [$mailSettings, $mailSettings]]);

        Notification::fake();
        $this->triggerBlock();
        Notification::assertSentTimes(UserBlockedNotification::class, 2);
        Notification::assertSentTo((new Notifiable), UserBlockedNotification::class, function ($notification, $channels) {
            return in_array('mail', $channels);
        });
    }

    /**
     * @test
     *
     * @group tripwire-notifications
     */
    public function Blocked_added_Send_notification_slack(): void
    {
        $this->setConfig();
        $slackSettings =[
            'enabled' => true,
            'from' => 'Tripwire@system.com',
            'to' => 'Tripwire@system.com',
            'channel' => 'xxxx',
        ];
        config(["tripwire.notifications.slack" => [$slackSettings, $slackSettings] ]);

        Notification::fake();
        $this->triggerBlock();
        Notification::assertSentTimes(UserBlockedNotification::class, 2);
        Notification::assertSentTo((new Notifiable), UserBlockedNotification::class, function ($notification, $channels) {
            return in_array('slack', $channels);
        });
    }

    protected function setConfig(): void
    {
        $settings = ['code' => 409];

        $mailSettings = [
            'enabled' => false,
            'name' => 'Tripwire',
            'from' => 'Tripwire@system.com',
            'to' => 'Tripwire@system.com',
            'template_plain' => 'tripwire-laravel::email_plain',
            'template_html' => 'tripwire-laravel::email',
        ];
        config(["tripwire.notifications.mail" => [$mailSettings]]);

        $slackSettings =[
            'enabled' => false,
            'from' => 'Tripwire@system.com',
            'to' => 'Tripwire@system.com',
            'channel' => 'xxxx',
        ];
        config(["tripwire.notifications.slack" => [$slackSettings] ]);


        config(["tripwire_wires.$this->tripwire.enabled" => true]);
        config(["tripwire_wires.$this->tripwire.methods" => ['*']]);
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => $settings]);
        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);

        config(["tripwire_wires.$this->tripwire.attack_score" => 10]);
        config(['tripwire.punish.score' => 21]);

        $this->setBlockConfig();
    }

    private function triggerBlock(): void
    {
        $this->resetBlockStartCount();

        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
    }
}
