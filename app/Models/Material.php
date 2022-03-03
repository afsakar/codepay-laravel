<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    const STATUS = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    protected $fillable = [
        'name',
        'sku',
        'sale_price',
        'purchase_price',
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

    public function getCreatedUserAttribute()
    {
        return User::where('id', $this->created_by)->get() ?? null;
    }

    public function category()
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function getSalePriceWithCurrencyAttribute()
    {
        if($this->currency->position == "after") {
            return number_format($this->sale_price, 2) . " " . $this->currency->symbol;
        } else {
            return $this->currency->symbol . " " . number_format($this->sale_price, 2);
        }
    }

    public function getPurchasePriceWithCurrencyAttribute()
    {
        if($this->currency->position == "after") {
            return number_format($this->purchase_price, 2) . " " . $this->currency->symbol;
        } else {
            return $this->currency->symbol . " " . number_format($this->purchase_price, 2);
        }
    }
}
