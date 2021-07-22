<?php

namespace App\Telegram\Controllers;

use App\Telegram\Requests\InlineBtnRequest;
use Illuminate\Support\Facades\Artisan;
use App\Telegram\Controllers\Concerns\BaseController;


class InlineBtnCallbackController extends BaseController
{

    /**
     * Execute the telegram callback controller.
     *
     * @param InlineBtnRequest $request
     * @return bool
     */
    protected function handler($request)
    {
        if($request->isInvalid()) return false;

        $this->log('InlineBtnCallbackController $request', $request);

        $url = route(
            $this->request->getRouteName(),
            $this->request->getQueryData()
        );

        $this->log('InlineBtnCallbackController $url', $url);

        Artisan::call('route:call', [
            'uri' => $url,
        ]);
    }
}
