<?php

namespace Jeanfprado\Cashier\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeanfprado\Cashier\Support\Facade\Cashier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jeanfprado\Cashier\Events\{SubscriptionCreated, SubscriptionCanceled};

class Subscription extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name', 'plan_id'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => SubscriptionCreated::class,
        'deleted' => SubscriptionCanceled::class,
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
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the parent subscribable model.
     */
    public function subscribable()
    {
        return $this->morphTo();
    }

    /**
     * Get the billings for the subscription.
     */
    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    public function pay($paymentMethod = 'banking_billet')
    {
        $this->payment_method = $paymentMethod;
        $this->save();

        return Cashier::paySubscription($this);
    }
}
