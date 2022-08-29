<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DumpData extends Model implements Auditable
{ 
use \OwenIt\Auditing\Auditable;

    use HasFactory;
}
