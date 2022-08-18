<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirebaseUrl extends Model
{
    protected $fillable = [
        'image_url',
        'status',
        'cron',
        'log_state',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */


    public function getResourceUrlAttribute()
    {
        return url('/admin/firebase-urls/'.$this->getKey());
    }

    public function polling_details()
    {
        return $this->hasMany(PollingDetail::class , 'url_id' , 'id');
    }

    public function polling_station()
    {
        return $this->hasOne(PollingStation::class , 'url_id' , 'id');
    }

    public function sector(){
        return $this->belongsTo(ElectionSector::class , 'import_ps_number' , 'block_code');
    }

    public function url_upload_log()
    {
        return $this->belongsTo(UrlUploadLog::class , 'url_upload_log_id' , 'id');
    }


    static public function firebase_url_log_state($log , $url_id)
    {
        if($url_id === null){
            return true;
        }

        $url = FirebaseUrl::find($url_id);
        $url->log_state = $log;
        $url->save();
    }
}
