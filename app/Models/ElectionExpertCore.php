<?php

namespace App\Models;

use App\Http\Traits\electionExpertTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionExpertCore extends Model
{
    use HasFactory , electionExpertTrait;
}
