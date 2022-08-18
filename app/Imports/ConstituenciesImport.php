<?php

namespace App\Imports;

use App\Models\Constituencies;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class ConstituenciesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        if($row['0'] != null) {
            $Constituencies_data = array("pk_values_1" => $row['1'], "pk_values_2" => $row['2'], "pk_values_3" => $row['3'], "pk_values_4" => $row['4']);
            $meta = json_encode($Constituencies_data);

            $na = $row['0'];

            $check_dup_na = Constituencies::where('NA', $na)->count();

            if ($check_dup_na == null) {

                return new Constituencies([
                    'NA' => $row['0'],
                    'PP' => $meta,
                    'province' => $row['5'],
                    'city' => $row['6'],
                ]);
            }
        }
    }
}
