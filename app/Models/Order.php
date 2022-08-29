<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Order extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;
    protected $table = 'order';
    protected $fillable = [
        'customer_id',
        'order_type',
        'order_meta',
        'status',
        'order_by',
        'total_voter',
        'invoice_no',

    ];

    protected $dates = [
        'created_at',
        'updated_at',

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
}
