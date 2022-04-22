<?php

namespace App\Models;

use App\Http\Traits\BelongsToBill;
use App\Http\Traits\BelongsToMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory, BelongsToMaterial, BelongsToBill;

    protected $fillable = [
        'material_id',
        'bill_id',
        'quantity',
        'price',
    ];

    public function getTaxRateAttribute()
    {
        return $this->material()->first()->tax->rate;
    }

    public function getMainPriceAttribute()
    {
        return $this->material()->first()->price;
    }

    public function getUnitNameAttribute()
    {
        return $this->material()->first()->unit->name;
    }
}
