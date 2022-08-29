<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Constituencies extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

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
