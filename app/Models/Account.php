<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

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

    public function getStatusColorAttribute()
    {
        return [
            'active' => 'green',
            'inactive' => 'red',
        ][$this->status];
    }

    public function getBalanceWithCurrencyAttribute()
    {
        return $this->currency_status == "after"
            ? number_format($this->revenue->sum('amount') + $this->balance - $this->expense->sum('amount'), 2)." ".$this->currency->symbol
            : $this->currency->symbol." ".number_format(($this->revenue->sum('amount') + $this->balance - $this->expense->sum('amount')), 2);
    }

    public function revenue()
    {
        return $this->hasMany(Revenue::class, 'account_id');
    }

    public function expense()
    {
        return $this->hasMany(Expense::class, 'account_id');
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
