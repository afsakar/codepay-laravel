<?php

namespace App\Models;

use App\Http\Traits\BelongsToAccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory, BelongsToAccount;

    const TYPES = [
        'formal' => 'Formal',
        'informal' => 'Informal',
    ];

    protected $fillable = [
        'account_id',
        'customer_id',
        'category_id',
        'company_id',
        'description',
        'amount',
        'exchange_rate',
        'type',
        'due_at',
    ];

    protected $dates = [
        'due_at',
    ];

    protected $casts = [
        'due_at' => 'date:m/d/Y',
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
        return $this->account()->first()->currency()->first()->status == "after"
            ? number_format($this->amount, 2)." ".$this->account()->first()->currency()->first()->symbol
            : $this->account()->first()->currency()->first()->symbol." ".number_format($this->amount, 2);
    }

    public function getSumAmountWithCurrencyAttribute()
    {
        $sumCustomerAmount = $this->where('customer_id', $this->customer_id)->sum('amount');

        return $this->account()->first()->currency()->first()->status == "after"
            ? number_format($sumCustomerAmount, 2)." ".$this->account()->first()->currency()->first()->symbol
            : $this->account()->first()->currency()->first()->symbol." ".number_format($sumCustomerAmount, 2);
    }

    public function getSumTimesWithExchangeRateAttribute()
    {
        $revenues = $this->where('customer_id', $this->customer_id)->where('company_id', get_company_info()->id)->get();

        $summer = 0;
        foreach ($revenues as $revenue) {
            $summer += $revenue->amount * $revenue->exchange_rate;
        }
        return number_format($summer, 2).' ₺';
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
