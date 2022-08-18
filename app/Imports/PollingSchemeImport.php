<?php

namespace App\Imports;

use App\Http\Controllers\PollingSchemeController;
use App\Models\PollingScheme;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Stichoza\GoogleTranslate\GoogleTranslate;

class PollingSchemeImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

dd($row);

    if ($row['0'] != null) {
            $address =  $row['1'];
//            $tr = new GoogleTranslate('ur');
//            $tranlater =  $tr->setSource('en')->setTarget('ur')->translate($address);
           // $latlng =  PollingScheme::GetLatlng($row['1']);
            $latlng = '';
            if($row['0'] != null){
                return new PollingScheme([
                    'serial_no'=>$row['0'],
                    'ward' => @$row['1'],
                    'polling_station_area' => @$row['2'],
                    'polling_station_area_urdu' => @$row['3'],
                    'gender_type'=> @$row['4'],
                    'block_code_area' => @$row['6'],
                    'block_code' => @$row['5'],
                    'male_both' => @$row['7'],
                    'female_both' => @$row['8'],
                    'total_both' => @$row['9'],
                    'latlng' => @$latlng,
                    'status'=>'ACTIVE',

                ]);
            }
        }
    }
}
