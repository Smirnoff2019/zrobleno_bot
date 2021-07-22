<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/telegram')->group(function() {

    //Routes for create auth telegram user's
    Route::get('/registration/{chat_id}', 'Telegram\Auth\RegisterController')
        ->name('register.notify');
    Route::post('/user/{token}', 'Telegram\Auth\AuthBotController')
        ->name('create.botUser');
    Route::get('/welcome/{token}', 'Telegram\Auth\WelcomeController')
        ->name('welcome.notify');
    //
    Route::get('/user/token/external/{user_id}', 'Backend\UserBotAuthTokenController@store')
        ->name('get.external.telegram.botToken');
    Route::get('/user/{token}/external/', 'Backend\UserBotAuthTokenController@show')
        ->name('get.external.telegram.botToken.byToken');
    //delete user in telegram_user table
    Route::delete('/remove/{id}', 'Telegram\Auth\RemoveController@deleteUser')
        ->name('remove.telegram.user');
    //delete all users in telegram_user table
    Route::delete('/remove', 'Telegram\Auth\RemoveController@deleteAllUser')
        ->name('remove.telegram.allUser');
});

/**
 * sending a notification from the manager to the user in the telegram chat
 */
Route::group(['prefix' => 'notification'], function () {
    Route::post('/telegram', 'Telegram\SendNotify\SendNotifyController')
        ->name('send.toTelegram.notify');
});

/**
 * setting up local telegram bot development.
 */
Route::group(['prefix' => 'webhook'], function () {

    Route::post('/telegram', 'Backend\TelegramController')
        ->name('hooks.tg.proxy');

    Route::post('proxy/add/{name}', function ($name) {
        return factory(\App\Models\WebhookProxy::class)->create([
            \App\Models\WebhookProxy::COLUMN_DOMAIN => $name ?? 'serious-bullfrog-92.loca.lt'
        ]);
    });

    Route::post('/test', 'Backend\ProxyTelegramHooksController')
        ->name('hooks.tg.proxy.test');
});
