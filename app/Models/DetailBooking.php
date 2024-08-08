<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'table_id'
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    
}
