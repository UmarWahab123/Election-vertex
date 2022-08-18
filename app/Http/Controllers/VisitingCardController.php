<?php

namespace App\Http\Controllers;

use App\Models\ElectionSector;
use App\Models\FirebaseUrl;
use App\Models\PollingDetail;
use App\Models\PollingStation;
use App\Models\UrlUploadLog;
use App\Models\VisitingCard;
use App\Models\VisitingCardImage;
use App\Models\VoterPhone;
use App\Models\BusinessAccount;
use App\Models\OfflineDataFile;
use Illuminate\Http\Request;
use Aws\Textract\TextractClient;
use DB;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\VisionClient;
use Google\Cloud\Vision\Image;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Cloudinary;
use Auth;
use Brackets\AdminAuth\Models\AdminUser;


putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/one-call-59851-40f314185b47.json');

class VisitingCardController extends Controller
{
    //    public function __construct()
    //    {
    //        $this->user = Auth::user();
    //        dd(Auth::user());
    //        if (Auth::user()->forbidden == 0) {
    //            abort(404);
    //        }
    //    }

    public function getCardDetails()
    {
        $card_details = VisitingCard::with('image')->get();
        return response(['data' => $card_details]);
    }

    public function saveCardDetails(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        //        $phone = preg_replace("/[^0-9]/", "", $request->phone );
        //        $new_phone = substr($phone, -10);
        //        $status = file_get_contents('https://onecall.plabesk.com/api/broadcast/marker-number/'.$new_phone);
        //        if($status == 0){
        $phone = VisitingCard::where('phone', $request->phone)->first();
        if (!$phone) {
            $visitingCard = new VisitingCard();
            $visitingCard->name = $request->bussiness;
            $visitingCard->user_id = $request->user_id;
            $visitingCard->user_type = $request->user_type;
            $visitingCard->address = $request->address;
            $visitingCard->phone = $request->phone;
            $visitingCard->meta = $request->meta;
            $visitingCard->save();

            $visiting_card_id = $visitingCard->id;

            $visitingCardImage = new VisitingCardImage();
            $visitingCardImage->visiting_card_id = $visiting_card_id;
            $visitingCardImage->image_link = $request->image_link;
            $visitingCardImage->save();

            return response(['message' => 'Saved!', 'success' => true]);
        } else {
            return response(['message' => 'Already Exist!', 'success' => false]);
        }
        //        }else{
        //            return response(['message' => 'Already Exist!' , 'success' => false]);
        //        }


    }

    public function visiting_card_textract()
    {
        header("Access-Control-Allow-Origin: *");
        $image = file_get_contents($_GET['img']);

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
            'FeatureTypes' => ['FORMS', 'TABLES'],
        ];

        $response = array();

        $result = $client->analyzeDocument($options);

