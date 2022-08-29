<?php

namespace App\Models;

use App\Http\Controllers\Admin\FirebaseUrlsController;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PollingDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'polling_details';

    protected $fillable = [
        'polling_station_id',
        'serial_no',
        'family_no',
        'gender',
        'polling_station_number',
        'cnic',
        'page_no',
        'url',
        'url_id',
        'boundingBox',
        'polygon',
        'status',

    ];

    protected $columns = [
        'id',
        'polling_station_id',
        'polling_station_number',
        'cnic',
        'gender',
        'age',
        'family_no',
        'serial_no',
        'page_no',
        'url',
        'url_id',
        'pic_slice',
        'crop_settings',
        'boundingBox',
        'polygon',
        'urdu_meta',
        'urdu_text',
        'first_name',
        'last_name',
        'address',
        'status',
        'type',
        'created_at',
        'updated_at',
        'check_status',
        'cron'];
    // add all columns from you table

    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function scopeExclude($query, $value = [])
    {
        return $query->select(array_diff($this->columns, (array) $value));
    }

    public function getResourceUrlAttribute()
    {
        return url('/admin/polling-details/'.$this->getKey());
    }

    public function polling_station()
    {
        return $this->belongsTo(PollingStation::class , 'polling_station_id' , 'id');
    }

    public function firebase_url()
    {
        return $this->belongsTo(FirebaseUrl::class , 'url_id' , 'id');
    }

    public function voter_phone(){
        return $this->belongsTo(VoterPhone::class , 'id' , 'polling_detail_id');
    }

    public function SchemeAddress(){
        return $this->belongsTo(PollingScheme::class , 'polling_station_number' , 'block_code');
    }


    public function SchemeAddressmulti(){
        return $this->hasMany(PollingScheme::class ,   'block_code','polling_station_number');
    }

    public function SchemeMaleAddress()
    {
        return $this->belongsTo(PollingScheme::class, 'polling_station_number', 'block_code')
            ->where(function ($query) {
                $query->where('gender_type', 'male')
                    ->orWhere('gender_type', 'combined');
            })
            ->select(['gender_type', 'serial_no','image_url','block_code']);
    }


    public function SchemeFemaleAddress(){
        return $this->belongsTo(PollingScheme::class , 'polling_station_number' , 'block_code')
            ->where(function ($query) {
                $query->where('gender_type', 'female')
                    ->orWhere('gender_type', 'combined');
            })
            ->select(['gender_type', 'serial_no','image_url','block_code']);
    }

    public function SchemeAllAddress(){
        return $this->belongsTo(PollingScheme::class , 'polling_station_number' , 'block_code')
            ->where('gender_type','combined');
    }

    public function sector(){
        return $this->belongsTo(ElectionSector::class , 'polling_station_number' , 'block_code');
    }

    public function polling_details_images(){
        return $this->hasOne(PollingDetailImage::class , 'polling_detail_id' , 'id');
    }

}
