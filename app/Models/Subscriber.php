<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subscriber extends Model
{
    protected $fillable = [
        'card_number',
        'card_owner',
        'expire_month',
        'expire_year',
        'cvv',
        'unique_hash',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function user(): User
    {
        return $this->users()->first();
    }
}
