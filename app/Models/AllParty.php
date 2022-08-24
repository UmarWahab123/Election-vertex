<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllParty extends Model
{
    // protected $table = 'all_parties';

    protected $fillable = [
        'party_name',
        'party_image_url',
        'created_by',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/all-parties/'.$this->getKey());
    }
}
