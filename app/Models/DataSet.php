<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DataSet extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $table = 'data_set';

    protected $fillable = [
        'phone',
        'address',
        'tag',
        'meta',
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
        return url('/admin/data-sets/'.$this->getKey());
    }
}
