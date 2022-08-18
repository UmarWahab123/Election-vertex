<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessAccount extends Model
{
    protected $table = 'business_account';

    protected $fillable = [
        'business_id',
        'ref_id',
        'details',
        'credit',
        'debit',
        'expiry_date',
        'balance',
        'img_url',
        'status',
        'meta',

    ];



    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/business-accounts/'.$this->getKey());
    }
}
