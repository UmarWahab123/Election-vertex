<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Payments extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;
    protected $table=[
        'invoice_no',
        'customer_id',
        'debit',
        'credit',
        'status',
        'transaction_type',
        'balance_type',
        'datetime'
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
