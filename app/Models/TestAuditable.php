<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class TestAuditable extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'auditable';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
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
        return url('/admin/auditables/'.$this->getKey());
    }
}
