<?php

namespace App\Models;

use App\Http\Controllers\VisitingCardController;
use Illuminate\Database\Eloquent\Model;
use App\Models\VisitingCardImage;
use OwenIt\Auditing\Contracts\Auditable;


class VisitingCard extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'latlng',
        'category',
        'status',
        'meta',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/visiting-cards/'.$this->getKey());
    }

    public function image()
    {
        return $this->hasOne(VisitingCardImage::class);
    }
}
