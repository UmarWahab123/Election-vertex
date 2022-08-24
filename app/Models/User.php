<?php

namespace App\Models;
use App\Models\Tag;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'business_id',
        'tag_id',
        'app_user_id',
        'name',
        'phone',
        'latlng',
        'status',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];


    public function tag()
    {
        return $this->hasMany(Tag::class);
    }

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/users/'.$this->getKey());
    }
}
