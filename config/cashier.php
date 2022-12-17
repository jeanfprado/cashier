<?php

use Jeanfprado\Cashier\Models\Plan;

return [

    'cashier_currency' => env('CASHIER_CURRENCY', 'BRL'),

    'trial_days' => env('CASHIER_TRIAL_DAYS', 1),

    'model' => \App\Models\User::class,

    'plans' => [
        [
            'name' => 'Premium',
            'description' => 'This is a description, human friendly description of the plan.',
            'type' => Plan::TYPE_MONTHLY,
            'amount' => 59.90,
            'features' => [
                'Feature 1',
                'Feature 2',
                'Feature 3',
                'Feature 4',
            ]
        ],[
            'name' => 'Premium Annual',
            'description' => 'This is a description, human friendly description of the plan.',
            'type' => Plan::TYPE_YEARLY,
            'amount' => 646.92,
            'features' => [
                'Feature 1',
                'Feature 2',
                'Feature 3',
                'Feature 4',
            ],
            'settings' => [
                'incentive' => 'Save 10%',
            ],
        ]
    ]
];
