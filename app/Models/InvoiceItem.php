<?php

namespace App\Models;

use App\Http\Traits\BelongsToInvoice;
use App\Http\Traits\BelongsToMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory, BelongsToMaterial, BelongsToInvoice;

    protected $fillable = [
        'material_id',
        'invoice_id',
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
