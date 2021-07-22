<?php

namespace App\Models;

use App\Models\UserChatBot;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserBotAuthToken extends Model
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_bot_auth_token';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token',
        'active',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'active' => true,
    ];

    /**
     * Get current user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get user chat with bot
     *
     * @return HasOne
     */
    public function chatBot()
    {
        return $this->hasOne(
            UserChatBot::class,
            'user_id',
            'user_id'
        );
    }

    /**
     * Generate user auth token for chat bot
     *
     * @return string
     */
    public static function createToken(): string
    {
        return (string) Str::substr(
            md5(Str::random(16)),
            0,
            16
        );
    }

    /**
     * Generate user auth token for chat bot
     *
     * @param int $user_id
     * @return static
     */
    public static function makeForUser(int $user_id)
    {
        return static::firstOrCreate(
            [
                'user_id'   => $user_id,
            ],
            [
                'token'     => static::createToken()
            ]
        );
    }

    /**
     * Find user ID by his chat bot auth token
     *
     * @param  string $token
     * @return static|null
     *
     * @throws ModelNotFoundException
     */
    public static function findByToken(string $token)
    {
        return static::whereToken($token)->firstOrFail();
    }

    /**
     * Find user chat bot auth tokens by his ID
     *
     * @param  int $user_id
     * @return static[]|null
     */
    public static function findByUserId(int $user_id)
    {
        return static::where('user_id', '=', $user_id)->latest()->firstOrFail();
    }

    /**
     * Created User in telegram bot by token.
     *
     * @param string $token
     * @return mixed
     */
    public static function createAuthUser(string $token)
    {
        return static::firstOrCreate(
            [
                'token'   => $token,
            ],
            [
                'user_id' => User::getUserIdFromToken($token),
                'active'  => true
            ]
        );
    }


    /**
     * Set in the request users who have the specified auth token
     *
     * @param Builder $query
     * @param string $token
     * @return Builder
     */
    public function scopeToken($query, string $token)
    {
        return $query->where('token', $token);
    }

}
