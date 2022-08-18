<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurlSwitch extends Model
{
    protected $fillable = [
        'name',
        'status',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/curl-switches/'.$this->getKey());
    }
}
