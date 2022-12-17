<?php

namespace Jeanfprado\Cashier;

use Jeanfprado\Cashier\Models\Plan;
use Jeanfprado\Cashier\Models\Subscription;
use Jeanfprado\Cashier\Contracts\Subscribable as SubscribableContract;

class CashierManager
{
    public function subscribe(SubscribableContract $subscribable, Plan $plan)
    {
        return $subscribable->subscription()->create([
            'plan_id' => $plan->id,
        ]);
    }

    public function unsubscribe(SubscribableContract $subscribable)
    {
        return $subscribable->cancelSubscription();
    }

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

    public function paySubscription(Subscription $subscription)
    {
        return $subscription->billings()->create([
            'amount' => $subscription->plan->amount
        ]);
    }
}
