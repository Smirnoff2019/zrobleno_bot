<?php

namespace App\Jobs;

use App\Models\WebhookProxy;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use App\Models\WebhookProxyRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProxyToLocalhost
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Webhook proxy instance
     *
     * @var \App\Models\WebhookProxy
     */
    protected $proxy;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WebhookProxy $proxy, $data)
    {
        $this->proxy = $proxy;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $proxy = $this->proxy;

        $url = implode('', [
            $proxy->{WebhookProxy::COLUMN_SSL} ? 'https://' : 'http://',
            $proxy->{WebhookProxy::COLUMN_DOMAIN},
            $proxy->{WebhookProxy::COLUMN_URI} ?? '',
        ]);

        return Http::post(
            $url,
            $this->data
        )->body();
    }

}
