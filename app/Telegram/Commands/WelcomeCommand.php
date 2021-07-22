<?php

namespace App\Telegram\Commands;

use App\Models\BotToken;
use App\Traits\Loger;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use NotificationChannels\Telegram\TelegramFile;

/**
 * Class TestCommand.
 */
class WelcomeCommand extends Command
{

    use Loger;

    /**
     * @var string Command Name
     */
    protected $name = 'welcome';

    /**
     * @var string Command Description
     */
    protected $description = 'Welcome command, Get a message for a auth user';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->log('$arguments', $arguments);

        $hook = Telegram::getWebhookUpdates();
        $hookData = json_decode($hook);

        $this->log('$hookData', var_dump($hookData));

        $chat_id = $hookData->message->from->id;

        $this->log('$chat_id', $chat_id);

//        $botToken = BotToken::firstOrCreate(
//            [
//                "chat_id" => $chat_id,
//            ],
//            [
//                "token" => BotToken::createToken(), //$token = Str::random(16)
//            ]
//        );

        $this->replyWithPhoto([
            'chat_id' => $chat_id,
            'photo' => public_path('images/bot_statuses/Info.png'),
        ]);
        $this->replyWithMessage([
            'text' => "\xF0\x9F\x98\x95 *Авторизация прошла успешно, теперь вы можете продолжить общение с BotZrobleno\!* \xF0\x9F\x91\x87",
            'parse_mode' => 'MarkdownV2',
            'reply_markup' => Telegram::replyKeyboardMarkup([
                'inline_keyboard' => [
                    [[
                        'text' => "Далее \xF0\x9F\x94\x93" ,
                        'url' => "https://bot.zrobleno.com.ua/telegram/", //goto $token
                    ]],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]),
        ]);
    }
}
