<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Telegram\Bot\Laravel\Facades\Telegram;

class SettingController extends Controller
{
    /**
     * вернет шаблон с настройками и коллекцию из модели Setting
     *
     * @return Factory|View
     */
    public function index()
    {
        return view('backend.setting', Setting::getSettings());
    }

    /**
     * создаем новый экземпляр модели
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        Setting::where('key', '!=', NULL)->delete();

        foreach ($request->except('_token') as $key => $value)
        {
            $setting = new Setting;
            $setting->key = $key;
            $setting->value = $request->$key;
            $setting->save();
        }
        return redirect()->route('admin.setting.index');
    }

    /**
     * функция для установки uri для webhook
     * @param Request $request
     * @return RedirectResponse
     * @throws GuzzleException
     */
    public function setwebhook(Request $request)
    {
        $result = $this->sendTelegramData('setwebhook', [
            'query' => ['url' => $request->url . '/' . Telegram::getAccessToken()]
        ]);

        return redirect()->route('admin.setting.index')->with('status', $result);
    }

    /**
     * функция для получения информации о подключении webhook
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws GuzzleException
     */
    public function getwebhookinfo(Request $request)
    {
        $result = $this->sendTelegramData('getWebhookInfo');

        return redirect()->route('admin.setting.index')->with('status', $result);
    }

    /**
     * функция для отправки параметров на сервер телеграм, по которым будет строится общение
     * @param string $route
     * @param array $params
     * @param string $method
     * @return string
     * @throws GuzzleException
     */
    public function sendTelegramData($route = '', $params = [], $method = 'POST')
    {
        $client = new Client(['base_uri' => 'https://api.telegram.org/bot' . Telegram::getAccessToken() . '/']);
        $result = $client->request($method, $route, $params);

        return (string) $result->getBody();
    }
}
