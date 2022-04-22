<?php

namespace App\Models;

use App\Http\Traits\BelongsToCreatedUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory, BelongsToCreatedUser;

    const STATUS = [
        'draft' => 'Draft',
        'paid' => 'Paid',
        'cancelled' => 'Cancelled',
    ];

    public $fillable = [
        'company_id',
        'corporation_id',
        'withholding_id',
        'issue_date',
        'notes',
        'bill_number',
        'status',
        'created_by',
        'discount',
    ];

    protected $dates = [
        'issue_date',
    ];

    protected $casts = [
        'issue_date' => 'date:m/d/Y',
    ];

    public function getStatusColorAttribute()
    {
        return [
            'draft' => 'gray',
            'paid' => 'green',
            'cancelled' => 'red',
        ][$this->status];
    }

    public function corporation()
    {
        return $this->belongsTo(Corporation::class, 'corporation_id');
    }

    public function withholding()
    {
        return $this->belongsTo(Withholding::class, 'withholding_id');
    }

    public function billItems()
    {
        return $this->hasMany(BillItem::class, 'bill_id');
    }

    public function getTotalAmountAttribute()
    {
        $sum = 0;
        foreach ($this->billItems as $billItem) {
            $tax = $billItem->material->tax->rate;
            $withholding = $this->withholding->rate > 0 ? ($billItem->price * ($this->withholding->rate / 10) * $billItem->quantity * ($tax / 100)) : 0;
            $sum += (($billItem->price * $billItem->quantity * (1 + $tax / 100)) - $withholding);
        }
        return $sum - $this->discount;
    }

    public function getTotalAmountWithOutTaxAttribute()
    {
        $sum = 0;
        foreach ($this->billItems as $billItem) {
            $tax = $billItem->material->tax->rate;
            $sum += ($billItem->price * $billItem->quantity);
        }
        return $sum;
    }

    public function getTotalWithholdingAttribute()
    {
        $sum = 0;
        foreach ($this->billItems as $billItem) {
            $tax = $billItem->material->tax->rate;
            $withholding = $this->withholding->rate > 0 ? ($billItem->price * ($this->withholding->rate / 10) * $billItem->quantity * ($tax / 100)) : 0;
            $sum += $withholding;
        }
        return $sum;
    }

    public function getTotalTaxAttribute()
    {
        $sum = 0;
        foreach ($this->billItems as $billItem) {
            $tax = $billItem->material->tax->rate;
            $sum += ($billItem->price * $billItem->quantity * ($tax / 100));
        }
        return $sum;
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($bill) {
            $bill->billItems()->delete();
        });
    }
}
