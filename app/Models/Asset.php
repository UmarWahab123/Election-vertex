<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'asset';

    protected $fillable = [
        'tag_id',
        'url',
        'title',
        'content',
        'htmlload',
        'status',
        'business_id'
    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/assets/'.$this->getKey());
    }
}
