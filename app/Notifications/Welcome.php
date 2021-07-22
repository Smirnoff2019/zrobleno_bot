<?php

namespace App\Notifications;

use App\Models\TelegramUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;

class Welcome extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the telegram representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return TelegramFile
     */
    public function toTelegram($notifiable)
    {
        return TelegramFile::create()
            ->to($notifiable->chat_id)
            ->content("\xF0\x9F\x91\x89 *Реєстрація пройшла з успіхом!* \xF0\x9F\x91\x88
            \nВітаємо *Вас*! \xF0\x9F\x98\x89 Тепер *Ви*, *$notifiable->username*, можете продовжити спілкування з *BotZrobleno*! \xF0\x9F\x91\x8D")
            //\nНатисніть на кнопку *меню*, що знаходиться під клавіатурою і з легкістю використовуйте можливості цієї функції. \xF0\x9F\x91\x87")
            ->file(public_path('images/bot/success-auth.png'), 'photo');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
