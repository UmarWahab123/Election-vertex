<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Brackets\AdminAuth\Models\AdminUser;
use OwenIt\Auditing\Contracts\Auditable;

class ClientPayment extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'client_payments';

    protected $fillable = [
        'user_id',
        'receipt_url',
        'amount',
        'status',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];
  
    /* ************************ ACCESSOR ************************* */
    public function clientuser(){
        return $this->belongsTo(AdminUser::class , 'user_id' , 'id');
    }


    public function getResourceUrlAttribute()
    {
        return url('/admin/clients-settings/'.$this->getKey());
    }
}
