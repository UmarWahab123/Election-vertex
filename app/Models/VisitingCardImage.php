<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitingCardImage extends Model
{
    protected $fillable = [
        'visiting_card_id',
        'image_link',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/visiting-card-images/'.$this->getKey());
    }
}
