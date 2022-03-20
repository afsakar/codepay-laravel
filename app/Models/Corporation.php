<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corporation extends Model
{
    use HasFactory;

    const STATUS = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    const TYPES = [
        'customer' => 'Customer',
        'supplier' => 'Supplier',
    ];

    protected $fillable = [
        'name',
        'owner',
        'tel_number',
        'gsm_number',
        'fax_number',
        'email',
        'address',
        'tax_office',
        'tax_number',
        'status',
        'type',
    ];

    public function getStatusColorAttribute()
    {
        return [
            'active' => 'green',
            'inactive' => 'red',
        ][$this->status];
    }

    public function revenue()
    {
        return $this->hasMany(Revenue::class, 'corporation_id');
    }

    public function expense()
    {
        return $this->hasMany(Expense::class, 'corporation_id');
    }
}
