<?php

namespace App\Http\Controllers\Telegram\Customer;

use App\Http\Controllers\Controller;
use App\Models\TelegramUser;
use App\Notifications\CustomerNotifications\ContractorConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Traits\Loger;

class NotifyContractorConfirmedController extends Controller
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
        $this->log('NotifyContractorConfirmedController $telegramUser', $telegramUser);
        Notification::send($telegramUser, new ContractorConfirmed());

        return true;
    }
}
