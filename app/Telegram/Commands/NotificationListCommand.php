<?php

namespace App\Telegram\Commands;

use App\User;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;
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
use App\Notifications\CustommerNotifications\{
    ConfirmationApp,
    FailedRestartTender,
    SuccessfulRestartTender,
    TenderBlock,
    ApplicationCanceled,
    AccessToDiscounts,
    ContractorConfirmed,
    ContractorSigned
};


/**
 * Class NotificationListCommand.
 */
class NotificationListCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'notificationCategory';

    /**
     * @var string Command Description
     */
    protected $description = 'Notifications list';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $updateHook = json_decode(Telegram::getWebhookUpdates(), true);
        // info("==============================================\n\r");
        // info('$updateHook');
        // info( $updateHook);
        // info("==============================================\n\r");

        $telegramUser = $updateHook['message'];

        // info("============================================\n\r");
        // info('$telegramUser');
        // info($telegramUser);
        // info("============================================\n\r");

        // info("============================================\n\r");
        // info('$this->getContactorKeyboard()');
        // info($this->getContactorKeyboard());
        // info("============================================\n\r");

        $reply_markup = Telegram::replyKeyboardMarkup([
            // 'inline_keyboard' => $this->getContactorKeyboard(),
            'inline_keyboard' => [
                [
                    [
                        'text' => '?????? ????????????????????',
                        'callback_data' => 'ContractorNotificationListCommand',
                    ],
                ],
                [
                    [
                        'text' => '?????? ??????????????????',
                        'callback_data' => 'CustomerNotificationListCommand',
                    ],
                ],
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false

        ]);

        // $keyboard = array(
        //     array(array('callback_data'=>'/butt1','text'=>'???????????? 1')),
        //     array(array('callback_data'=>'/buut2','text'=>'???????????? 2')),
        // );

        // $reply_markup = $telegram->replyKeyboardMarkup([
        //     'keyboard' => $keyboard,
        //     'resize_keyboard' => true,
        //     'one_time_keyboard' => false
        // ]);

        $chat_id = (int) $telegramUser['from']['id'];

        $response = Telegram::sendMessage([
            'chat_id' => $chat_id ,
            'text' => "???????????????? ???????????? ??????????????????????.\n???? ?????????????? ?????????????? ???????? ?????????????????????? ?? ???????????????????? ??????.\n\n?????????????????????? ?????????????????? - /customerNotificationList\n\n?????????????????????? ???????????????????? - /contractorNotificationList \n\n",
            // 'reply_markup' => $reply_markup
        ]);

        $text = 'dawda';

        // $this->replyWithMessage(compact('text'));
    }

}
