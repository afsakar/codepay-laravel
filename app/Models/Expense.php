<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    const TYPES = [
        'formal' => 'Formal',
        'informal' => 'Informal',
    ];

    protected $fillable = [
        'account_id',
        'supplier_id',
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

    protected $appends = [
        'expense_category',
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
            ? number_format($this->where('supplier_id', $this->supplier_id)->sum('amount'), 2)." ".$this->account->currency->symbol
            : $this->account->currency->symbol." ".number_format($this->where('supplier_id', $this->supplier_id)->sum('amount'), 2);
    }

    public function getSumTimesWithExchangeRateAttribute()
    {
        $suppliers = $this->where('supplier_id', $this->supplier_id)->get();

        $summer = 0;
        foreach ($suppliers as $supplier) {
            $summer += $supplier->amount * $supplier->exchange_rate;
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function getExpenseCategoryAttribute()
    {
        return Category::where('type', 'expense')->get();
    }

}
