<?php

use Illuminate\Support\Facades\Route;

/**
 * --------------------------------------------------------------------------
 * NotificationRoutes Telegram Bot Routes
 * --------------------------------------------------------------------------
 */

/**
 * Get 'foo' model resource
 *
 * @uri 'foo'
 * @controller 'FooController'
 */

Route::group(['prefix' => 'notification'], function () {
    //post contractor notifications to telegram
    Route::get('/newTender/{chat_id}', 'Telegram\NotifyNewTenderController')
        ->name('tg.ntf.newTender');//->middleware('auth.telegram');

    Route::get('/register/success/{chat_id}', 'Telegram\NotifySuccessfulRegistrController')
        ->name('notify-regSuccess')->middleware('auth.telegram');

    Route::get('/account/replenished/{chat_id}', 'Telegram\NotifyAccountReplenishedController')
        ->name('notify-accountRepl')->middleware('auth.telegram');

    Route::get('/account/update/{chat_id}', 'Telegram\NotifyAccountUpdateController')
        ->name('notify-accountUpdate')->middleware('auth.telegram');

    Route::get('/account/withdrawn/{chat_id}', 'Telegram\NotifyAccountWithdrawnController')
        ->name('notify-accountWithdrawn');

    Route::get('/contact/customer/{chat_id}', 'Telegram\NotifyContactFromCustomerController')
        ->name('notify-contCustomer')->middleware('auth.telegram');

    Route::get('/tender/blocked/{chat_id}', 'Telegram\NotifyTenderBlockedController')
        ->name('notify-tenderBlocked')->middleware('auth.telegram');

    Route::get('/tender/canceled/{chat_id}', 'Telegram\NotifyTenderCanceledController')
        ->name('notify-tenderCanceled')->middleware('auth.telegram');

    Route::get('/tender/over/{chat_id}', 'Telegram\NotifyTenderOverController')
        ->name('notify-tenderOver')->middleware('auth.telegram');

    Route::get('/tender/victory/{chat_id}', 'Telegram\NotifyVictoryInTenderController')
        ->name('notify-tenderVictory')->middleware('auth.telegram');

    //post customer notifications to telegram
    Route::get('/access/discount/{chat_id}', 'Telegram\Customer\NotifyAccessToDiscountsController')
        ->name('notify-accessToDiscounts')->middleware('auth.telegram');

    Route::get('/application/canceled/{chat_id}', 'Telegram\Customer\NotifyApplicationCanceledController')
        ->name('notify-AppCanceled')->middleware('auth.telegram');

    Route::get('/application/confirm/{chat_id}', 'Telegram\Customer\NotifyConfirmaAppController')
        ->name('notify-confirmApp')->middleware('auth.telegram');

    Route::get('/contractor/confirm/{chat_id}', 'Telegram\Customer\NotifyContractorConfirmedController')
        ->name('notify-contractorConfirm')->middleware('auth.telegram');

    Route::get('/contractor/signed/{chat_id}', 'Telegram\Customer\NotifyContractorSignedController')
        ->name('notify-contractorSigned')->middleware('auth.telegram');

    Route::get('/tender/failedRestart/{chat_id}', 'Telegram\Customer\NotifyFailedRestartTenderController')
        ->name('notify-failedRestart')->middleware('auth.telegram');

    Route::get('/tender/successRestart/{chat_id}', 'Telegram\Customer\NotifySuccessRestartTenderController')
        ->name('notify-successRestart')->middleware('auth.telegram');

    Route::get('/tender/block/{chat_id}', 'Telegram\Customer\NotifyTenderBlockController')
        ->name('notify-tenderBlock')->middleware('auth.telegram');
});
