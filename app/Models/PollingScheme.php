<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PollingScheme extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'polling_scheme';

    protected $fillable = [
        'ward',
        'image_url',
        'serial_no',
        'polling_station_area',
        'block_code_area',
        'block_code',
        'latlng',
        'gender_type',
        'status',
        'male_both',
        'female_both',
        'total_both',
        'polling_station_area_urdu',
        'image_url'


    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/polling-schemes/'.$this->getKey());
    }

    public function pollingScheme(){
        return $this->belongsTo(PollingDetail::class , 'polling_station_number' , 'block_code');
    }

    static function GetLatlng($address)
    {
        $url = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyCG7fIHuTg3jGxWRLQuBiq5mpQcX8lOYQE&callback=initMap&address=".urlencode($address);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $responseJson = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($responseJson);

        if ($response->status == 'OK') {
            $latitude = $response->results[0]->geometry->location->lat;
            $longitude = $response->results[0]->geometry->location->lng;
            $latlng =$latitude.','.$longitude;
            return $latlng;

        }
    }


}
