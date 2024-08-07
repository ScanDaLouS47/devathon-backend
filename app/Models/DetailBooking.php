<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_booking',
        'id_table'
    ];

    
}
