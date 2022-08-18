<?php

namespace App\Imports;

use App\Models\VoterDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class pollingDetailsImports implements ToModel
{
//    protected $excel;
//    protected $work_sheet;
//    protected $excel_data = [];
//    public function __construct(Request $request)
//    {
//        //Load file from request
//        $this->excel = PHPExcel_IOFactory::load($request->file('file'));
//        //Get active sheet
//        $this->work_sheet = $this->excel->getActiveSheet();
//    }

    public function model(array $row)
    {

        if($row['1'] != null && $row['2'] != null) {
//            $this->work_sheet->getDrawingCollection() as $drawing;

            $first = substr($row['2'], 0, 5);
            $middle = substr($row['2'], 5, 7);
            $last = substr($row['2'], 12);
            $cnic_format = "$first-$middle-$last";
            $pollingDetails=VoterDetail::create([
                'serial_no' => $row['6'],
                'family_no' => $row['5'],
                'name' => $row['4'],
                'father_name' => $row['3'],
                'id_card' => $cnic_format,
                'age' => $row['1'],
                'address' => $row['0'],
                'block_code'=>'037130908',
                'meta' => json_encode(['Data'=>'Excel']),
            ]);
            return $pollingDetails;
        }
    }
    /**
     * @return array
     */
//    public function import()
//    {
//        //Iterate through drawing collection
//        foreach ($this->work_sheet->getDrawingCollection() as $drawing) {
//            //check if it is instance of drawing
//            if ($drawing instanceof PHPExcel_Worksheet_Drawing) {
//                //creating image name with extension
//                $file_name = str_replace(' ', '_', $drawing->getName()).'.'.$drawing->getExtension();
//                //Get image contents from path and store them in Laravel storage
//                Storage::put('public/'.$file_name, file_get_contents($drawing->getPath()));
//                //create images array initially
//                $this->excel_data[] = [
//                    'image' => $file_name
//                ];
//            }
//        }
//        //Map other data present in work sheet
//        return $this->rowData();
//    }
    /**
     * @return array
     */
//    private function rowData()
//    {
//        $i = 0;
//        //Iterate through row by row
//        foreach ($this->work_sheet->getRowIterator(2) as $row) {
//            //iterate through cell by cell of row
//            foreach ($row->getCellIterator() as $cell) {
//                //In case of image data that would be null continue
//                //We have already populated them in array
//                if(is_null($cell->getValue())){continue;}
//                //Map other excel data into the array
//                $this->excel_data[$i]['name'] = $cell->getValue();
//            }
//            $i++;
//        }
//        //Return final data array
//        return $this->excel_data;
//    }


}
