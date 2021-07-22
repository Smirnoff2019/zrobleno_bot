<?php

namespace App\Telegram\Requests;

use Illuminate\Support\Str;

use Illuminate\Http\Request;
//use function GuzzleHttp\json_decode;
use App\Telegram\Requests\Concerns\BaseRequest;

class InlineBtnRequest extends BaseRequest
{

    /**
     * Parse the input data
     *
     * @param Illuminate\Http\Request $request
     * @return void
     */
    protected function handler($request)
    {
        $callbackData = (string) $request['callback_query']['data'];

        $data = Str::of($callbackData);

        $this->log('InlineBtnRequest->handler $data', $data);

        $routeName = $data->before(':')->trim();
        $queryData = json_decode((string) $data->after(':')->trim(), true);

        // $this->log('InlineBtnRequest->handler $routeName', (string) $routeName);
        // $this->log('InlineBtnRequest->handler $queryData', $queryData);

        $this->routeName = (string) $routeName;
        $this->queryData = $queryData;

    }

    /**
     * Get a route name from request data
     *
     * @param Illuminate\Http\Request $request
     * @return bool
     */
    public static function isCallbackRequest($request)
    {
        $checkType = (bool) isset($request['callback_query']) && !empty($request['callback_query']);
        $isValidData = (bool) isset($request['callback_query']['data']) && !empty($request['callback_query']['data']);

        return (bool) $checkType && $isValidData;
    }

    /**
     * Get a route name from request data
     *
     * @return bool
     */
    public function isValid()
    {
        $routeName = $this->getRouteName();

        if($routeName && strlen($routeName) > 2) {
            return true;
        }

        return false;
    }

    /**
     * Get a route name from request data
     *
     * @return bool
     */
    public function isInvalid()
    {
        return !$this->isValid();
    }

    /**
     * Get a route name from request data
     *
     * @return string
     */
    public function getRouteName()
    {
        return (string) $this->routeName;
    }

    /**
     * Get a request input data from request data
     *
     * @return array
     */
    public function getQueryData()
    {
        return (array) $this->queryData;
    }

}
