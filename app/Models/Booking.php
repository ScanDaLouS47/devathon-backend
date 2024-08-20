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
        'user_id',
        'persons',
        'shift_id',
        'additional_info',
        'allergens',
        'statusId'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'statusId');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function detailBookings(): HasMany
    {
        return $this->hasMany(DetailBooking::class, 'booking_id');
    }
}
