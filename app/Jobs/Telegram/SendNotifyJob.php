<?php

namespace App\Jobs\Telegram;

use App\Models\TelegramUser as TelegramUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\CustomerNotifications\TenderBlock;
use App\Notifications\ContractorNotifications\AccountReplenished;
use App\Notifications\CustomerNotifications\SuccessfulRestartTender;
use Illuminate\Support\Facades\Notification;
use App\Traits\Loger;

class SendNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Loger;

    /**
     * @var $telegramUser
     */
    protected $telegramUser;

    /**
     * Create a new job instance.
     *
     * @param TelegramUser $telegramUser
     */
    public function __construct(TelegramUser $telegramUser)
    {
        $this->telegramUser = $telegramUser;
        $this->log("SendNotifyJob construct", $telegramUser);
    }

    /**
     * method of sending notification by parameters.
     *
     * @return void
     */
    private function sendTenderBlock()
    {
        $when = now()->addSeconds(20);
        // $this->telegramUser->notify((new TenderBlock())->delay($when));
        Notification::send($this->telegramUser, new TenderBlock());
        // $this->telegramUser->notify(new TenderBlock());
    }

    /**
     * method of sending notification by parameters.
     *
     * @return void
     */
    private function sendAccountReplenished()
    {
        $when = now()->addSeconds(40);
        // $this->telegramUser->notify((new AccountReplenished())->delay($when));
        $this->telegramUser->notify(new AccountReplenished());
    }

    /**
     * method of sending notification by parameters.
     *
     * @return void
     */
    private function sendSuccessfulRestartTender()
    {
        $when = now()->addSeconds(60);
        // $this->telegramUser->notify((new SuccessfulRestartTender())->delay($when));
        $this->telegramUser->notify(new SuccessfulRestartTender());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendTenderBlock();
        $this->sendAccountReplenished();
        $this->sendSuccessfulRestartTender();
    }
}