        $blocks = $result['Blocks'];
        foreach ($blocks as $key => $value) {
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if (isset($value['Text']) && $value['Text']) {
                    $text = $value['Text'];
                    if ($blockType == 'LINE' && (strlen($text) > 2)) {
                        $response[] = $text;
                        // echo print_r($text, true) . "</br>";
                    }
                }
            }
        }
        return json_encode($response);
    }

    public function get_cards_per_user($user_id)
    {
        //        header("Access-Control-Allow-Origin: *");
        $cards = VisitingCard::where('user_id', $user_id)->with('image')->get();
        return $cards;
    }

    public function stop_cron()
    {
        return 1;
    }


    //Firebase urls to Database

    public function save_firebase_url($url, $url_upload_log_id)
    {
        $flag = FirebaseUrl::where('image_url', $url)
            ->select('id', 'image_url')
            ->first();

        if (!$flag) {
            $temp = explode('%2F', $url);
            $block_code = $temp[1];
            $url_id = DB::table('firebase_urls')->insertGetId([
                'image_url' => $url, 'import_ps_number' => $block_code, 'url_upload_log_id' => $url_upload_log_id
            ]);
        } else {
            return $url;
        }
        return $url;
    }

    public function check_sector_status(Request $request)
    {
        header("Access-Control-Allow-Origin: *");

        $constituency_status = ElectionSector::where('sector', $request->constituency)
            ->where('block_code', $request->polling)
            ->first();

        if ($constituency_status) {
            return response(['status' => 'already_exist']);
        } else {
            return response(['status' => 'continue_process']);
        }
    }

    public function check_and_save_firebase_url(Request $request)
    {
        $total = $request->male + $request->female;
        ElectionSector::insert([
            'sector' => $request->constituency,
            'user_id' => $request->userId,
            'block_code' => $request->polling,
            'male_vote' => $request->male,
            'female_vote' => $request->female,
            'total_vote' => $total,
            'status' => 'ACTIVE',
        ]);

        $offline_data_file = OfflineDataFile::where('ward' , 'like' , '%'.$request->constituency.'%')->get();

        if(count($offline_data_file) === 0){
            OfflineDataFile::insert([
                'ward' => $request->constituency,
                'cron' => 0,
                'state' => null
            ]);
        }

        $user_id = $request->userId;
        $file_count = $request->filesCount;
        $meta = json_encode($request->data);

        $url_upload_log_id = UrlUploadLog::insertGetId([
            'user_id' => $user_id,
            'files_count' => $file_count,
            'url_meta' => $meta,
        ]);

        $user=AdminUser::where('id',$request->userId)->first();
        $refId=rand(231554,63215465);
        $businessAccount=BusinessAccount::insertGetId([
            'business_id'=>$user->business_id,
            'ref_id'=>$refId,
            'debit'=>0.0051*$file_count,
            'details'=>'Page Upload Cost',
            'meta'=>json_encode(['url_upload_log_id'=>$url_upload_log_id,'block_code'=>$request->polling,'user_id'=>$request->userId]),
        ]);


        $data = [];
        $block_code = $request->polling;
        $urls = $request->data;

        foreach ($urls as $url) {
            $data[] = ['image_url' => $url, 'import_ps_number' => $block_code, 'url_upload_log_id' => $url_upload_log_id];
        }

        FirebaseUrl::insert($data);

        unset($data);
        unset($urls);

        return true;
    }


    //Textract

    public function textract_multiple_url()
    {
        $urls = FirebaseUrl::where('status', '0')
            ->where('cron', '0')
            ->select('id', 'image_url', 'status', 'cron')
            ->take(10)
            ->get();

        $urls_ids = $urls->pluck('id');
        FirebaseUrl::whereIn('id', $urls_ids)->update([
            'cron' => '2',
            'log_state' => 'Coron in process'
        ]);

        foreach ($urls as $key => $value) {
            FirebaseUrl::firebase_url_log_state('Before Auto Textract', $value->id);
            $status = $this->auto_textract($value->image_url, $value->id);
            FirebaseUrl::firebase_url_log_state('Finish | ' . $status, $value->id);

            if ($status == 'complete') {
                $value->update(['status' => '1', 'cron' => '1']);
            } else if ($status == 'invalid_page') {
                $value->update(['status' => '3', 'cron' => '1']);
            } else if ($status == 'duplicate') {
                $value->update(['status' => '4', 'cron' => '1']);
            } else if ($status == 'without_block_code') {
                $value->update(['status' => '5', 'cron' => '1']);
            } else if ($status == 'already_extract') {
                $value->update(['status' => '6', 'cron' => '1']);
            }
        }

        return true;
    }

    public function auto_textract_cloudinery(Request $request)
    {
        $url = $request->img_url;
        $url_id = $request->url_id;
        FirebaseUrl::where('id', $url_id)->update([
            'image_url' => $url
        ]);

        $status = $this->auto_textract($url, $url_id);
        if ($status == 'complete') {
            FirebaseUrl::where('id', $url_id)->update(['status' => '1', 'cron' => '1']);
        } else if ($status == 'no_cnic') {
            DB::table('firebase_urls')->where('id', $url_id)->update(['status' => '2', 'cron' => '1']);
        } else if ($status == 'invalid_page') {
            DB::table('firebase_urls')->where('id', $url_id)->update(['status' => '3', 'cron' => '1']);
        } else if ($status == 'duplicate') {
            DB::table('firebase_urls')->where('id', $url_id)->update(['status' => '4', 'cron' => '1']);
        } else if ($status == 'without_block_code') {
            DB::table('firebase_urls')->where('id', $url_id)->update(['status' => '5', 'cron' => '1']);
        } else if ($status == 'already_extract') {
            DB::table('firebase_urls')->where('id', $url_id)->update(['status' => '6', 'cron' => '1']);
        }

        FirebaseUrl::firebase_url_log_state('from_cloudinery_url', $url_id);

        return response(['message' => 'saved', 'status' => $status]);
    }

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
            if ($polling_station_id) {
                $status = $this->save_polling_details($polling_station_id, $polling_station_number, $cnic, $url, $url_id);
            } else {
                $status = $this->save_polling_details(null, null, $cnic, $url, $url_id);
                if ($status == 'detail_saved') {
                    $status = 'saved_without_block_code';
                }
            }

            unset($cnic);
            unset($polling_station_number);
            unset($page_number);

            if ($status == 'detail_saved') {
                return 'complete';
            } else if ($status == 'already_extract') {
                return 'already_extract';
            } else if ($status == 'duplicate') {
                return 'duplicate';
            } else if ($status == 'saved_without_block_code') {
                return 'without_block_code';
            }
        } else {
            return 'invalid_page';
        }
    }

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
                "LanguageCode" => "ur",
                "LanguageCode" => "ar"
            ]
        ];

        FirebaseUrl::firebase_url_log_state('Before Analyze Image', $url_id);

        $result = $client->analyzeDocument($options);

        FirebaseUrl::firebase_url_log_state('After Analyze Image', $url_id);

        $blocks = $result['Blocks'];

        return $blocks;
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
                    'url_id' => $url_id
                ]);
                FirebaseUrl::firebase_url_log_state('Single Polling Detail Saved', $url_id);
                $state = 'detail_saved';    //detail saved successfully
            } else if ($polling_detail->url_id == $url_id) {
                $state =  'already_extract';    //this page is already extracted
            } else if ($polling_detail->url_id != $url_id) {
                $state = 'duplicate';   //this page is uploaded more than one time
            }
        }
        FirebaseUrl::firebase_url_log_state('Saving Extra Details', $url_id);

        //Saving serial and family number against each cnic record
        $this->save_extra_details($url_id, $cnic);

        return $state;
    }

    public function getPollingStationId($polling_station_number, $meta, $url_id)
    {
        FirebaseUrl::firebase_url_log_state('Getting Polling Station', $url_id);
        if ($polling_station_number != null) {
            $polling_station = PollingStation::where('polling_station_number', $polling_station_number)
                ->select('id', 'polling_station_number')
                ->first();
            if ($polling_station) {
                $polling_station_id = $polling_station->id;
            } else {
                $new_polling_station = PollingStation::insertGetId([
                    'polling_station_number' => $polling_station_number,
                    'meta' => $meta,
                    'url_id' => $url_id
                ]);
                $polling_station_id = $new_polling_station;
            }
        } else {
            $polling_station_id = null;
        }
        FirebaseUrl::firebase_url_log_state('Returning Polling Station', $url_id);

        return $polling_station_id;
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

        $cnic = [];
        $polling_station_number = '';
        $page_number = '';
        $response = [];

        $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';
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

                    FirebaseUrl::firebase_url_log_state('Polling Number Check', $url_id);
                    if ($blockType == 'LINE' &&  preg_match_all($ps_number_pattern, $text, $matches) && ($value['Confidence'] > 85)) {
                        $polling_station_number = $text;
                    } else {
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

    public function save_extra_details($url_id, $cnic)
    {

        $f_url = FirebaseUrl::where('id', $url_id)
            ->select('id', 'link_meta')
            ->first();
        $old_blocks = json_decode($f_url->link_meta, true);
        $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';
        $blocks = [];
        $blocks = $this->filterBlocks($old_blocks, 90);
        $_cnic = array_column($cnic, 'cnic');
        foreach ($blocks as $key => $value) {
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if (isset($value['Text']) && $value['Text']) {
                    $text = $value['Text'];
                    if ($blockType == 'LINE' && preg_match_all($cnic_pattern, $text, $matches)) {
                        if (@$blocks[$key + 4]) {
                            if (preg_match_all($cnic_pattern, $blocks[$key + 4]['Text'], $matches)) {

                                foreach ($_cnic as $c) {
                                    if ($c == $text) {
                                        $cnic_to_update = $c;
                                        break;
                                    }
                                }

                                if ($cnic_to_update) {
                                    if (@$blocks[$key + 2] && is_numeric($blocks[$key + 2]['Text'])) {
                                        $s_no = $blocks[$key + 2]['Text'];
                                    } else {
                                        $s_no = null;
                                    }

                                    if (@$blocks[$key + 1] && is_numeric($blocks[$key + 1]['Text'])) {
                                        $f_no = $blocks[$key + 1]['Text'];
                                    } else {
                                        $f_no = null;
                                    }

                                    if (@$blocks[$key - 1] && is_numeric($blocks[$key - 1]['Text'])) {
                                        $age = $blocks[$key - 1]['Text'];
                                    } else {
                                        $age = null;
                                    }

                                    //    dd($s_no , $f_no , $text , $age);

                                    PollingDetail::where('cnic', $text)->update([
                                        'age' => $age,
                                        'serial_no' => $s_no,
                                        'family_no' => $f_no
                                    ]);
                                }
                            }
                        } elseif (@$blocks[$key - 4]) {
                            if (preg_match_all($cnic_pattern, $blocks[$key - 4]['Text'], $matches)) {

                                foreach ($_cnic as $c) {
                                    if ($c == $text) {
                                        $cnic_to_update = $c;
                                        break;
                                    }
                                }

                                if ($cnic_to_update) {
                                    if (@$blocks[$key + 2] && is_numeric($blocks[$key + 2]['Text'])) {
                                        $s_no = $blocks[$key + 2]['Text'];
                                    } else {
                                        $s_no = null;
                                    }

                                    if (@$blocks[$key + 1] && is_numeric($blocks[$key + 1]['Text'])) {
                                        $f_no = $blocks[$key + 1]['Text'];
                                    } else {
                                        $f_no = null;
                                    }

                                    if (@$blocks[$key - 1] && is_numeric($blocks[$key - 1]['Text'])) {
                                        $age = $blocks[$key - 1]['Text'];
                                    } else {
                                        $age = null;
                                    }

                                    PollingDetail::where('cnic', $text)->update([
                                        'age' => $age,
                                        'serial_no' => $s_no,
                                        'family_no' => $f_no
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        unset($f_url);
        unset($blocks);
        unset($cnic_pattern);
        unset($blocks);

        return true;
    }



    public function filterBlocksTextract($blocks, $confidence)
    {
        $new_blocks = [];
        foreach ($blocks as $key => $value) {
            if (isset($value->BlockType) && $value->BlockType &&  $value->BlockType == 'LINE') {
                $blockType = $value->BlockType;
                if ($blockType == 'LINE' && ($value->Confidence > $confidence)) {
                    $new_blocks[] = $value;
                }
            }
        }
        return $new_blocks;
    }

    public function assignGender()
    {
        ini_set('max_execution_time', '0');
        ini_set("memory_limit", "-1");
        $gender = 'undefined';
        $male = [];
        $female = [];
        $polling_details = PollingDetail::where('gender', 'undefined')
            ->take(2000)
            ->get();

        if ($polling_details) {
            foreach ($polling_details as $key => $item) {

                $break_cnic = explode('-', $item->cnic);
                if (@$break_cnic[2]) {
                    $last_digit = $break_cnic[2];
                } else {
                    $last_digit = substr(trim(@$item->cnic), -1);
                }
                $last_digit = (int) $last_digit;

                if ($last_digit % 2 !== 0) {
                    $gender = 'male';
                    $male[] = $item->id;
                } else {
                    $gender = 'female';
                    $female[] = $item->id;
                }
            }
            PollingDetail::whereIn('id', $male)->update(['gender' => 'male']);
            PollingDetail::whereIn('id', $female)->update(['gender' => 'female']);
        }

        return true;
    }

    public function getFamilyAndSerialNo()
    {

        dd('hi');
        $output_new = [];
        $textAnnotations = [];
        $output = [];

        $polling_details =  PollingDetail::where(function ($q) {
            $q->where('serial_no', '!=', null)->orWhere('family_no', '!=', null);
        })
            ->select('id', 'cnic', 'pic_slice', 'url', 'crop_settings', 'type', 'serial_no', 'family_no')
            ->orderBy('id', 'desc')
            ->take(100)
            ->get();

        foreach ($polling_details as $k => $detail) {
            $y = json_decode($detail->crop_settings, true)['y'];
            if ($detail->type == 'textract') {
                $path = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_0.035326910972595,w_0.15,x_0.78,y_' . $y . '/' . urlencode($detail->url);
            } else {
                $path = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_127,w_500,x_2600,y_' . $y . '/' . urlencode($detail->url);
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

                if ($response->info() != []) {
                    $info = (object)$response->info();
                    $textAnnotations = (array)$info->textAnnotations;
                    if (count($textAnnotations) < 3) {
                        $row_data = $this->getFamilyAndSerialNoTextract($imageData, $detail);
                        $output_new[] = $row_data;
                        continue;
                    }
                } else {
                    $row_data = $this->getFamilyAndSerialNoTextract($imageData, $detail);
                    $output_new[] = $row_data;
                    continue;
                }
            } catch (exception $e) {
                echo $e;
            }

            if (count(@$textAnnotations) > 2) {
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
            } else {
                continue;
            }
        }

        if (count($output) > 0) {
            foreach ($output as $i => $i_val) {
                $row_data['cnic'] = @$i_val[0];
                $row_data['s_no'] = null;
                $row_data['f_no'] = null;
                $s_no = str_replace('.', '', trim(@$i_val[1]));
                $temp_s_no = explode(' ', $s_no);
                if (@$temp_s_no[1]) {
                    $s_no = $temp_s_no[1];
                } else {
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
                    } else {
                        $row_data['s_no'] = null;
                        $row_data['f_no'] = null;
                    }
                } else {
                    $row_data['s_no'] = null;
                    $row_data['f_no'] = null;
                }

                $output_new[] = $row_data;
                unset($row_data);
            }
        }

        foreach ($output_new as $index => $res) {
            $f_no = $res['f_no'];
            $s_no = $res['s_no'];
            $voter = PollingDetail::where('cnic', $res['cnic'])
                ->select('family_no', 'id')
                ->first();

            $voter->family_no = $f_no;
            $voter->serial_no = $s_no;
            $voter->update();
        }

        return true;
    }


    //verify Payment From Screenshot

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

    public function verifyPaymentFromScreenshot(Request $request)
    {

        $image = file_get_contents($request->url);
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

        //        $phone_pattern = '/^((\+92)?(0092)?(92)?(0)?)(3)([0-9]{9})$/';
        //        $amount_pattern = '/Rs/';

        $phone = $request->phone;
        $user_phone = $request->user_phone;
        $business_phone = $request->business_phone;
        $amount = $request->amount;

        $phone_read = null;
        $amount_read = null;
        $phone_status = null;

        $blocks = $this->filterBlocks($blocks, 80);

        foreach ($blocks as $key => $value) {
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if (isset($value['Text']) && $value['Text']) {
                    $text = $value['Text'];
                    //                    if($phone_read == null){
                    //                        if ($blockType == 'LINE' && preg_match_all($phone_pattern, $text, $matches)) {
                    //                            $phone_read = $text;
                    //                        }
                    //                    }

                    if ($blockType == 'LINE') {

                        if ($amount_read == null) {
                            if (strpos($text, $amount) !== false) {
                                $amount_read = $amount;
                            }
                        }

                        if ($phone_read == null) {
                            if ($phone != null && strpos($text, $phone) !== false) {

                                $phone_read = $phone;
                                $phone_status = 'matched_with_meta_phone';
                            } else if ($user_phone != null && strpos($text, $user_phone) !== false) {

                                $phone_read = $user_phone;
                                $phone_status = 'matched_with_user_phone';
                            } else if ($business_phone != null && strpos($text, $business_phone) !== false) {

                                $phone_read = $business_phone;
                                $phone_status = 'matched_with_business_phone';
                            }
                        }
                    }

                    //                    if ($blockType == 'LINE' && preg_match_all($amount_pattern, $text, $matches)) {
                    //                        $temp = str_replace('Rs. ' , '' , $text);
                    //                        $amount_read = str_replace('.00' , '' , $temp);
                    //                    }

                }
            }
        }

        if ($amount_read != null && $phone_read != null) {
            //            dd($amount_read , $phone_read , 'true');
            return response(['status' => true, 'phone_status' => $phone_status]);
        } else {
            //            dd($amount_read , $phone_read , 'flase');
            return response(['status' => false, 'phone_status' => null]);
        }
    }



    //cut slice from pictures

    public function cut_slice_from_pic_cron()
    {
        try {

            $polling_detail = PollingDetail::with('firebase_url')
                ->where('pic_slice', null)
                ->where('polygon', '!=', null)
                ->where('type', 'textract')
                //                ->exclude(['polling_station_id', 'polling_station_number', 'gender', 'age', 'family_no', 'serial_no', 'page_no','boundingBox', 'urdu_meta', 'urdu_text', 'first_name', 'last_name', 'address', 'status', 'created_at', 'updated_at', 'check_status', 'cron'])
                ->orderBy('id', 'asc')
                ->take(500)
                ->get()
                ->groupBy('url_id');

            $next_y_axis = 0;
            if ($polling_detail) {
                foreach ($polling_detail as $key => $item) {
                    foreach ($item as $key => $value) {
                        $firebase_url = $value->url;
                        $polygon = json_decode($value->polygon, true);

                        $next_polygon = json_decode(@$item[$key + 1]->polygon, true);
                        $y_axis = $polygon[0]['Y'];

                        if (@$next_polygon[0]['Y'] && ($next_y_axis > $y_axis)) {
                            $next_y_axis = @$next_polygon[0]['Y'];
                            $h = 0.035326910972595;
                        } else if ($y_axis > $next_y_axis) {
                            $h = 0.035326910972595;
                        }

                        $y_axis_thereshold_percent = 1;
                        $percent_value = ($y_axis * $y_axis_thereshold_percent) / 100;
                        $y_axis = $y_axis - $percent_value;
                        $threshold_percent = 25;
                        $addition = ($h * $threshold_percent) / 100;
                        $h = $h + $addition;


                        $data = urlencode($firebase_url);


                        if ($h > 0.25) {
                            $h = 0.035326910972595;
                        }
                        if ($h < 0) {
                            $h = $h * (-1);
                        }
                        $h = 0.035326910972595;

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

            $polling_detail = null;
        } catch (\Exception $e) {
            echo $e;
        }

        return true;
    }

    public function cut_slice_from_pic($blockcode)
    {
        try {

            $polling_detail = PollingDetail::with('firebase_url')
                //                ->exclude(['polling_station_id', 'polling_station_number', 'gender', 'age', 'family_no', 'serial_no', 'page_no','boundingBox', 'urdu_meta', 'urdu_text', 'first_name', 'last_name', 'address', 'status', 'created_at', 'updated_at', 'check_status', 'cron'])
                ->where('polling_station_number', $blockcode)
                ->where('polygon', '!=', null)
                ->where('type', 'textract')
                ->orderBy('id', 'asc')
                ->get()
                ->groupBy('url_id');

            $next_y_axis = 0;
            if ($polling_detail) {
                foreach ($polling_detail as $key => $item) {
                    foreach ($item as $key => $value) {
                        $firebase_url = $value->url;
                        $polygon = json_decode($value->polygon, true);

                        $next_polygon = json_decode(@$item[$key + 1]->polygon, true);
                        $y_axis = $polygon[0]['Y'];

                        if (@$next_polygon[0]['Y'] && ($next_y_axis > $y_axis)) {
                            $next_y_axis = @$next_polygon[0]['Y'];
                            //                        $h = $next_y_axis - $y_axis;
                            $h = 0.035326910972595;
                        } else if ($y_axis > $next_y_axis) {
                            //                         $h =  $y_axis - $next_y_axis;
                            $h = 0.035326910972595;
                        }

                        $y_axis_thereshold_percent = 1;
                        $percent_value = ($y_axis * $y_axis_thereshold_percent) / 100;
                        $y_axis = $y_axis - $percent_value;
                        $threshold_percent = 25;
                        $addition = ($h * $threshold_percent) / 100;
                        $h = $h + $addition;
                        $data = urlencode($firebase_url);
                        if ($h > 0.25) {
                            $h = 0.035326910972595;
                        }
                        if ($h < 0) {
                            $h = $h * (-1);
                        }
                        $h = 0.035326910972595;

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

            $polling_detail = null;
        } catch (\Exception $e) {
            echo $e;
        }

        return true;
    }

    public function cut_slice_of_invalid()
    {
        try {
            $polling_detail = PollingDetail::with('firebase_url')
                //                ->exclude(['polling_station_id', 'polling_station_number', 'gender', 'age', 'family_no', 'serial_no', 'page_no','boundingBox', 'urdu_meta', 'urdu_text', 'first_name', 'last_name', 'address', 'status', 'created_at', 'updated_at', 'check_status', 'cron'])
                ->where('pic_slice', null)
                ->where('polygon', '!=', null)
                ->whereIn('type', ['cld', 'gva'])
                ->orderBy('id', 'asc')
                ->take(1000)
                ->get()
                ->groupBy('url_id');

            $next_y_axis = 0;
            $temp = [];

            if ($polling_detail) {
                foreach ($polling_detail as $k => $item) {
                    foreach ($item as $key => $value) {
                        $recheck = 0;
                        $firebase_url = $value->url;
                        $polygon = json_decode($value->polygon, true);

                        $next_polygon = json_decode(@$item[$key + 1]->polygon, true);
                        $y_axis = $polygon[0]['y'];

                        if (@$next_polygon) {
                            $next_y_axis = $next_polygon[0]['y'];
                        }


                        if (@$next_polygon[0]['y'] && ($next_y_axis > $y_axis)) {
                            $h = $next_y_axis - $y_axis;
                        } else if (@$next_polygon[0]['y'] && ($y_axis > @$next_y_axis)) {
                            $h =  $y_axis - $next_y_axis;
                        } else {
                            $h = 100;
                        }


                        $data = urlencode($firebase_url);
                        $y_axis = $y_axis - 25;

                        if ($h > 140) {
                            $recheck = 1;
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

            $count = count($polling_detail);
            $polling_detail = null;
        } catch (\Exception $e) {
            dd($e);
        }

        return true;
    }


    //Imagick Cropping or picture and save url to DB and Picture to Server

    public function crop_image($url, $y, $cnic, $block_code)
    {
        $y_axis = $y - 0.015;
        $file    = file_get_contents($url);
        $image = new \Imagick();
        $image->readImageBlob($file);
        $threshold = 2000;
        $w = $threshold;
        $x = 0;
        $y = $y_axis * $threshold;
        $image->cropImage(7000, 85, $x, $y);

        $thumbnail = $image->getImageBlob();

        //        echo "<img src='data:image/jpg;base64,".base64_encode($thumbnail)."' />";


        $name   = $cnic . '.jpg';

        if (!is_dir(public_path() . '/images/' . $block_code)) {
            // dir doesn't exist, make it
            mkdir(public_path() . '/images/' . $block_code);
        }

        file_put_contents(public_path() . '/images/' . $block_code . '/' . $name, $image->getImageBlob());

        return asset('/images/' . $block_code . '/' . $name);
    }

    public function crop_and_save_crop_image($blockcode)
    {
        ini_set('max_execution_time', '-1');
        $polling_details = PollingDetail::where('polling_station_number', $blockcode)
            //            ->where('pic_slice' , null)
            ->orderBy('serial_no', 'asc')
            ->get();

        foreach ($polling_details as $key => $value) {
            $polygon = json_decode($value->polygon);
            $y_axis = $polygon[0]->Y;
            $url = $value->url;
            $cnic = $value->cnic;
            $block_code = $value->polling_station_number;
            $image = $this->crop_image($url, $y_axis, $cnic, $block_code);
            //            dd($image);
            $value->pic_slice = $image;
            $value->update();
        }




        return count($polling_details);
    }


    //Cloudinery Invalid page processing

    public function process_invalid_page()
    {
        ini_set("memory_limit", -1);
        set_time_limit(-1);

        $config = new Configuration();
        $config->cloud->cloudName = 'one-call-app';
        $config->cloud->apiKey = '231534778562361';
        $config->cloud->apiSecret = 'V8j97Iq0SWLKCMpNg7gqUnYHxWo';
        $config->url->secure = true;
        $cloudinary = new Cloudinary($config);

        $invalid_images = FirebaseUrl::where('cron', 1)
            ->where('status', 3)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $total = 0;

        if ($invalid_images) {

            foreach ($invalid_images as $k => $item) {

                $item->status = 19;
                $item->log_state = 'invalid process cron';
                $item->update();
                $polling_station = PollingStation::where('polling_station_number', $item->import_ps_number)->first();
                if ($polling_station) {
                    $polling_station_id = $polling_station->id;
                    $polling_station_number = $polling_station->polling_station_number;
                } else {
                    $new_polling_station = DB::table('polling_station')->insertGetId([
                        'polling_station_number' => $item->import_ps_number,
                        'meta' => null,
                        'url_id' => null
                    ]);
                    $polling_station_id = $new_polling_station->id;
                    $polling_station_number = $new_polling_station->polling_station_number;
                }

                $url = $item->image_url;
                $url_id = $item->id;
                $res = $cloudinary->uploadApi()->upload(
                    $url,
                    ["ocr" => "adv_ocr"]
                );
                $blocks = [];
                $blocks = $res['info']['ocr']['adv_ocr']['data'][0]['textAnnotations'];

                $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';
                $data = [];
                $save_check = 0;
                if ($blocks) {
                    foreach ($blocks as $key => $value) {
                        if (isset($value['description']) && $value['description'] && $key != 0) {
                            $text = $value['description'];

                            if (preg_match_all($cnic_pattern, $text, $matches)) {

                                $cnic = $text;
                                $s_no = null;
                                $f_no = null;
                                $age  = null;
                                $boundingPoly = json_encode($value['boundingPoly']['vertices']);

                                $status = PollingDetail::where('cnic', $cnic)->first();

                                if ($status == null) {
                                    PollingDetail::insert([
                                        'polling_station_number' => $polling_station_number,
                                        'polling_station_id' => $polling_station_id,
                                        'cnic' => $cnic,
                                        'age' => $age,
                                        'family_no' => $f_no,
                                        'serial_no' => $s_no,
                                        'url' => $url,
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

        return true;
    }

    //Google Vision API , Urdu text extraction

    public function google_vision_API($blockcode)
    {
        ini_set('max_execution_time', '-1');
        $data = [];
        $queryCnicString = '';
        $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';

        $polling_details = PollingDetail::where('polling_station_number', $blockcode)
            ->where('pic_slice', '!=', null)
            ->where('urdu_meta', null)
            ->orderBy('id', 'asc')
            ->with('voter_phone')
            ->get();


        foreach ($polling_details as $item) {

            //            Geting urdu text from Google Vision
            //            $image = $item->pic_slice;
            //            $response = $this->findCoordsOfTextFromImage($image);
            //            if ($response) {
            //                $item->urdu_meta = $response['meta'];
            //                $item->update();
            //                $data[] = $response['data'];

            //Making CNIC string to get phone from Azure
            $cnic = str_replace('-', '', $item->cnic);
            $queryCnicString = $queryCnicString . $cnic . ' ';

            //                foreach ($response['data'] as $temp){
            //                    preg_match_all($cnic_pattern, $temp, $matches);
            //                    if($matches[0] != [] && $item->cnic == $temp){
            //                        $cnic = $temp;
            //                        $item->urdu_text = $response['data'];
            //                        $item->save();
            //                    }
            //                }
            //            }else{
            //                continue;
            //            }

        }


        $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'top' => 5000, 'search' => $queryCnicString];
        $peram = json_encode($post);
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
            CURLOPT_POSTFIELDS => $peram,
            CURLOPT_HTTPHEADER => array(
                'api-key: F25691FB11A563B9E287AE4B78B64A8B',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        $resp = $response['value'];

        foreach ($polling_details as $item) {
            $cnic = str_replace('-', '', $item->cnic);
            $resp_phone = $this->search_by_cnic($resp, $cnic);
            if ($resp_phone != 0) {

                $voter_phone = VoterPhone::where('polling_detail_id', $item->id)->first();
                if (!$voter_phone) {
                    $voter_phone = new VoterPhone();
                }

                $voter_phone->phone = $resp_phone['phone1'];
                $voter_phone->meta = json_encode($resp_phone);
                $voter_phone->polling_detail_id = $item->id;
                $voter_phone->state = 1;
                $voter_phone->save();
            }
        }

        //        $polling_details = PollingDetail::where('polling_station_number', $blockcode)
        //            ->with('voter_phone')
        //            ->get();

        //        $this->fill_number($polling_details);

        return count($polling_details);
    }

    public function findCoordsOfTextFromImage($image_link)
    {
        $path = $image_link;

        try {
            $projectId = 'nlpapiupwork';
            $imageAnnotator = new ImageAnnotatorClient([
                'projectId' => $projectId,
                //'keyFilePath' => $credPath
            ]);
            $vision = new VisionClient([
                'projectId' => $projectId,
                //'keyFilePath' => $credPath
            ]);

            $imageData = file_get_contents($path);
            $image = new Image($imageData, [
                'TEXT_DETECTION',
                // 'LABEL_DETECTION',
                // 'DOCUMENT_TEXT_DETECTION'
            ]);

            $response = (object)$vision->annotate($image);
            if ($response->info() != []) {
                $info = (object)$response->info();
                $textAnnotations = (array)$info->textAnnotations;
            } else {
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

    public  function search_by_cnic($response, $search_string)
    {
        $phone_numbers = [];
        foreach ($response as $key => $res) {
            if (count($phone_numbers) > 5) {
                break;
            }
            if ($res['idcard'] == $search_string) {
                $phone_numbers[] = $res;
            }
        }
        return $phone_numbers;
    }

    public  function fill_number($polling_details)
    {
        $family_no = 0;
        $phone = 0;
        $meta = '';

        foreach ($polling_details as $key =>  $detail) {

            if ($detail->voter_phone != null) {
                $family_no = $detail->family_no;
                $phone = $detail->voter_phone->phone;
                $meta = $detail->voter_phone->meta;
            } else if ($detail->family_no == $family_no) {

                $polling_data =  PollingDetail::where("polling_station_number", $detail->polling_station_number)
                    ->where("family_no", $detail->family_no)
                    ->with("voter_phone")
                    ->whereDoesntHave('voter_phone')
                    ->get();

                foreach ($polling_data as $row => $item) {


                    $data = VoterPhone::where('polling_detail_id', $item->id)->first();

                    if (!$data) {
                        $data = new VoterPhone();
                    }

                    $data->phone = $phone;
                    $data->meta = $meta;
                    $data->polling_detail_id = $item->id;
                    $data->state = 0;

                    $data->save();
                }

                $family_no = 0;
                $phone = 0;
                $meta = '';
            }
        }

        return true;
    }

    public function process_invalid_page_404()
    {
        $invalid_images = FirebaseUrl::where('cron', 1)
            ->where('status', 404)
            ->paginate(5);

        foreach ($invalid_images as $k => $item) {

            $item->status = 401;
            $item->log_state = '401';
            $item->update();
            $blockcode = PollingStation::where('polling_station_number', $item->import_ps_number)->first();

            $url = $item->image_url;
            $url_id = $item->id;
            $res = $this->findCoordsOfTextFromImage($url);

            $blocks = [];

            if (@$res->orignal) {
                $blocks = $res->orignal;
            } else {
                $item->status = 500;
                $item->log_state = 'rejected | vision_api';
                $item->update();
                continue;
            }

            $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';
            $data = [];
            $save_check = 0;

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

                        $status = PollingDetail::where('cnic', $cnic)->first();
                        if (!$status) {
                            PollingDetail::insert([
                                'polling_station_number' => $blockcode->polling_station_number,
                                'polling_station_id' => $blockcode->id,
                                'cnic' => $cnic,
                                'age' => $age,
                                'family_no' => $f_no,
                                'serial_no' => $s_no,
                                'url' => $url,
                                'url_id' => $url_id,
                                'polygon' => $boundingPoly,
                                'boundingBox' => $boundingPoly,
                                'type' => 'cld',
                            ]);
                        }
                    }
                }
            }

            if ($save_check > 0) {
                FirebaseUrl::where('id', $url_id)->update([
                    'status' => 200,
                    'log_state' => 'Finish | from_vision_api'
                ]);
            } else {
                FirebaseUrl::where('id', $url_id)->update([
                    'status' => 500,
                    'log_state' => 'rejected | vision_api'
                ]);
            }
        }

        return true;
    }


    //Get Phone Number from Azzure

    public function get_phone_number()
    {
        ini_set('max_execution_time', '-1');
        ini_set("memory_limit", "-1");
        $queryCnicString = '';

        $polling_details = PollingDetail::where('cnic', '!=', null)
            ->whereIn('status', [0, 1])
            ->orderBy('id', 'desc')
            //            ->exclude(['polling_station_id', 'polling_station_number', 'gender', 'age', 'family_no', 'serial_no', 'page_no', 'url', 'url_id', 'pic_slice', 'crop_settings', 'boundingBox', 'polygon', 'urdu_meta', 'urdu_text', 'first_name', 'last_name', 'address', 'type', 'created_at', 'updated_at', 'check_status', 'cron'])
            ->take(200)
            ->get();


        $polling_details_ids = [];

        if ($polling_details) {
            foreach ($polling_details as $item) {

                //Making CNIC string to get phone from Azure
                $cnic = str_replace('-', '', $item->cnic);
                $queryCnicString = $queryCnicString . $cnic . ' ';
                $polling_details_ids[] = $item->id;
            }

            PollingDetail::whereIn('id', $polling_details_ids)->update(['status' => 202]);

            $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'top' => 5000, 'search' => $queryCnicString];
            $card1 = json_encode($post);
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
            $response = json_decode($response, true);

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

                    $voter_phone->phone = implode(',', $_voterPhone);
                    $voter_phone->meta = json_encode($resp_phone);
                    $voter_phone->polling_detail_id = $item->id;
                    $voter_phone->state = 1;
                    $voter_phone->save();
                } else {
                    $phone_not_found[] = $item->id;
                }
            }

            PollingDetail::whereIn('id', $phone_found)->update(['status' => 3]);
            PollingDetail::whereIn('id', $phone_not_found)->update(['status' => 2]);
        }

        return true;
    }

    public function get_phone_number_block_code($blockcode)
    {
        ini_set('max_execution_time', '-1');
        ini_set("memory_limit", "-1");
        $queryCnicString = '';

        $polling_details = PollingDetail::where('cnic', '!=', null)
            ->where('polling_station_number', $blockcode)
            ->whereIn('status', [0, 1])
            //            ->exclude(['polling_station_id', 'polling_station_number', 'gender', 'age', 'family_no', 'serial_no', 'page_no', 'url', 'url_id', 'pic_slice', 'crop_settings', 'boundingBox', 'polygon', 'urdu_meta', 'urdu_text', 'first_name', 'last_name', 'address', 'type', 'created_at', 'updated_at', 'check_status', 'cron'])
            ->orderBy('id', 'desc')
            ->get();


        $polling_details_ids = [];

        if ($polling_details) {
            foreach ($polling_details as $item) {

                //Making CNIC string to get phone from Azure
                $cnic = str_replace('-', '', $item->cnic);
                $queryCnicString = $queryCnicString . $cnic . ' ';
                $polling_details_ids[] = $item->id;
            }

            PollingDetail::whereIn('id', $polling_details_ids)->update(['status' => 202]);

            $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'top' => 5000, 'search' => $queryCnicString];
            $card1 = json_encode($post);
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
            $response = json_decode($response, true);

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

                    $voter_phone->phone = implode(',', $_voterPhone);
                    $voter_phone->meta = json_encode($resp_phone);
                    $voter_phone->polling_detail_id = $item->id;
                    $voter_phone->state = 1;
                    $voter_phone->save();
                } else {
                    $phone_not_found[] = $item->id;
                }
            }

            PollingDetail::whereIn('id', $phone_found)->update(['status' => 3]);
            PollingDetail::whereIn('id', $phone_not_found)->update(['status' => 2]);
        }

        return count($polling_details);
    }

    public function get_phone_number_202()
    {
        ini_set('max_execution_time', '-1');
        ini_set("memory_limit", "-1");
        $queryCnicString = '';

        $polling_details = PollingDetail::where('cnic', '!=', null)
            //            ->exclude(['polling_station_id', 'polling_station_number', 'gender', 'age', 'family_no', 'serial_no', 'page_no', 'url', 'url_id', 'pic_slice', 'crop_settings', 'boundingBox', 'polygon', 'urdu_meta', 'urdu_text', 'first_name', 'last_name', 'address', 'type', 'created_at', 'updated_at', 'check_status', 'cron'])
            //            ->where('polling_station_number' , $blockcode)
            ->where('status', 202)
            ->orderBy('id', 'desc')
            ->take(300)
            ->get();


        $polling_details_ids = [];

        if ($polling_details) {
            foreach ($polling_details as $item) {

                //Making CNIC string to get phone from Azure
                $cnic = str_replace('-', '', $item->cnic);
                $queryCnicString = $queryCnicString . $cnic . ' ';
                $polling_details_ids[] = $item->id;
            }

            PollingDetail::whereIn('id', $polling_details_ids)->update(['status' => 202]);

            $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'top' => 5000, 'search' => $queryCnicString];
            $card1 = json_encode($post);
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
            $response = json_decode($response, true);

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

                    $voter_phone->phone = implode(',', $_voterPhone);
                    $voter_phone->meta = json_encode($resp_phone);
                    $voter_phone->polling_detail_id = $item->id;
                    $voter_phone->state = 1;
                    $voter_phone->save();
                } else {
                    $phone_not_found[] = $item->id;
                }
            }

            PollingDetail::whereIn('id', $phone_found)->update(['status' => 3]);
            PollingDetail::whereIn('id', $phone_not_found)->update(['status' => 2]);
        }

        return count($polling_details);
    }


    //Convert images to base 64

    public function polling_details_images()
    {

        $start_mem = memory_get_usage();
        $start_time = microtime(true);

        ini_set('max_execution_time', '-1');
        ini_set("memory_limit", "-1");

        $sectors = ElectionSector::where('sector', 'like', 'LCB%')->orwhere('sector', 'like', 'WCB%')
            ->get()
            ->groupby('sector');

        $blockcodes = [];
        foreach ($sectors as $sector => $records) {
            foreach ($records as $key => $value) {
                $blockcodes[] = $value->block_code;
            }
        }

        $voters = PollingDetail::where('pic_slice', '!=', null)
            ->where('crop_settings', '!=', null)
            ->where('type', '!=', null)
            ->whereIn('polling_station_number', $blockcodes)
            ->where('cron', 0)
            ->whereDoesntHave('polling_details_images')
            ->take(2000)
            ->get();

        $voters_ids = $voters->pluck('id');

        PollingDetail::whereIn('id', $voters_ids)->update(['cron' => 2]);
        $exc_count = 0;
        foreach ($voters as $key => $voter) {
            try {
                if ($voter->type == 'cld') {
                    $img_address = "https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_"
                        . json_decode($voter->crop_settings, true)['h'] . ",w_900,x_340,y_" .
                        json_decode($voter->crop_settings, true)['y'] . "/" . urlencode(@$voter->url);

                    $img_name = "https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_"
                        . json_decode($voter->crop_settings, true)['h'] . ",w_900,x_1800,y_" .
                        json_decode($voter->crop_settings, true)['y'] . "/" . urlencode(@$voter->url);
                } else if ($voter->type == 'textract') {
                    $img_name = "https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_"
                        . json_decode($voter->crop_settings, true)['h'] . ",w_0.24435,x_0.58,y_" .
                        json_decode($voter->crop_settings, true)['y'] . "/" . urlencode(@$voter->url);

                    $img_address = "https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_"
                        . json_decode($voter->crop_settings, true)['h'] . ",w_0.32435,x_0.07,y_" .
                        json_decode($voter->crop_settings, true)['y'] . "/" . urlencode(@$voter->url);
                }

                $name_pic_data = file_get_contents($img_name);
                $address_pic_data = file_get_contents($img_address);
                $encode_name_pic = base64_encode($name_pic_data);
                $encode_address_pic = base64_encode($address_pic_data);
            } catch (Exception $e) {
                continue;
            }

            $check_existance = DB::table('polling_details_images')
                ->where('polling_detail_id', $voter->id)
                ->select('id', 'polling_detail_id')->first();

            if ($check_existance) {

                DB::table('polling_details_images')->where('id', $check_existance->id)->update([
                    'polling_detail_id' => $voter->id,
                    'name_pic' => $encode_name_pic,
                    'address_pic' => $encode_address_pic,
                    'name_url' => $img_name,
                    'address_url' => $img_address,
                    'firebase_url' => $voter->url,
                    'url_id' => $voter->url_id
                ]);
            } else {
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

        PollingDetail::whereIn('id', $voters_ids)->update(['cron' => 1]);

        unset($voters);
        unset($voters_ids);

        return true;
    }

    //others

    public function textract_blocks($image)
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

    public function get_url_from_log()
    {
        $upload_logs = UrlUploadLog::where('id', 121)->withCount('firebase_url')->get();
        dd($upload_logs);
        foreach ($upload_logs as $key => $log) {

            if ($log->files_count != $log->firebase_url_count) {

                $log_id = $log->id;
                $all_urls = json_decode($log->url_meta, true);

                $e_urls = array();
                $ne_urls = array();
                foreach ($all_urls as $item) {

                    $flag = FirebaseUrl::where('image_url', $item)->first();
                    if (!$flag) {
                        $ne_urls[] = $item;
                        //                        $firebase_url = new FirebaseUrl();
                        //                        $firebase_url->image_url = $item;
                        //                        $firebase_url->status = 0;
                        //                        $firebase_url->cron = 0;
                        //                        $firebase_url->url_upload_log_id = $log_id;
                        //                        $firebase_url->save();
                    } else {
                        $e_urls[] = $item;
                    }
                }

                dd($e_urls);

                //                $log->status = 1;
                //                $log->update();

            }
        }
    }

    public function manual_textract($url, $url_id)
    {
        $response = $this->image_textract($url, $url_id);
        dd($response);
        return $url;
    }

    //Get blocks of page from DB
    //Filter blocks on the basis of Confidence (Defined by textract API OCR while extracting) level
    //Find serial and family number against cnic and update in DB
    public function get_extra_details($url_id, $cnic)
    {

        $f_url = FirebaseUrl::where('id', $url_id)
            ->select('id', 'link_meta')
            ->first();
        $old_blocks = json_decode($f_url->link_meta, true);
        $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';
        $blocks = [];
        $blocks = $this->filterBlocks($old_blocks, 90);
        $_cnic = array_column($cnic, 'cnic');
        foreach ($blocks as $key => $value) {
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if (isset($value['Text']) && $value['Text']) {
                    $text = $value['Text'];
                    if ($blockType == 'LINE' && preg_match_all($cnic_pattern, $text, $matches)) {
                        //Check if cnic exist after 3 indexes to current index
                        if (@$blocks[$key + 4]) {
                            if (preg_match_all($cnic_pattern, $blocks[$key + 4]['Text'], $matches)) {
                                foreach ($_cnic as $c) {
                                    if ($c == $text) {
                                        $cnic_to_update = $c;
                                        break;
                                    }
                                }
                                if ($cnic_to_update) {
                                    if (@$blocks[$key + 2] && is_numeric($blocks[$key + 2]['Text'])) {
                                        $s_no = $blocks[$key + 2]['Text'];
                                    } else {
                                        $s_no = null;
                                    }

                                    if (@$blocks[$key + 1] && is_numeric($blocks[$key + 1]['Text'])) {
                                        $f_no = $blocks[$key + 1]['Text'];
                                    } else {
                                        $f_no = null;
                                    }

                                    if (@$blocks[$key - 1] && is_numeric($blocks[$key - 1]['Text'])) {
                                        $age = $blocks[$key - 1]['Text'];
                                    } else {
                                        $age = null;
                                    }

                                    //    dd($s_no , $f_no , $text , $age);

                                    PollingDetail::where('cnic', $text)->update([
                                        'age' => $age,
                                        'serial_no' => $s_no,
                                        'family_no' => $f_no
                                    ]);
                                }
                            }
                        }
                        //Check if cnic exist before 3 indexes to current index
                        else if (@$blocks[$key - 4]) {
                            if (preg_match_all($cnic_pattern, $blocks[$key - 4]['Text'], $matches)) {

                                foreach ($_cnic as $c) {
                                    if ($c == $text) {
                                        $cnic_to_update = $c;
                                        break;
                                    }
                                }

                                if ($cnic_to_update) {
                                    if (@$blocks[$key + 2] && is_numeric($blocks[$key + 2]['Text'])) {
                                        $s_no = $blocks[$key + 2]['Text'];
                                    } else {
                                        $s_no = null;
                                    }

                                    if (@$blocks[$key + 1] && is_numeric($blocks[$key + 1]['Text'])) {
                                        $f_no = $blocks[$key + 1]['Text'];
                                    } else {
                                        $f_no = null;
                                    }

                                    if (@$blocks[$key - 1] && is_numeric($blocks[$key - 1]['Text'])) {
                                        $age = $blocks[$key - 1]['Text'];
                                    } else {
                                        $age = null;
                                    }

                                    PollingDetail::where('cnic', $text)->update([
                                        'age' => $age,
                                        'serial_no' => $s_no,
                                        'family_no' => $f_no
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        unset($f_url);
        unset($blocks);
        unset($cnic_pattern);
        unset($blocks);

        return true;
    }
}
