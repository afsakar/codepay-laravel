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
}
