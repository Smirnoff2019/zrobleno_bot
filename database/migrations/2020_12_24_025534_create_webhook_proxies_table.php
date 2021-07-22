<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\WebhookProxy;

class CreateWebhookProxiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(WebhookProxy::TABLE, function (Blueprint $table) {
            $table->id();

            $table->string(WebhookProxy::COLUMN_NAME)
                ->nullable()
                ->unique();

            $table->string(WebhookProxy::COLUMN_DOMAIN)
                ->unique();

            $table->string(WebhookProxy::COLUMN_URI)
                ->nullable();

            $table->boolean(WebhookProxy::COLUMN_SSL)
                ->default(true);

            $table->boolean(WebhookProxy::COLUMN_STATUS)
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
        Schema::dropIfExists(WebhookProxy::TABLE);
    }
}
