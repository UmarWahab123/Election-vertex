<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlUploadLog extends Model
{
    protected $table = 'url_upload_log';

    protected $fillable = [
        'user_id',
        'files_count',
        'url_meta',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/url-upload-logs/'.$this->getKey());
    }

    static public function url_upload_log($url_meta , $user_id , $file_count){
        $id = UrlUploadLog::insertGetId(['user_id' => $user_id , 'files_count' => $file_count , 'url_meta' => $url_meta]);
        return $id ;
    }

    public function firebase_url(){
        return $this->hasMany(FirebaseUrl::class , 'url_upload_log_id' , 'id');
    }
}
