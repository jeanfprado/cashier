## Introduction

Cashier provides a subscription billing services. It handles almost all of the boilerplate subscription billing code you are dreading writing. In addition to basic subscription management, Cashier can handle plans.

## Documentation

- [`Installation`](#Installation)
- [`Configuration`](#Configuration)
  - [`Plan`](#Plan)
- [`Subscription`](#Subscription)
- [`Billing`](#Billing)

### `Installation`

You can pull in the package via composer:
``` bash
composer require jeanfprado/cashier
```

The package will automatically register itself.

### `Configuration`

#### Laravel without auto-discovery:

If you don't use auto-discovery, add the CashierServiceProvider to the providers array in config/app.php

```php
Jeanfprado\Cashier\CashierServiceProvider::class,
```

If you want to use the facade to log messages, add this to your facades in app.php:

```php
'Cashier' => Jeanfprado\Cashier\Support\Facade\Cashier::class,
```
#### Copy the package config to your local config with the publish command:

```bash
php artisan vendor:publish --provider="Jeanfprado\Cashier\CashierServiceProvider"
```

### `Plan`

Creating all plans from `config/cashier.php` via artisan
```bash
php artisan cashier:seed-plans
```

### `Subscription`

Before you create a subscription you need prepare what class will be `subscribable`

In `config/cashier.php` change key `model` to model that will be a `subscribable`.

Now in this model you need implements a contract. see example:

```php
<?php

namespace App;

use Jeanfprado\Cashier\Subscribable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Jeanfprado\Cashier\Contracts\Subscribable as SubscribableContract;


class User extends Model implements SubscribableContract
{
    use Notifiable,
        Subscribable;
    ...
}
```

Now you can to create a subscription.

```php
 $user->subscribe($plan);
```

### `Billing`

Creating a billing is very easy, you only need run a command.

```bash
    php artisan cashier:subscription-check
```
Than if has a billing to generate is done, and when the billing is paid, you need mark to paid.

```php
    $billing->markToPaid();
```

## Contributing

Thank you for considering contributing to Cashier!

## License

Cashier is open-sourced software licensed under the [MIT license](LICENSE).