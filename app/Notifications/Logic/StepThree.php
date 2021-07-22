<?php

namespace App\Notifications\Logic;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Telegram\Bot\Laravel\Facades\Telegram;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;

class StepThree extends Notification
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
     * @param mixed $notifiable
     * @return TelegramFile
     */
    public function toTelegram($notifiable)
    {
        return TelegramFile::create()
            ->to($notifiable->chat_id)
            ->content("\xF0\x9F\x91\x89 *Інформація* \xF0\x9F\x91\x88
            \nДане меню допоможе тобі, $notifiable->username, знайти необхідну інформацію, виконати певні дії в системі чи відреагувати на події

            \nТож тисни, кнопка знаходиться біля строки вводу тексту \xF0\x9F\x91\x87")
            ->file(public_path("images/bot_statuses/Info.png"), 'photo')
            ->options([
                'reply_markup' => Telegram::replyKeyboardMarkup([
                    'keyboard' => [
                        [[
                            'text' => "Menu" ,
                        ]],
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ]),
            ]);
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
