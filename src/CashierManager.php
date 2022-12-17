<?php

namespace Jeanfprado\Cashier;

use Jeanfprado\Cashier\Models\Plan;
use Jeanfprado\Cashier\Models\Subscription;
use Jeanfprado\Cashier\Support\Facade\Gateway;
use Jeanfprado\Cashier\Contracts\Subscribable as SubscribableContract;

class CashierManager
{
    public function subscribe(SubscribableContract $subscribable, Plan $plan)
    {
        $response = Gateway::subscribe($subscribable, $plan);

        return $subscribable->subscription()->create([
            'plan_id' => $plan->id,
            'gateway_name' => Gateway::getName(),
            'gateway_data' => $response,
        ]);
    }

    public function unsubscribe(SubscribableContract $subscribable)
    {
        Gateway::unsubscribe($subscribable->subscription);

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

            $response = Gateway::createPlan($data);

            $plansSeed[] = Plan::create(array_merge($data, [
                'gateway_name' => Gateway::getName(),
                'gateway_data' => $response,
            ]));
        }

        return $plansSeed;
    }

    public function paySubscription(Subscription $subscription, $paymentToken = '')
    {
        $response =  $subscription->gateway->paySubscription($subscription, $paymentToken);

        return $subscription->billings()->create([
            'amount' => $subscription->plan->amount,
            'gateway_name' => Gateway::getName(),
            'gateway_data' => $response,
        ]);
    }
}
