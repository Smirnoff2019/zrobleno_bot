<?php

namespace App\Notifications\ContractorNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;

class NewTender extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the telegram representation of the notification.
     *
     * @param $notifiable
     * @return TelegramFile
     */
    public function toTelegram($notifiable)
    {
        return TelegramFile::create()
            ->to($notifiable->chat_id)
            //->to($notifiable->to)
            ->content("\xF0\x9F\x91\x89* Новий тендер *\xF0\x9F\x91\x88
            \n*Доброго времени суток!* Сьогодні з'явився новий тендер!!!
            \nС деталями можно ознакомиться перейдя по ссылке под сообщением! \xF0\x9F\x91\x87
            \n[Посмотреть детали](http://customer.zrobleno.com.ua/tender)")
            ->file(public_path('images/bot_statuses/New-tender.png'), 'photo');
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
