<?php

namespace App\Http\Controllers\Telegram;

use App\Models\TelegramUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContractorNotifications\SuccessfulRegistr;
use App\Traits\Loger;

class NotifySuccessfulRegistrController extends Controller
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
        $this->log('NotifySuccessfulRegistrController $telegramUser', $telegramUser);
        Notification::send($telegramUser, new SuccessfulRegistr());

        return true;
    }
}
