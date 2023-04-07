<?php

namespace Jeanfprado\Cashier\Models;

use RuntimeException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeanfprado\Cashier\Support\Facade\Cashier;
use Jeanfprado\Cashier\Concerns\HasPublicIdAttribute;
use Jeanfprado\Cashier\Events\{SubscriptionCreated, SubscriptionCanceled};

class Subscription extends Model
{
    use SoftDeletes;
    use HasPublicIdAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'plan_id', 'next_billing_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'next_billing_at' => 'datetime'
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

     /**
     * Get the plan for the subscription.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    /**
     * Generate new billing to pay.
     *
     * @param string $paymentMethod
     * @return void
     */
    public function generateBilling()
    {
        throw_unless($this->canGenerateBilling(), RuntimeException::class, 'Billing does not be created');

        return DB::transaction(function () {
            $billing = Cashier::generateBillingFor($this);

            $this->next_billing_at = $this->getNextBilling();
            $this->save();

            return $billing;
        });
    }

    /**
     * Return if can generate a new billing.
     *
     * @return bool
     */
    public function canGenerateBilling()
    {
        return $this->next_billing_at->lte(now());
    }

    /**
     * Return if the next billing for the subscription.
     *
     * @return Carbon
     */
    protected function getNextBilling()
    {
        return $this->plan->calculateNextBillingFor($this->next_billing_at);
    }

    /**
     * Return if this subscription has billing pending.
     *
     * @return bool
     */
    public function hasBillingPending()
    {
        return $this->billings()->pending()->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Query String
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include subscription of a given next billing.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNextBilling($query)
    {
        return $query->whereDate('next_billing_at', '<=', now()->toDateString());
    }
}
