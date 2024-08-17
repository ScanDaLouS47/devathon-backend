<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'size',
        'status'
    ];

    public function detailBookings(): HasMany
    {
        return $this->hasMany(DetailBooking::class);
    }

}
