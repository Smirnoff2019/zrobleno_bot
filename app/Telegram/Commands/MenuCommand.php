<?php

namespace App\Telegram\Commands;

use App\User;
use App\Models\Role;
use App\Traits\Loger;
use Telegram\Bot\Actions;
use App\Models\UserChatBot;
use App\Notifications\Menu;
use App\Models\TelegramUser;
use App\Notifications\Register;
use Telegram\Bot\Commands\Command;
use App\Notifications\CustomerMenu;
use App\Jobs\Telegram\SendNotifyJob;
use App\Notifications\ContractorMenu;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class TestCommand.
 */
class MenuCommand extends Command
{

    use Loger;

    /**
     * @var string Command Name
     */
    protected $name = 'menu';

    /**
     * @var string Command Description
     */
    protected $description = 'Menu command, Get a menu to facilitate work with the service';

    /**
     * {@inheritdoc}
     */
    public function handle($token)
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $hook = Telegram::getWebhookUpdates();
        $hookData = json_decode($hook);
        $chat_id = $hookData->message->from->id;
        $this->log('MenuCommand chat_id: ', $chat_id);

        $telegramUser = TelegramUser::where('chat_id', $chat_id)->firstOrFail();

        try {
            $userChatBot = UserChatBot::where('chat_id', $chat_id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            Notification::send($telegramUser, new Register());
            return true;
        }

        try {
            $user = User::with('role')->where('id', $userChatBot->user_id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            Notification::send($telegramUser, new Register());
            return true;
        }

        // SendNotifyJob::dispatchNow($telegramUser);

        switch ($user->role->slug) {
            case 'customer':
                Notification::send($telegramUser, new CustomerMenu());
                break;

            case 'contractor':
                Notification::send($telegramUser, new ContractorMenu());
                break;

            default:
                Notification::send($telegramUser, new Menu());
                break;
        }

        return true;
    }
}
