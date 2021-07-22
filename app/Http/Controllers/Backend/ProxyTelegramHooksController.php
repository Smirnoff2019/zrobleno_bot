<?php


namespace App\Http\Controllers\Backend;

use App\Traits\Loger;
use App\Models\UserChatBot;
use App\Models\TelegramUser;
use App\Models\WebhookProxy;
use Illuminate\Http\Request;
use App\Jobs\ProxyToLocalhost;
use App\Notifications\Welcome;
use App\Notifications\Register;
use App\Models\UserBotAuthToken;
use App\Http\Controllers\Controller;
use App\Models\WebhookProxyRequest;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Telegram\Requests\InlineBtnRequest;
use App\Telegram\Controllers\CallbackQueryController;
use App\Telegram\Controllers\InlineBtnCallbackController;


class ProxyTelegramHooksController extends Controller
{
    use Loger;

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return string
     */
    public function __invoke(Request $request)
    {
        $data = $request->all();

        $res = WebhookProxy::status(true)
            ->get()
            ->each(function($proxy) use($data) {
                $proxy->requests()->create([
                    WebhookProxyRequest::COLUMN_DATA => json_encode((array) $data),
                ]);

                return ProxyToLocalhost::dispatch($proxy, $data);
            });

        return response()->json([
            'status' => true,
            'result' => $res
        ]);
    }

    /**
     * Processing requests and displaying notifications.
     *
     * @param Request $request
     * @param TelegramUser $telegramUser
     */
    private function sendNotify(Request $request, TelegramUser $telegramUser)
    {
        InlineBtnCallbackController::make(
            InlineBtnRequest::make($request),
            $telegramUser
        );
    }
}
