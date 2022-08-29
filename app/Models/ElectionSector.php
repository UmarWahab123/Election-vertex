<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class ElectionSector extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'election_sector';

    protected $fillable = [
        'sector',
        'user_id',
        'block_code',
        'male_vote',
        'female_vote',
        'total_vote',
        'status',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/election-sectors/'.$this->getKey());
    }

    public function pollingData()
    {
        return $this->hasMany(PollingDetail::class , 'polling_station_number' , 'block_code');
    }

}
