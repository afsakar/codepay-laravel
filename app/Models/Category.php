<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    const STATUS = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    const TYPES = [
        'income' => 'Income',
        'expense' => 'Expense',
    ];

    protected $fillable = [
        'name',
        'type',
        'status',
    ];

    public function getStatusColorAttribute()
    {
        return [
            'active' => 'green',
            'inactive' => 'red',
        ][$this->status];
    }

    public function getTypeColorAttribute()
    {
        return [
            'income' => 'green',
            'expense' => 'red',
        ][$this->type];
    }
}
