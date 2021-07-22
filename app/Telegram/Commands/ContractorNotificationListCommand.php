<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Telegram\QueryBuilders\InlineBtnQueryBuilder;
use App\Notifications\ContractorNotifications\{
    NewTender,
    SuccessfulRegistr,
    ContactFromCustomer,
    VictoryInTender,
    TenderBlocked,
    TenderCanceled,
    TenderIsOver,
    AccountReplenished,
    AccountWithdrawn,
    AccountUpdate
};
use App\Traits\Loger;

/**
 * Class NotificationListCommand.
 */
class ContractorNotificationListCommand extends Command
{

    use Loger;

    /**
     * @var string Command Name
     */
    protected $name = 'contractorNotificationList';

    /**
     * @var string Command Description
     */
    protected $description = 'Notifications list for contractor';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $updateHook = json_decode(Telegram::getWebhookUpdates(), true);

        $telegramUser = $updateHook['message'];

        $chat_id = (int) $telegramUser['from']['id'];

        $reply_markup = Telegram::replyKeyboardMarkup([
            'inline_keyboard' => $this->getContactorKeyboard($chat_id),
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ]);

        $response = Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Воспользуйтесь данным меню для выбора необходимой опции.',
            'reply_markup' => $reply_markup
        ]);

        $text = 'ContractorNotificationListCommand';

        // $this->replyWithMessage(compact('text'));
    }

    protected function getNotifyButtonData(string $className) {
        return "notify:$className:data";
    }

    protected function getContactorKeyboard(int $chat_id) {
        return [
            [[
                'text' => 'Новый тендер',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'tg.ntf.newTender',
                    ['chat_id' => $chat_id]
                ),
            ]],
            [[
                'text' => 'Успешная регистрация (покупка) тендера',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-regSuccess',
                    ['chat_id' => $chat_id]
                ),
            ]],
            [[
                'text' => 'Доступны контакты заказчика',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-contCustomer',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('ContactFromCustomer'),
            ]],
            [[
                'text' => 'Заказчик выбрал вас победителем тендера',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-tenderVictory',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('VictoryInTender'),
            ]],
            [[
                'text' => 'Тендер заморожен или приостановлен',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-tenderBlocked',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('TenderBlocked'),
            ]],
            [[
                'text' => 'Тендер анулирован',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-tenderCanceled',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('TenderCanceled'),
            ]],
            [[
                'text' => 'Окончание тендера',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-tenderOver',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('TenderIsOver'),
            ]],
            [[
                'text' => 'Статусы пополения счёта',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-accountRepl',
                    ['chat_id' => $chat_id]
                ),
            ]],
            [[
                'text' => 'Статусы списания счёта',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-accountWithdrawn',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('AccountWithdrawn'),
            ]],
            [[
                'text' => 'Личные данные успешно обновлены',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-accountUpdate',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('AccountUpdate'),
            ]],
        ];
    }
}
