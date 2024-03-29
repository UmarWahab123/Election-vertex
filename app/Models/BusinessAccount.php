<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BusinessAccount extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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
