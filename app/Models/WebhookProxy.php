<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookProxy extends Model
{

    public const TABLE              = 'webhook_proxies';
    public const COLUMN_ID          = 'id';
    public const COLUMN_NAME        = 'name';
    public const COLUMN_DOMAIN      = 'domain';
    public const COLUMN_URI         = 'uri';
    public const COLUMN_SSL         = 'ssl';
    public const COLUMN_STATUS      = 'status';
    public const COLUMN_UPDATED_AT  = 'updated_at';
    public const COLUMN_CREATED_AT  = 'created_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_NAME,
        self::COLUMN_DOMAIN,
        self::COLUMN_URI,
        self::COLUMN_SSL,
        self::COLUMN_STATUS,
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        self::COLUMN_SSL => true,
        self::COLUMN_STATUS => true,
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        self::COLUMN_UPDATED_AT,
        self::COLUMN_CREATED_AT,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        self::COLUMN_SSL    => 'boolean',
        self::COLUMN_STATUS => 'boolean',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [];

    /**
     * Get the webhook proxy requests.
     *
     * @return HasMany \App\Models\WebhookProxyRequest
     */
    public function requests()
    {
        return $this->hasMany(WebhookProxyRequest::class);
    }

    /**
     * Scope a query to only include proxies where `status` is "true"
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  bool $status
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeStatus($query, bool $status = true)
    {
        return $query->where(
            self::COLUMN_STATUS,
            $status
        );
    }

}
