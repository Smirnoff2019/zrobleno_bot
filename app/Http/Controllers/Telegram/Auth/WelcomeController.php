<?php

namespace App\Http\Controllers\Telegram\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Models\UserBotAuthToken;
use App\Notifications\Welcome;

class WelcomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param UserBotAuthToken $userBotAuthToken
     * @return RedirectResponse
     */
    public function __invoke(Request $request, UserBotAuthToken $userBotAuthToken)
    {
//        try {
//            $userBotAuthToken = UserBotAuthToken::whereToken($request->token)->get();
//            Notification::send($userBotAuthToken, new Welcome());
//        } catch ()
//        {
//
//        }
//        if(!UserChatBot::where('chat_id', $request->chat_id))
//        {
//            return redirect()->route('registr.notify');
//        }
//
//        $userChatBot = UserChatBot::where('chat_id', $request->chat_id)->get();
//        Notification::send($userChatBot, new Welcome());
//
//        return response()->json($userChatBot);
    }
}
