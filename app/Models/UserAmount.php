<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAmount extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'amount', 'user_id'
    ];

    /**
     * Get the user that owns the amount.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
