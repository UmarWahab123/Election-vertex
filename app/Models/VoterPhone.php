<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class VoterPhone extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;
    protected $table = 'voter_phones';

    public function Polling_detail(){
        return $this->belongsTo(PollingDetail::class , 'polling_detail_id' , 'id');
    }
}
