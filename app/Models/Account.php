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
        'owner',
        'account_type_id',
        'description',
        'balance',
        'status',
        'currency_id',
        'currency_status',
    ];

    public function getBalanceWithCurrencyAttribute()
    {
        return $this->currency_status == "after"
            ? number_format($this->balance, 2)." ".$this->currency->symbol
            : $this->currency->symbol." ".number_format($this->balance, 2);
    }

    public function getStatusColorAttribute()
    {
        return [
            'active' => 'green',
            'inactive' => 'red',
        ][$this->status];
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function account_type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }
}
