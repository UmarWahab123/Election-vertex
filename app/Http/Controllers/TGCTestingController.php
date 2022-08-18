<?php

namespace App\Http\Controllers;

use Aws\Textract\TextractClient;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Google\Cloud\Vision\Image;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\VisionClient;
use Illuminate\Http\Request;
use App\Models\CurlSwitch;
use App\Models\FirebaseUrl;
use App\Models\PollingStation;
use App\Models\PollingDetail;
use DB;

class TGCTestingController extends Controller
{
    public function cloudinaryOCR_api()
    {
//        $lambdaSwitch = CurlSwitch::where('name' , 'cloudinery_lambda')->first();
//        if($lambdaSwitch->status == 1){
//            dd('call lambda function, cURL');
//        }
//        else {

            ini_set("memory_limit", -1);
            set_time_limit(-1);

            $config = new Configuration();
            $config->cloud->cloudName = 'one-call-app';
            $config->cloud->apiKey = '231534778562361';
            $config->cloud->apiSecret = 'V8j97Iq0SWLKCMpNg7gqUnYHxWo';
            $config->url->secure = true;
            $cloudinary = new Cloudinary($config);

            $invalid_urls = FirebaseUrl::where('cron', 1)
                ->where('status', 3)
                ->where('created_at','>','2021-12-10')
//                ->where('id',136)
                ->select('id', 'image_url', 'import_ps_number')
                ->take(5)
                ->get();

//            dd($invalid_urls);

            if ($invalid_urls) {

                $urls_ids = $invalid_urls->pluck('id');

//                status 19 is a middle state which get updated after process is completed
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
//dd($blocks);
                    $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/'; // Cnic dashes pattren
                    $cnic_pattern_number = '/^\d{13,13}$/';  //cnic pattern

                    $save_check = 0;
//                    dd($url);
                    if ($blocks) {
                        $meta = json_encode($blocks);
                        $polling_station_id = $this->getPollingStationId($ps_number, $meta, $url_id);

                        foreach ($blocks as $key => $value) {
                            if (isset($value['description']) && $value['description'] && $key != 0) {
                                $text = $value['description'];

                                if (preg_match_all($cnic_pattern, $text, $matches)) {
//                                    dd($text);
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
                                            'type' => 'cld_excel',
                                        ]);

                                    }

                                    $save_check++;

                                }
                                elseif (preg_match_all($cnic_pattern_number, $text, $matches)) {

                                    $first = substr($text, 0, 5);
                                    $middle = substr($text, 5, 7);
                                    $last = substr($text, 12);
                                    $cnic_format = "$first-$middle-$last";
                                    $cnic = $cnic_format;
//                                    dd($cnic);
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
                                                'type' => 'cld_excel',
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
//        }

        return true;
    }

    public function getPollingStationId($polling_station_number, $meta, $url_id)
    {
        FirebaseUrl::firebase_url_log_state('Getting Polling Station', $url_id);
        if ($polling_station_number != null) {
            $polling_station = PollingStation::where('polling_station_number', $polling_station_number)
                ->select('id', 'polling_station_number', 'meta')
                ->first();
//                dd($polling_station);
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

    public function cropImageCloudinary_api(){
        try {
            $polling_detail = PollingDetail::with('firebase_url')
                ->where('pic_slice' , null)
                ->where('polygon', '!=', null)
//                ->whereIn('type' , ['cld_excel' , 'gva'])
                ->select('id', 'url', 'pic_slice', 'polygon', 'crop_settings')
                ->orderBy('id' , 'asc')
                ->take(1000)
                ->get()
                ->groupBy('url_id');
            dd($polling_detail);

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

                        $cloudinary_url = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_' . ($h) . ',w_10000,x_0,y_' . ($y_axis) . '/' . $data;

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

    public function assignGender_api(){
        $male = [];
        $female = [];
        $polling_details = PollingDetail::where('gender' , null)
            ->select('id', 'cnic', 'gender')
            ->orderBy('id' , 'desc')
            ->take(1000)
            ->get();
//dd($polling_details);
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

    public function googleVisionOCR_api()
    {
        $invalid_urls = FirebaseUrl::where('cron' , 1)
            ->where('status' , 3)
            ->where('created_at' ,'>', '2021-12-10' )
            ->select('id', 'image_url', 'import_ps_number')
            ->orderBy('id', 'desc')
            ->take(1)
            ->get();
//dd($invalid_urls);

        if($invalid_urls){
            $urls_ids = $invalid_urls->pluck('id');

//            status 401 is a middle state which get updated after process is completed
            FirebaseUrl::whereIn('id' , $urls_ids)->update([
                'status' => 401,
                'log_state' => 'GVA Cron in process'
            ]);

            foreach ($invalid_urls as $k => $url){

                $url_id = $url->id;
                $url_img = $url->image_url;
                $ps_number = $url->import_ps_number;    //polling_station_number
                $res = $this->findCoordsOfTextFromImage($url_img);
dd($res);
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
//            dd($response);
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
//dd($response);
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


    public function textract_multiple_url()
    {
        ini_set("memory_limit", -1);
        set_time_limit(-1);

        $urls = FirebaseUrl::
        where('status', '0')
            ->where('cron', '0')
//            ->where('id', '58')
            ->where('import_ps_number',37130901)
//            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
//        dd($urls);
        foreach ($urls as $key => $value) {
            DB::table('firebase_urls')->where('id', $value->id)->update(['cron' => '307']);
        }

        foreach ($urls as $key => $value) {
            $status = $this->auto_textract($value->image_url, $value->id);


            if ($status == true) {
                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '40', 'cron' => '1']);
            } else if ($status == 'no_cnic') {
                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '309', 'cron' => '1']);
            } else if ($status == 'invalid_page') {
                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '313', 'cron' => '1']);
            } else if ($status == 'duplicate_page') {
                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '4', 'cron' => '1']);
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

    public function auto_textract($url, $url_id)
    {
        $response = $this->image_textract($url, $url_id);

        $cnic = $response['cnic'];
        $polling_station_number =  $response['polling_station_number'];
        $page_number =  $response['page_number'];
        $meta = $response['meta'];

        if ((count($cnic) != 0)) {
            $polling_station_id = $this->getPollingStationId($polling_station_number, $meta, $url_id);
            if ($polling_station_id != null) {
                $status = $this->save_polling_details($polling_station_id, $polling_station_number, $cnic, $url, $url_id);

                $cnic = array();
                $polling_station_number = '';
                $page_number = '';

                if ($status == true) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $status = $this->save_polling_details(null, null, $cnic, $url, $url_id);
                $cnic = array();
                $polling_station_number = '';
                $page_number = '';
                return true;
            }
        } else {

            return 'invalid_page';
        }
    }

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
//dd($blocks);
        $cnic = [];
        $temp_cnic = [];
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
//                dd($blockType);
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
        $response = ['cnic' => $cnic, 'polling_station_number' => $polling_station_number, 'meta' => $meta,'page_number'=>$page_number];
//        dd($response);

        FirebaseUrl::firebase_url_log_state('Sending Filtered Data', $url_id);

        return $response;
    }
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


    public function cropImageTextract_api()
    {
        try {
            $polling_detail = PollingDetail::with('firebase_url')
//                ->where('pic_slice' , null)
                ->where('polygon', '!=', null)
//                ->where('type', 'textract')
//                ->select('id', 'url', 'pic_slice', 'polygon', 'crop_settings')
//                ->where('url_id' , 58)
                ->take(1)
                ->get()
                ->groupBy('url_id');
dd($polling_detail);
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

                        $cloudinary_url = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_' . ($h) . ',w_20,x_0,y_' . ($y_axis) . '/' . $data;



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


}
