<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithHolding extends Model
{
    use HasFactory;

    const STATUS = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    protected $fillable = [
        'name',
        'rate',
        'status',
        'created_by',
    ];

    public function getStatusColorAttribute()
    {
        return [
            'active' => 'green',
            'inactive' => 'red',
        ][$this->status];
    }

    public function getCreatedUserAttribute()
    {
        return User::where('id', $this->created_by)->get() ?? null;
    }
}