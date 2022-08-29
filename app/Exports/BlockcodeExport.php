<?php

namespace App\Exports;

use App\Models\ElectionSector;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
class BlockcodeExport implements FromArray,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $blockCode;
    function __construct($blockCode) {
            $this->blockCode = $blockCode;
    }
    public function array(): array
    {
        $data = ElectionSector::where('block_code',$this->blockCode)->get();
        $res = [];
        foreach($data as $key=>$row)
        {
            $res[$key]['key'] = $row->$key+1;
            $res[$key]['block_code'] = $row->block_code;
        }
        return $res;
    }
    public function headings(): array
    {
        return [
            'Sr No',
            'Block Code',
        ];
    }
}
