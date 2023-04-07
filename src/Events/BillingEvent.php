<?php

namespace Jeanfprado\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Jeanfprado\Cashier\Models\Billing;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class BillingEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $billing;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Billing $billing)
    {
        $this->billing = $billing;
    }
}
