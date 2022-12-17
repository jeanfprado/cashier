<?php

namespace Jeanfprado\Cashier\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

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
        'name', 'description', 'type', 'gateway_name',
        'amount', 'features', 'settings', 'gateway_data'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'features' => 'json',
        'settings' => 'json',
        'gateway_data' => 'json'
    ];

    /**
     * Boot the has Sluggable trait for a model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        // create a new uuid and inject into the creating user
        static::creating(function ($model) {
            $model->public_id = Str::uuid();
        });

        // update the slug name field based on the slug source
        static::saving(function ($model) {
            $model->slug_name = Str::slug($model->name);
        });
    }

    public function getGatewayId()
    {
        return data_get($this->gateway_data, 'data.plan_id');
    }
}
