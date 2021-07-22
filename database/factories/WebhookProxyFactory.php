<?php

/** @var Factory $factory */

use App\Models\WebhookProxy;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(WebhookProxy::class, function (Faker $faker) {
    return [
        WebhookProxy::COLUMN_NAME => 'bot_localhost_'.now(),
        WebhookProxy::COLUMN_URI => '/api/v1/webhook/telegram',
    ];
});
