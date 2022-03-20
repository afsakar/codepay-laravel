<?php

namespace App\Http\Traits;

use App\Models\MaterialCategory;

trait BelongsToMaterialCategory
{
    public function category()
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }
}
