<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory;

    const TYPES = [
        'formal' => 'Formal',
        'informal' => 'Informal',
    ];

    protected $fillable = [
        'account_id',
        'customer_id',
        'category_id',
        'description',
        'amount',
        'exchange_rate',
        'type',
        'due_at',
    ];

    protected $dates = [
        'due_at',
    ];

    protected $appends = [
        'income_category',
    ];

    public function getAmountWithCurrencyAttribute()
    {
        return number_format($this->amount * $this->exchange_rate, 2).' ₺';
    }

    public function getAmountWithTotalCurrencyAttribute()
    {
        return $this->account->currency_status == "after"
            ? number_format($this->amount, 2)." ".$this->account->currency->symbol
            : $this->account->currency->symbol." ".number_format($this->amount, 2);
    }

    public function getSumAmountWithCurrencyAttribute()
    {
        return $this->account->currency_status == "after"
            ? number_format($this->where('customer_id', $this->customer_id)->sum('amount'), 2)." ".$this->account->currency->symbol
            : $this->account->currency->symbol." ".number_format($this->where('customer_id', $this->customer_id)->sum('amount'), 2);
    }

    public function getSumTimesWithExchangeRateAttribute()
    {
        $customers = $this->where('customer_id', $this->customer_id)->get();

        $summer = 0;
        foreach ($customers as $customer) {
            $summer += $customer->amount * $customer->exchange_rate;
        }
        return number_format($summer, 2).' TL';
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function getIncomeCategoryAttribute()
    {
        return Category::where('type', 'income')->get();
    }

}
