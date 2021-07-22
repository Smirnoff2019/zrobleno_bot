<?php

namespace App\Telegram\QueryBuilders\Concerns;

use App\Traits\Loger;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Artisan;
use App\Telegram\Requests\InlineBtnRequest;


abstract class BaseQueryBuilder
{

    use Loger;

    /**
     * Request query data 'routeName'
     *
     * @var string
     */
    protected $routeName;

    /**
     * Request query data
     *
     * @var array
     */
    protected $queryData;

    /**
     * String data for inline btn callback query
     *
     * @var string
     */
    protected $data;

    /**
     * Create a new controller instance.
     *
     * @param string $routeName
     * @param array  $queryData
     * @return void
     */
    public function __construct(string $routeName, array $queryData)
    {
        $this->routeName = $routeName;
        $this->queryData = $queryData;

        $this->toQuery($routeName, $queryData);
    }

    /**
     * Generates a string of data for inline btn callback query
     *
     * @param string $routeName
     * @param array  $queryData
     * @return void
     */
    abstract protected function toQuery(string $routeName, array $queryData = []);

    /**
     * Get a string data for inline btn callback query
     *
     * @return string
     */
    public function getData()
    {
        return (string) $this->data;
    }

    /**
     * Create a new controller instance and get string data for inline btn callback query
     *
     * @param string $routeName
     * @param array  $queryData
     * @return string
     */
    public static function create(...$args)
    {
        return (new static(...$args))->getData();
    }

    /**
     * Convert class instance to string
     *
     * @return string
     */
    public function __toString()
    {
        $name = get_class($this);
        $info = json_encode([
            $name => [
                'routeName' => $this->routeName,
                'queryData' => $this->queryData,
                'data' => $this->data,
            ]
        ]);
        return (string) $info;
    }

}
