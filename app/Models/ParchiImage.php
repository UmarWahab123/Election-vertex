<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParchiImage extends Model
{
    protected $table = 'parchi_image';

    protected $fillable = [
        'user_id',
        'Party',
        'image_url',
        'status',
        'candidate_name',
        'block_code',
        'candidate_image_url'

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/parchi-images/'.$this->getKey());
    }
}
