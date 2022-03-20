<?php

namespace App\Http\Traits;

use App\Models\Unit;

trait BelongsToUnit
{
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
