<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constituencies extends Model
{
    use HasFactory;

    Protected $table = 'constituencies';

    protected $fillable = [
        'NA',
        'PP',
        'province',
        'city',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

}
