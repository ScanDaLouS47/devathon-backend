<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'name',
        'lName',
        'address',
        'phone',
        'dni',
        'gender',
        'age',
        'birthDate',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
