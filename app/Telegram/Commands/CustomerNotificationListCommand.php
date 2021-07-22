<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Telegram\QueryBuilders\InlineBtnQueryBuilder;
use App\Notifications\CustommerNotifications\{
    ConfirmationApp,
    FailedRestartTender,
    SuccessfulRestartTender,
    TenderBlock,
    ApplicationCanceled,
    AccessToDiscounts,
    ContractorConfirmed,
    ContractorSigned,
};

/**
 * Class NotificationListCommand.
 */
class CustomerNotificationListCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'customerNotificationList';

    /**
     * @var string Command Description
     */
    protected $description = 'Notifications list for customer';

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
            'inline_keyboard' => $this->getCustomerKeyboard($chat_id),
            'resize_keyboard' => true,
            'one_time_keyboard' => false

        ]);

        $chat_id = (int) $telegramUser['from']['id'];

        $response = Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Воспользуйтесь данным меню для выбора необходимой опции.',
            'reply_markup' => $reply_markup
        ]);

        $text = 'CustomerNotificationListCommand';

        // $this->replyWithMessage(compact('text'));
    }

    protected function getNotifyButtonData(string $className) {
        return "notify:$className:data";
    }

    protected function getCustomerKeyboard(int $chat_id) {
        return [
            [[
                'text' => 'Подтверждение заявки на тендер',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-confirmApp',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('ConfirmationApp'),
            ]],
            [[
                'text' => 'Подрядчик подписался на тендер',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-contractorSigned',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('ContractorSigned'),
            ]],
            [[
                'text' => 'Подрядчик подтвердил сделку',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-contractorConfirm',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('ContractorConfirmed'),
            ]],
            [[
                'text' => 'Доступ к скидкам',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-accessToDiscounts',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('AccessToDiscounts'),
            ]],
            [[
                'text' => 'Заявка на тендер анулирована',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-AppCanceled',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('ApplicationCanceled'),
            ]],
            [[
                'text' => 'Тендер заморожен или приостановлен',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-tenderBlock',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('TenderBlock'),
            ]],
            [[
                'text' => 'Об успешном перезапуске тендера',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-successRestart',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('SuccessfulRestartTender'),
            ]],
            [[
                'text' => 'Об провальном перезапуске тендера',
                'callback_data' => InlineBtnQueryBuilder::create(
                    'notify-failedRestart',
                    ['chat_id' => $chat_id]
                ),
                //'callback_data' => $this->getNotifyButtonData('FailedRestartTender'),
            ]],
        ];
    }

}
