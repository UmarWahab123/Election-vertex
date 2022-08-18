<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';

    protected $fillable = [
        'name',
        'phone',
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
        return url('/admin/customers/'.$this->getKey());
    }

    public function order()
    {
        return $this->hasMany(Order::class,'customer_id','');
    }

    public function payment()
    {
        return $this->hasMany(Payments::class,'id','customer_id');
    }
}
