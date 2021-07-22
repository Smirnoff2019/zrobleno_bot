<?php

namespace App\Http\Controllers\Telegram\Auth;

use App\Http\Controllers\Controller;
use App\Models\TelegramUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Models\BotToken;
use App\Models\UserChatBot;
use App\Notifications\Welcome;
use App\Notifications\Menu;

class AuthBotController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $botToken = BotToken::where('token', $request->token)->firstOrFail();

        $userChatBot = UserChatBot::bindTelegramChat($request->user_id, $botToken->chat_id);

        $telegramUser = TelegramUser::where('chat_id', $botToken->chat_id)->first();

        Notification::send($userChatBot, new Welcome());
        //Notification::send($userChatBot, new Menu());

        return response()->json(['success' => true, 'data' => $telegramUser]);
    }
}
