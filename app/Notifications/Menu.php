<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;
use Telegram\Bot\Laravel\Facades\Telegram;

class Menu extends Notification
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
            \nНажаль, дане меню *Вам*, на цей час, нічим не допоможе. Але є деякі корисні посилання, приємного перегляду \xF0\x9F\x98\x89")
            ->file(public_path('images/bot/menu.png'), 'photo')
            ->options([
                'reply_markup' => Telegram::replyKeyboardMarkup([
                    'inline_keyboard' => [
                        [[
                            'text' => "Головна \xE2\x84\xA2",
                            'url' => "https://zrobleno.com.ua",
                        ]],
                        [[
                            'text' => "Проєкт \xE2\xAD\x90",
                            'url' => "https://app.zrobleno.com.ua",
                        ]],
                        [[
                            'text' => "Месенжер \xF0\x9F\x93\xB1",
                            'url' => "https://bot.zrobleno.com.ua",
                        ]],
                        [[
                            'text' => "Авторизація \xF0\x9F\x94\x90",
                            'url' => "https://auth.zrobleno.com.ua",
                        ]],
                        [[
                            'text' => "Замовник \xF0\x9F\x91\xA8",
                            'url' => "https://customer.zrobleno.com.ua",
                        ]],
                        [[
                            'text' => "Виконавець \xF0\x9F\x91\xB7",
                            'url' => "https://contractor.zrobleno.com.ua",
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
