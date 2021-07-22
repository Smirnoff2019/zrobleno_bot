<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContractorNotifications\AccountUpdate;
use App\Traits\Loger;

class NotifyAccountUpdateController extends Controller
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
        $this->log('NotifyAccountUpdateController $telegramUser', $telegramUser);
        Notification::send($telegramUser, new AccountUpdate());

        return true;
    }
}
