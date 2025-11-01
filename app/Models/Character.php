<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = [
        'id',
        'name',
        'age',
        'birthdate',
        'gender',
        'occupation',
        'portrait_path',
        'phrases',
        'status'
    ];

    protected $casts = [
        'phrases' => 'array',
        'birthdate' => 'date',
    ];

    public $incrementing = false; // usamos el ID de la API
}
