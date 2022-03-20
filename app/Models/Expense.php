<?php

namespace App\Models;

use App\Http\Traits\BelongsToAccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory, BelongsToAccount;

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

    protected $casts = [
        'due_at' => 'date:m/d/Y',
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
        return $this->account()->first()->currency()->first()->position == "after"
            ? number_format($this->amount, 2)." ".$this->account()->first()->currency()->first()->symbol
            : $this->account()->first()->currency()->first()->symbol." ".number_format($this->amount, 2);
    }

    public function getSumAmountWithCurrencyAttribute(): string
    {
        $sumSupplierAmount = $this->where('supplier_id', $this->supplier_id)->sum('amount');

        return $this->account()->first()->currency()->first()->position == "after"
            ? number_format($sumSupplierAmount, 2)." ".$this->account()->first()->currency()->first()->symbol
            : $this->account()->first()->currency()->first()->symbol." ".number_format($sumSupplierAmount, 2);
    }

    public function getSumTimesWithExchangeRateAttribute(): string
    {
        $suppliers = $this->where('supplier_id', $this->supplier_id)->where('company_id', get_company_info()->id)->get();

        $summer = 0;
        foreach ($suppliers as $supplier) {
            $summer += $supplier->amount * $supplier->exchange_rate;
        }
        return number_format($summer, 2).' ₺';
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function getExpenseCategoryAttribute()
    {
        return Category::where('type', 'expense')->get();
    }

}
