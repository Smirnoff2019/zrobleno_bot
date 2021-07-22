<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class UserChatBot extends Model
{
    use Notifiable;

    /**
     * we use the table that corresponds to this model.
     *
     * @var string
     */
    protected $table = 'user_chat_bots';

    /**
     * fields from the table to use.
     *
     * @var array
     */
    public $fillable = [
        'chat_id',
        'user_id',
        'app',
        'status'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'app' => 'telegram',
        'status' => true,
    ];

    /**
     * Get the telegram user for this user.
     *
     * @return HasOne
     */
    public function telegramUser()
    {
        return $this->hasOne(TelegramUser::class, 'chat_id', 'chat_id');
    }

    /**
     * Bind telegram chat ID to user
     *
     * @param int $chat_id
     * @param int $user_id
     * @return self
     */
    public static function bindTelegramChat(int $chat_id, int $user_id)
    {
        return static::firstOrCreate(
            [
            'chat_id' => $chat_id,
            'app'     => 'telegram'
            ],
            [
            'user_id' => $user_id,
            'status'  => true
            ]
        );
    }

    /**
     * Get user chat bot by user ID.
     *
     * @param int $user_id
     * @return mixed
     */
    public static function getUserChatBotByUserId(int $user_id)
    {
        return UserChatBot::where('user_id', $user_id)->first();
    }

    /**
     * Check if user have authorize in Telegram app
     *
     * @return bool
     */
    public function haveTelegramApp()
    {
        return $this->app === 'telegram';
    }

    /**
     * Scope a query to only include users who have telegram app
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeTelegramApp($query)
    {
        return $query->where('app', 'telegram');
    }

    /**
     * Scope a query to only include users from the specified chat
     *
     * @param  Builder  $query
     * @param  string  $chat_id
     * @return Builder
     */
    public function scopeChat($query, string $chat_id)
    {
        return $query->where('chat_id', $chat_id);
    }



}
