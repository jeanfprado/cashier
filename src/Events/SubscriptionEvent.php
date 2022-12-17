<?php

namespace Jeanfprado\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Jeanfprado\Cashier\Models\Subscription;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SubscriptionEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $subscription;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
}
