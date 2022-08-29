<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Brackets\AdminAuth\Models\AdminUser;

class Audits extends Model 
{

    protected $table = 'audits';

    protected $fillable = [
        'user_type',
        'user_id ',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'tags',
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */
    public function auditusers(){
        return $this->belongsTo(AdminUser::class , 'user_id' , 'id');
    }
    // public function getResourceUrlAttribute()
    // {
    //     return url('/admin/auditables/'.$this->getKey());
    // }
}
