<?php

namespace App\Models;

use App\Http\Traits\BelongsToCreatedUser;
use App\Http\Traits\BelongsToCurrency;
use App\Http\Traits\BelongsToMaterialCategory;
use App\Http\Traits\BelongsToTax;
use App\Http\Traits\BelongsToUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory, BelongsToCreatedUser, BelongsToCurrency, BelongsToUnit, BelongsToTax, BelongsToMaterialCategory;

    const STATUS = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    const TYPES = [
        'service' => 'Service',
        'procurement' => 'Procurement',
        'service_procurement' => 'Service & Procurement',
    ];

    protected $fillable = [
        'name',
        'sku',
        'price',
        'quantity',
        'tax_id',
        'material_category_id',
        'unit_id',
        'currency_id',
        'type',
        'description',
        'status',
        'created_by',
    ];

    public function getStatusColorAttribute()
    {
        return [
            'active' => 'green',
            'inactive' => 'red',
        ][$this->status];
    }

    public function getPriceWithCurrencyAttribute()
    {
        if($this->currency()->first()->position == "after") {
            return number_format($this->price, 2) . " " . $this->currency()->first()->symbol;
        } else {
            return $this->currency()->first()->symbol . " " . number_format($this->price, 2);
        }
    }
}
