<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;
use Telegram\Bot\Laravel\Facades\Telegram;

class ContractorMenu extends Notification
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
            \nДане меню допоможе *Вам* швидко отримати важливу інформацію та оперативно відреагувати на зміни, що відбулись.")
            ->file(public_path('images/bot/menu.png'), 'photo')
            ->options([
                'reply_markup' => Telegram::replyKeyboardMarkup([
                    'inline_keyboard' => [
                        [[
                            'text' => "Список доступных тендеров \xF0\x9F\x93\x83",
                            'url' => "https://contractor.zrobleno.com.ua/new_tender",
                        ]],
                        [[
                            'text' => "Список моих тендеров \xE2\x9C\x8C",
                            'url' => "https://contractor.zrobleno.com.ua/my_tender",
                        ]],
                        [[
                            'text' => "Пополнение баланса \xF0\x9F\x92\xB5",
                            'url' => "https://contractor.zrobleno.com.ua/refill",
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
