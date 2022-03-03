<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    const STATUS = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'status',
        'position',
    ];

    public function getStatusColorAttribute()
    {
        return [
            'active' => 'green',
            'inactive' => 'red',
        ][$this->status];
    }
}
