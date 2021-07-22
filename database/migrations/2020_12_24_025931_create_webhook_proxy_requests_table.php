<?php

use App\Models\WebhookProxy;
use App\Models\WebhookProxyRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebhookProxyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(WebhookProxyRequest::TABLE, function (Blueprint $table) {
            $table->id();

            $table->foreignId(WebhookProxyRequest::COLUMN_WEBHOOK_PROXY_ID)
                ->nullable()
                ->constrained(WebhookProxy::TABLE);

            $table->text(WebhookProxyRequest::COLUMN_DATA)
                ->nullable();

            $table->boolean(WebhookProxyRequest::COLUMN_STATUS)
                ->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(WebhookProxyRequest::TABLE);
    }
}
