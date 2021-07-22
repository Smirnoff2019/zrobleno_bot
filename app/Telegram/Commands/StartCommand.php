<?php

namespace App\Telegram\Commands;

use App\Models\TelegramUser;
use App\Notifications\Logic\OneNotify;
use App\Notifications\Logic\StepOne;
use App\Traits\Loger;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Notification;
use Telegram\Bot\Actions;
use App\Models\UserChatBot;
use App\Models\UserBotAuthToken;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Notifications\Welcome;
use App\Notifications\Register;

/**
 * Class TestCommand.
 */
class StartCommand extends Command
{
    use Loger;

    /**
     * @var string Command Name
     */
    protected $name = 'start';

    /**
     * @var string Command Description
     */
    protected $description = 'Start command, Get a message of command start';

    /**
     * {@inheritdoc}
     */
    public function handle($token)
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->log('StartCommand@handle $arguments', $token);

        $hook = Telegram::getWebhookUpdates();
        $hookData = json_decode($hook);
        $this->log($hook);
        $this->log('$hookData', var_dump($hookData));

        $chat_id = $hookData->message->from->id;
        $this->log('$chat_id', $chat_id);

        $telegramUser = TelegramUser::where('chat_id', $chat_id)->firstOrFail();
        $this->log('$telegramUser', $telegramUser);
        //---------------------------------------------------------
        if (!$token || empty($token))
        {
            try {

                if (UserChatBot::where('chat_id', $chat_id)->firstOrFail())
                {
                    Notification::send(
                        $telegramUser,
                        new OneNotify());
                }

            } catch (ModelNotFoundException $e) {

                Notification::send(
                    $telegramUser,
                    new Register()
                );

                $this->replyWithMessage([
                    'text' => "\xF0\x9F\x98\x95 Вам необхідно пройти *реєстрацію* \xF0\x9F\x91\x86",
                    'parse_mode' => 'MarkdownV2',
                    'reply_markup' => Telegram::replyKeyboardMarkup([
                        'keyboard' => [
                            [[
                                'text' => "/start" ,
                            ]],
                        ],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true,
                    ]),
                ]);
                return response(true);
            }
            return true;
        }
        //--------------------------------------------------------
        if ($token)
            {
                $userBotAuthToken = UserBotAuthToken::where('token', $token)
                    ->firstOrFail();
                UserChatBot::bindTelegramChat($chat_id, $userBotAuthToken->user_id);

                Notification::send(
                    $telegramUser,
                    new Welcome()
                );

                Notification::send(
                    $telegramUser,
                    new OneNotify()
                );
            }
        return true;
    }
}
