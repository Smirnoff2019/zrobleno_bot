<?php

namespace App\Notifications\ContractorNotifications;

use App\Models\TelegramUser;
use App\Models\UserChatBot;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Telegram\Bot\Laravel\Facades\Telegram;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;

class AccountUpdate extends Notification
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
        $userChatBot = UserChatBot::where('chat_id', $notifiable->chat_id)->firstOrFail();
        $user = User::with('role')->where('id', $userChatBot->user_id)->firstOrFail();

        return TelegramFile::create()
            ->to($notifiable->chat_id)
            ->content("Доброго дня!
*Хочемо Вам повідомити*! Ваші особисті дані облікового запису
були *успішно оновлені*.
Ім'я                  - $user->first_name
Фамілія               - $user->last_name
Телефон               - $user->phone
Поштова скринька      - $user->email
Ім'я у телеграмі      - $notifiable->first_name
Фамілія у телеграмі   - $notifiable->last_name
Звернення у телеграмі - $notifiable->username
")
            ->file(public_path('images/bot_statuses/Your-personal-data-has-been-successfully-updated.png'), 'photo')
            ->options([
                'reply_markup' => Telegram::replyKeyboardMarkup([
                    'inline_keyboard' => [
                        [[
                            'text' => "Далі",
                            'callback_data' => 'stepFive',
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
