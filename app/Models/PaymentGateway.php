<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PaymentGateway extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'payment_gateway';

    protected $fillable = [
        'business_id',
        'ref_id',
        'service_charges',
        'expiry_date',
        'on_demand_cloud_computing',
        'multi_bit_visual_redux',
        'scan_reading',
        'googly',
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
        return url('/admin/payment-gateways/'.$this->getKey());
    }
}
