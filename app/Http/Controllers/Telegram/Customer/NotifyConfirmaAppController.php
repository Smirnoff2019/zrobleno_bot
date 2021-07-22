<?php

namespace App\Http\Controllers\Telegram\Customer;

use App\Http\Controllers\Controller;
use App\Models\TelegramUser;
use App\Notifications\CustomerNotifications\ConfirmationApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Traits\Loger;

class NotifyConfirmaAppController extends Controller
{
    use Loger;

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param int $chat_id
     * @return bool
     */
    public function __invoke(Request $request, int $chat_id)
    {
        $telegramUser = TelegramUser::where('chat_id', $chat_id)->first();
        $this->log('NotifyConfirmaAppController $telegramUser', $telegramUser);
        Notification::send($telegramUser, new ConfirmationApp());

        return true;
    }
}
