<?php

namespace App\Notifications\ContractorNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;

class MessageFromManager extends Notification //implements ShouldQueue
{
    use Queueable;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Create a new notification instance.
     *
     * @param string $telegramUserId
     * @param string $content
     */
    public function __construct(string $telegramUserId, string $content)
    {
        //$this->data = $data;
        $this->data['telegram_user_id'] = $telegramUserId;
        $this->data['content'] = $content;
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
     * @param string $telegramUserId
     * @param string $content
     * @return TelegramFile
     */
    public function toTelegram($notifiable, string $telegramUserId, string $content)
    {
//            $this->telegram_user_id = $request->input('telegram_user_id'),
//            $this->content = $request->input('content');

        return TelegramFile::create()
            //->to($notifiable->telegram_user_id) // Optional
            ->to($telegramUserId)
            //->options(['parse_mode'])
            ->content($content)
            ->file(public_path('images/bot_statuses/New-notification.png'), 'photo');
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
