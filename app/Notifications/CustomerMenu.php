<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;
use Telegram\Bot\Laravel\Facades\Telegram;

class CustomerMenu extends Notification
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
                            'text' => "Мої тендери \xE2\x9C\x8C",
                            'url' => "https://contractor.zrobleno.com.ua/tenders",
                        ]],
                        [[
                            'text' => "Картки знижок \xF0\x9F\x92\xB3",
                            'url' => "https://customer.zrobleno.com.ua/sale_cards",
                        ]],
                        [[
                            'text' => "Партнери \xF0\x9F\x8D\xBB",
                            'url' => "https://customer.zrobleno.com.ua/materials",
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
