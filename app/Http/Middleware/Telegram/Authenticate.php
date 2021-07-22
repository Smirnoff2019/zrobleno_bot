<?php

namespace App\Http\Middleware\Telegram;

use Closure;
use App\Traits\Loger;
use App\Models\UserChatBot;
use Illuminate\Support\Str;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use App\Notifications\Register;
use App\Models\UserBotAuthToken;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Authenticate
{

    use Loger;

    /**
     * Telegram auth command name by token
     *
     * @var string
     */
    protected $commandName = '/start';

    /**
     * The route name the user should be redirected to when they are not authenticated.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $message = $this->getMessage($request);
        $chat_id = $this->getChatId($message);

        if ($this->isStartCommand($message)) return $next($request);

        try {

            $userAuth = UserChatBot::chat($chat_id)
                ->telegramApp()
                ->firstOrFail();

        } catch (ModelNotFoundException $e) {

            Notification::send(
                $this->makeTelegramUser($message),
                new Register()
            );
            $this->log('Authenticate:', "User with [chat_id: $chat_id] is unauthorized!");

            return response(true);
        }

        return $next($request);
    }

    /**
     * define the part of the message where the command name and the sender's token are present.
     *
     * @param $message
     * @return bool
     */
    protected function isStartCommand($message)
    {
        return Str::startsWith($message->text, $this->commandName);
    }

    /**
     * get sender message.
     *
     * @param Request $request
     * @return object
     */
    protected function getMessage(Request $request) {
        return (object) $request->message;
    }

    /**
     * get the sender's personal token.
     *
     * @param $message
     * @return string
     */
    protected function getToken($message) {
        return (string) Str::of($message->text)
            ->after($this->authCommandName)
            ->trim();
    }

    /**
     * get sender id.
     *
     * @param $message
     * @return string|null
     */
    protected function getChatId($message) {
        $userData = $this->getSenderData($message);

        return (string) $userData->id ?? null;
    }

    /**
     * get message data.
     *
     * @param $message
     * @return object
     */
    protected function getSenderData($message) {
        return (object) $message->chat;
    }

    /**
     * get the user who is sending the message.
     *
     * @param $message
     * @return TelegramUser
     */
    protected function makeTelegramUser($message) {
        return TelegramUser::bindTelegramUser(
            $this->getChatId($message),
            $message->chat
        );
    }

}
