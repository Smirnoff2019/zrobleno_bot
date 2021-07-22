<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\TelegramUser;
use App\Notifications\ContractorNotifications\TenderIsOver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Traits\Loger;

class NotifyTenderOverController extends Controller
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
        $this->log('NotifyTenderOverController $telegramUser', $telegramUser);
        Notification::send($telegramUser, new TenderIsOver());

        return true;
    }
}
