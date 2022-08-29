<?php

namespace App\Models;

use App\Http\Traits\electionExpertTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionExpertCore extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory , electionExpertTrait;
}
