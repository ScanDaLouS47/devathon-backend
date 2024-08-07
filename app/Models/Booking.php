<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservationDate',
        'userId',
        'total_capacity',
        'persons',
        'shift_id',
        'adicional_info',
        'allergens',
        'statusId'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'statusId');
    }
}
