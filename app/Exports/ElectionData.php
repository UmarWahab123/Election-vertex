<?php

namespace App\Exports;

use App\Models\PollingDetail;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ElectionData implements FromArray,WithHeadings
{
    protected $id;

    function __construct($id) {
        $this->id = '259190302';
    }

    public function array(): array
    {
        $data = PollingDetail::where('polling_station_number', $this->id)
            ->with('voter_phone')
            ->get();

        $res = [];
        foreach ($data as $key=> $row)
        {
            $res[$key]['cnic'] = $row->cnic;
            $res[$key]['gender'] = $row->gender;
            $res[$key]['family_no'] = $row->family_no;
            $res[$key]['serial_no'] = $row->serial_no;
            $res[$key]['polling_station_number'] = $row->polling_station_number;
            $name = json_decode(@$row->voter_phone['meta'],true);

            $res[$key]['name'] = @$name[0]['firstname'];

            $res[$key]['phone'] = @$row->voter_phone['phone'];



        }


        return $res;

    }

    public function headings(): array
    {
        return [
            'CNIC',
            'Gender',
            'Family No',
            'Serial No',
            'Block Code',
            'Name',
            'Phone',
        ];
    }
}
