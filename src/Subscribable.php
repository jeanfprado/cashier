<?php

namespace Jeanfprado\Cashier;

use RuntimeException;
use Jeanfprado\Cashier\Models\Subscription;
use Jeanfprado\Cashier\Support\Facade\Cashier;

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
    | Business Logic
    |--------------------------------------------------------------------------
    */

    /**
     * Return if model is subscribed
     *
     * @return bool
     */
    public function subscribed()
    {
        return $this->subscription()->exists();
    }

    /**
     * Cancel model's subscription deleted the instance of database
     *
     * @return bool
     */
    public function cancelSubscription()
    {
        throw_unless($this->subscribed(), RuntimeException::class, 'You do not have a subscription to cancel');

        return $this->subscription->delete();
    }

    public function subscribe($plan)
    {
        return Cashier::subscribe($this, $plan);
    }
}
