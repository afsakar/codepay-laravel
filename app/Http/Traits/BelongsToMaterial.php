<?php

namespace App\Http\Traits;

use App\Models\Material;

trait BelongsToMaterial
{
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
