<?php

namespace App\Notifications\Logic;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Telegram\Bot\Laravel\Facades\Telegram;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;

class StepFour extends Notification
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
            ->content("\xF0\x9F\x91\x89 *Меню* \xF0\x9F\x91\x88
            \nТепер, $notifiable->username, скористайся наданими тобі функціями, щоб переглянути особисті данні чи отримати інформацію про власний рахунок.

            \nТож тисни, щоб продовжити перегляд \xF0\x9F\x91\x87")
            ->file(public_path('images/bot_statuses/Info.png'), 'photo')
            ->options([
                'reply_markup' => Telegram::replyKeyboardMarkup([
                    'inline_keyboard' => [
                        [[
                            'text' => "Особисті данні",
                            'callback_data' => 'my_data',
                        ]],
                        [[
                            'text' => "Перевірити рахунок",
                            'callback_data' => 'my_score',
                        ]],
                        [[
                            'text' => "Мені це не потрібно",
                            'callback_data' => 'end',
                        ]],
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ])
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
