<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoterPhone extends Model
{
    use HasFactory;
    protected $table = 'voter_phones';

    public function Polling_detail(){
        return $this->belongsTo(PollingDetail::class , 'polling_detail_id' , 'id');
    }
}
