<?php

namespace Jeanfprado\Cashier;

use RuntimeException;
use Jeanfprado\Cashier\Models\{Plan, Billing, Subscription};
use Jeanfprado\Cashier\Contracts\Subscribable as SubscribableContract;

class CashierManager
{
    /**
     * Create a subscription give on subscribable.
     *
     * @param SubscribableContract $subscribable
     * @param Plan $plan
     * @return void
     */
    public function subscribe(SubscribableContract $subscribable, Plan $plan)
    {
        throw_if($subscribable->subscribed(), RuntimeException::class, 'Subscription already exists.');

        return $subscribable->subscription()->create([
            'plan_id' => $plan->id,
            'next_billing_at' => now()->addDays(config('cashier.trial_days')),
        ]);
    }

    /**
     * Unsubscribe a subscription give on subscribable.
     *
     * @param SubscribableContract $subscribable
     * @param Plan $plan
     * @return void
     */
    public function unsubscribe(SubscribableContract $subscribable)
    {
        throw_unless($subscribable->subscribed(), RuntimeException::class, 'Subscription does not exists.');

        return $subscribable->cancelSubscription();
    }

    /**
     * Create Plans.
     *
     * @return array
     */
    public function createPlans()
    {
        $plans = config('cashier.plans');

        $plansSeed = [];
        foreach ($plans as $data) {
            if (Plan::where('name', $data['name'])->exists()) {
                continue;
            }

            $data['settings'] = $data['settings'] ?? null;
            $data['options'] = $data['options'] ?? null;

            $plansSeed[] = Plan::create($data);
        }

        return $plansSeed;
    }

    /**
     * Create Billing this subscription.
     *
     * @param Subscription $subscription
     * @return Billing
     */
    public function generateBillingFor(Subscription $subscription)
    {
        return $subscription->billings()->create([
            'amount' => $subscription->plan->amount,
            'expires_at' => $subscription->next_billing_at->copy()->endOfDay(),
        ]);
    }
}
