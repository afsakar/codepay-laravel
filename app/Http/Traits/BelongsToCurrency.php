<?php

namespace App\Http\Traits;

use App\Models\Currency;

trait BelongsToCurrency
{
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
