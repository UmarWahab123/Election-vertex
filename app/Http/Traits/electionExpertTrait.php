<?php

namespace App\Http\Traits;

use App\Models\CurlSwitch;
use App\Models\ElectionSector;
use App\Models\FirebaseUrl;
use App\Models\PollingDetail;
use App\Models\PollingStation;
use App\Models\VoterPhone;
use Aws\Textract\TextractClient;

use Carbon\Carbon;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\VisionClient;
use Google\Cloud\Vision\Image;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Cloudinary;
use Auth;
use DB;

trait electionExpertTrait {

    //----------------------------------//
    //             AWS TEXTRACT         //
    //----------------------------------//

    //Cron textract API call this method which took 10 firebase urls and process them
    //Update status of page accordingly
    public function textractMultipleUrl_api()
    {
        $urls = FirebaseUrl::where('status', '0')
            ->where('cron', '0')
            ->select('id', 'image_url', 'status', 'cron', 'log_state', 'import_ps_number')
            ->take(10)
            ->get();

        $urls_ids = $urls->pluck('id');

        //cron status 2 is a middle state which get updated after process is completed
        FirebaseUrl::whereIn('id' , $urls_ids)->update([
            'cron' => '2',
            'log_state' => 'Textract Cron in process'
        ]);

       $lambdaSwitch = CurlSwitch::where('name' , 'textract_lambda')->first();

        if($lambdaSwitch->status == 1){
            dd('call lambda function, cURL');
        }else{
            foreach ($urls as $key => $value) {
                FirebaseUrl::firebase_url_log_state('Before Auto Textract', $value->id);
                $status = $this->auto_textract($value->image_url, $value->id);
                FirebaseUrl::firebase_url_log_state('Finish | ' . $status, $value->id);

                if ($status == 'complete') {
                    $value->update(['status' => '1', 'cron' => '1']);
                }
                else if ($status == 'invalid_page') {
                    $value->update(['status' => '3', 'cron' => '1']);
                }
                else if ($status == 'duplicate') {
                    $value->update(['status' => '4', 'cron' => '1']);
                }
                else if ($status == 'saved_with_import_ps_no') {
                    $value->update(['status' => '5', 'cron' => '1']);
                }
                else if ($status == 'already_extract') {
                    $value->update(['status' => '6', 'cron' => '1']);
                }
            }
        }

        return $urls;

    }

    //Auto textract check and save polling station and details get from child function named image_textract
    //Use getPollingStationId & save_polling_details to determine the state of page
    //Return status of page e.g. Complete, Duplicate, Invalid etc
    public function auto_textract($url, $url_id)
    {
        FirebaseUrl::firebase_url_log_state('Before Image Textract', $url_id);
        $response = $this->image_textract($url, $url_id);
        FirebaseUrl::firebase_url_log_state('After Image Textract', $url_id);

        $cnic = $response['cnic'];
        $polling_station_number =  $response['polling_station_number'];
        $meta = $response['meta'];

        if ((count($cnic) > 0)) {
            FirebaseUrl::firebase_url_log_state('Save or Check Polling Station', $url_id);
            $polling_station_id = $this->getPollingStationId($polling_station_number, $meta, $url_id);

            FirebaseUrl::firebase_url_log_state('Save or Check Polling Detail', $url_id);
            $status = '';
            if($polling_station_id){
                $status = $this->save_polling_details($polling_station_id, $polling_station_number, $cnic, $url, $url_id);
            }
            else{
                $firebase_url = FirebaseUrl::where('id' , $url_id)->first();
                $import_ps_number = $firebase_url->import_ps_number;
                $meta = null;
                $polling_station_id = $this->getPollingStationId($import_ps_number, $meta, $url_id);
                $status = $this->save_polling_details($polling_station_id, $import_ps_number, $cnic, $url, $url_id);
                if ($status == 'detail_saved') {
                    $status = 'saved_with_import_ps_no';
                }
            }

            unset($cnic);
            unset($polling_station_number);
            unset($page_number);

            if ($status == 'detail_saved') {
                return 'complete';
            }
            else if ($status == 'already_extract') {
                return 'already_extract';
            }
            else if ($status == 'duplicate') {
                return 'duplicate';
            }
            else if($status == 'saved_with_import_ps_no') {
                return 'saved_with_import_ps_no';
            }

        }
        else {
            return 'invalid_page';
        }

        return true;

    }

    //Image textract is a chain function of auto_textract which use Textract API
    //Filter the blocks(data) of Textract API response
    //Return the expected extracted data as response array to auto_textract
    public function image_textract($url, $url_id)
    {
        $image = file_get_contents($url);

        FirebaseUrl::firebase_url_log_state('Before Textract API', $url_id);
        $blocks = $this->textract_api($image, $url_id);
        FirebaseUrl::firebase_url_log_state('After Textract API', $url_id);
        $meta = json_encode($blocks);

        if ($meta) {
            $firebase_url = FirebaseUrl::where('id', $url_id)
                ->select('id', 'link_meta')
                ->first();
            $firebase_url->link_meta = $meta;
            $firebase_url->update();
        }

        $cnic = [];
        $polling_station_number = '';
        $page_number = '';
        $response = [];

        $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';
        $cnic_pattern_number = '/^\d{13,13}$/';  //cnic pattern
        $ps_number_pattern = '/^\d{5,20}$/';  //polling station number pattern

        FirebaseUrl::firebase_url_log_state('Before Filtering Blocks', $url_id);

        foreach ($blocks as $key => $value) {
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if (isset($value['Text']) && $value['Text']) {
                    $text = $value['Text'];
                    FirebaseUrl::firebase_url_log_state('CNIC Check', $url_id);
                    if ($blockType == 'LINE' && preg_match_all($cnic_pattern, $text, $matches)) {
                        $BoundingBox = json_encode($value['Geometry']['BoundingBox']);
                        $Polygon = json_encode($value['Geometry']['Polygon']);
                        $cnic[] = ['cnic' => $matches[0][0], 'BoundingBox' => $BoundingBox, 'Polygon' => $Polygon];
                    }
                    elseif ($blockType == 'LINE' && preg_match_all($cnic_pattern_number, $text, $matches))
                    {
                        $BoundingBox = json_encode($value['Geometry']['BoundingBox']);
                        $Polygon = json_encode($value['Geometry']['Polygon']);
                        $first = substr($matches[0][0], 0, 5);
                        $middle = substr($matches[0][0], 5, 7);
                        $last = substr($matches[0][0], 12);
                        $cnic_format = "$first-$middle-$last";

                        $cnic[] = ['cnic' => $cnic_format, 'BoundingBox' => $BoundingBox, 'Polygon' => $Polygon];
                    }

                    FirebaseUrl::firebase_url_log_state('Polling Number Check', $url_id);
                    if ($blockType == 'LINE' &&  preg_match_all($ps_number_pattern, $text, $matches) && ($value['Confidence'] > 85)) {
                        $polling_station_number = $text;
                    }
                    else {
                        $polling_station_number = null;
                    }

                }
            }
        }

