<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineDataFile extends Model
{
    Protected $table = 'offline_data_files';

    protected $fillable = [
        'ward',
        'state',
        'type',
        'cron'
    ];


    protected $dates = [
        'created_at',
        'updated_at',
    ];
    public $timestamps = false;

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/offline-data-files/'.$this->getKey());
    }
}