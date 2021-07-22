<?php

use App\User;
use App\Models\BotMessages;
use App\Models\UserChatBot;
use App\Models\WebhookProxy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Telegram\Requests\InlineBtnRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContractorNotifications\NewTender;
use App\Notifications\CustomerNotifications\TenderBlock;
use App\Notifications\ContractorNotifications\TenderIsOver;
use App\Notifications\ContractorNotifications\AccountUpdate;
use App\Notifications\ContractorNotifications\TenderBlocked;
use App\Notifications\CustomerNotifications\ConfirmationApp;
use App\Notifications\ContractorNotifications\TenderCanceled;
use App\Notifications\CustomerNotifications\ContractorSigned;
use App\Notifications\ContractorNotifications\VictoryInTender;
use App\Notifications\CustomerNotifications\AccessToDiscounts;

use App\Notifications\ContractorNotifications\AccountWithdrawn;
use App\Notifications\ContractorNotifications\SuccessfulRegistr;
use App\Notifications\CustomerNotifications\ApplicationCanceled;
use App\Notifications\CustomerNotifications\ContractorConfirmed;
use App\Notifications\CustomerNotifications\FailedRestartTender;
use App\Notifications\ContractorNotifications\AccountReplenished;
use App\Notifications\ContractorNotifications\MessageFromManager;
use App\Notifications\ContractorNotifications\ContactFromCustomer;
use App\Notifications\CustomerNotifications\SuccessfulRestartTender;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Заглушка от Laravel
Route::get('/', function () {
    return view('welcome');

});

Route::get('/testSend', function () {
    $user = User::first();
    Notification::send($user, new NewTender());
    return true;
});

//Test от Laravel
Route::get('/bot', function () {
    $user = User::userRole()->latest()->paginate(25)->get();
    echo '<pre>$messages => <br />'; print_r($user->toArray()); echo '</pre>';
    return true;
});

//Роуты оповещения в Telegram исполнителя
Route::prefix('/telegram/contractor')->group(function () {
    //Route::get('/newTender', 'NewTenderController')->name('bot.newTender');
    //Contractor's routes
    Route::get('/new-tender', function () {
        $user = User::first();
        Notification::send($user, new NewTender());
        return true;
    });
    Route::get('/successful-registration-tender', function () {
        $user = User::first();
        Notification::send($user, new SuccessfulRegistr());
        return true;
    });
    Route::get('/account-replenished', function () {
        $user = User::first();
        Notification::send($user, new AccountReplenished());
        return true;
    });
    Route::get('/account-update', function () {
        $user = User::first();
        Notification::send($user, new AccountUpdate());
        return true;
    });
    Route::get('/account-withdrawn', function () {
        $user = User::first();
        Notification::send($user, new AccountWithdrawn());
        return true;
    });
    Route::get('/contact-from-customer', function () {
        $user = User::first();
        Notification::send($user, new ContactFromCustomer());
        return true;
    });
    Route::get('/tender-blocked', function () {
        $user = User::first();
        Notification::send($user, new TenderBlocked());
        return true;
    });
    Route::get('/tender-canceled', function () {
        $user = User::first();
        Notification::send($user, new TenderCanceled());
        return true;
    });
    Route::get('/tender-is-over', function () {
        $user = User::first();
        Notification::send($user, new TenderIsOver());
        return true;
    });
    Route::get('/victory-in-tender', function () {
        $user = User::first();
        Notification::send($user, new VictoryInTender());
        return true;
    });
});

//Роуты оповещения в Telegram заказчика
Route::prefix('/telegram/customer')->group(function () {
    //Route::get('/newTender', 'NewTenderController')->name('bot.newTender');
    //Customer's routes
    Route::get('/confirmation/{chat_id}', function ($chat_id) {
        $user = UserChatBot::whereChatId($chat_id)->first();
        Notification::send($user, new ConfirmationApp());
        return true;
    });
    Route::get('/confirmation-app', function () {
        $user = User::first();
        Notification::send($user, new ConfirmationApp());
        return true;
    });
    Route::get('/contractor-signed', function () {
        $user = User::first();
        Notification::send($user, new ContractorSigned());
        return true;
    });
    Route::get('/contractor-confirmed', function () {
        $user = User::first();
        Notification::send($user, new ContractorConfirmed());
        return true;
    });
    Route::get('/access-to-discounts', function () {
        $user = User::first();
        Notification::send($user, new AccessToDiscounts());
        return true;
    });
    Route::get('/application-canceled', function () {
        $user = User::first();
        Notification::send($user, new ApplicationCanceled());
        return true;
    });
    Route::get('/tender-block', function () {
        $user = User::first();
        Notification::send($user, new TenderBlock());
        return true;
    });
    Route::get('/successful-restart-tender', function () {
        $user = User::first();
        Notification::send($user, new SuccessfulRestartTender());
        return true;
    });
    Route::get('/failed-restart-tender', function () {
        $user = User::first();
        Notification::send($user, new FailedRestartTender());
        return true;
    });
});


//Роуты настройки Webhook
Route::middleware(['auth'])->prefix('admin')->namespace('Backend')->name('admin.')->group(function () {
    Route::get('/', 'DashboardController@index')->name('index');
    //Отображение списка маршрутов
    Route::get('/setting', 'SettingController@index')->name('setting.index');
    //Роут для сохранения в DB введенного uri для построения Webhook и отображение его
    Route::post('/setting/store', 'SettingController@store')->name('setting.store');
    //Роут запроса для проверки правильности создания Webhook
    Route::post('/setting/setwebhook', 'SettingController@setwebhook')->name('setting.setwebhook');
    //Роут, ответ сервера Telegram, для получения информации о созданом Webhook
    Route::post('/setting/getwebhookinfo', 'SettingController@getwebhookinfo')->name('setting.getwebhookinfo');
});

// Route::post(Telegram::getAccessToken(), 'Backend\TelegramController')->name('first.endpoint');
Route::post(Telegram::getAccessToken(), 'Backend\ProxyTelegramHooksController')->name('first.endpoint');

Auth::routes();

Route::match(['post', 'get'], 'register', function () {
    Auth::logout();
    return redirect('/');
})->name('register');

Route::get('/home', 'HomeController@index')->name('home');
