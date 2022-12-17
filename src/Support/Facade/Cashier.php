<?php

namespace Jeanfprado\Cashier\Support\Facade;

use Illuminate\Support\Facades\Facade;
use Jeanfprado\Cashier\CashierManager;

class Cashier extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CashierManager::class;
    }
}
