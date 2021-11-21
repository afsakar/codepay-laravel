<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'permissions'
    ];

    protected $appends = ['perms'];

    /**
     * @return mixed
     */
    public function getPermsAttribute()
    {
        return json_decode($this->permissions, true);
    }
}
