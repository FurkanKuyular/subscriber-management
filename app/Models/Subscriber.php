<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subscriber extends Model
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    protected $fillable = [
        'card_number',
        'card_owner',
        'expire_month',
        'expire_year',
        'cvv',
        'unique_hash',
        'is_active',
        'expired_at',
        'cancelled_at',
    ];

    public function user(): User
    {
        return $this->users()->first();
    }
}
