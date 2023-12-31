<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'origin' => 'array',
        'destination' => 'array',
        'is_started' => 'boolean',
        'is_complete' => 'boolean',
        'driver_location' => 'array',
    ];
public function user(){
    return $this->belongsTo(User::class);
}
public function driver(){
    return $this->belongsTo(Driver::class);
}
}
