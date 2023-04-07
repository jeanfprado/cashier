<?php

namespace Jeanfprado\Cashier\Concerns;

use Illuminate\Support\Str;

trait HasPublicIdAttribute
{
    /**
     * Boot the has Sluggable trait for a model.
     *
     * @return void
     */
    public static function bootHasPublicIdAttribute()
    {
        // create a new uuid and inject into the creating user
        static::creating(function ($model) {
            $model->public_id = Str::uuid();
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return $this->public_id;
    }
}
