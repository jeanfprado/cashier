<?php

namespace Jeanfprado\Cashier\Console;

use Illuminate\Console\Command;
use Jeanfprado\Cashier\Models\Subscription;

class CashierSubscriptionCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashier:subscription-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if it has a billing to generate.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking subscription');
        $subscriptions = Subscription::nextBilling()->get();

        $subscriptions->each(function ($subscription) {
            $subscription->generateBilling();
        });

        $this->line('<comment>Total billings generated:</comment> '. $subscriptions->count());
        $this->info('Subscription Checked');
    }
}
