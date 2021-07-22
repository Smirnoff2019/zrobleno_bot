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
class MenuCustomerCommand extends Command
{

    use Loger;

    /**
     * @var string Command Name
     */
    protected $name = 'menuCustomer';

    /**
     * @var string Command Description
     */
    protected $description = 'MenuCustomer command, Get a message with menu for a customer';

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
                        'text' => "Мои тендеры \xE2\x9C\x8C",
                        'url' => "https://customer.zrobleno.com.ua/tender",
                    ]],
                    [[
                        'text' => "Скидочные карты \xF0\x9F\x92\xB3",
                        'url' => "https://customer.zrobleno.com.ua/sale_cards",
                    ]],
                    [[
                        'text' => "Партнёры \xF0\x9F\x8D\xBB",
                        'url' => "https://customer.zrobleno.com.ua/materials",
                    ]],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]),
        ]);
    }
}

