<?php

namespace App\Http\Traits;

use App\Models\User;

trait BelongsToCreatedUser
{
    public function getCreatedUserAttribute()
    {
        return User::where('id', $this->created_by)->get() ?? null;
    }
}
