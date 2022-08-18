<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSetting extends Model
{
    protected $table = 'page_setting';

    protected $fillable = [
        'business_id',
        'tag_name',
        'businessHome_H1',
        'businessHome_H2',
        'businessHome_H3',
        'status',
        'reg_title',
        'reg_img_title'

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/page-settings/'.$this->getKey());
    }
}
