<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class ElectionSetting extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;
    protected $table = 'election_setting';

    protected $fillable = [
        'meta_key',
        'meta_value',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/election-settings/'.$this->getKey());
    }
}
