<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollingStation extends Model
{
    protected $table = 'polling_station';

    protected $fillable = [
        'polling_station_number',
        'meta',
        'url_id',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/polling-stations/'.$this->getKey());
    }

    public function sector(){
        return $this->belongsTo(ElectionSector::class , 'polling_station_number' , 'block_code');
    }

    public function polling_details(){
        return $this->hasMany(PollingDetail::class , 'polling_station_id' , 'id');
    }

    public function firebase_urls(){
        return $this->hasMany(FirebaseUrl::class , 'import_ps_number' , 'polling_station_number');
    }

    public function SchemeAddressmulti(){
        return $this->hasMany(PollingScheme::class ,   'block_code','polling_station_number');
    }
}
