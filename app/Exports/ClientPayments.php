<?php

namespace App\Exports;

use App\Models\ClientPayment;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ClientPayments implements FromArray,WithHeadings 
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        $data = ClientPayment::with('clientuser')->get();
        $res = [];
        foreach ($data as $key=> $row)
        {
            $res[$key]['key'] = $row->$key+1;
            $res[$key]['user_id'] = $row->clientuser->first_name .' '. $row->clientuser->last_name;
            $res[$key]['receipt_url'] = $row->receipt_url;
            $res[$key]['amount'] = $row->amount;
            $res[$key]['status'] = $row->status;
        }
        return $res;
    }
    public function headings(): array
    {
        return [
            'Sr No',
            'User Name',
            'Receipt Url',
            'Amount',
            'status',
        ];
    }
}
