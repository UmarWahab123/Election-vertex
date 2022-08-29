<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PdfPolling extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'pdf_polling';

    protected $fillable = [
        'email',
        'block_code',
        'status',
        'cron_status',
        'record_type',
        'type',
        'meta',
        'party_type',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/pdf-pollings/'.$this->getKey());
    }

    static public function update_status($row_id , $status){
        PdfPolling::where('id' , $row_id)->update(['status' => $status]);
    }
}
