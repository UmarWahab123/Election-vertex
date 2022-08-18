<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoterDetail extends Model
{
    protected $fillable = [
        'id_card',
        'serial_no',
        'family_no',
        'age',
        'block_code',
        'name',
        'father_name',
        'address',
        'cron',
        'status',
        'meta',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/voter-details/'.$this->getKey());
    }
}
