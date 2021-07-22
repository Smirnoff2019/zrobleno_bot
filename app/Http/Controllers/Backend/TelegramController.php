<?php

namespace App\Http\Controllers\Backend;

use App\Notifications\ContractorNotifications\AccountUpdate;
use App\Notifications\CustomerNotifications\AccessToDiscounts;
use App\Notifications\Logic\StepFive;
use App\Notifications\Logic\StepFour;
use App\Notifications\Logic\StepSix;
use App\Notifications\Logic\StepThree;
use App\Notifications\Logic\StepTwo;
use App\Traits\Loger;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use App\Telegram\Requests\InlineBtnRequest;
use App\Telegram\Controllers\CallbackQueryController;
use App\Telegram\Controllers\InlineBtnCallbackController;
use Illuminate\Support\Str;
use Telegram\Bot\Laravel\Facades\Telegram;


class TelegramController extends Controller
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
        $this->middleware('auth.command.start');
        $this->middleware('auth.telegram');

        $this->log('Start request');
        $this->log('request: ', $request);

        $chat_id = $request['message']['from']['id'] ?? $request['callback_query']['from']['id'];
        $telegramMessage = $request['message'] ?? $request['callback_query'];

        $this->log('$telegramMessage: ', $request['callback_query']);

        $tgUser = $telegramMessage['from'];
        $telegramUser = TelegramUser::bindTelegramUser($chat_id, $tgUser);

        //$this->sendNotify($request, $telegramUser);
        $this->sendCallbackData($request, $telegramUser);
        $this->getLearningMenu($request, $telegramUser);

        $this->log('Finish request \n\r');

        Telegram::commandsHandler(true);

        return response(true);
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

    /**
     * processing the learning logic
     *
     * @param $request
     * @param $telegramUser
     * @return bool
     */
    private function sendCallbackData($request, $telegramUser)
    {
        $callbackData = (string) $request['callback_query']['data'];
        $data = Str::of($callbackData);
        $this->log('TelegramController->request $data', $data);

        switch ($data) {
            case 'stepTwo':
                Notification::send($telegramUser, new StepTwo());
                break;

            case 'stepThree':
                Notification::send($telegramUser, new StepThree());
                break;

            case 'my_data':
                Notification::send($telegramUser, new AccountUpdate());
                break;

            case 'my_score':
                Notification::send($telegramUser, new AccessToDiscounts());
                break;

            case 'stepFive':
                Notification::send($telegramUser, new StepFive());
                break;

            case 'end':
                Notification::send($telegramUser, new StepSix());
                break;
        }
        return true;
    }

    /**
     * back to processing the learning logic
     *
     * @param $request
     * @param $telegramUser
     * @return bool
     */
    private function getLearningMenu($request, $telegramUser)
    {
        if ($request['message']['text'] == 'Menu')
        {
            Notification::send($telegramUser, new StepFour());
        }
        return true;
    }
}
