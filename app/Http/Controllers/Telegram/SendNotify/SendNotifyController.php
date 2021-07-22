<?php

namespace App\Http\Controllers\Telegram\SendNotify;

use App\Http\Controllers\Controller;
use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendFromManager\SendNotify;

class SendNotifyController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        try {
            $result = Notification::send(
                TelegramUser::userId((array) $request->user_id)->firstOrFail(),
                new SendNotify($request->all())
            );
            return response()->json([
                'status' => 200,
                'message' => 'Notification successful send to user!',
                'data' => (array) $result
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }
}
