<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workday extends Model
{
    use HasFactory;

    // Permitir asignación masiva para estos campos
    protected $fillable = [
        'user_id',  // Agrega 'user_id' aquí
        'date',
        'start_time',
        'end_time',
        'break_minutes',
    ];
}