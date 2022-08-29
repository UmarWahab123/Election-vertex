<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GeneralNotice extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'general_notice';

    protected $fillable = [
        'bussiness_id',
        'title',
        'html_tag',
        'content',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/general-notices/'.$this->getKey());
    }
}
