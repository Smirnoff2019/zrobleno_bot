<?php

namespace App;

use App\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserBotAuthToken;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'api_db';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'role_id',
        'status_id',
        'image_id',
        'account_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * get id from user table.
     *
     * @param int $user_id
     * @return mixed
     */
    public function idValid(int $user_id)
    {
        if (User::where('id', $user_id)->exists()) return true;

        return $this;
    }

    /**
     * Get the user that owns the role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the user bot auth token.
     *
     * @return HasOne \App\Models\Bot\UserBotAuthToken\UserBotAuthToken
     */
    public function userBotAuthToken()
    {
        return $this->hasOne(UserBotAuthToken::class);
    }

    /**
     * Get find the user bot auth token.
     *
     * @param string $token
     * @return HasOne \App\Models\Bot\UserBotAuthToken\UserBotAuthToken
     */
    public function findUserBotAuthToken(string $token)
    {
        return $this->userBotAuthToken()->where('token', $token)->first();
    }

}
