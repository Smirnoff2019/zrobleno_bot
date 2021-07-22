<?php

namespace App\Http\Controllers\Telegram\Auth;

use App\Http\Controllers\Controller;
use App\Models\TelegramUser;
use Illuminate\Http\JsonResponse;

class RemoveController extends Controller
{
    /**
     * @param int $id
     * @return JsonResponse
     */
    public function deleteUser(int $id)
    {
        $telegramUser = TelegramUser::findOrFail($id);
        $telegramUser->delete();

        return response()->json(["message" => "TelegramUser deleted successfully."], 200);
    }

    /**
     * @param TelegramUser $telegramUser
     * @return JsonResponse
     */
    public function deleteAllUser(TelegramUser $telegramUser)
    {
        $telegramUser = TelegramUser::truncate();
        //$telegramUser->delete();

        return response()->json(["message" => "TelegramUsers deleted successfully."], 200);
    }
}
