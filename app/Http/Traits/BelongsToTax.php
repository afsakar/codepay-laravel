<?php

namespace App\Http\Traits;

use App\Models\Tax;

trait BelongsToTax
{
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }
}
