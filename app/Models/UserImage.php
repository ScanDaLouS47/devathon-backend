<?php

namespace App\Models;

use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserImage extends Model
{
    use HasFactory, MediaAlly;

    protected $fillable = [
        'public_id',
        'url',
        'user_id'
    ];



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
