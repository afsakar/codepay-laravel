<?php

namespace App\Http\Traits;

use App\Models\Bill;

trait BelongsToBill
{
    public function invoice()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }
}
