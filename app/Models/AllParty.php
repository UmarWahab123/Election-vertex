<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class AllParty extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'all_parties';

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
