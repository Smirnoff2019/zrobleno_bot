<?php

namespace App\Telegram\Commands;

use App\Models\BotToken;
use App\Traits\Loger;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Telegram\Requests\InlineBtnRequest;
use NotificationChannels\Telegram\TelegramFile;

/**
 * Class TestCommand.
 */
class MenuContractorCommand extends Command
{

    use Loger;

    /**
     * @var string Command Name
     */
    protected $name = 'menuContractor';

    /**
     * @var string Command Description
     */
    protected $description = 'MenuContractor command, Get a message with menu for a contractor';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->log('$arguments', $arguments);

//         $user = User::find(1);
//         $this->replyWithMessage(['text' => 'Почта пользователя в laravel: ' . $user->email]);

//         $telegramUser = Telegram::getWebhookUpdates()['message'];
//         $text = sprintf('%s: %s'.PHP_EOL, 'Ваш номер чата', $telegramUser['from']['id']);
        //Что бы не было ошибки, нужна проверка данных на сервере телеграма
        // $text .= sprintf('%s: %s'.PHP_EOL, 'Ваше имя пользователя в телеграм', $telegramUser['from']['username']);

//         $keyboard = [
//             ['/start'],
//         ];
//
//         $reply_markup = Telegram::replyKeyboardMarkup([
//             'keyboard' => $keyboard,
//             'resize_keyboard' => true,
//             'one_time_keyboard' => true
//         ]);
//
//         $response = Telegram::sendMessage([
//             'chat_id' => $telegramUser['from']['id'],
//             'text' => 'Доброго времени суток! *Спасибо*, что Вы с нами. Для продолжения введите свой *email*',
//             'reply_markup' => $reply_markup
//         ]);

        //$messageId = $response->getMessageId();

        //$text = "Доброго времени суток!";
//=============================================================
        $hook = Telegram::getWebhookUpdates();
        $hookData = json_decode($hook);

        $this->log('$hookData', var_dump($hookData));

        $chat_id = $hookData->message->from->id;

        $this->log('$chat_id', $chat_id);

//        $botToken = BotToken::firstOrCreate(
//            [
//                "chat_id" => $chat_id,
//                "token" => BotToken::createToken() //$token = Str::random(12)
//            ]
//        );

        $this->replyWithPhoto([
            'chat_id' => $chat_id,
            'photo' => public_path('images/bot_statuses/New-mail.png'),
        ]);
        $this->replyWithMessage([
            'text' => "\xF0\x9F\x91\x89 *Воспользуйтесь данным меню для получения нужной вам информации\!* \xF0\x9F\x91\x87",
            'parse_mode' => 'MarkdownV2',
            'reply_markup' => Telegram::replyKeyboardMarkup([
                'inline_keyboard' => [
                    [[
                        'text' => "Список доступных тендеров \xF0\x9F\x93\x83",
                        'url' => "https://contractor.zrobleno.com.ua/new_tender",
                    ]],
                    [[
                        'text' => "Список моих тендеров \xE2\x9C\x8C",
                        'url' => "https://contractor.zrobleno.com.ua/my_tender",
                    ]],
                    [[
                        'text' => "Пополнение баланса \xF0\x9F\x92\xB5",
                        'url' => "https://contractor.zrobleno.com.ua/refill",
                    ]],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]),
        ]);
//============================================================================
    }
}
