<?php

namespace App\Http\Controllers\Telegram\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Register;
use App\Models\TelegramUser;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param TelegramUser $telegramUser
     * @param int $chat_id
     * @return JsonResponse
     */
    public function __invoke(TelegramUser $telegramUser, int $chat_id)
    {
        try {
            $telegramUser = TelegramUser::where('chat_id', $chat_id)->get();
            Notification::send($telegramUser, new Register());

            return response()->json($telegramUser);
        } catch (ModelNotFoundException $e)
        {
            return response()->json($e);
        }
    }
}
