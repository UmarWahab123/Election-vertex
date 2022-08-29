<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PdfPollingLog extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'pdf_polling_log';

    protected $fillable = [
        'key',
        'value',
        'meta',
        'log',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/pdf-polling-logs/'.$this->getKey());
    }
}
