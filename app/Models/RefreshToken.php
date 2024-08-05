<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefreshToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'user_id',
        'expires_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
