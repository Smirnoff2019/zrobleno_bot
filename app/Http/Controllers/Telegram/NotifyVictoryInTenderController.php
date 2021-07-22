<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\TelegramUser;
use App\Notifications\ContractorNotifications\VictoryInTender;
use Illuminate\Http\Request;
use App\Traits\Loger;
use Illuminate\Support\Facades\Notification;

class NotifyVictoryInTenderController extends Controller
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
        $this->log('NotifyVictoryInTenderController $telegramUser', $telegramUser);
        Notification::send($telegramUser, new VictoryInTender());

        return true;
    }
}
