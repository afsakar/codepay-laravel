<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    const STATUS = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    protected $fillable = [
        'name',
        'description',
        'balance',
        'status',
        'currency',
        'currency_status',
    ];

    public function getBalanceWithCurrencyAttribute()
    {
        return $this->currency_status == "after" 
            ? number_format($this->balance, 2)." ".$this->currency 
            : $this->currency." ".number_format($this->balance, 2);
    }

    public function getStatusColorAttribute()
    {
        return [
            'active' => 'green',
            'inactive' => 'red',
        ][$this->status];
    }
}
