<?php

namespace Jeanfprado\Cashier\Models;

use RuntimeException;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Jeanfprado\Cashier\Concerns\HasPublicIdAttribute;
use Jeanfprado\Cashier\Events\{BillingCreated, BillingPaid};

class Billing extends Model
{
    use HasPublicIdAttribute;

    /**
     * Enum Type
     */
    public const STATUS_CREATED = 'created';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'amount', 'expires_at'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => BillingCreated::class,
    ];

    public function __construct(array $attributes = [])
    {
        $this->status = static::STATUS_CREATED;

        parent::__construct($attributes);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */

    /**
     * Get the subscription for the billing.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    /**
     * Mark this billing to paid.
     *
     * @return bool
     */
    public function markToPaid()
    {
        throw_unless($this->isCreated(), RuntimeException::class, 'You cannot to pay this billing');

        $this->paid_at = now();
        $this->status = static::STATUS_PAID;
        $save = $this->save();

        if ($save) {
            Event::dispatch(new BillingPaid($this));
        }

        return $save;
    }

    /**
     * Return if this billing status is created.
     *
     * @return bool
     */
    public function isCreated()
    {
        return $this->status === static::STATUS_CREATED;
    }

    /**
     * Return if this billing status is paid.
     *
     * @return bool
     */
    public function isPaid()
    {
        return $this->status === static::STATUS_PAID;
    }

    /*
    |--------------------------------------------------------------------------
    | Scope Query
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include billing of a given pending.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where($this->qualifyColumn('status'), static::STATUS_CREATED);
    }
}
