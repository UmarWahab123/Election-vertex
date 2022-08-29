<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserImage extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'user_image';

    protected $fillable = [
        'user_id',
        'file_url',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/user-images/'.$this->getKey());
    }
}
