<?php

namespace Jeanfprado\Cashier\Contracts;

use Jeanfprado\Cashier\Models\Plan;
use Jeanfprado\Cashier\Models\Subscription;

interface Subscribable
{
    /**
     * Return if model is subscribed
     *
     * @return bool
     */
    public function subscribed(): bool;

    /**
    * Cancel model's subscription deleted the instance of database
    *
    * @return bool
    */
    public function cancelSubscription();

    /**
     * Create Subscription.
     *
     * @param Plan $plan
     * @return Subscription
     */
    public function subscribe(Plan $plan): Subscription;
}
