<?php

namespace App\Http\Traits;

use App\Models\Invoice;

trait BelongsToInvoice
{
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
