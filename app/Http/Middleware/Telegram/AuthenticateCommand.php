<?php

namespace App\Http\Middleware\Telegram;

use Closure;
use App\Traits\Loger;
use Illuminate\Support\Str;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use App\Notifications\Register;
use App\Models\UserBotAuthToken;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthenticateCommand
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
        $this->log('AuthenticateCommand request: ', $request);

        $message = $this->getMessage($request);

        if($this->isStartCommand($message)) {
            try {
                $res = UserBotAuthToken::token($token = $this->getToken($message))->firstOrFail();
                $this->log('AuthenticateCommand UserBotAuthToken: ', $res);

                return $next($request);
            } catch (ModelNotFoundException $e) {
                Notification::send(
                    $this->makeTelegramUser($message),
                    new Register()
                );
                $this->log('AuthenticateCommand:', "Invalid token [token: $token]!");

                return response(true);
            }
        }

        return $next($request);
    }

    /**
     * get sender message.
     *
     * @param Request $request
     * @return object
     */
    protected function getMessage(Request $request)
    {
        return (object) $request->message;
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
     * get the sender's personal token.
     *
     * @param $message
     * @return string
     */
    protected function getToken($message)
    {
        return (string) Str::of($message->text)
            ->after($this->commandName)
            ->trim();
    }

    /**
     * get sender id.
     *
     * @param $message
     * @return string|null
     */
    protected function getChatId($message)
    {
        $userData = $this->getSenderData($message);

        return (string) $userData->id ?? null;
    }

    /**
     * get message data.
     *
     * @param $message
     * @return object
     */
    protected function getSenderData($message)
    {
        return (object) $message->chat;
    }

    /**
     * get the user who is sending the message.
     *
     * @param $message
     * @return TelegramUser
     */
    protected function makeTelegramUser($message)
    {
        return TelegramUser::bindTelegramUser(
            $this->getChatId($message),
            $message->chat
        );
    }
}
