<?php

namespace Jeanfprado\Cashier\Console;

use Illuminate\Console\Command;
use Jeanfprado\Cashier\Support\Facade\Cashier;

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
    protected $description = 'Create all plan from configuration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting Create Plans');
        $plans = Cashier::createPlans();

        $this->line('<comment>Total plans created:</comment> '. count($plans));
        $this->info('Finished Create Plans');
    }
}
