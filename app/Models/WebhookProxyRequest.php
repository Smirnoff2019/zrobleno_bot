<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookProxyRequest extends Model
{

    public const TABLE                      = 'webhook_proxy_requests';
    public const COLUMN_ID                  = 'id';
    public const COLUMN_WEBHOOK_PROXY_ID    = 'webhook_proxy_id';
    public const COLUMN_DATA                = 'data';
    public const COLUMN_STATUS              = 'status';
    public const COLUMN_UPDATED_AT          = 'updated_at';
    public const COLUMN_CREATED_AT          = 'created_at';

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
        self::COLUMN_WEBHOOK_PROXY_ID,
        self::COLUMN_DATA,
        self::COLUMN_STATUS,
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
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
        self::COLUMN_DATA   => 'array',
        self::COLUMN_STATUS => 'boolean',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [];

    /**
     * Get the data about webhook proxy for requests.
     *
     * @return BelongsTo \App\Models\WebhookProxy
     */
    public function webhookData()
    {
        return $this->belongsTo(WebhookProxy::class);
    }

}
