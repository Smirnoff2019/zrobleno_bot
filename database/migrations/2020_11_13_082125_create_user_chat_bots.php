<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserChatBots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_chat_bots', function (Blueprint $table) {
            $table->id();

            $table->integer('chat_id')
                ->nullable();
            $table->unsignedBigInteger('user_id')
                ->nullable()
                ->index();
            $table->string('app')
                ->nullable();
            $table->text('status')
                ->nullable();
            $table->foreign('chat_id')
                ->references('chat_id')
                ->on('telegram_bot_token')
                ->onDelete('set null');

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
        //
        Schema::dropIfExists('user_chat_bots');
    }
}
