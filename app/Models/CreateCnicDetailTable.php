<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CreateCnicDetailTable extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'cnic_detail';

    protected $fillable = [ 'cnic_number','family_number','name','father_name','address','polling_station'

    ];


    protected $dates = [

    ];
    public $timestamps = false;

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/create-cnic-detail-tables/'.$this->getKey());
    }
}
