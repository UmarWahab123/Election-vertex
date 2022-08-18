<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyName extends Model
{
    protected $table = 'party_names';
    
    protected $fillable = [
        'party_name',
        'party_image_url',
        'created_by',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

   
}
