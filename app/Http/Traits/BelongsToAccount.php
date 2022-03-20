<?php

namespace App\Http\Traits;

use App\Models\Account;

trait BelongsToAccount
{
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