        FirebaseUrl::firebase_url_log_state('After Filtering Blocks', $url_id);

        $response = ['cnic' => $cnic, 'polling_station_number' => $polling_station_number, 'meta' => $meta];

        FirebaseUrl::firebase_url_log_state('Sending Filtered Data', $url_id);

        return $response;
    }

    //Check if polling station already exist or not (get from parameters)
    //Save polling station if not already exit
    //Return Polling Station ID
    public function getPollingStationId($polling_station_number, $meta, $url_id)
    {
        FirebaseUrl::firebase_url_log_state('Getting Polling Station', $url_id);
        if ($polling_station_number != null) {
            $polling_station = PollingStation::where('polling_station_number', $polling_station_number)
                ->select('id', 'polling_station_number', 'meta')
                ->first();

            if ($polling_station) {
                $polling_station_id = $polling_station->id;
                if( $meta != null ){
                    $f_url = FirebaseUrl::where('id' , $url_id)->first('import_ps_number');
                    $import_ps_number = $f_url->import_ps_number;
                    if($polling_station_number == $import_ps_number){
                        $polling_station->meta = $meta;
                        $polling_station->url_id = $url_id;
                        $polling_station->save();
                    }
                }
            } else {
                $new_polling_station = PollingStation::insertGetId([
                    'polling_station_number' => $polling_station_number,
                    'meta' => $meta,
                    'url_id' => $url_id
                ]);
                $polling_station_id = $new_polling_station;
            }
        }
        else {
            $polling_station_id = null;
        }
        FirebaseUrl::firebase_url_log_state('Returning Polling Station', $url_id);

        return $polling_station_id;
    }

    //Check if voter detail already exist or not (get from parameters)
    //Save voter details if not exist
    //Return state of page e.g. Complete, Duplicate, Invalid etc
    public function save_polling_details($polling_station_id, $polling_station_number, $cnic, $url, $url_id)
    {
        FirebaseUrl::firebase_url_log_state('Saving Polling Details', $url_id);
        $state = false;

        foreach ($cnic as $key => $value) {
            $polling_detail = PollingDetail::where('cnic', $value['cnic'])
                ->select('id', 'cnic', 'url_id')
                ->first();
            if (is_null($polling_detail)) {
                FirebaseUrl::firebase_url_log_state('Saving Single Polling Detail', $url_id);
                PollingDetail::insert([
                    'polling_station_id' => $polling_station_id,
                    'polling_station_number' => $polling_station_number,
                    'cnic' => $value['cnic'],
                    'polygon' => $value['Polygon'],
                    'boundingBox' => $value['BoundingBox'],
                    'url' => $url,
                    'url_id' => $url_id,
                    'type' => 'textract'
                ]);
                FirebaseUrl::firebase_url_log_state('Single Polling Detail Saved', $url_id);
                $state = 'detail_saved';    //detail saved successfully
            }
            else if ($polling_detail->url_id == $url_id) {
                $state =  'already_extract';    //this page is already extracted
            }
            else if ($polling_detail->url_id != $url_id) {
                $state = 'duplicate';   //this page is uploaded more than one time
            }
        }
        FirebaseUrl::firebase_url_log_state('Saving Extra Details', $url_id);

        return $state;
    }

    //Connect with AWS Textract API and get blocks as response
    //Returns blocks as response
    public function textract_api($image, $url_id)
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
                "LanguageCode" => "ur"
            ]
        ];

        FirebaseUrl::firebase_url_log_state('Before Analyze Image', $url_id);

        $result = $client->analyzeDocument($options);

        FirebaseUrl::firebase_url_log_state('After Analyze Image', $url_id);

        $blocks = $result['Blocks'];

        return $blocks;
    }

    //Accept old blocks as parameters and return blocks of specified confidence level
    public function filterBlocks($blocks, $confidence)
    {
        $blocks = [];
        foreach ($blocks as $key => $value) {
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if (isset($value['Text']) && $value['Text'] && $value['Confidence'] > $confidence) {
                    $text = $value['Text'];
                    $blocks[] = $value;
                }
            }
        }
        return $blocks;
    }

    //---------------------------------------------------------//
    //             CLOUDINARY WITH GOOGLE VISION OCR           //
    //---------------------------------------------------------//

    //Cron Cloudinary Extraction API calls this method which took 10 firebase urls and process them
    //Select the urls/images which are marked invalid by Textract
    //Connect with cloudinary
    public function cloudinaryOCR_api()
    {
        $lambdaSwitch = CurlSwitch::where('name' , 'cloudinery_lambda')->first();
        if($lambdaSwitch->status == 1){
            dd('call lambda function, cURL');
        }
        else {

            ini_set("memory_limit", -1);
            set_time_limit(-1);

            $config = new Configuration();
            // $config->cloud->cloudName = 'one-call-app';
            // $config->cloud->apiKey = '231534778562361';
            // $config->cloud->apiSecret = 'V8j97Iq0SWLKCMpNg7gqUnYHxWo';

             $config->cloud->cloudName = 'election-experts';
             $config->cloud->apiKey = '986962648313337';
             $config->cloud->apiSecret = 'qWC1laqICrORQ72n9HYexSJmVwU';
           
             $config->url->secure = true;
            $cloudinary = new Cloudinary($config);

            $invalid_urls = FirebaseUrl::where('cron', 1)
                ->where('status', 3)
                ->select('id', 'image_url', 'import_ps_number')
                ->take(1)
                ->get();



            if ($invalid_urls) {

                $urls_ids = $invalid_urls->pluck('id');

                //status 19 is a middle state which get updated after process is completed
                FirebaseUrl::whereIn('id', $urls_ids)->update([
                    'status' => 19,
                    'log_state' => 'CLD Cron in process'
                ]);

                foreach ($invalid_urls as $k => $url) {

                    $url_id = $url->id;
                    $url_img = $url->image_url;
                    $ps_number = $url->import_ps_number;    //polling_station_number

                    $res = $cloudinary->uploadApi()->upload($url_img,
                        ["ocr" => "adv_ocr"]);
                    $blocks = [];
                    $blocks = @$res['info']['ocr']['adv_ocr']['data'][0]['textAnnotations'];


                    $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';
                    $save_check = 0;
                    if ($blocks) {
                        $meta = json_encode($blocks);
                        $polling_station_id = $this->getPollingStationId($ps_number, $meta, $url_id);

                        foreach ($blocks as $key => $value) {
                            if (isset($value['description']) && $value['description'] && $key != 0) {
                                $text = $value['description'];

                                if (preg_match_all($cnic_pattern, $text, $matches)) {

                                    $cnic = $text;
                                    $s_no = null;
                                    $f_no = null;
                                    $age = null;
                                    $boundingPoly = json_encode($value['boundingPoly']['vertices']);

                                    $polling_detail = PollingDetail::where('cnic', $cnic)->first();

                                    if ($polling_detail == null) {
                                        PollingDetail::insert([
                                            'polling_station_number' => $ps_number,
                                            'polling_station_id' => $polling_station_id,
                                            'cnic' => $cnic,
                                            'age' => $age,
                                            'family_no' => $f_no,
                                            'serial_no' => $s_no,
                                            'url' => $url_img,
                                            'url_id' => $url_id,
                                            'polygon' => $boundingPoly,
                                            'boundingBox' => $boundingPoly,
                                            'type' => 'cld',
                                        ]);

                                    }

                                    $save_check++;

                                }
                            }

                        }
                    }

                    if ($save_check > 0) {
                        FirebaseUrl::where('id', $url_id)->update([
                            'status' => 20,
                            'log_state' => 'Finish | from_cloudinary'
                        ]);
                    } else {
                        FirebaseUrl::where('id', $url_id)->update([
                            'status' => 404,
                            'log_state' => 'Rejected | from_cloudinary'
                        ]);
                    }

                }
            }
        }

        return true;
    }

    //----------------------------------------//
    //             GOOGLE VISION OCR          //
    //----------------------------------------//

    //Cron Google Vision Extraction API calls this method which took 5 firebase urls and process them
    //Select the urls/images which are marked rejected by Cloudinary
    public function googleVisionOCR_api()
    {
        $invalid_urls = FirebaseUrl::where('cron' , 1)
            ->where('status' , 404)
            ->select('id', 'image_url', 'import_ps_number')
            ->orderBy('id', 'desc')
            ->take(20)
            ->get();
//


        if($invalid_urls){
            $urls_ids = $invalid_urls->pluck('id');

            //status 401 is a middle state which get updated after process is completed
            FirebaseUrl::whereIn('id' , $urls_ids)->update([
                'status' => 401,
                'log_state' => 'GVA Cron in process'
            ]);

            foreach ($invalid_urls as $k => $url){

                $url_id = $url->id;
                $url_img = $url->image_url;
                $ps_number = $url->import_ps_number;    //polling_station_number
                $res = $this->findCoordsOfTextFromImage($url_img);

                $blocks = [];

                if(@$res->orignal){
                    $blocks = $res->orignal;
                }
                else{
                    $url->status = 500;
                    $url->log_state = 'rejected | vision_api';
                    $url->update();
                    continue;
                }

                $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';
                $save_check = 0;

                if($blocks){
                    $meta = json_encode($blocks);
                    $polling_station_id = $this->getPollingStationId($ps_number, $meta, $url_id);
                    foreach ($blocks as $key => $value) {
                        if (isset($value['description']) && $value['description'] && $key != 0) {
                            $text = $value['description'];
                            if (preg_match_all($cnic_pattern, $text, $matches)) {
                                $save_check++;
                                $cnic = $text;
                                $s_no = null;
                                $f_no = null;
                                $age  = null;
                                $boundingPoly = json_encode($value['boundingPoly']['vertices']);

                                $status = PollingDetail::where('cnic' , $cnic)->first();
                                if(!$status){
                                    PollingDetail::insert([
                                        'polling_station_number' => $ps_number,
                                        'polling_station_id' => $polling_station_id,
                                        'cnic' => $cnic,
                                        'age' => $age,
                                        'family_no' => $f_no  ,
                                        'serial_no' => $s_no ,
                                        'url' => $url_img,
                                        'url_id' => $url_id,
                                        'polygon' => $boundingPoly,
                                        'boundingBox' => $boundingPoly,
                                        'type' => 'gva',
                                    ]);
                                }
                            }
                        }
                    }

                }

                if($save_check > 0) {
                    $url->status = 200;
                    $url->log_state = 'Finish | from_vision_api';
                    $url->update();
                }
                else {
                    $url->status = 500;
                    $url->log_state = 'Rejected | from_vision_api';
                    $url->update();
                }

            }

        }

        return true;
    }

    //Core function of google vision API to read text from image
    public function findCoordsOfTextFromImage($image_link)
    {
        $path = $image_link;

        try {
            $projectId = 'nlpapiupwork';
            $imageAnnotator = new ImageAnnotatorClient([
                'projectId' => $projectId,
            ]);
            $vision = new VisionClient([
                'projectId' => $projectId,
            ]);

            $imageData = file_get_contents($path);
            $image = new Image($imageData, [
                'TEXT_DETECTION',
            ]);

            $response = (object)$vision->annotate($image);
            if($response->info() != []){
                $info = (object)$response->info();
                $textAnnotations = (array)$info->textAnnotations;
            }else{
                return false;
            }

        } catch (exception $e) {
            echo $e;
        }

        $response =  response()->json(
            $textAnnotations,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );

        $urdu_meta = json_encode($textAnnotations);

        $detail_array = array();

        //GETTING X,Y COORDINATES OF EACH LETTER
        for ($i = 1; $i < count($textAnnotations); $i++) {
            try {
                $detail_array[$i]['text'] = $textAnnotations[$i]['description'];

                if (@$textAnnotations[$i]['boundingPoly']['vertices'][0]['x']) {
                    $detail_array[$i]['left_x'] = $textAnnotations[$i]['boundingPoly']['vertices'][0]['x'];
                } else if (@$textAnnotations[$i + 1]['boundingPoly']['vertices'][0]['x']) {
                    $detail_array[$i]['left_x'] = $textAnnotations[$i + 1]['boundingPoly']['vertices'][0]['x'];
                } else if (@$textAnnotations[$i - 1]['boundingPoly']['vertices'][0]['x']) {
                    $detail_array[$i]['left_x'] = $textAnnotations[$i - 1]['boundingPoly']['vertices'][0]['x'];
                } else {
                    $detail_array[$i]['left_x'] = null;
                }

                if (@$textAnnotations[$i]['boundingPoly']['vertices'][2]['x']) {
                    $detail_array[$i]['right_x'] = $textAnnotations[$i]['boundingPoly']['vertices'][2]['x'];
                } else if (@$textAnnotations[$i + 1]['boundingPoly']['vertices'][2]['x']) {
                    $detail_array[$i]['right_x'] = $textAnnotations[$i + 1]['boundingPoly']['vertices'][2]['x'];
                } else if (@$textAnnotations[$i - 1]['boundingPoly']['vertices'][2]['x']) {
                    $detail_array[$i]['right_x'] = $textAnnotations[$i - 1]['boundingPoly']['vertices'][2]['x'];
                } else {
                    $detail_array[$i]['right_x'] = null;
                }

                if (@$textAnnotations[$i]['boundingPoly']['vertices'][0]['y']) {
                    $detail_array[$i]['left_y'] = $textAnnotations[$i]['boundingPoly']['vertices'][0]['y'];
                } else if (@$textAnnotations[$i + 1]['boundingPoly']['vertices'][0]['y']) {
                    $detail_array[$i]['left_y'] = $textAnnotations[$i + 1]['boundingPoly']['vertices'][0]['y'];
                } else if (@$textAnnotations[$i - 1]['boundingPoly']['vertices'][0]['y']) {
                    $detail_array[$i]['left_y'] = $textAnnotations[$i - 1]['boundingPoly']['vertices'][0]['y'];
                } else {
                    $detail_array[$i]['left_y'] = null;
                }

                if (@$textAnnotations[$i]['boundingPoly']['vertices'][2]['y']) {
                    $detail_array[$i]['right_y'] = $textAnnotations[$i]['boundingPoly']['vertices'][2]['y'];
                } else if (@$textAnnotations[$i + 1]['boundingPoly']['vertices'][2]['y']) {
                    $detail_array[$i]['right_y'] = $textAnnotations[$i + 1]['boundingPoly']['vertices'][2]['y'];
                } else if (@$textAnnotations[$i - 1]['boundingPoly']['vertices'][2]['y']) {
                    $detail_array[$i]['right_y'] = $textAnnotations[$i - 1]['boundingPoly']['vertices'][2]['y'];
                } else {
                    $detail_array[$i]['right_y'] = null;
                }
            } catch (Exception $e) {
                dd($i);
            }
        }

        //SORTING ARRAY ACCORDING TO X AXIS
        $swapped = true;
        while ($swapped) {
            $swapped = false;
            for ($i = 1, $c = count($detail_array); $i < $c; $i++) {
                if ($detail_array[$i]['left_x'] < $detail_array[$i + 1]['left_x']) {
                    list($detail_array[$i + 1], $detail_array[$i]) = array($detail_array[$i], $detail_array[$i + 1]);
                    $swapped = true;
                }
            }
        }

        //GETTING HIGH AND LOW Y AXIS
        $y_min = 0;
        $y_max = 0;
        foreach ($detail_array as $key => $value) {
            if ($key == 1) {
                $y_min = $value['left_y'];
                $y_max = $value['left_y'];
            }
            if ($y_max < $value['left_y']) {
                $y_max = $value['left_y'];
            }
            if ($y_min > $value['left_y']) {
                $y_min = $value['left_y'];
            }
        }

        //NEGLECTING EXTRA DATA
        $y_lim_low = $y_min;
        $y_lim_high = $y_max + 100;
        $result = [];
        foreach ($detail_array as $k => $index) {
            if ($index['left_y'] >= $y_lim_low && $index['left_y'] <= $y_lim_high) {
                $result[] = $index;
            }
        }

        //CALCULATING DISTANCE BETWEEN ELEMENTS
        $detail_array = $result;
        foreach ($detail_array as $key => $index) {
            if (isset($detail_array[$key + 1])) {
                $detail_array[$key]['d'] = $index['left_x'] - $detail_array[$key + 1]['right_x'];
            } else {
                $detail_array[$key]['d'] = 0;
            }
        }

        //GETTING ACTUAL SORTED FINAL DATA
        $row = '';
        $data = [];
        foreach ($detail_array as $key => $val) {
            if ($val['d'] > 15) {
                $row = $row . $val['text'];
                $data[] = $row;
                $row = "";
            } else {
                $row = $row . $val['text'] . ' ';
            }
        }
        $data[] = $row;

        return ['meta' => $urdu_meta, 'data' => $data];
    }

    //----------------------------------------//
    //            CROPPING OF PICTURE         //
    //----------------------------------------//

    //Crop slice from complete page picture against cnic number
    //Crop slice of pages which are read by Textract OCR
    public function cropImageTextract_api()
    {
        try {
            $polling_detail = PollingDetail::with('firebase_url')
                ->where('pic_slice' , null)
                ->where('polygon', '!=', null)
                ->where('type', 'textract')
                ->select('id', 'url', 'pic_slice', 'polygon', 'crop_settings')
                ->orderBy('id' , 'asc')
                ->take(1000)
                ->get()
                ->groupBy('url_id');




            $next_y_axis = 0;
            if($polling_detail){
                foreach ($polling_detail as $key => $item) {
                    foreach ($item as $key => $value) {
                        $firebase_url = $value->url;
                        $polygon = json_decode($value->polygon, true);

                        $next_polygon = json_decode(@$item[$key + 1]->polygon, true);
                        $y_axis = $polygon[0]['Y'];

                        if(@$next_polygon[0]['Y'] && ($next_y_axis > $y_axis)){
                            $next_y_axis = @$next_polygon[0]['Y'];
                            $h=0.035326910972595;
                        }
                        else if($y_axis > $next_y_axis){
                            $h=0.035326910972595;
                        }

//                        $y_axis_thereshold_percent = 1.25;
                        $y_axis_thereshold_percent = 1;
                        $percent_value = ($y_axis * $y_axis_thereshold_percent) / 100;
                        $y_axis = $y_axis - $percent_value;


                        $threshold_percent = 25;
                        $addition = ($h * $threshold_percent) / 100;
                        $h = $h + $addition;

                        $data = urlencode($firebase_url);

                        if($h > 0.25)
                        {
                            $h=0.035326910972595;
                        }
                        if($h < 0){
                            $h = $h * (-1);
                        }

                        $cloudinary_url = 'https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_' . ($h) . ',w_20,x_0,y_' . ($y_axis) . '/' . $data;

                        $arr = [];
                        $arr['h'] = $h;
                        $arr['y'] = $y_axis;
                        $arr['url'] = $data;
                        $value->crop_settings = json_encode($arr);
                        $value->pic_slice = $cloudinary_url;
                        $value->update();
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            echo $e;
        }

        unset($polling_detail);
        return true;
    }

    //Crop slice from complete page picture against cnic number
    //Crop slice of pages which are read by Google Vision OCR
    public function cropImageCloudinary_api(){
        try {
            $polling_detail = PollingDetail::with('firebase_url')
                ->where('pic_slice' , null)
                ->where('polygon', '!=', null)
                ->whereIn('type' , ['cld' , 'gva'])
                ->select('id', 'url', 'pic_slice', 'polygon', 'crop_settings')
                ->orderBy('id' , 'asc')
                ->take(1000)
                ->get()
                ->groupBy('url_id');

            $next_y_axis = 0;

            if($polling_detail){
                foreach ($polling_detail as $k => $item) {
                    foreach ($item as $key => $value) {
                        $recheck=0;
                        $firebase_url = $value->url;
                        $polygon = json_decode($value->polygon, true);

                        $next_polygon = json_decode(@$item[$key + 1]->polygon, true);
                        $y_axis = $polygon[0]['y'];

                        if(@$next_polygon){
                            $next_y_axis = $next_polygon[0]['y'];
                        }

                        if(@$next_polygon[0]['y'] && ($next_y_axis > $y_axis)){
                            $h = $next_y_axis - $y_axis;
                        }
                        else if(@$next_polygon[0]['y'] && ($y_axis > @$next_y_axis)){
                            $h =  $y_axis - $next_y_axis;
                        }else{
                            $h = 100;
                        }


                        $data = urlencode($firebase_url);
                        $y_axis = $y_axis-25;

                        if($h > 140)
                        {
                            $recheck=1;
                            $h = 110;
                        }

                        $cloudinary_url = 'https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_' . ($h) . ',w_10000,x_0,y_' . ($y_axis) . '/' . $data;

                        $arr = [];
                        $arr['h'] = $h;
                        $arr['y'] = $y_axis;
                        $arr['url'] = $data;
                        $arr['recheck'] = $recheck;
                        $value->crop_settings = json_encode($arr);
                        $value->pic_slice = $cloudinary_url;
                        $value->update();
                    }
                }
            }

        }
        catch (\Exception $e)
        {
            dd($e);
        }

        unset($polling_detail);
        return true;
    }

    //----------------------------------------//
    //             SIDE FUNCTIONALITY         //
    //----------------------------------------//

    //Assign the gender to voters on the basis of last digit of there CNIC
    public function assignGender_api(){
        $male = [];
        $female = [];
        $polling_details = PollingDetail::where('gender' , 'undefined')
            ->select('id', 'cnic', 'gender')
            ->orderBy('id' , 'asc')
            ->take(1000)
            ->get();

        if($polling_details){
            foreach ($polling_details as $key => $item){

                $cnic = (int) preg_replace("/[^0-9\s]/", "", $item->cnic);
                $last_digit = (int) substr(trim($cnic),-1);

                if($last_digit % 2 !== 0){
                    $male[] = $item->id;
                }else {
                    $female[] = $item->id;
                }
            }

            PollingDetail::whereIn('id' , $male)->update([
                'gender' => 'male'
            ]);
            PollingDetail::whereIn('id' , $female)->update([
                'gender' => 'female'
            ]);
        }

        unset($polling_details);
        return true;

    }

    //Read the serial number and family number using textract and google vision OCR and update against the CNIC
    public function getFamilyAndSerialNo_api(){
        $output_new = [];
        $textAnnotations = [];
        $output = [];

        $polling_details =  PollingDetail::where('serial_cron' , 0)
            ->select('id', 'cnic', 'pic_slice', 'url', 'crop_settings', 'type', 'serial_no', 'family_no', 'serial_cron')
            ->where('created_at','>' ,'2022-07-10 00:00:00')
            ->where('crop_settings','!=' ,null)
             ->with('voter_phone')
              ->orderBy('id' , 'desc')
             ->take(100)
             ->get();

         PollingDetail::whereIn('id',$polling_details->pluck('id'))->update(['serial_cron'=> 206]);


        foreach ($polling_details as $k => $detail){
            $y = json_decode($detail->crop_settings , true)['y'];
            if($detail->type == 'textract'){
                $path = 'https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_0.035326910972595,w_0.15,x_0.83,y_'.$y.'/'.urlencode($detail->url);
            }
            else{


                $path = 'https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_127,w_500,x_2850,y_'.$y.'/'.urlencode($detail->url);
            }


            try {
                $projectId = 'nlpapiupwork';
                $vision = new VisionClient([
                    'projectId' => $projectId,
                ]);

                $imageData = file_get_contents($path);
                $image = new Image($imageData, [
                    'TEXT_DETECTION',
                ]);

                $response = (object)$vision->annotate($image);

                if($response->info() != []){
                    $info = (object)$response->info();
                    $textAnnotations = (array)$info->textAnnotations;
                    if(count($textAnnotations) < 3){
                        $row_data = $this->getFamilyAndSerialNoTextract($imageData , $detail);
                        $output_new[] = $row_data;
                        continue;
                    }
                }
                else{
                    $row_data = $this->getFamilyAndSerialNoTextract($imageData , $detail);
                    $output_new[] = $row_data;
                    continue;
                }

            }
            catch (exception $e) {
                echo $e;
            }

            if(count(@$textAnnotations) > 2){
                $detail_array = array();

                //GETTING X,Y COORDINATES OF EACH LETTER
                for ($i = 1; $i < count($textAnnotations); $i++) {
                    try {
                        $detail_array[$i]['text'] = $textAnnotations[$i]['description'];

                        if (@$textAnnotations[$i]['boundingPoly']['vertices'][0]['x']) {
                            $detail_array[$i]['left_x'] = $textAnnotations[$i]['boundingPoly']['vertices'][0]['x'];
                        } else if (@$textAnnotations[$i + 1]['boundingPoly']['vertices'][0]['x']) {
                            $detail_array[$i]['left_x'] = $textAnnotations[$i + 1]['boundingPoly']['vertices'][0]['x'];
                        } else if (@$textAnnotations[$i - 1]['boundingPoly']['vertices'][0]['x']) {
                            $detail_array[$i]['left_x'] = $textAnnotations[$i - 1]['boundingPoly']['vertices'][0]['x'];
                        } else {
                            $detail_array[$i]['left_x'] = null;
                        }

                        if (@$textAnnotations[$i]['boundingPoly']['vertices'][2]['x']) {
                            $detail_array[$i]['right_x'] = $textAnnotations[$i]['boundingPoly']['vertices'][2]['x'];
                        } else if (@$textAnnotations[$i + 1]['boundingPoly']['vertices'][2]['x']) {
                            $detail_array[$i]['right_x'] = $textAnnotations[$i + 1]['boundingPoly']['vertices'][2]['x'];
                        } else if (@$textAnnotations[$i - 1]['boundingPoly']['vertices'][2]['x']) {
                            $detail_array[$i]['right_x'] = $textAnnotations[$i - 1]['boundingPoly']['vertices'][2]['x'];
                        } else {
                            $detail_array[$i]['right_x'] = null;
                        }

                        if (@$textAnnotations[$i]['boundingPoly']['vertices'][0]['y']) {
                            $detail_array[$i]['left_y'] = $textAnnotations[$i]['boundingPoly']['vertices'][0]['y'];
                        } else if (@$textAnnotations[$i + 1]['boundingPoly']['vertices'][0]['y']) {
                            $detail_array[$i]['left_y'] = $textAnnotations[$i + 1]['boundingPoly']['vertices'][0]['y'];
                        } else if (@$textAnnotations[$i - 1]['boundingPoly']['vertices'][0]['y']) {
                            $detail_array[$i]['left_y'] = $textAnnotations[$i - 1]['boundingPoly']['vertices'][0]['y'];
                        } else {
                            $detail_array[$i]['left_y'] = null;
                        }

                        if (@$textAnnotations[$i]['boundingPoly']['vertices'][2]['y']) {
                            $detail_array[$i]['right_y'] = $textAnnotations[$i]['boundingPoly']['vertices'][2]['y'];
                        } else if (@$textAnnotations[$i + 1]['boundingPoly']['vertices'][2]['y']) {
                            $detail_array[$i]['right_y'] = $textAnnotations[$i + 1]['boundingPoly']['vertices'][2]['y'];
                        } else if (@$textAnnotations[$i - 1]['boundingPoly']['vertices'][2]['y']) {
                            $detail_array[$i]['right_y'] = $textAnnotations[$i - 1]['boundingPoly']['vertices'][2]['y'];
                        } else {
                            $detail_array[$i]['right_y'] = null;
                        }
                    }
                    catch (Exception $e) {
                        dd($i);
                    }
                }

                //SORTING ARRAY ACCORDING TO X AXIS
                $swapped = true;
                while ($swapped) {
                    $swapped = false;
                    for ($i = 1, $c = count($detail_array); $i < $c; $i++) {
                        if ($detail_array[$i]['left_x'] < $detail_array[$i + 1]['left_x']) {
                            list($detail_array[$i + 1], $detail_array[$i]) = array($detail_array[$i], $detail_array[$i + 1]);
                            $swapped = true;
                        }
                    }
                }

                //GETTING HIGH AND LOW Y AXIS
                $y_min = 0;
                $y_max = 0;
                foreach ($detail_array as $key => $value) {
                    if ($key == 1) {
                        $y_min = $value['left_y'];
                        $y_max = $value['left_y'];
                    }
                    if ($y_max < $value['left_y']) {
                        $y_max = $value['left_y'];
                    }
                    if ($y_min > $value['left_y']) {
                        $y_min = $value['left_y'];
                    }
                }

                //NEGLECTING EXTRA DATA
                $y_lim_low = $y_min;
                $y_lim_high = $y_max + 100;
                $result = [];
                foreach ($detail_array as $k => $index) {
                    if ($index['left_y'] >= $y_lim_low && $index['left_y'] <= $y_lim_high) {
                        $result[] = $index;
                    }
                }

                //CALCULATING DISTANCE BETWEEN ELEMENTS
                $detail_array = $result;
                foreach ($detail_array as $key => $index) {
                    if (isset($detail_array[$key + 1])) {
                        $detail_array[$key]['d'] = $index['left_x'] - $detail_array[$key + 1]['right_x'];
                    } else {
                        $detail_array[$key]['d'] = 0;
                    }
                }

                //GETTING ACTUAL SORTED FINAL DATA
                $row = '';
                $data = [];
                $data[] = $detail->cnic;
                foreach ($detail_array as $key => $val) {
                    if ($val['d'] > 10) {
                        $row = $row . $val['text'];
                        $data[] = $row;
                        $row = "";
                    } else {
                        $row = $row . $val['text'] . ' ';
                    }
                }
                $data[] = $row;
                $output[] = $data;
            }
            else{
                continue;
            }
        }

        if(count($output) > 0){
            foreach ($output as $i => $i_val){
                $row_data['cnic'] = @$i_val[0];
                $row_data['s_no'] = null;
                $row_data['f_no'] = null;
                $s_no = str_replace('.', '', trim(@$i_val[1]));
                $temp_s_no = explode(' ', $s_no);
                if(@$temp_s_no[1]){
                    $s_no = $temp_s_no[1];
                }
                else{
                    $s_no = $temp_s_no[0];
                }
                $f_no = str_replace('.', '', trim(@$i_val[2]));
                if (@$s_no && @$f_no) {
                    if (ctype_digit($s_no)) {
                        $row_data['s_no'] = $s_no;
                        if (ctype_digit($f_no)) {
                            $row_data['f_no'] = $f_no;
                        } else if (preg_match('/[\d]*[-Q]+/', $f_no, $output_array)) {
                            if (@$output_array[0] && $output_array[0] != "") {
                                $row_data['f_no'] = @$output_array[0];
                            } else {
                                $row_data['s_no'] = null;
                                $row_data['f_no'] = null;
                            }
                        } else {
                            $temp_f_no = (int)filter_var($f_no, FILTER_SANITIZE_NUMBER_INT);
                            if ($temp_f_no != '') {
                                $row_data['f_no'] = (string) $temp_f_no;
                            } else {
                                $row_data['s_no'] = $s_no;
                                $row_data['f_no'] = null;
                            }
                        }
                    }
                    else {
                        $row_data['s_no'] = null;
                        $row_data['f_no'] = null;
                    }
                }
                else {
                    $row_data['s_no'] = null;
                    $row_data['f_no'] = null;
                }

                $output_new[] = $row_data;
                unset($row_data);

            }
        }

        foreach($output_new as $index => $res){
            $f_no = $res['f_no'];
            $s_no = $res['s_no'];
            $voter = PollingDetail::where('cnic' , $res['cnic'])
                ->select('id', 'family_no', 'serial_no','serial_cron')
                ->first();

            $voter->family_no = $f_no;
            $voter->serial_no = $s_no;
            $voter->serial_cron = 1;

            if(empty($s_no) ||empty($f_no))
            {
                $voter->serial_cron = 207;
            }
            $voter->update();



        }
                $data=[];
            if(count($polling_details) > 0)
            {
                $data=$polling_details[0];
            }

        unset($polling_details);
        unset($output);
        unset($output_new);
        unset($textAnnotations);

        return $data;
    }

    //Child function of getFamilyAndSerialNo_api to extract serial and family number using Textract API.
    public function getFamilyAndSerialNoTextract($imageData, $detail){

        $blocks = $this->textract_api($imageData , null);
        $blocks = $this->filterBlocks($blocks , 97);

        $s_no = str_replace('.', '', trim(@$blocks[1]['Text']));
        $f_no = str_replace('.', '', trim(@$blocks[0]['Text']));

        $row_data['cnic'] = $detail->cnic;
        $row_data['s_no'] = null;
        $row_data['f_no'] = null;

        if (@$s_no && @$f_no){
            if(ctype_digit($s_no)){
                $row_data['s_no'] = $s_no;
                if(ctype_digit($f_no)){
                    $row_data['f_no'] = $f_no;
                }
                elseif(preg_match_all('/[\d]*[-Q]*/', $f_no, $output_array)){
                    if(@$output_array[0][0] && $output_array[0][0] != ""){
                        $row_data['f_no'] = @$output_array[0][0];
                    }
                    else{
                        $row_data['f_no'] = null;
                    }
                }
                else{
                    $temp_f_no = (int) filter_var($f_no, FILTER_SANITIZE_NUMBER_INT);
                    if($temp_f_no){
                        $row_data['f_no'] = $temp_f_no;
                    }else{
                        $row_data['f_no'] = null;
                    }
                }
            }else{
                $row_data['s_no'] = null;
                $row_data['f_no'] = null;
            }
        }
        else{
            $row_data['s_no'] = null;
            $row_data['f_no'] = null;
        }

        return $row_data;
    }

    //Get phone numbers of voter from Azure against there cnic
    public function getPhoneNumber_api()
    {
        $queryCnicString = '';

        $polling_details = PollingDetail::where('cnic', '!=', null)
            ->whereIn('status' , [0 , 1])
            ->select('id', 'cnic', 'status')
            ->orderBy('id' , 'desc' )
            ->take(1000)
            ->get();


        $polling_details_ids =[] ;

        if($polling_details){
            foreach ($polling_details as $item) {

                //Making CNIC string to get phone from Azure
                $cnic = str_replace('-', '', $item->cnic);
                $queryCnicString = $queryCnicString . $cnic . ' ';
                $polling_details_ids[] = $item->id;

            }

            //Status 202 is middle state of cron
            PollingDetail::whereIn('id',$polling_details_ids)->update([
                'status'=> 202
            ]);

            $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'top' => 5000, 'search' => $queryCnicString];
            $card1=json_encode($post);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://onecallsearch.search.windows.net/indexes/searchindex/docs/search?api-version=2020-06-30',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $card1,
                CURLOPT_HTTPHEADER => array(
                    'api-key: F25691FB11A563B9E287AE4B78B64A8B',
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response=json_decode($response,true);

            $resp = $response['value'];


            $phone_found = [];
            $phone_not_found = [];

            foreach ($polling_details as $item) {
                $cnic = str_replace('-', '', $item->cnic);
                $resp_phone = $this->search_by_cnic($resp, $cnic);

                if ($resp_phone != null) {
                    $_voterPhone = array_column($resp_phone, 'phone1');
                    $phone_found[] = $item->id;
                    $voter_phone = VoterPhone::where('polling_detail_id', $item->id)->first();
                    if (!$voter_phone) {
                        $voter_phone = new VoterPhone();
                    }

                    $voter_phone->phone = implode(',' , $_voterPhone);
                    $voter_phone->meta = json_encode($resp_phone);
                    $voter_phone->polling_detail_id = $item->id;
                    $voter_phone->state = 1;
                    $voter_phone->save();

                }else{
                    $phone_not_found[] = $item->id;
                }
            }

            PollingDetail::whereIn('id',$phone_found)->update([
                'status'=> 3
            ]);

            PollingDetail::whereIn('id',$phone_not_found)->update([
                'status'=> 2
            ]);
        }

        unset($polling_details);
        unset($queryCnicString);
        unset($response);
        unset($phone_found);
        unset($phone_not_found);

        return true;
    }


    //Get phone numbers of voter from Lambda S3 against there cnic
    public function getPhoneNumber_lambda()
    {
        $queryCnicString = '';

        $polling_details = PollingDetail::where('cnic', '!=', null)
            ->whereIn('status' , [0 , 1])
            ->select('id', 'cnic', 'status')
            ->orderBy('id' , 'desc' )
            ->take(100)
            ->get();

        $polling_details_ids =[] ;

        if($polling_details){


            foreach ($polling_details as $item) {
                //Making CNIC string to get phone from Azure
                $cnic = str_replace('-', '', $item->cnic);
                $queryCnicString = $queryCnicString . $cnic . '%20';
                $polling_details_ids[] = $item->id;

            }
//dd($polling_details_ids);
//            Status 202 is middle state of cron
            PollingDetail::whereIn('id',$polling_details_ids)->update([
                'status'=> 202
            ]);

            $url ="https://ykshsq0e8c.execute-api.eu-west-1.amazonaws.com/testinglambda?number=".$queryCnicString.'&multi=1';

            $result = file_get_contents($url);
            $res = json_decode($result,true);

       //   print_r($res);


            $phone_found = [];
            $phone_not_found = [];


            foreach ($polling_details as $item) {
                $cnic = str_replace('-', '', $item->cnic);

                $resp_phone = $this->search_by_cnic($res, $cnic);

                if ($resp_phone != null) {
                    $_voterPhone = array_column($resp_phone, 'phone1');
                    $phone_found[] = $item->id;
                    $voter_phone = VoterPhone::where('polling_detail_id', $item->id)->first();
                    if (!$voter_phone) {
                        $voter_phone = new VoterPhone();
                    }

                    $voter_phone->phone = implode(',' , $_voterPhone);
                    $voter_phone->meta = json_encode($resp_phone);
                    $voter_phone->polling_detail_id = $item->id;
                    $voter_phone->state = 1;
                    $voter_phone->save();

                }else{
                    $phone_not_found[] = $item->id;
                }
            }

            PollingDetail::whereIn('id',$phone_found)->update([
                'status'=> 3
            ]);

            PollingDetail::whereIn('id',$phone_not_found)->update([
                'status'=> 2
            ]);
        }

        unset($polling_details);
        unset($queryCnicString);
        unset($response);
        unset($phone_found);
        unset($phone_not_found);

        return true;
    }

    //Get phone numbers backup method for details which get stuck in middle state due to some reason
    public function getPhoneNumberBackUp_api()
    {
        $queryCnicString = '';
        $polling_details = PollingDetail::where('cnic', '!=', null)
            ->where('status' , 202)
            ->select('id', 'cnic', 'status')
            ->orderBy('id' , 'desc' )
            ->whereDate('created_at','>',Carbon::yesterday())
            ->take(50)
            ->get();



        $polling_details_ids =[] ;

        if($polling_details){


            foreach ($polling_details as $item) {
                //Making CNIC string to get phone from Azure
                $cnic = str_replace('-', '', $item->cnic);
                $queryCnicString = $queryCnicString . $cnic . '%20';
                $polling_details_ids[] = $item->id;

            }
 //            Status 202 is middle state of cron
            PollingDetail::whereIn('id',$polling_details_ids)->update([
                'status'=> 203
            ]);

            $url ="https://ykshsq0e8c.execute-api.eu-west-1.amazonaws.com/testinglambda?number=".$queryCnicString.'&multi=1';

            $result = file_get_contents($url);
            $res = json_decode($result,true);
//dd($res);
            //print_r($res);


            $phone_found = [];
            $phone_not_found = [];


            foreach ($polling_details as $item) {
                $cnic = str_replace('-', '', $item->cnic);

                $resp_phone = $this->search_by_cnic($res, $cnic);

                if ($resp_phone != null) {
                    $_voterPhone = array_column($resp_phone, 'phone1');
                    $phone_found[] = $item->id;
                    $voter_phone = VoterPhone::where('polling_detail_id', $item->id)->first();
                    if (!$voter_phone) {
                        $voter_phone = new VoterPhone();
                    }

                    $voter_phone->phone = implode(',' , $_voterPhone);
                    $voter_phone->meta = json_encode($resp_phone);
                    $voter_phone->polling_detail_id = $item->id;
                    $voter_phone->state = 1;
                    $voter_phone->save();

                }else{
                    $phone_not_found[] = $item->id;
                }
            }

            PollingDetail::whereIn('id',$phone_found)->update([
                'status'=> 3
            ]);

            PollingDetail::whereIn('id',$phone_not_found)->update([
                'status'=> 2
            ]);
        }

        unset($polling_details);
        unset($queryCnicString);
        unset($response);
        unset($phone_found);
        unset($phone_not_found);

        return true;
    }

    //search for phone number in response against CNIC
    public  function search_by_cnic($response, $search_string)
    {
        $phone_numbers = [];
        foreach ($response as $key => $res) {
            if(count($phone_numbers) > 5){
                break;
            }
            if ($res['idcard'] == $search_string) {
                $phone_numbers[] = $res;
            }
        }
        return $phone_numbers;
    }

    //Convert images to base 64 and save in polling_details_images table in DB
    public function convertBase64_api(){

        $voters = PollingDetail::where('pic_slice', '!=' , null)
            ->where('crop_settings' , '!=' , null)
            ->where('type' , '!=' , null)
            ->where('cron' , 0)
            ->whereDoesntHave('polling_details_images')
            ->select('id', 'url', 'url_id', 'pic_slice', 'cnic', 'crop_settings', 'polygon', 'type')
            ->take(20)
            ->get();

        $voters_ids = $voters->pluck('id');

        PollingDetail::whereIn('id' , $voters_ids)->update(['cron' => 2]);
        $exc_count=0;
        foreach ($voters as $key => $voter){
            try {
                if($voter->type == 'cld')
                {
                    $img_address="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_"
                        .json_decode($voter->crop_settings , true)['h'].",w_900,x_340,y_".
                        json_decode($voter->crop_settings , true)['y']."/".urlencode(@$voter->url);

                    $img_name="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_"
                        .json_decode($voter->crop_settings , true)['h'].",w_900,x_1800,y_".
                        json_decode($voter->crop_settings , true)['y']."/".urlencode(@$voter->url);
                }
                else if($voter->type == 'textract')
                {
                    $img_name="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_"
                        .json_decode($voter->crop_settings , true)['h'].",w_0.24435,x_0.58,y_".
                        json_decode($voter->crop_settings , true)['y']."/".urlencode(@$voter->url);

                    $img_address="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_"
                        .json_decode($voter->crop_settings , true)['h'].",w_0.32435,x_0.07,y_".
                        json_decode($voter->crop_settings , true)['y']."/".urlencode(@$voter->url);
                }

                $name_pic_data = file_get_contents($img_name);
                $address_pic_data = file_get_contents($img_address);
                $encode_name_pic = base64_encode($name_pic_data);
                $encode_address_pic = base64_encode($address_pic_data);
            }
            catch (Exception $e){
                continue;
            }

            $check_existance = DB::table('polling_details_images')
                ->where('polling_detail_id' , $voter->id)
                ->select('id' , 'polling_detail_id')->first() ;

            if($check_existance){

                DB::table('polling_details_images')->where('id',$check_existance->id)->update([
                    'polling_detail_id' => $voter->id,
                    'name_pic' => $encode_name_pic,
                    'address_pic' => $encode_address_pic,
                    'name_url' => $img_name,
                    'address_url' => $img_address,
                    'firebase_url' => $voter->url,
                    'url_id' => $voter->url_id
                ]);

            }
            else{
                DB::table('polling_details_images')->insert([
                    'polling_detail_id' => $voter->id,
                    'name_pic' => $encode_name_pic,
                    'address_pic' => $encode_address_pic,
                    'name_url' => $img_name,
                    'address_url' => $img_address,
                    'firebase_url' => $voter->url,
                    'url_id' => $voter->url_id
                ]);

                $exc_count++;

            }

            unset($img_address);
            unset($img_name);
            unset($name_pic_data);
            unset($address_pic_data);
            unset($encode_name_pic);
            unset($encode_address_pic);

        }

        PollingDetail::whereIn('id' , $voters_ids)->update([
            'cron' => 1
        ]);

        unset($voters);
        unset($voters_ids);

        return true;

    }

    //Genrate Block Code Report With respect To Election Sector

    public function getBlockcodeReport_api()
    {
//        $election_sector = ElectionSector::where('response' , null)
//            ->where('sector' , 'NA-133')
//            ->first();
//
//
//
//            $election_sector->response=205;
//            $election_sector->save();




        $firebase_urls = FirebaseUrl::select('id','image_url','cron','status')->where('import_ps_number' , 259120102)
            ->withcount('polling_details')
            ->with('polling_details')

            ->get();



        dd($firebase_urls);

        $page_24 = 0;
        $page_28 = 0;
        $other = 0;
        $male = 0;
        $female = 0;
        $invalid_pages = 0;
        $duplicate_pages = 0;
        $missing_blockcode_pages = 0;

        foreach ($firebase_urls as $key => $item){
            if($item->polling_details_count == 28){
                $page_28 ++ ;
            }else if($item->polling_details_count == 24){
                $page_24 ++;
            }else{
                $other ++;
            }

            if($item->staus === 3 || $item->staus === 500 || $item->staus === 404){
                $invalid_pages++;
            }

            if($item->staus === 4){
                $duplicate_pages++;
            }

            if($item->staus === 5){
                $missing_blockcode_pages++;
            }

            foreach($item->polling_details as $value){
                if($value->gender === 'male'){
                    $male++;
                }else{
                    $female++;
                }
            }
        }

        $response = [
            'page_24' => $page_24,
            'page_28' => $page_28,
            'other' => $other,
            'male' => $male,
            'female' => $female,
            'invalid_pages' => $invalid_pages,
            'duplicate_pages' => $duplicate_pages,
            'missing_blockcode_pages' => $missing_blockcode_pages,
        ];

        $election_sector->response = json_encode($response);


        $election_sector->update();

//        dd($response);

//        $dis = $this->discrip($election_sector->block_code);

        return true;

    }


    public function discrip($blockcode)
    {
        $mpolling_details = PollingDetail::where('polling_station_number', $blockcode)
            ->where('gender', '=', "male")
//            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
//            ->take(50)
//            ->with('voter_phone')
            ->get()
            ->groupBy('serial_no');

        $fpolling_details = PollingDetail::where('polling_station_number', $blockcode)
            ->where('gender', '=', "female")
//            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
//            ->take(50)
//            ->with('voter_phone')
            ->get()
            ->groupBy('serial_no');

        $pre_key=0;
        $pre_data=0;
        $serial_break_range = [];
        $duplicate = 0;

        $fpre_key=0;
        $fpre_data=0;
        $fserial_break_range = [];
        $fduplicate = 0;





        foreach ($mpolling_details as $key=> $detail)
        {

            foreach ($detail as $key1=> $line)
            {
                if($pre_key !=0 && $pre_key != ($key-1) )
                {

                    $serial_break_range[]=['start'=> $pre_key,'end'=>$key,'difference'=> ($key-$pre_key-1) ,'pre_data'=> $pre_data,'data'=> $line,'gender'=>'male'];
//                    dd($pre_key,$serial_break_range,$mpolling_details);
                }
                else if( count($detail) > 1)
                {
                    $duplicate=$duplicate+count($detail);
                }
            }

            $pre_key=$key;
            $pre_data=$detail;

        }


        foreach ($fpolling_details as $key=> $detail)
        {

            foreach ($detail as $key1=> $line)
            {
                if($fpre_key !=0 && $fpre_key != ($key-1) )
                {

                    $fserial_break_range[]=['start'=> $fpre_key,'end'=>$key,'difference'=> ($key-$fpre_key-1) ,'pre_data'=> $fpre_data,'data'=> $line,'gender'=>'female'];
//                    dd($pre_key,$serial_break_range,$mpolling_details);
                }
                else if( count($detail) > 1)
                {
                    $duplicate=$duplicate+count($detail);
                }
            }

            $fpre_key=$key;
            $fpre_data=$detail;

        }


        $data=[];

        $data['male_duplicate']=$duplicate;
        $data['male_serial_break']=$serial_break_range;
        $data['femail_serial_break']=$fserial_break_range;
        $data['female_duplicate']=$fduplicate;



        return $data;






    }
}
