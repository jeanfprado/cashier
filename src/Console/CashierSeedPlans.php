<?php

namespace Jeanfprado\Console;

use Illuminate\Console\Command;
use Jeanfprado\Cashier\Models\Plan;

class CashierSeedPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashier:seed-plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $plans = config('cashier.plans');

        $this->seedPlans($plans);
    }

    protected function seedPlans($plans)
    {
        $plansSeed = [];
        foreach ($plans as $data) {
            $data['settings'] = $data['settings'] ?? null;
            $data['options'] = $data['options'] ?? null;

            $plansSeed[] = Plan::firstOrCreate(
                ['name'=> $data['name']],
                $data
            );
        }

        return $plansSeed;
    }
}
