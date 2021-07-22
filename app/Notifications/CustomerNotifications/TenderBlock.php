<?php

namespace App\Notifications\CustomerNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;

class TenderBlock extends Notification implements ShouldQueue
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
            //->to($notifiable->telegram_user_id) // Optional
            ->to($notifiable->chat_id)
            ->content('Доброго времени суток!
*Спешим Вам сообщить*! Ваш тендер *"Ремонт двух
комнат в новострое"* был приостановлен.

С деталями можно ознакомиться перейдя по ссылке под
сообщением!

[Посмотреть детали](http://customer.zrobleno.com.ua/tender)')
            ->file(public_path('images/bot_statuses/The-tender-has-been-frozen-or-suspended.png'), 'photo');
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
