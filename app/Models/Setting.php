<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Setting extends Model
{

    /**
     * возвращаем модель без указанного поля таблицы
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * метод для вывода коллекций с базы данных
     *
     * @param null $key
     * @return Collection
     */
    public static function getSettings($key = null)
    {
        $settings = $key ? self::where('key', $key)->first() : self::get() ;

        $collect = collect();
        foreach ($settings as $setting)
        {
            $collect->put($setting->key, $setting->value);
        }

        return $collect;
    }

}
