<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\PollingScheme;

class pollingScheme implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

    }
    public function model(array $row)
    {
dd($row);
        if ($row['0'] != null) {
            $latlng =  PollingScheme::GetLatlng($row['1']);
//          dd($latlng);
            if($row['0'] != null){
                return new PollingScheme([
                    'ward' => @$row['0'],
                    'polling_station_area' => @$row['1'],
                    'block_code_area' => @$row['2'],
                    'block_code' => @$row['3'],
                    'latlng' => @$latlng

                ]);
            }
        }
    }
}
