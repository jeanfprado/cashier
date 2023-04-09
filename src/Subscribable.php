<?php

namespace Jeanfprado\Cashier;

use RuntimeException;
use Jeanfprado\Cashier\Support\Facade\Cashier;
use Jeanfprado\Cashier\Models\{Plan, Subscription};

trait Subscribable
{
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the model's subscription.
     */
    public function subscription()
    {
        return $this->morphOne(Subscription::class, 'subscribable');
    }

    /*
    |--------------------------------------------------------------------------
    | Contract
    |--------------------------------------------------------------------------
    */


    /**
     * {@inheritDoc}
     */
    public function subscribed(): bool
    {
        return $this->subscription()->exists();
    }

    /**
     * {@inheritDoc}
     */
    public function cancelSubscription()
    {
        throw_unless($this->subscribed(), RuntimeException::class, 'You do not have a subscription to cancel');

        return $this->subscription->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function subscribe(Plan $plan, $trialDays = null): Subscription
    {
        return Cashier::subscribe($this, $plan, $trialDays);
    }
}
