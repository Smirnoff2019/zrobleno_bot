<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\UserChatBot;

class TelegramUser extends Model
{
    use Notifiable;

    /**
     * указываем таблицу, которая отвечает данной модели.
     *
     * @var string
     */
    protected $table = 'telegram_users';

    /**
     * указываем поля, которые запрещены для заполнения в таблице telegram_users.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    // public $incrementing = false;

    /**
     * get column chat_id from table telegram_user.
     *
     * @return mixed
     */
    public function getChatId()
    {
        return $this->only('chat_id')->get();
    }

    /**
     * Get the chhat bot user for this user.
     *
     * @return HasOne
     */
    public function chatBotUser()
    {
        return $this->hasOne(UserChatBot::class, 'chat_id', 'chat_id');
    }

    /**
     * Bind telegram user.
     *
     * @param int $chat_id
     * @param $tgUser
     * @return self
     */
    public static function bindTelegramUser(int $chat_id, array $tgUser)
    {
        return static::firstOrCreate(
            ['chat_id' => (int) $chat_id],
            [
                'is_bot'        => $tgUser['is_bot'] ?? false,
                'first_name'    => $tgUser['first_name'] ?? '',
                'last_name'     => $tgUser['last_name'] ?? '',
                'username'      => $tgUser['username'] ?? '',
                'language_code' => $tgUser['language_code'] ?? '',
            ]
        );
    }

    /**
     * Scope a query to only include users from the specified chat
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $chat_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeChat($query, string $chat_id)
    {
        return $query->where('chat_id', $chat_id);
    }

    /**
     * Scope a query to only include users who has specified `user_id`
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $chat_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUserId($query, $user_id)
    {
        return $query->whereHas('chatBotUser', function (Builder $query) use($user_id) {
            $query->whereIn('user_id', $user_id);
        });
    }

}
