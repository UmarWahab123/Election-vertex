<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Aws\Textract\TextractClient;
use DB;

class ExtractUrlDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function url_textract()
    {
        $urls = DB::table('firebase_urls')->where('status', '0')->get();
        foreach ($urls as $key => $value) {
            $status = $this->pdfTextract($value->image_url);
            if ($status == true) {
                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '1']);
            } else if ($status == 'no_cnic') {
                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '2']);
            }


            
        }
    }

    public function textract_api($image)
    {
        $client = new TextractClient([
            'version'     => '2018-06-27',
            'region'      => 'us-east-1',
            'credentials' => [
                'key'    => 'AKIAXWVDESS7ZUKLBS7A',
                'secret' => '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'
            ]

        ]);
        $options = [
            'Document' => [
                'Bytes' => $image
            ],
            'FeatureTypes' => ['TABLES'],
            'Languages' => [
                "LanguageCode" => "ur",
                "LanguageCode" => "ar"
            ]
        ];


        $result = $client->analyzeDocument($options);
        $blocks = $result['Blocks'];

        return $blocks;
    }

    public function save_polling_details($polling_station_id, $polling_station_number, $cnic, $page_number, $url)
    {
        foreach ($cnic as $key => $value) {
            DB::table('polling_details')->insert(['polling_station_id' => $polling_station_id, 'polling_station_number' => $polling_station_number, 'cnic' => $value, 'page_no' => $page_number, 'url' => $url]);
        }
        return true;
    }

    public function getPollingStationId($polling_station_number, $meta)
    {
        $polling_station = DB::table('polling_station')->where('polling_station_number', $polling_station_number)->first();
        if ($polling_station) {
            $polling_station_id = $polling_station->id;
        } else {
            $new_polling_station = DB::table('polling_station')->insertGetId(['polling_station_number' => $polling_station_number, 'meta' => $meta]);
            $polling_station_id = $new_polling_station;
        }

        return $polling_station_id;
    }

    public function pdfTextract($url)
    {
        // $url = 'https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1624568925530.jpg?alt=media&token=629dbe94-e22d-4e75-98d0-f196f04b48d6';
        $image = file_get_contents($url);
        $blocks = $this->textract_api($image);

        $meta = json_encode($blocks);

        $cnic = array();
        $polling_station_number = '';
        $page_number = '';

        $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})+/';
        $polling_station_number_pattern = '/^\d{5,20}$/';
        $page_number_pattern = '/^(Page)+/';

        foreach ($blocks as $key => $value) {
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if (isset($value['Text']) && $value['Text']) {
                    $text = $value['Text'];
                    if ($blockType == 'LINE' && preg_match_all($cnic_pattern, $text, $matches)) {
                        $cnic[] = $text;
                    }
                    if ($blockType == 'LINE' &&  preg_match_all($polling_station_number_pattern, $text, $matches)) {
                        $polling_station_number = $text;
                    }
                    if ($blockType == 'LINE' &&  preg_match_all($page_number_pattern, $text, $matches)) {
                        $page_number = $text;
                        $temp = explode(' ', $page_number);
                        $temp = explode('/', $temp[1]);
                        $page_number = $temp[0];
                    }
                }
            }
        }

        $polling_station_id = $this->getPollingStationId($polling_station_number, $meta);

        if ($cnic != []) {
            $status = $this->save_polling_details($polling_station_id, $polling_station_number, $cnic, $page_number, $url);
            if ($status == true) {
                return true;
            }
        } else {
            return 'no_cnic';
        }
    }


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->url_textract();
    }
}
