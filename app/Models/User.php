<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name'
    ];

    /**
     * Get the amount associated with the user.
     */
    public function amount(): HasOne
    {
        return $this->hasOne(UserAmount::class);
    }
}
