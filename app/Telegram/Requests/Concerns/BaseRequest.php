<?php

namespace App\Telegram\Requests\Concerns;

use App\Traits\Loger;
use Illuminate\Http\Request;


abstract class BaseRequest
{

    use Loger;

    /**
     * Telegram callback query data store
     *
     * @var array
     */
    protected $data = [];

    /**
     * Create a new class instance.
     *
     * @param Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->handler($request);
    }

    /**
     * Static method for creating a new class instance.
     *
     * @param Illuminate\Http\Request $request
     * @return self
     */
    public static function make(Request $request): self
    {
        return new static($request);
    }

    /**
     * Parse the input data
     *
     * @param Illuminate\Http\Request $request
     * @return void
     */
    abstract protected function handler($request);

    /**
     * Get data as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return (array) $this->data;
    }

    /**
     * Get data value by key.
     *
     * @param  string $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->__get($name);
    }

    /**
     * __set
     *
     * @param  string $name
     * @param  mixed $value
     * @return mixed
     */
    public function __set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    /**
     * __get
     *
     * @param  string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
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
                'data' => $this->data,
            ]
        ]);
        return (string) $info;
    }

}
