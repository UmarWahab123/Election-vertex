<?php

namespace App\Models;

use Brackets\AdminAuth\Models\AdminUser;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class CandidateWard extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'candidate_ward';

    protected $fillable = [
        'user_id',
        'ward_id',
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
        return url('/admin/candidate-wards/'.$this->getKey());
    }

    public function UserName()
    {
        return $this->hasOne(AdminUser::class , 'id' , 'user_id');
    }

    public function SectorName(){
        return $this->hasMany(ElectionSector::class , 'id' , 'ward_id');
    }

}
