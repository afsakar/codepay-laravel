<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    const STATUS = [
        'active' => 'Active',
        'inactive' => 'Inactive',
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
        'balance',
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
        return $this->hasMany(Revenue::class, 'customer_id');
    }
}
