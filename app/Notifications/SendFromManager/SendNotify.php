<?php

namespace App\Notifications\SendFromManager;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramChannel;


class SendNotify extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Request
     * @var array
     */
    public $data;

    /**
     * Create a new notification instance.
     *
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->data = $request;
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
     * @param $user
     * @return TelegramFile
     */
    public function toTelegram($user)
    {
        $data = (object) $this->data;

        return TelegramFile::create()
            ->to((string) $user->chat_id)
            ->content($data->content)
            ->file($data->attachment['cover']['url'], 'photo')
            ->options([
                'reply_markup' => $data->attachment['buttons']
                    ? Telegram::replyKeyboardMarkup([
                        'inline_keyboard' => collect($data->attachment['buttons'])
                            ->map(function ($btn) {
                                return [[
                                    "text" => $btn['name'],
                                    "url" => $btn['url']
                                ]];
                            })
                            ->toArray(),
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true,
                    ])
                    : [],
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
