<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class PollingDetailImage extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'polling_details_images';
    use HasFactory;
}
