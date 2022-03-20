<?php

namespace App\Models;

use App\Http\Traits\BelongsToCreatedUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, BelongsToCreatedUser;

    const STATUS = [
        'draft' => 'Draft',
        'paid' => 'Paid',
        'cancelled' => 'Cancelled',
    ];

    public $fillable = [
        'company_id',
        'customer_id',
        'withholding_id',
        'issue_date',
        'notes',
        'invoice_number',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function withholding()
    {
        return $this->belongsTo(Withholding::class, 'withholding_id');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function getTotalAmountAttribute()
    {
        $sum = 0;
        foreach ($this->invoiceItems as $invoiceItem) {
            $tax = $invoiceItem->material->tax->rate;
            $withholding = $this->withholding->rate > 0 ? ($invoiceItem->price * ($this->withholding->rate / 100) * $invoiceItem->quantity * ($tax / 100)) : 0;
            $sum += (($invoiceItem->price * $invoiceItem->quantity * (1 + $tax / 100)) - $withholding);
        }
        return $sum - $this->discount;
    }

    public function getTotalAmountWithOutTaxAttribute()
    {
        $sum = 0;
        foreach ($this->invoiceItems as $invoiceItem) {
            $tax = $invoiceItem->material->tax->rate;
            $sum += ($invoiceItem->price * $invoiceItem->quantity);
        }
        return $sum;
    }

    public function getTotalWithholdingAttribute()
    {
        $sum = 0;
        foreach ($this->invoiceItems as $invoiceItem) {
            $tax = $invoiceItem->material->tax->rate;
            $withholding = $this->withholding->rate > 0 ? ($invoiceItem->price * ($this->withholding->rate / 100) * $invoiceItem->quantity * ($tax / 100)) : 0;
            $sum += $withholding;
        }
        return $sum;
    }

    public function getTotalTaxAttribute()
    {
        $sum = 0;
        foreach ($this->invoiceItems as $invoiceItem) {
            $tax = $invoiceItem->material->tax->rate;
            $sum += ($invoiceItem->price * $invoiceItem->quantity * ($tax / 100));
        }
        return $sum;
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($invoice) {
            $invoice->invoiceItems()->delete();
        });
    }
}
