<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GeneralSetting extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'general_setting';

    protected $fillable = [
        'business_id',
        'general_tag',
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
        return url('/admin/general-settings/'.$this->getKey());
    }
}
