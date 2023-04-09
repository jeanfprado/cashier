<?php

namespace Jeanfprado\Cashier\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Jeanfprado\Cashier\Concerns\HasPublicIdAttribute;

class Plan extends Model
{
    use HasPublicIdAttribute;

    /**
     * Enum Type
     */
    public const TYPE_MONTHLY = 'monthly';
    public const TYPE_TRIENNIAL = 'triennial';
    public const TYPE_SEMESTRIAL = 'semestrial';
    public const TYPE_YEARLY = 'yearly';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name', 'description', 'type',
        'amount', 'features', 'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'features' => 'json',
        'settings' => 'json',
    ];

    /**
     * Boot the has Sluggable trait for a model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        // update the slug name field based on the slug source
        static::saving(function ($model) {
            $model->slug_name = Str::slug($model->name);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */

    /**
     * Get the billings for the subscription.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate next billing for date.
     *
     * @param  Carbon $date
     * @return Carbon
     */
    public function calculateNextBillingFor($date)
    {
        $types = [
            static::TYPE_MONTHLY => 'month',
            static::TYPE_TRIENNIAL => [
                'type' => 'months',
                'value' => 3,
            ],
            static::TYPE_SEMESTRIAL => [
                'type' => 'months',
                'value' => 6,
            ],
            static::TYPE_YEARLY => 'year',
        ];

        $frequency = $types[$this->type];

        if (is_array($frequency)) {
            $method = 'add' . Str::studly($frequency['type']);

            return $date->copy()->$method($frequency['value']);
        }

        $method = 'add' . Str::studly($frequency);

        return $date->copy()->$method();
    }
}
