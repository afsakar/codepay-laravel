<?php

namespace App\Models;

use App\Http\Traits\BelongsToCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Account extends Model
{
    use HasFactory, BelongsToCurrency;

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
        $currency = $this->currency()->first();
        $expenseSum = $this->expense()->get()->sum('amount');
        $revenueSum = $this->revenue()->get()->sum('amount');

        return $currency->position == "after"
            ? number_format($revenueSum + $this->balance - $expenseSum, 2)." ".$currency->symbol
            : $currency->symbol." ".number_format(($revenueSum + $this->balance - $expenseSum), 2);
    }

    public function revenue()
    {
        return $this->hasMany(Revenue::class, 'account_id');
    }

    public function expense()
    {
        return $this->hasMany(Expense::class, 'account_id');
    }

    public function account_type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }
}
