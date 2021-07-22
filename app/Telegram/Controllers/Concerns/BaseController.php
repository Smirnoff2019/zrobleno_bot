<?php

namespace App\Telegram\Controllers\Concerns;

use App\Traits\Loger;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Artisan;
use App\Telegram\Requests\InlineBtnRequest;
use function GuzzleHttp\json_encode;

abstract class BaseController
{

    use Loger;

    /**
     * InlineBtnRequest instance
     *
     * @package App\Telegram\Requests\InlineBtnRequest
     * @var InlineBtnRequest
     */
    protected $request;

    /**
     * TelegramUser model instance
     *
     * @package App\Models\TelegramUser
     * @var TelegramUser
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @param \App\Telegram\Requests\InlineBtnRequest $request
     * @param \App\Models\TelegramUser $user
     */
    public function __construct(InlineBtnRequest $request, TelegramUser $user)
    {
        $this->request = $request;
        $this->user = $user;

        $this->handler($request);
    }

    /**
     * Execute the telegram callback controller.
     *
     * @param \App\Telegram\Requests\InlineBtnRequest $request
     * @return void
     */
    abstract protected function handler($request);

    /**
     * Static method for creating a new class instance.
     *
     * @param \App\Telegram\Requests\InlineBtnRequest $request
     * @param \App\Models\TelegramUser $user
     * @return self
     */
    public static function make(...$args): self
    {
        return new static(...$args);
    }

    /**
     * Convert class instance to string
     *
     * @return string
     */
    public function __toString()
    {
        $name = get_class($this);
        $info = json_encode([
            $name => [
                'request' => $this->request,
                'user' => $this->user,
            ]
        ]);
        return (string) $info;
    }

}
