<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\FirebaseUrlsController;
use App\Models\CurlSwitch;
use App\Models\ElectionSector;
use App\Models\ParchiImage;
use App\Models\PdfPolling;
use App\Models\PdfPollingLog;
use App\Models\PollingDetail;
use App\Models\PollingDetailImage;
use App\Models\OfflineDataFile;
use App\Models\UrlUploadLog;
use App\Models\VoterDetail;
use App\Models\VoterPhone;
use Aws\Textract\TextractClient;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\FirebaseUrl;
use App\Models\PollingStation;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\VisionClient;
use Google\Cloud\Vision\Image;
use function Couchbase\defaultDecoder;
use function Sodium\add;
use Cloudinary\Configuration\Configuration;
use File;
use PDF;
use MPDF;
use App\Mail\OfferMail;
use Mail;
use Response;
use ZipArchive;
use Illuminate\Support\Facades\Schema;

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/one-call-59851-40f314185b47.json');


class TestingController extends Controller
{
    public function cloudinary_api()
    {

        $url = 'https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/politics%2F188010104%2F1628110700682.jpg?alt=media&token=926eee32-0e79-49a6-ae83-8c8caaba7839';

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => 'one-call-app',
                'api_key'  => '231534778562361',
                'api_secret' => 'V8j97Iq0SWLKCMpNg7gqUnYHxWo',
                'url' => [
                    'secure' => true
                ]
            ]
        ]);

        $res = $cloudinary->uploadApi()->upload(
            $url,
            ["ocr" => "adv_ocr"]
        );

        dd($res);
    }

    public function voterDetailEntry($blockcode)
    {
        ini_set("memory_limit", -1);
        set_time_limit(0);
        $polling_details = PollingDetail::where('polling_station_number', $blockcode)
            ->where(function ($q) {
                $q->where('serial_no', null)->orWhere('family_no', null);
            })
            //            ->where('serial_no' , null)
            //            ->orWhere('family_no' , null)
            ->orderBy('url_id', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(100);
        //            ->get();

        return view('voterDetailEntry', compact('polling_details', 'blockcode'));
    }

    public function delete_details($blockcode)
    {
        $firebase_urls = FirebaseUrl::where('import_ps_number', $blockcode)->get();
        foreach ($firebase_urls as $key => $value) {
            PollingDetail::where('url_id', $value->id)->delete();
            PollingStation::where('url_id', $value->id)->delete();
        }
        //       FirebaseUrl::where('import_ps_number' , $blockcode)->delete();
        return 'deleted';
    }

    public function compareImages()
    {

        $url = 'https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1628493487108.jpg?alt=media&token=9331eb6d-86bf-44e8-9c0d-94241827e778';
        $file    = file_get_contents($url);
        $image = new \Imagick();
        $image->readImageBlob($file);
        file_put_contents(public_path() . '/images/temp_page_image.jpg', $image->getImageBlob());

        $imagick_type = new \Imagick();
        $file_to_grab = public_path('/images/temp_page_image.jpg');
        $file_handle_for_viewing_image_file = fopen($file_to_grab, 'a+');
        $imagick_type->readImageFile($file_handle_for_viewing_image_file);
        $imagick_type_properties = $imagick_type->getImageProperties('*', TRUE);

        dd($imagick_type_properties);
    }

    public function crop_image($url, $y, $cnic, $block_code)
    {

        $y_axis = $y - 0.007;
        $file    = file_get_contents($url);
        $image = new \Imagick();
        $image->readImageBlob($file);
        $threshold = 700;
        $w = 7000;
        $x = 0;
        $y = $y_axis * $threshold;
        //        dd($y , $y_axis);
        $image->cropImage($w, 200, $x, $y);
        $name   = $cnic . '.jpg';

        $thumbnail = $image->getImageBlob();
        $contents =  ob_get_contents();

        echo "<img src='data:image/jpg;base64," . base64_encode($thumbnail) . "' />";
        exit();

        if (!is_dir(public_path() . '/images/' . $block_code)) {
            // dir doesn't exist, make it
            mkdir(public_path() . '/images/' . $block_code);
        }

        file_put_contents(public_path() . '/images/' . $block_code . '/' . $name, $image->getImageBlob());

        return asset('/images/' . $block_code . '/' . $name);
    }

    public function crop_and_save_crop_image()
    {
        ini_set('max_execution_time', '-1');
        $polling_details = PollingDetail::where('urdu_text', null)
            ->take(10)
            ->orderBy('id', 'desc')
            ->get();

        foreach ($polling_details as $key => $value) {
            $polygon = json_decode($value->polygon);
            $y_axis = $polygon[0]->Y;
            $url = $value->url;
            $cnic = $value->cnic;
            $block_code = $value->polling_station_number;
            $image = $this->crop_image($url, $y_axis, $cnic, $block_code);
            $value->pic_slice = $image;
            $value->update();
        }
    }

    public function cropImageTextract_api()
    {



        try {
            $polling_detail = PollingDetail::with('firebase_url')
                ->where('pic_slice' , null)
                ->where('polygon', '!=', null)
                ->where('type', 'textract')
//                ->select('id', 'url', 'pic_slice', 'polygon', 'crop_settings')
//                ->where('url_id' , 57)
                ->take(1)
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


    //Poling station details extraction and storing

    public function voter_card()
    {

        $data = $this->demo_data();
        $queryCnicString = '';
        foreach ($data as &$item) {
            $item[0] = (int) filter_var($item[0], FILTER_SANITIZE_NUMBER_INT);
            $item[1] = (int) filter_var($item[1], FILTER_SANITIZE_NUMBER_INT);
        }
        $block_code = '188020101';
        $parchiImage = ParchiImage::where('block_code', $block_code)->first();
        //dd($parchiImage);
        ini_set('max_execution_time', '-1');
        $polling_details = PollingDetail::where('polling_station_number', $block_code)
            //            ->where('urdu_text' , '!=' , null)
            //            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
            ->take(10)
            ->with('voter_phone', 'SchemeAddress')
            ->get();

        return view('voterCard', compact('data', 'block_code', 'polling_details', 'parchiImage'));
    }

    function array_sort_by_column(&$arr, $col, $dir = SORT_ASC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
    }

    public function google_vision_API()
    {
        ini_set('max_execution_time', '-1');
        $data = [];
        $polling_details = PollingDetail::where('polling_station_number', 188010201)
            // ->where('serial_no', '!=', null)
            // ->where('urdu_text', '!=', null)
            ->take(1000)
            // ->orderBy('serial_no', 'asc')
            // ->orderBy('url_id', 'asc')
            ->get();
        // foreach ($polling_details as $item) {
        //     $image = $item->pic_slice;
        //     $response = $this->findCoordsOfTextFromImage($image);
        //     $item->urdu_meta = $response['meta'];
        //     $item->update();
        //     $data[] = $response['data'];
        // }

        //Fetching Phone numbers against NIC number from Azure
        $queryCnicString = '';

        //Getting all CNIC
        foreach ($polling_details as $key => $value) {
            // if (is_numeric(@$value[5])) {
            $cnic = str_replace('-', '', $value->cnic);
            $queryCnicString = $queryCnicString . $cnic . ' ';
            // }
        }
// dd($queryCnicString);

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

        dd($resp);
        foreach ($data as $key => $value) {
            if (is_numeric(@$value[5])) {
                $cnic = str_replace('-', '', $value[4]);
                $phone = $this->search_by_cnic($resp, $cnic);
                if ($phone != 0) {
                    $data[$key][10] = $phone['phone1'];
                } else {
                    $data[$key][10] = 'not found';
                }
            }
        }

        //        dd($data);

        foreach ($data as $item) {
            $polling_detail = PollingDetail::where('cnic', $item[4])->first();
            if (!is_null($polling_detail)) {
                $polling_detail->urdu_text = $item;
                $polling_detail->update();
            }
        }

        //        dd($data);
        //        return view('google-vision-api', compact('data'));
        return true;
    }

    public function cropImageCloudinary_api()
    {
//        $polling=PollingDetail::where('polling_station_number','%like%','037130901')->get();
//        dd($polling);
        try {
            $polling_detail = PollingDetail::with('firebase_url')
                ->where('pic_slice' , null)
                ->where('polygon', '!=', null)
//                ->whereIn('type' , ['cld_excel' , 'gva'])
                ->select('id', 'url', 'pic_slice', 'polygon', 'crop_settings')
                ->orderBy('id' , 'asc')
                ->take(1000)
//                ->where('url_id',57)
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
//                        dd($y_axis);
                        if($h > 140)
                        {
                            $recheck=1;
                            $h = 110;
                        }
                        $cloudinary_url = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_' . ($h) . ',w_10000,x_0,y_' . ($y_axis) . '/' . $data;
//                        dd($next_polygon , $next_y_axis ,$h , $cloudinary_url);
//                      Textract
//                      w_0.20,x_0.50
//                      w_0.26,x_0.09
//                        dd($cloudinary_url);

                        $arr = [];
                        $arr['h'] = $h;
                        $arr['y'] = $y_axis;
                        $arr['url'] = $data;
                        $arr['recheck'] = $recheck;
                        $value->crop_settings = json_encode($arr);
                        $value->pic_slice = $cloudinary_url;
                        $value->update();

//                        dd($value);
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

    public function SerialFamilyUpdate()
    {

        $pollingDetails=PollingDetail::where('serial_no',null)
            ->where('family_no',null)
            ->where('age',null)
            ->orderBy('id','DESC')
//            ->select('cnic','id')
                ->take(300)
            ->get();
//        dd($pollingDetails);
        $id_card=$pollingDetails->pluck('cnic');

        $vertexVoters=VoterDetail::wherein('id_card',$id_card)
            ->get();
//        dd($vertexVoters);
        foreach ($pollingDetails as $pollingDetail)
        {
            foreach ($vertexVoters as $vertexVoter) {
                if ($pollingDetail->cnic == $vertexVoter->id_card) {
//             dd($vertexVoter);
                    $pollingDetail->serial_no = $vertexVoter->serial_no;
                    $pollingDetail->family_no = $vertexVoter->family_no;
                    $pollingDetail->age = $vertexVoter->age;
                    $pollingDetail->polling_station_number = $vertexVoter->block_code;
                    $pollingDetail->save();
                    $vertexVoter->status = 1;
                    $vertexVoter->save();
                }
            }

        }
        dd($vertexVoters , $pollingDetails);

    }

    public function findCoordsOfTextFromImage($image_link)
    {
        //        $path = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_0.04,w_20,x_0,y_0.25536437094212/https%3A%2F%2Ffirebasestorage.googleapis.com%2Fv0%2Fb%2Fone-call-59851.appspot.com%2Fo%2Fpolitics%252F6%252F1624645189886.jpg%3Falt%3Dmedia%26token%3D9ab6b61d-f81e-4417-ba63-a75212642501';

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
                'LABEL_DETECTION',
                'DOCUMENT_TEXT_DETECTION'
            ]);

            $response = (object)$vision->annotate($image);

            $info = (object)$response->info();
            $textAnnotations = (array)$info->textAnnotations;
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

        //GETTING X,y COORDINATES OF EACH LETTER
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
        //        $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';
        //
        //        foreach ($detail_array as $item){
        //            preg_match_all($cnic_pattern, $item['text'], $matches);
        //            if($matches[0] != []){
        //                $y_of_cnic = $item['left_y'];
        //            }
        //        }

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
        //        dd($detail_array);

        //GETTING ACTUAL SORTED FINAL DATA
        $row = '';
        $data = [];
        foreach ($detail_array as $key => $val) {
            if ($val['d'] > 45) {
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
        foreach ($response as $res) {
            if ($res['idcard'] == $search_string) {
                return $res;
            }
        }
        return 0;
    }

    function demo_data()
    {
        return $data = [
            [
                1,
                1,
                "حسین",
                "ملک",
                "ناظر حسین",
                "35201-6788866-9",
                "44",
                "مکان نمبر 91 - -91 E ، سٹریٹ نمبر 4 ، حتے بلا گت اسقاط کالونی ، لاہور کینٹ لاہور "
            ],
            [
                1,
                1,
                "سلم",
                "لي",
                "زوجہ ملک ناظر حسین",
                "35201-1260809-6",
                "71",
                "امکان نمبر 91 - E ٹر یٹ نمبر 4 ، محلہ شاد کالونی ، باکٹ اے ، اور کینٹ ، ضلع پور "
            ],
            [
                94,
                15,
                "مسرور",
                "عزت بیگ",
                "35202-0672089-5",
                "60",
                "مکان نمبر 40 - E گلی نمبرلے اے بلا",
                "نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                94,
                46,
                "غلام زد",
                "دختر غلام رسول",
                "35201-5051770-6",
                "27",
                "امکان نمبر 1165 ، سٹریٹ نمبر 1 محلہ شارک اونی آراے بازار ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                95,
                15,
                "وما",
                "نیم",
                "35202-0293400-1",
                "53",
                "مکان ااور نمبر 5/1 ، لی نمبر",
                "ملے گا کالونی کوٹ خواجہ حیدر ، باہور ، شمیل نا ہورٹیکل "
            ]
        ];

    }

    public function filterBlocks($blocks, $confidence)
    {
        $new_blocks = [];
        foreach ($blocks as $key => $value) {
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if ($blockType == 'LINE' && ($value['Confidence'] > $confidence)) {
                    $new_blocks[] = $value;
                }
            }
        }
        return $new_blocks;
    }


    public function getPdfView($block_code)
    {
        ini_set('max_execution_time', '-1');
        $polling_details = PollingDetail::where('polling_station_number', $block_code)
            //            ->where('polygon' , '!=' , null)
            //            ->where('crop_settings', '!=' , null)
            ->orderBy('serial_no', 'asc')
            //            ->take(10)
            ->with('voter_phone')
            ->get();

        $dpi = 400;


        return view('google-vision-api', compact('block_code', 'polling_details', 'dpi'));
        // $this->fill_number($polling_details);


        //        foreach ($polling_details as $item) {
        //
        //            $urdu = json_decode($item->urdu_text, true);
        //            $urdu_meta = json_decode($item->urdu_meta, true);
        //
        //            $descrption = (explode("\n", $urdu_meta[0]['description']));
        //            $len = 0;
        //            $address = "";
        //            foreach ($descrption as $desc) {
        //                if (strlen($desc) > $len && strlen($desc)  > 4) {
        //                    $address = $desc;
        //                    $len = strlen($desc);
        //                }
        //            }
        //
        //            $name_array = [];
        //
        //            foreach ($urdu as $data) {
        //                if (!is_numeric($data) && $item->cnic == $data) {
        //                    break;
        //                } else if (!is_numeric($data) && $data != $address) {
        //                    $name_array[] = $data;
        //                }
        //            }
        //
        //            $item->first_name = @$name_array[0];
        //            $item->last_name = @$name_array[1];
        //
        //            $age_check = substr($address,  0, 2);
        //            if ($age_check == $item->age) {
        //                $item->address = substr($address, 2);
        //            } else {
        //                $item->address = $address;
        //            }
        //            $item->update();
        //        }

        //        dd($polling_details);

        //        return view('google-vision-api', compact( 'block_code' , 'polling_details'));

        // return true;
    }

    public function getPdfViewBackUp($block_code)
    {
        ini_set('max_execution_time', '-1');
        $polling_details = PollingDetail::where('polling_station_number', $block_code)
            //            ->where('urdu_text' , '!=' , null)
            //            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
            //            ->take(50)
            ->with('voter_phone')
            ->get();


        return view('google-vision-api-backUp', compact('block_code', 'polling_details'));
        // $this->fill_number($polling_details);


        //        foreach ($polling_details as $item) {
        //
        //            $urdu = json_decode($item->urdu_text, true);
        //            $urdu_meta = json_decode($item->urdu_meta, true);
        //
        //            $descrption = (explode("\n", $urdu_meta[0]['description']));
        //            $len = 0;
        //            $address = "";
        //            foreach ($descrption as $desc) {
        //                if (strlen($desc) > $len && strlen($desc)  > 4) {
        //                    $address = $desc;
        //                    $len = strlen($desc);
        //                }
        //            }
        //
        //            $name_array = [];
        //
        //            foreach ($urdu as $data) {
        //                if (!is_numeric($data) && $item->cnic == $data) {
        //                    break;
        //                } else if (!is_numeric($data) && $data != $address) {
        //                    $name_array[] = $data;
        //                }
        //            }
        //
        //            $item->first_name = @$name_array[0];
        //            $item->last_name = @$name_array[1];
        //
        //            $age_check = substr($address,  0, 2);
        //            if ($age_check == $item->age) {
        //                $item->address = substr($address, 2);
        //            } else {
        //                $item->address = $address;
        //            }
        //            $item->update();
        //        }

        //        dd($polling_details);

        //        return view('google-vision-api', compact( 'block_code' , 'polling_details'));

        // return true;
    }


    public function save_firebase_url1()
    {
        ini_set("memory_limit", -1);
        set_time_limit(-1);


        $firebase_url =     PollingDetail::where('polling_station_number', 188060203)->get()->groupby('url_id');

        dd($firebase_url);

        $firebase_url = FirebaseUrl::orderBy('id', 'asc')->withCount('polling_details')
            ->whereHas('polling_details', function ($q) {
                $q->where('polling_station_number', 188060203);
            })
            ->get();
        //        dd($firebase_url);
        //        135
        $i = [];
        foreach ($firebase_url as $url) {
            if ($url->polling_details_count > 0) {
                continue;
            } else {
                $i[] = $url;
            }
        }
        dd(count($firebase_url), $i);
    }

    public function save_firebase_url()
    {
        ini_set("memory_limit", -1);
        set_time_limit(-1);
        $firebase_url = FirebaseUrl::where('status', 401)->orderBy('id', 'asc')->withCount('polling_details')->paginate(20);
        //        dd($firebase_url);
        //        135
        $i = 0;
        foreach ($firebase_url as $url) {
            if ($url->polling_details_count > 0) {
                $url->status = 200;
                $url->log_state = 'Finish | from_vision_api';
                $url->update();
            } else {
                $i++;
                $url->status = 404;
                $url->log_state = '404';
                $url->update();
            }
        }
        dd(count($firebase_url), $i);
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

                    $data->save();
                }
            }
        }

        return true;
    }


//    public function auto_textract($url, $url_id)
//    {
//        $response = $this->image_textract($url, $url_id);
//
//        $cnic = $response['cnic'];
//        $polling_station_number =  $response['polling_station_number'];
//        $page_number =  $response['page_number'];
//        $meta = $response['meta'];
//
//        if ((count($cnic) != 0)) {
//            $polling_station_id = $this->getPollingStationId($polling_station_number, $meta, $url_id);
//            if ($polling_station_id != null) {
//                $status = $this->save_polling_details($polling_station_id, $polling_station_number, $cnic, $url, $url_id);
//
//                $cnic = array();
//                $polling_station_number = '';
//                $page_number = '';
//
//                if ($status == true) {
//                    return true;
//                } else {
//                    return false;
//                }
//            } else {
//                $status = $this->save_polling_details(null, null, $cnic, $url, $url_id);
//                $cnic = array();
//                $polling_station_number = '';
//                $page_number = '';
//                return true;
//            }
//        } else {
//
//            return 'invalid_page';
//        }
//    }

    public function auto_textract($url, $url_id)
    {
//        FirebaseUrl::firebase_url_log_state('Before Image Textract', $url_id);
        $response = $this->image_textract($url, $url_id);
        dd($response);
//        FirebaseUrl::firebase_url_log_state('After Image Textract', $url_id);

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

//    public function textract_multiple_url()
//    {
//        $urls = FirebaseUrl::
//        where('status', '20')
//            ->where('cron', '1')
////            where('id', '57')
//                ->where('import_ps_number',37130908)
//            ->orderBy('id', 'desc')
////            ->take(5)
//            ->count();
//dd($urls);
//        foreach ($urls as $key => $value) {
//            DB::table('firebase_urls')->where('id', $value->id)->update(['cron' => '307']);
//        }
//
//        foreach ($urls as $key => $value) {
//            $status = $this->auto_textract($value->image_url, $value->id);
//
//
//            if ($status == true) {
//                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '40', 'cron' => '1']);
//            } else if ($status == 'no_cnic') {
//                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '309', 'cron' => '1']);
//            } else if ($status == 'invalid_page') {
//                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '313', 'cron' => '1']);
//            } else if ($status == 'duplicate_page') {
//                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '4', 'cron' => '1']);
//            }
//        }
//    }


    public function textract_multiple_url()
    {
        $urls = FirebaseUrl::where('status', '0')
            ->where('cron', '0')
            ->select('id', 'image_url', 'status', 'cron', 'log_state', 'import_ps_number')
            ->where('id',50773)
            ->take(10)


            ->get();

       // dd($urls);

        $urls_ids = $urls->pluck('id');

        //cron status 2 is a middle state which get updated after process is completed
//        FirebaseUrl::whereIn('id' , $urls_ids)->update([
//            'cron' => '2',
//            'log_state' => 'Textract Cron in process'
//        ]);

        $lambdaSwitch = CurlSwitch::where('name' , 'textract_lambda')->first();

        if($lambdaSwitch->status == 1){
            dd('call lambda function, cURL');
        }else{
            foreach ($urls as $key => $value) {
//                FirebaseUrl::firebase_url_log_state('Before Auto Textract', $value->id);
                $status = $this->auto_textract($value->image_url, $value->id);
                dd($status);
//                FirebaseUrl::firebase_url_log_state('Finish | ' . $status, $value->id);

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
//        FirebaseUrl::firebase_url_log_state('Saving Extra Details', $url_id);

        return $state;
    }

    public function getPollingStationId($polling_station_number, $meta, $url_id)
    {

        if ($polling_station_number != null) {
            $polling_station = DB::table('polling_station')->where('polling_station_number', $polling_station_number)->first();
            if ($polling_station) {
                $polling_station_id = $polling_station->id;
            } else {
                $new_polling_station = DB::table('polling_station')->insertGetId(['polling_station_number' => $polling_station_number, 'meta' => $meta, 'url_id' => $url_id]);
                $polling_station_id = $new_polling_station;
            }
        } else {
            $polling_station_id = null;
        }


        return $polling_station_id;
    }

    public function image_textract($url, $url_id)
    {
        $image = file_get_contents($url);

//        FirebaseUrl::firebase_url_log_state('Before Textract API', $url_id);
        $blocks = $this->textract_api($image);
//        FirebaseUrl::firebase_url_log_state('After Textract API', $url_id);
        $meta = json_encode($blocks);

        if ($meta) {
            $firebase_url = FirebaseUrl::where('id', $url_id)
                ->select('id', 'link_meta')
                ->first();
            $firebase_url->link_meta = $meta;
            $firebase_url->update();
        }

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
     dd($response['cnic']);

        FirebaseUrl::firebase_url_log_state('Sending Filtered Data', $url_id);

        return $response;
    }

    public function cloudinary_url()
    {
        $image_url = 'https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/politics%2F6%2F1624645185607.jpg?alt=media&token=11f1f1c2-ec02-48f9-ad9d-270329095c57';
        $polling_detail = PollingDetail::with('firebase_url')->where('polling_station_number', 188110704)->get()->groupBy('url_id');

        foreach ($polling_detail as $key => $item) {

            if ($key == 2372) {

                //            $firebase_url = $item->image_url;
                //            $firebase_url_meta = json_decode($item->firebase_url->link_meta, true);
                foreach ($item as $key => $value) {
                    $firebase_url = $value->url;
                    $polygon = json_decode($value->polygon, true);
                    $boundingBox = json_decode($value->boundingBox, true);
                    $boundingBox1 = json_decode($item[$key + 1]->boundingBox, true);
                    //                  dd($boundingBox ,$boundingBox1 );
                    $y_axis = $polygon[0]['Y'];
                    $next_polygon = json_decode(@$item[$key + 1]->polygon, true);
                    $next_y_axis = $next_polygon[0]['Y'];
                    $y_axis_thereshold_percent = 1;
                    $percent_value = ($y_axis * $y_axis_thereshold_percent) / 100;
                    $y_axis = $y_axis - $percent_value;
                    $h = $next_y_axis - $y_axis;
                    $threshold_percent = 20;
                    $addition = ($h * $threshold_percent) / 100;
                    $h = $h + $addition;

                    //                $y_axis_thereshold_percent=2;
                    //                $percent_value=($y_axis*$y_axis_thereshold_percent)/100;
                    //                $y_axis=$y_axis-$percent_value;

                    //                $y=0.04;
                    //                dd($y_axis . '  ' . $next_y_axis . ' = ' . $y);
                    $data = urlencode($firebase_url);
                    $cloudinary_url = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_' . $h . ',w_20,x_0,y_' . ($y_axis) . '/' . $data;
                    echo '<img src="' . $cloudinary_url . '" width="100%"/></br></br></br>';
                    $value->cut_image = $cloudinary_url;
                }
            }
        }
        return 'done';
    }

    public function getExtraDetails($block_code)
    {
        ini_set('max_execution_time', '0');
        ini_set("memory_limit", "-1");
        $type = 'NEW';

        $polling_details = PollingDetail::whereIn('polling_station_number', ['187020101', '187020102', '187020103', '187020104', '187020105', '187020106'])
            ->where('type', 'textract')
            ->where(function ($q) {
                $q->where('serial_no', null)->orWhere('family_no', null);
            })
            ->get()
            ->groupBy('url_id');

        foreach ($polling_details as $url_id => $details) {

            $meta_of_page = FirebaseUrl::where('id', $url_id)->first('link_meta');
            $blocks = json_decode($meta_of_page->link_meta);

            //filter blocks greater than given confidence level
            if (is_array($blocks)) {
                $new_blocks = $this->filterBlocks($blocks, 75);
            } else {
                continue;
            }

            $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';

            foreach ($details as $index => $item) {
                $cnic_to_update = $item->cnic;
                //                dd($cnic_to_update);
                foreach ($new_blocks as $key => $value) {
                    if (isset($value->BlockType) && $value->BlockType &&  $value->BlockType == 'LINE') {
                        $blockType = $value->BlockType;
                        if (isset($value->Text) && $value->Text) {
                            $text = $value->Text;
                            if ($type == 'NEW') {
                                if ($blockType == 'LINE' && preg_match_all($cnic_pattern, $text, $matches)) {
                                    if ($text == $cnic_to_update) {
                                        if (@$new_blocks[$key + 2] && is_numeric($new_blocks[$key + 2]->Text)) {
                                            $s_no = $new_blocks[$key + 2]->Text;
                                        } else {
                                            $s_no = null;
                                        }

                                        if (@$new_blocks[$key + 1] && is_numeric($new_blocks[$key + 1]->Text)) {
                                            $f_no = $new_blocks[$key + 1]->Text;
                                        } else {
                                            $f_no = null;
                                        }

                                        if (@$new_blocks[$key - 1] && is_numeric($new_blocks[$key - 1]->Text)) {
                                            $age = $new_blocks[$key - 1]->Text;
                                        } else {
                                            $age = null;
                                        }

                                        $temp[] = [
                                            'age' => $age,
                                            'serial_no' => $s_no,
                                            'family_no' => $f_no
                                        ];
                                        //                                        dd($s_no , $f_no , $text , $age);

                                        PollingDetail::where('cnic', $text)->update([
                                            'age' => $age,
                                            'serial_no' => $s_no,
                                            'family_no' => $f_no
                                        ]);
                                    }
                                }
                            } else if ($type == 'OLD') {
                                if (isset($value->BlockType) && $value->BlockType &&  $value->BlockType == 'LINE') {
                                    $blockType = $value->BlockType;
                                    if (isset($value->Text) && $value->Text) {
                                        $text = $value->Text;
                                        if ($blockType == 'LINE' && preg_match_all($cnic_pattern, $text, $matches)) {
                                            if ($text == $cnic_to_update) {
                                                if (@$new_blocks[$key + 1] && is_numeric($new_blocks[$key + 1]->Text)) {
                                                    $s_no = $new_blocks[$key + 1]->Text;
                                                } else {
                                                    $s_no = null;
                                                }

                                                if (@$new_blocks[$key - 1] && is_numeric($new_blocks[$key - 1]->Text)) {
                                                    $age = $new_blocks[$key - 1]->Text;
                                                } else {
                                                    $age = null;
                                                }

                                                $cnic = $text;

                                                dd($s_no, $cnic, $age);

                                                PollingDetail::where('cnic', $cnic)->update([
                                                    'age' => $age,
                                                    'serial_no' => $s_no
                                                ]);
                                            }
                                        }
                                    }
                                }
                            } else {
                                return 'Invalid Page Type';
                            }
                        }
                    }
                }
            }
        }

        return count($temp);
    }

    public function urdu_keyboard()
    {
        return view('urduKeyboard');
    }

    public function getElectionSector()
    {
        $sector = DB::table('election_sector')->get()->unique('sector');

        return $sector;
    }

    public function blockCodeReport()
    {
        $election_sector = ElectionSector::where('block_code', '188010407')->first();
        $firebase_urls = FirebaseUrl::where('import_ps_number', '188010407')
            ->withcount('polling_details')
            ->with('polling_details')
            ->get();

        $page_24 = 0;
        $page_28 = 0;
        $other = 0;
        $male = 0;
        $female = 0;
        $invalid_pages = 0;
        $duplicate_pages = 0;
        $missing_blockcode_pages = 0;

        foreach ($firebase_urls as $key => $item) {
            if ($item->polling_details_count == 28) {
                $page_28++;
            } else if ($item->polling_details_count == 24) {
                $page_24++;
            } else {
                $other++;
            }

            if ($item->staus === 3 || $item->staus === 500 || $item->staus === 404) {
                $invalid_pages++;
            }

            if ($item->staus === 4) {
                $duplicate_pages++;
            }

            if ($item->staus === 5) {
                $missing_blockcode_pages++;
            }

            foreach ($item->polling_details as $value) {
                if ($value->gender === 'male') {
                    $male++;
                } else {
                    $female++;
                }
            }
        }

        dd($election_sector, $firebase_urls, $page_24, $page_28, $other, $male, $female, $invalid_pages, $duplicate_pages, $missing_blockcode_pages);
    }

    public function assignGender()
    {
        ini_set('max_execution_time', '0');
        ini_set("memory_limit", "-1");
        $gender = 'undefined';
        $male = [];
        $female = [];
        $polling_details = PollingDetail::where('gender', 'undefined')->get();

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

        return true;
    }

    public function dublicateEntries()
    {
        $duplicates = DB::table('polling_details_images')
            ->select('id', 'polling_detail_id', DB::raw('COUNT(*) as `count`'))
            ->groupBy('polling_detail_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        dd($duplicates);
        $del = [];


        //        foreach ($duplicates as $dub)
        //        {
        //            $detail= DB::table('election_sector')->where('block_code', $dub->block_code)->where('sector' , $dub->sector)->orderBy('id','asc')->get();
        //            if(count($detail) > 1)
        //            {
        //                DB::table('election_sector')->where('block_code', $dub->block_code)->where('sector', $dub->sector)->where('id','!=',$detail[0]->id)->delete();
        //
        //                $del[]=$detail[0]->id;
        //            }
        //
        //        }
        //
        //
        //
        //        dd($detail[0],$dub);
        return 1;
    }

    public function testraw1()
    {

        $polling_details_images = [];
        $polling_details = PollingDetail::where('polling_station_number', 187040104)
            ->where('cron', 0)
            ->take(10)
            ->get();
        $polling_details_ids = $polling_details->pluck('id');
        $res = PollingDetail::whereIn('id', $polling_details_ids)->update(['cron' => 2]);

        $name_pic_w = 0.22435;
        $name_pic_x = 0.58;
        $address_pic_w = 0.2735;
        $address_pic_x = 0.1;

        $cld_name_pic_w = 900;
        $cld_name_pic_x = 1800;
        $cld_address_pic_w = 900;
        $cld_address_pic_x = 340;

        foreach ($polling_details as $index => $voter) {

            $crop_settings = json_decode($voter->crop_settings, true);

            if ($voter->type === 'textract') {

                $crop_settings['ad_w'] = $address_pic_w;
                $crop_settings['ad_x'] = $address_pic_x;
                $crop_settings['nm_w'] = $name_pic_w;
                $crop_settings['nm_x'] = $name_pic_x;

                $name_pic = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_' . json_decode(@$voter->crop_settings, true)['h'] . ',w_' . $name_pic_w . ',x_' . $name_pic_x . ',y_' . json_decode(@$voter->crop_settings, true)['y'] . '/' . urlencode(@$voter->url);
                $address_pic = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_' . json_decode(@$voter->crop_settings, true)['h'] . ',w_' . $address_pic_w . ',x_' . $address_pic_x . ',y_' . json_decode(@$voter->crop_settings, true)['y'] . '/' . urlencode(@$voter->url);
            } else {
                $crop_settings['ad_w'] = $cld_address_pic_w;
                $crop_settings['ad_x'] = $cld_address_pic_x;
                $crop_settings['nm_w'] = $cld_name_pic_w;
                $crop_settings['nm_x'] = $cld_name_pic_x;
                $name_pic = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_' . json_decode(@$voter->crop_settings, true)['h'] . ',w_' . $cld_name_pic_w . ',x_' . $cld_name_pic_x . ',y_' . json_decode(@$voter->crop_settings, true)['y'] . '/' . urlencode(@$voter->url);
                $address_pic = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_' . json_decode(@$voter->crop_settings, true)['h'] . ',w_' . $cld_address_pic_w . ',x_' . $cld_address_pic_x . ',y_' . json_decode(@$voter->crop_settings, true)['y'] . '/' . urlencode(@$voter->url);
            }

            $voter->crop_settings = json_encode($crop_settings);
            //            $voter->update();

            $name_pic_data = file_get_contents($name_pic);
            $address_pic_data = file_get_contents($address_pic);
            $encode_name_pic = base64_encode($name_pic_data);
            $encode_address_pic = base64_encode($address_pic_data);


            $polling_details_images[] = [
                'polling_detail_id' => $voter->id,
                'name_pic' => $encode_name_pic,
                'address_pic' => $encode_address_pic,
                'name_url' => $name_pic,
                'address_url' => $address_pic,
                'firebase_url' => $voter->url,
                'url_id' => $voter->url_id,
            ];
        }

        DB::table('polling_details_images')->insert($polling_details_images);


        $res = PollingDetail::whereIn('id', $polling_details_ids)->update(['cron' => 1]);

        return $polling_details_ids;
    }

    public function voterParchiNew()
    {

        $polling_details = PollingDetail::where('polling_station_number', 188020303)
            ->where('cnic', '35201-9373569-7')
            ->take(1)
            ->with('polling_details_images', 'SchemeAddress', 'sector')
            ->get();

        $parchiImages = ParchiImage::where('block_code', 187060201)->where('Party', 'PMLN')->first();

        //        dd($polling_details);

        return view('email.voterParchiNew', compact('polling_details', 'parchiImages'));
    }

    public function testraw2()
    {

        $parchiImages = ParchiImage::where('block_code', '179020301')->where('Party', 'PTI')->first();
        dd($parchiImages);

        $polling_detail = PollingDetail::where('created_at', '>=', '2021-08-08 00:00:00')
            ->whereDoesntHave('SchemeAddress')
            ->where('polling_station_number', '>', 180000000)
            ->get();

        $polling_details =   $polling_detail->groupBy('polling_station_number');
        dd($polling_details, count($polling_detail));
    }

    public function wardreport()
    {
        ini_set("pcre.backtrack_limit", "50000000");
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "0");




        $sectors = ElectionSector::where('sector', 'like', 'LCB%')
            ->orwhere('sector', 'like', 'WCB%')
            ->orderby('sector', 'asc')

            ->get()->groupby('sector');



        $countdata = 0;
        $alldata = [];


        $doc_counter = 0;
        $actual_counter = 0;
        foreach ($sectors as $key => $sector) {

            $block_code_numbers = $sector->pluck('block_code');

            $polling_details = PollingDetail::whereIn('polling_station_number', $block_code_numbers)->count();


            $temp = [];

            $actual_counter += $polling_details;
            $doc_counter += $sector->sum('total_vote');

            $temp['ward'] = $key;
            $temp['doc_data'] = $sector->sum('total_vote');
            $temp['actual_data'] = $polling_details;

            $temp['diff'] = $sector->sum('total_vote') - $temp['actual_data'] = $polling_details;



            $alldata[] = $temp;
        }

        //        dd($alldata,$actual_counter,$doc_counter,$doc_counter-$actual_counter);

        $temp['ward'] = "ZSummary";
        $temp['doc_data'] = $doc_counter;
        $temp['actual_data'] = $actual_counter;
        $temp['diff'] = $doc_counter - $actual_counter;

        $alldata[] = $temp;



        return response()->json(['data' => $alldata]);
    }

    public function wardreport1()
    {
        ini_set('max_execution_time', '-1');
        $sectors = ElectionSector::where('sector', 'like', 'LC%')->get()->groupby('sector');

        $alldata = [];
        foreach ($sectors as $sector) {
            $block_code_numbers = $sector->pluck('block_code');

            $polling_details = PollingDetail::whereIn('polling_station_number', $block_code_numbers)

                ->with('SchemeAddressmulti')
                //                ->with(['SchemeAddress'=>function($query) use ($mgender){
                //                    $query->where('gender_type',$mgender)
                //                        ->orwhere('gender_type','combined');
                //                }])

                //                ->select('polling_station_number','cnic','gender')

                ->get();


            $alldata[] = $polling_details;

            //            return response()->json(['data' =>$alldata ]);

        }

        dd($alldata);

        return response()->json(['data' => $alldata]);


        // return view('voterDetailEntryRecheck', compact('blockcode', 'mpolling_details','fpolling_details'));

    }

    public function testraw3()
    {








        $voter_ids = [];
        $ward = $_GET['ward'];
        $no = $_GET['no'];

        $sectors = ElectionSector::where('sector', 'like', $ward . ' ' . $no)
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
            //            ->where('cron' , 2)
            ->whereDoesntHave('polling_details_images')
            //            ->take(20000)
            ->count();

        dd($voters);

        $voter_ids = $voters->pluck('id');

        PollingDetail::whereIn('id', $voter_ids)->update(['cron' => 0]);

        dd(count($voters));
    }

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

    public function ElectionReport()
    {

        $total_ward = ElectionSector::where('sector', 'LA-38')->get()->groupBy('sector');
        foreach ($total_ward as $ward) {
            foreach ($ward as $i) {
                $bc[] = $i->block_code;
            }
        }

        $party_voters = PollingDetail::whereIn('polling_station_number', $bc)->count();

        dd(count($total_ward), count($bc), $party_voters);
        $party = $_GET['party'];

        $party_blockcodes = [];
        $party_wards = [];

        $party_details = ParchiImage::where('Party', $party)->get();

        $party_parchi_and_list_count = count($party_details);

        $party_blockcodes = $party_details->pluck('block_code');

        $sectors = ElectionSector::whereIn('block_code', $party_blockcodes)
            ->select('id', 'sector', 'block_code')
            ->withCount('pollingData')
            ->get()
            ->groupBy('sector');

        foreach ($sectors as $sector => $records) {
            $ward_voter = 0;
            $party_wards[] = $sector;
            foreach ($records as $k => $v) {
                $ward_voter += $v->polling_data_count;
            }
            $records['ward_voter'] = $ward_voter;
        }

        $wards_count = count($party_wards);

        $party_voters = PollingDetail::whereIn('polling_station_number', $party_blockcodes)->count();

        //        dd($party.' get served for '.$party_parchi_and_list_count.' block codes from '.$wards_count.' wards',
        //            'Total Voter Proccesed : '.$party_voters,
        //            'Wards List : ' , $party_wards,
        //            'Block Codes List' , $party_blockcodes);


        return view('election_cost_report', compact('party', 'sectors', 'party_voters', 'party_wards', 'party_blockcodes', 'party_parchi_and_list_count', 'party', 'wards_count'));
    }

    public function testingElectionExpertCore()
    {

        $firebase_urls_ids = FirebaseUrl::where('import_ps_number', '188050601')
            ->pluck('id');

        $polling_details_ids = PollingDetail::whereIn('url_id', $firebase_urls_ids)
            ->pluck('id');

        $firebase_urls = FirebaseUrl::whereIn('id', $firebase_urls_ids)
            ->select('id', 'status', 'log_state', 'cron')
            ->update([
                'status' => 0,
                'cron' => 0,
                'log_state' => null
            ]);


        $voter_phone = VoterPhone::whereIn('polling_detail_id', $polling_details_ids)
            ->select('id')
            ->delete();

        $polling_details_images = PollingDetailImage::whereIn('polling_detail_id', $polling_details_ids)
            ->select('id')
            ->delete();

        $polling_station = PollingStation::where('polling_station_number', '188050601')
            ->select('id')
            ->delete();

        $election_sector = ElectionSector::where('block_code', '188050601')
            ->count();

        $polling_details = PollingDetail::whereIn('url_id', $firebase_urls_ids)
            ->select('id')
            ->delete();

        dd(true);

        dd(
            'urls = ' . count($firebase_urls),
            'voters = ' . count($polling_details),
            'base_64 = ' . $polling_details_images,
            'phone = ' . $voter_phone,
            'PS = ' . $polling_station,
            'ES = ' . $election_sector
        );
    }

    public function getFamilyAndSerialNo()
    {

        $block_code = $_GET['block_code'];
        $pds = PollingDetail::where('polling_station_number', $block_code)->get();
        foreach ($pds as $i => $v) {
            $v->family_no = explode('_', $v->family_no)[0];
            //            dd($v);
            $v->update();
        }
        dd(count($pds));
        $output_new = [];
        $textAnnotations = [];
        $output = [];
        $block_code = $_GET['block_code'];

        //        $polling_details =  PollingDetail::where(function($q) {
        //            $q->where('serial_no', '!=' , null)->where('family_no', '!=' , null);
        //        })
        //            ->select('id', 'cnic', 'pic_slice', 'url', 'crop_settings', 'type', 'serial_no', 'family_no')
        //            ->orderBy('id' , 'desc')
        //            ->take(100)
        //            ->get();

        //        $polling_details =  PollingDetail::where('polling_station_number' , $block_code)
        //            ->select('id', 'cnic', 'pic_slice', 'url', 'crop_settings', 'type', 'serial_no', 'family_no')
        //            ->orderBy('id' , 'desc')
        //            ->paginate(100);

        //        dd($polling_details);

        //        foreach ($polling_details as $k => $detail){
        //            $y = json_decode($detail->crop_settings , true)['y'];
        //            if($detail->type == 'textract'){
        //                $path = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_0.035326910972595,w_0.15,x_0.78,y_'.$y.'/'.urlencode($detail->url);
        //            }
        //            else{
        //                $path = 'https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_127,w_500,x_2600,y_'.$y.'/'.urlencode($detail->url);
        //            }
        //
        //            try {
        //                $projectId = 'nlpapiupwork';
        //                $vision = new VisionClient([
        //                    'projectId' => $projectId,
        //                ]);
        //
        //                $imageData = file_get_contents($path);
        //                $image = new Image($imageData, [
        //                    'TEXT_DETECTION',
        //                ]);
        //
        //                $response = (object)$vision->annotate($image);
        //
        ////                dd($response, $path);
        //
        //                if($response->info() != []){
        //                    $info = (object)$response->info();
        //                    $textAnnotations = (array)$info->textAnnotations;
        //                    if(count($textAnnotations) < 3){
        //                        $row_data = $this->getFamilyAndSerialNoTextract($imageData , $detail);
        //                        $output_new[] = $row_data;
        //                        continue;
        //                    }
        //                }
        //                else{
        //                    $row_data = $this->getFamilyAndSerialNoTextract($imageData , $detail);
        //                    $output_new[] = $row_data;
        //                    continue;
        //                }
        //
        //            }
        //            catch (exception $e) {
        //                echo $e;
        //            }
        //
        //            if(count(@$textAnnotations) > 2){
        //                $detail_array = array();
        //
        //                //GETTING X,Y COORDINATES OF EACH LETTER
        //                for ($i = 1; $i < count($textAnnotations); $i++) {
        //                    try {
        //                        $detail_array[$i]['text'] = $textAnnotations[$i]['description'];
        //
        //                        if (@$textAnnotations[$i]['boundingPoly']['vertices'][0]['x']) {
        //                            $detail_array[$i]['left_x'] = $textAnnotations[$i]['boundingPoly']['vertices'][0]['x'];
        //                        } else if (@$textAnnotations[$i + 1]['boundingPoly']['vertices'][0]['x']) {
        //                            $detail_array[$i]['left_x'] = $textAnnotations[$i + 1]['boundingPoly']['vertices'][0]['x'];
        //                        } else if (@$textAnnotations[$i - 1]['boundingPoly']['vertices'][0]['x']) {
        //                            $detail_array[$i]['left_x'] = $textAnnotations[$i - 1]['boundingPoly']['vertices'][0]['x'];
        //                        } else {
        //                            $detail_array[$i]['left_x'] = null;
        //                        }
        //
        //                        if (@$textAnnotations[$i]['boundingPoly']['vertices'][2]['x']) {
        //                            $detail_array[$i]['right_x'] = $textAnnotations[$i]['boundingPoly']['vertices'][2]['x'];
        //                        } else if (@$textAnnotations[$i + 1]['boundingPoly']['vertices'][2]['x']) {
        //                            $detail_array[$i]['right_x'] = $textAnnotations[$i + 1]['boundingPoly']['vertices'][2]['x'];
        //                        } else if (@$textAnnotations[$i - 1]['boundingPoly']['vertices'][2]['x']) {
        //                            $detail_array[$i]['right_x'] = $textAnnotations[$i - 1]['boundingPoly']['vertices'][2]['x'];
        //                        } else {
        //                            $detail_array[$i]['right_x'] = null;
        //                        }
        //
        //                        if (@$textAnnotations[$i]['boundingPoly']['vertices'][0]['y']) {
        //                            $detail_array[$i]['left_y'] = $textAnnotations[$i]['boundingPoly']['vertices'][0]['y'];
        //                        } else if (@$textAnnotations[$i + 1]['boundingPoly']['vertices'][0]['y']) {
        //                            $detail_array[$i]['left_y'] = $textAnnotations[$i + 1]['boundingPoly']['vertices'][0]['y'];
        //                        } else if (@$textAnnotations[$i - 1]['boundingPoly']['vertices'][0]['y']) {
        //                            $detail_array[$i]['left_y'] = $textAnnotations[$i - 1]['boundingPoly']['vertices'][0]['y'];
        //                        } else {
        //                            $detail_array[$i]['left_y'] = null;
        //                        }
        //
        //                        if (@$textAnnotations[$i]['boundingPoly']['vertices'][2]['y']) {
        //                            $detail_array[$i]['right_y'] = $textAnnotations[$i]['boundingPoly']['vertices'][2]['y'];
        //                        } else if (@$textAnnotations[$i + 1]['boundingPoly']['vertices'][2]['y']) {
        //                            $detail_array[$i]['right_y'] = $textAnnotations[$i + 1]['boundingPoly']['vertices'][2]['y'];
        //                        } else if (@$textAnnotations[$i - 1]['boundingPoly']['vertices'][2]['y']) {
        //                            $detail_array[$i]['right_y'] = $textAnnotations[$i - 1]['boundingPoly']['vertices'][2]['y'];
        //                        } else {
        //                            $detail_array[$i]['right_y'] = null;
        //                        }
        //                    }
        //                    catch (Exception $e) {
        //                        dd($i);
        //                    }
        //                }
        //
        //                //SORTING ARRAY ACCORDING TO X AXIS
        //                $swapped = true;
        //                while ($swapped) {
        //                    $swapped = false;
        //                    for ($i = 1, $c = count($detail_array); $i < $c; $i++) {
        //                        if ($detail_array[$i]['left_x'] < $detail_array[$i + 1]['left_x']) {
        //                            list($detail_array[$i + 1], $detail_array[$i]) = array($detail_array[$i], $detail_array[$i + 1]);
        //                            $swapped = true;
        //                        }
        //                    }
        //                }
        //
        //                //GETTING HIGH AND LOW Y AXIS
        //                $y_min = 0;
        //                $y_max = 0;
        //                foreach ($detail_array as $key => $value) {
        //                    if ($key == 1) {
        //                        $y_min = $value['left_y'];
        //                        $y_max = $value['left_y'];
        //                    }
        //                    if ($y_max < $value['left_y']) {
        //                        $y_max = $value['left_y'];
        //                    }
        //                    if ($y_min > $value['left_y']) {
        //                        $y_min = $value['left_y'];
        //                    }
        //                }
        //
        //                //NEGLECTING EXTRA DATA
        //                $y_lim_low = $y_min;
        //                $y_lim_high = $y_max + 100;
        //                $result = [];
        //                foreach ($detail_array as $k => $index) {
        //                    if ($index['left_y'] >= $y_lim_low && $index['left_y'] <= $y_lim_high) {
        //                        $result[] = $index;
        //                    }
        //                }
        //
        //                //CALCULATING DISTANCE BETWEEN ELEMENTS
        //                $detail_array = $result;
        //                foreach ($detail_array as $key => $index) {
        //                    if (isset($detail_array[$key + 1])) {
        //                        $detail_array[$key]['d'] = $index['left_x'] - $detail_array[$key + 1]['right_x'];
        //                    } else {
        //                        $detail_array[$key]['d'] = 0;
        //                    }
        //                }
        //
        //                //GETTING ACTUAL SORTED FINAL DATA
        //                $row = '';
        //                $data = [];
        //                $data[] = $detail->cnic;
        //                foreach ($detail_array as $key => $val) {
        //                    if ($val['d'] > 10) {
        //                        $row = $row . $val['text'];
        //                        $data[] = $row;
        //                        $row = "";
        //                    } else {
        //                        $row = $row . $val['text'] . ' ';
        //                    }
        //                }
        //                $data[] = $row;
        //                $output[] = $data;
        //            }
        //            else{
        //                continue;
        //            }
        //        }
        //
        //        if(count($output) > 0){
        //            foreach ($output as $i => $i_val){
        //                $row_data['cnic'] = @$i_val[0];
        //                $row_data['s_no'] = null;
        //                $row_data['f_no'] = null;
        //                $s_no = str_replace('.', '', trim(@$i_val[1]));
        //                $temp_s_no = explode(' ', $s_no);
        //                if(@$temp_s_no[1]){
        //                    $s_no = $temp_s_no[1];
        //                }
        //                else{
        //                    $s_no = $temp_s_no[0];
        //                }
        //                $f_no = str_replace('.', '', trim(@$i_val[2]));
        //                if (@$s_no && @$f_no) {
        //                    if (ctype_digit($s_no)) {
        //                        $row_data['s_no'] = $s_no;
        //                        if (ctype_digit($f_no)) {
        //                            $row_data['f_no'] = $f_no;
        //                        } elseif (preg_match('/[\d]*[-Q]+/', $f_no, $output_array)) {
        //                            if (@$output_array[0] && $output_array[0] != "") {
        //                                $row_data['f_no'] = @$output_array[0];
        //                            } else {
        //                                $row_data['f_no'] = null;
        //                            }
        //                        } else {
        //                            $temp_f_no = (int)filter_var($f_no, FILTER_SANITIZE_NUMBER_INT);
        //                            if ($temp_f_no != '') {
        //                                $row_data['f_no'] = (string) $temp_f_no;
        //                            } else {
        //                                $row_data['f_no'] = null;
        //                            }
        //                        }
        //                    } else {
        //                        $row_data['s_no'] = null;
        //                        $row_data['f_no'] = null;
        //                    }
        //                }
        //                else {
        //                    $row_data['s_no'] = null;
        //                    $row_data['f_no'] = null;
        //                }
        //
        //                $output_new[] = $row_data;
        //                unset($row_data);
        //
        //            }
        //        }
        //        $i = 0;
        //        foreach($output_new as $index => $res){
        //
        //            $f_no = $res['s_no'].'_'.$res['f_no'];
        //
        //            $voter = PollingDetail::where('cnic' , $res['cnic'])
        //                ->select('family_no','id')
        //                ->first();
        //
        //            if($voter->family_no != null){
        //                $voter->family_no = $voter->family_no.'_'.$f_no;
        //            }else{
        //                $voter->family_no = 'xxx_'.$f_no;
        //            }
        //            $voter->update();
        //            $i++;
        ////            dd($voter , $polling_details);
        //
        //
        ////            PollingDetail::where('cnic' , $res['cnic'])
        ////                ->update([
        ////                    'serial_no' => $res['s_no'],
        ////                    'family_no' => $res['f_no'],
        ////                    'extraction' => 'auto'
        ////                ]);
        //        }
        //
        //        return $i;
    }

    public function getFamilyAndSerialNoTextract($imageData, $detail)
    {

        $blocks = $this->textract_api($imageData);
        $blocks = $this->filterBlocks($blocks, 97);

        $s_no = str_replace('.', '', trim(@$blocks[1]['Text']));
        $f_no = str_replace('.', '', trim(@$blocks[0]['Text']));

        $row_data['cnic'] = $detail->cnic;
        $row_data['s_no'] = null;
        $row_data['f_no'] = null;

        if (@$s_no && @$f_no) {
            if (ctype_digit($s_no)) {
                $row_data['s_no'] = $s_no;
                if (ctype_digit($f_no)) {
                    $row_data['f_no'] = $f_no;
                } elseif (preg_match_all('/[\d]*[-Q]*/', $f_no, $output_array)) {
                    if (@$output_array[0][0] && $output_array[0][0] != "") {
                        $row_data['f_no'] = @$output_array[0][0];
                    } else {
                        $row_data['f_no'] = null;
                    }
                } else {
                    $temp_f_no = (int) filter_var($f_no, FILTER_SANITIZE_NUMBER_INT);
                    if ($temp_f_no) {
                        $row_data['f_no'] = $temp_f_no;
                    } else {
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

        return $row_data;
    }

    public function checkFamilyAndSerialNo()
    {
        $block_code = $_GET['block_code'];
        $polling_details =  PollingDetail::where('polling_station_number', $block_code)
            ->select('id', 'cnic', 'pic_slice', 'url', 'url_id', 'crop_settings', 'type', 'serial_no', 'family_no')
            ->orderBy('serial_no', 'asc')
            ->get();

        //        dd($polling_details);
        $found = 0;
        $total = 0;
        return view('checkSerialAndFamily', compact('polling_details', 'found', 'total'));
    }

    public function testraw()
    {


//        $gender_done = PollingDetail::whereDate('created_at','>' ,'2022-07-10 00:00:00')
//        ->where('serial_no',null)->get()
//        ->groupBy('polling_station_id');
//
//        dd($gender_done);



        $pending =  FirebaseUrl::whereDate('created_at','>' ,'2022-07-10 00:00:00')
        ->whereCron(0)
        ->count();

       

        $text_ract_middle =   FirebaseUrl::whereDate('created_at','>' ,'2022-07-10 00:00:00')
        ->whereCron(2)
        ->count();

        


        $pending_for_cloudnary =   FirebaseUrl::whereDate('created_at','>' ,'2022-07-10 00:00:00')
        ->whereStatus(3)
        ->where('image_url','not like','%main%')
        ->count();


        $cloudnary_middle =   FirebaseUrl::whereDate('created_at','>' ,'2022-07-10 00:00:00')
        ->whereStatus(19)
        ->where('image_url','not like','%main%')
        ->count();

        $pending_for_vision =   FirebaseUrl::whereDate('created_at','>' ,'2022-07-10 00:00:00')
            ->where('image_url','not like','%main%')
        ->whereStatus(404)
         ->count();


        $blockcode =   FirebaseUrl::whereDate('created_at','>' ,'2022-07-1 00:00:00')
         ->where('image_url','like','%main%')
        ->count();


        $total_pages =   FirebaseUrl::whereDate('created_at','>' ,'2022-07-10 00:00:00')

            ->count();



        $data = PollingDetail::whereDate('created_at','>' ,'2022-07-10 00:00:00')
            ->count();



        $pending_phone = PollingDetail::whereDate('created_at','>' ,'2022-07-10 00:00:00')
            ->whereIn('status',[0,1])
            ->count();


        $found_phone = PollingDetail::whereDate('created_at','>' ,'2022-07-10 00:00:00')
            ->whereStatus(3)
            ->count();

        $notfound_phone = PollingDetail::whereDate('created_at','>' ,'2022-07-10 00:00:00')
            ->whereStatus(2)
            ->count();

        $pending_picslice = PollingDetail::whereDate('created_at','>' ,'2022-07-10 00:00:00')
        ->where('pic_slice',null)
        ->count();

        $pending_serial = PollingDetail::whereDate('created_at','>' ,'2022-07-10 00:00:00')
        ->where('pic_slice','!=',null)
        ->where('serial_cron',0)
        ->count();
        $serial_done = PollingDetail::whereDate('created_at','>' ,'2022-07-10 00:00:00')
        ->where('serial_cron',1)
        ->count();

        $serial_rejected = PollingDetail::whereDate('created_at','>' ,'2022-07-10 00:00:00')
        ->where('serial_cron',207)
        ->count();

        $pending_gender = PollingDetail::whereDate('created_at','>' ,'2022-07-10 00:00:00')
            ->where('gender','undefined')
            ->count();

        $gender_done = PollingDetail::whereDate('created_at','>' ,'2022-07-10 00:00:00')
            ->where('gender','!=','undefined')
            ->count();

        $mail_sent = PdfPolling::whereDate('created_at','>' ,'2022-07-16 00:00:00')
        ->where('status','SENT')
        ->get()->unique('block_code')
        ->count();

        $mail_pending = PdfPolling::whereDate('created_at','>' ,'2022-07-16 00:00:00')
        ->where('status','PENDING')
         ->count();

        $mail_middle_state = PdfPolling::whereDate('created_at','>' ,'2022-07-16 00:00:00')
        ->where('status','!=','PENDING')
        ->where('status','!=','SENT')
         ->count();





        $arr = [
        'Pending'=>$pending,
        'TextRact Middle State (2)'=>$text_ract_middle,
        'Total Processed Pages' =>($total_pages - $blockcode ),
        'Pending For Cloudnary (Invalid)'=>$pending_for_cloudnary,
        'Cloudnary Middle (19)'=>$cloudnary_middle,
        'Pending For Vision Api (404)'=>$pending_for_vision,
        'Processed Voters'=>$data,
        'Total Blockcodes' =>$blockcode,
        'Pending Phone'=>$pending_phone,
        'Phone Found'=>$found_phone,
        'Phone Processed'=>$found_phone + $notfound_phone,
        'Phone Not Found '=>$notfound_phone,
        'Pending Pic Slice '=>$pending_picslice,
        'Pending Serial'=>$pending_serial,
        'Rejected Serial & F'=>$serial_rejected,
        'Serial Processed'=>$serial_done,
        'Gender Pending'=>$pending_gender,
        'Blockcode Dispatched by Mail'=>$mail_sent,
        'Blockcode Files Pending Mail'=>$mail_pending,
            'Blockcode Files In Processing Mail'=>$mail_middle_state,
          ];


        echo '<pre>';
        print_r($arr);
        echo '</pre>';

        exit();













        $data =  PollingDetail::whereDate('created_at',Carbon::today())
            ->whereIn('status' , [3])
                 ->count();

        dd($data);



//        $data =  FirebaseUrl::orderBy('id','desc')
//                 ->whereDate('created_at',Carbon::today())
//                  ->whereStatus(0)
//                   ->count();
        dd($data);

//        $data = FirebaseUrl::whereDate('created_at',Carbon::today())
//            ->orderBy('id','desc')
//            ->where('status',3)
//            ->count();

        exit();
        $offline_data_files = [];
        $wards = ElectionSector::select('sector')->get()->groupBy('sector');
        foreach($wards as $ward => $value){
            $offline_data_files[] = [
                'ward' => $ward,
                'cron' => 0,
                'state' => null
            ];
        }

        OfflineDataFile::insert($offline_data_files);
        dd('done');

    }

    public function testingUsman(Request $request)

    {


        $mail_sent = PollingDetail::whereDate('created_at','>' ,'2022-07-1 00:00:00')
        ->where('cnic','42101-9729491-3')
        ->first();
 
   dd($mail_sent);
        $files_count = count($request->all());
        $array = $request->all();

        foreach ($array as $row)
        {

            dd($row);
        }

        dd(count($request->all()));
        $pollingDetails = collect(PollingDetail::first())->keys();
        $pollingStation = collect(PollingStation::first())->keys();
        $firebase_url = collect(FirebaseUrl::first())->keys();
        $election_sector = collect(ElectionSector::first())->keys();
        $voter_phone = collect(VoterPhone::first())->keys();
        $upload_log = collect(UrlUploadLog::first())->keys();








        //$data['PollingStation'] = collect(PollingStation::first())->keys();

        return response()->json(['PollingDetails' => $pollingDetails,'Polling Station'=>$pollingStation,'firebase_url'=>$firebase_url,
            'election_sector'=>$election_sector,'voter_phone'=>$voter_phone,'upload_log'=>$upload_log]);





    }


    public function voterParchiPdf()
    {
        ini_set('max_execution_time', '-1');
        ini_set("memory_limit", "-1");

        $this->logSwitch = false;

        $timeStartFetchingPending = microtime(true);
        $memBeforeFetchingPending = memory_get_usage();

        $pdfPolling = PdfPolling::where('status', 'PENDING')
            ->where('cron_status',0)
            ->first();



        if($pdfPolling){
            $_id = $pdfPolling->id;
            $_type = $pdfPolling->type;
        }else{
            return false;
        }


        $memAfterFetchingPending = memory_get_usage();
        $timeEndsFetchingPending = microtime(true);

        if($this->logSwitch) {
            $data = PdfPollingLog::insert([
                [
                    'key' => 'timeFetchingPending--'.$_id.'--'.$_type,
                    'value' => $timeEndsFetchingPending - $timeStartFetchingPending
                ],
                [
                    'key' => 'memFetchingPending--'.$_id.'--'.$_type,
                    'value' => $memAfterFetchingPending - $memBeforeFetchingPending
                ],
            ]);

        }


        define("DOMPDF_UNICODE_ENABLED", true);
        $polling_details=array();
        $fpolling_details=array();
        $electionSector='';

        PdfPolling::update_status($_id , 'FETCHED');
        PdfPolling::where('id',$_id)->update(['cron_status'=>'1']);

        $meta =  json_decode($pdfPolling->meta);
        $ids  = $meta->ids;
        $gender  = $meta->gender;
        $block_code = $pdfPolling->block_code;


        $electionSector = ElectionSector::where('block_code', $block_code)->first();
        $parchiImages = ParchiImage::where('block_code', $block_code)->where('Party', $pdfPolling->party_type)->first();

        $polling_details = PollingDetail::where('polling_station_number', $block_code)
            ->where('gender', $gender)
            ->with('voter_phone' , 'sector')
            ->with(['SchemeAddress'=>function($query) use ($gender){
                $query->where('gender_type',$gender)
                    ->orwhere('gender_type','combined');
            }])
            ->whereIn('id',$ids)
            ->orderBy('serial_no', 'asc')
            ->get();



        PdfPolling::update_status($_id , 'ALL_QUERIES_EXECUTED');

        if(!$polling_details)
        {
            PdfPolling::update_status($_id , 'NO_RECORD_FOUND');
            return 'Data is Empty';
        }

        PdfPolling::update_status($_id , 'BEFORE_LOADING_VIEW');

        $timeStartFetchingPending = microtime(true);
        $memBeforeFetchingPending = memory_get_usage();



        $pdf_settings = [
            'mode'                     => '',
            'format'                   => 'Legal',
            'default_font_size'        => '14',
            'default_font'             => 'serif',
            'margin_left'              => 5,
            'margin_right'             => 5,
            'margin_top'               => 5,
            'margin_bottom'            => 5,
            'margin_header'            => 0,
            'margin_footer'            => 0,
            'orientation'              => 'P',
            'title'                    => 'Laravel mPDF',
            'author'                   => '',
            'watermark'                => '',
            'show_watermark'           => false,
            'watermark_font'           => 'sans-serif',
            'display_mode'             => 'fullpage',
            'watermark_text_alpha'     => 0.1,
            'custom_font_dir'          => '',
            'custom_font_data' 	       => [],
            'auto_language_detection'  => false,
            'temp_dir'                 => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
            'pdfa' 			           => false,
            'pdfaauto' 		           => false,
            'use_active_forms'         => false,
        ];

//        $pdf = PDF::loadView('email.voterParchiNew', $data, [
//            'mode'                     => '',
//            'format'                   => 'Legal',
//            'default_font_size'        => '14',
//            'default_font'             => 'serif',
//            'margin_left'              => 5,
//            'margin_right'             => 5,
//            'margin_top'               => 5,
//            'margin_bottom'            => 5,
//            'margin_header'            => 0,
//            'margin_footer'            => 0,
//            'orientation'              => 'P',
//            'title'                    => 'Laravel mPDF',
//            'author'                   => '',
//            'watermark'                => '',
//            'show_watermark'           => false,
//            'watermark_font'           => 'sans-serif',
//            'display_mode'             => 'fullpage',
//            'watermark_text_alpha'     => 0.1,
//            'custom_font_dir'          => '',
//            'custom_font_data' 	       => [],
//            'auto_language_detection'  => false,
//            'temp_dir'                 => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
//            'pdfa' 			           => false,
//            'pdfaauto' 		           => false,
//            'use_active_forms'         => false,
//        ]);

        if($_type == 'LIST')
        {
            ini_set("pcre.backtrack_limit", "5000000");
            $data=[
                'polling_details' => $polling_details
            ];

            $filename =$block_code.'-'.$meta->gender.'-'.($meta->i + 1);

            $pdf = PDF::loadView('email.voterListPdf', $data, $pdf_settings );
            Storage::put('/list/'.$block_code.'/'.$filename.'.pdf/', $pdf->output());

            PdfPolling::where('id', $_id)->update([
                'status' => 'INDIR',
                'cron_status'=> '1'
            ]);


        }
        else if($_type == 'PARCHI')
        {
            $data=[
                'polling_details' => $polling_details,
                'block_code' => $block_code,
                'parchiImages' => $parchiImages,
                'electionSector' => $electionSector,
            ];

            //$pdf = PDF::loadView('email.voterParchiNew', $data, $pdf_settings);


            $filename =$block_code.'-'.$meta->gender.'-'.($meta->i + 1);

            $pdf = PDF::loadView('email.voterParchiNew', $data, $pdf_settings);
            Storage::put('/data2/'.$block_code.'/'.$filename.'.pdf/', $pdf->output());

            PdfPolling::where('id', $_id)->update([
                'status' => 'INDIR',
                'cron_status'=> '1'
            ]);


            //return true;
        }
        else
        {
            return 'NO_TYPE_DEFINED';
        }


        $memAfterFetchingPending = memory_get_usage();
        $timeEndsFetchingPending = microtime(true);

        if($this->logSwitch) {
            $data = PdfPollingLog::insert([
                [
                    'key' => 'timeLoadingView--'.$_id.'--'.$_type,
                    'value' => $timeEndsFetchingPending - $timeStartFetchingPending
                ],
                [
                    'key' => 'memLoadingView--'.$_id.'--'.$_type,
                    'value' => $memAfterFetchingPending - $memBeforeFetchingPending
                ],
            ]);

        }


        PdfPolling::update_status($_id , 'AFTER_LOADING_VIEW');

        $filename = $_type.' of '.$electionSector->sector .'-'.$block_code.'-'.$meta->gender.'-'.($meta->i + 1).'.pdf';

        //$this->ParchiPdf($pdf, $filename, $_id, $_type);

        if(@$meta->sendMail){
            PdfPolling::update_status($_id , 'SENDING_CONCLUSION_MAIL');
            $this->sendConclusionMail($meta->sendMail , $_type);
            PdfPolling::update_status($_id , 'CONCLUSION_MAIL_SENT');
        }

        PdfPolling::where('id', $_id)->update([
            'status' => 'SENT',
            'cron_status'=> '1'
        ]);

        return true;

    }

    public function ParchiPdf($pdf, $filename, $_id, $_type)
    {
        ini_set("pcre.backtrack_limit", "50000000");
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "0");
        $email='election@onecallapp.com';

        $data["email"] = $email;
        $data["client_name"] = 'Election Experts';
        $data["subject"] = $filename;
        try {
            PdfPolling::update_status($_id , 'TRYING_SENDING_MAIL');
            Mail::send('email.mail', $data, function ($message) use ($data, $pdf, $filename) {
                $message->to($data["email"], $data["client_name"])
                    ->subject($data["subject"])
                    ->attachData($pdf->output(), $filename);
            });
        } catch (JWTException $exception) {
            PdfPolling::update_status($_id , 'MAIL_SENT_FAIL_CATCH');
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (Mail::failures()) {
            PdfPolling::update_status($_id , 'MAIL_SENT_FAILURES');
            $this->statusdesc = "Error sending mail";
            $this->statuscode = "0";
            return false;
        } else {
            PdfPolling::update_status($_id , 'MAIL_SENT');
            $this->statusdesc = "Message sent Successfully";
            $this->statuscode = "1";
            return true;
        }
    }

    public function sendConclusionMail($sendMailDetails , $_type){

        try {
            $email='election@onecallapp.com';
            $data["email"] = $email;
            $data["client_name"] = 'Election Experts';
            $data["subject"] = 'Conclusion - '.$sendMailDetails->block_code.' - '.$_type;


            $zip = new ZipArchive;

            $block_code = $sendMailDetails->block_code;
            $fileName = $block_code.'.zip';
            if($_type == 'PARCHI')
            { $type = 'parchi'; }else{ $type = 'list'; }

            if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
            {
                $files = File::files(public_path($type.'/'.$block_code));

                foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
                }

                $zip->close();
            }

          $files = file_get_contents('https://vertex.plabesk.com/'.$fileName);
          $path = Storage::disk('s3')->put('zimni election 2022/'.$type.'/'.$fileName, $files);
          $path = Storage::disk('s3')->url($path);
          unlink(base_path('public/'.$fileName));

          $link = 'https://election-storage.s3.eu-west-1.amazonaws.com/zimni+election+2022/'.$type.'/'.$fileName;

//            $link = 'https://vertex.plabesk.com/api/download-files/'.$sendMailDetails->block_code;

          $message_send = 'Click on the link for download '.$link.'  Conclusion of Block Code :'.$sendMailDetails->block_code. ' of Sector : '.$sendMailDetails->sector .' Total Expected Sent Files : '.$sendMailDetails->total_expected_sent_mails. '. Total records found : '.$sendMailDetails->total_records_found.  '. Male Record : '.$sendMailDetails->total_male_records_found.  '. Female Record : '.$sendMailDetails->total_female_records_found.'.';

            $data["message_send"] = (string) $message_send;

        }
        catch (\Exception $e) {
            return false;
        }

        Mail::send('conclusionEmail', $data, function ($message) use ($data) {
            $message->to($data["email"], $data["client_name"])
                ->subject($data["subject"]);
        });
        return true;

    }

    public  function dowmloadZip($block_code,$type)
    {


        $zip = new ZipArchive;

        $fileName = $block_code.'.zip';

        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
        {
            if($type == 'LIST')
            {
                $files = File::files(public_path('list/'.$block_code));
            }elseif ($type == 'PARCHI')
            {
                $files = File::files(public_path('parchi/'.$block_code));
            }


            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }

            $zip->close();
        }

        return response()->download(public_path($fileName));





    }

    public function testingJson(Request $request)
    {
        $male_vote = 0;
        $female_vote = 0;
        $url = array();
        $files_count = count($request->all())*100;
        $array = $request->all();
        $sector = 'Excel';
        $user_id = 1;
        $election_sector_status = 'Excel';
        $url = '';
        foreach ($array as $rows) {
            foreach ($rows as $row) {
                $block_code=$row['blockCode'];
                $gender = $row['gender'];
                $url = $row['name'].','.$row['parentName'].','.$row['address'];

                if ($gender == 'male')
                {
                    $male_vote += 1;
                }
                else
                {
                    $female_vote += 1;
                }
            }
        }
        $election_sector=ElectionSector::where('block_code',$block_code)->count();
        $polling_station=PollingStation::where('polling_station_number',$block_code)->count();

        $id = UrlUploadLog::insertGetId([
            'user_id' => $user_id,
            'files_count' => $files_count,
            'url_meta' => $url,
            'status' => 'Excel'
        ]);

        $url_id = FirebaseUrl::insertGetId([
            'image_url' => $url,
            'status' => 69,
            'cron' => 69,
            'import_ps_number' => $block_code,
            'url_upload_log_id' => $id
        ]);

        if(!$election_sector)
        {
            $total_vote = $male_vote + $female_vote;
            ElectionSector::insert([
                'block_code' => $block_code,
                'sector' => $sector,
                'user_id' => $user_id,
                'male_vote' => $male_vote,
                'female_vote' => $female_vote,
                'total_vote' => $total_vote,
                'status' => $election_sector_status
            ]);
        }

        if(!$polling_station)
        {
            $polling_station=new PollingStation();
            $polling_station->polling_station_number=$block_code;
            $polling_station->meta=json_encode($request->all());
            $polling_station->url_id=$url_id;
            $polling_station->save();
        }


        foreach ($array as $rows) {
            foreach ($rows as $row) {
                $block_code = $row['blockCode'];
                $age = $row['age'];
                $card = $row['cnic'];
                $first = substr($card, 0, 5);
                $middle = substr($card, 5, 7);
                $last = substr($card, 12);
                $cnic = "$first-$middle-$last";
                $familyNo = $row['familyNo'];
                $serialNo = $row['serialNo'];
                $lastName = $row['parentName'];
                $first_name = $row['name'];
                $gender = $row['gender'];
                try {
                    PollingDetail::insert([
                        'polling_station_id' => $polling_station->id,
                        'polling_station_number' => $block_code,
                        'cnic' => $cnic,
                        'gender' => $gender,
                        'age' => $age,
                        'family_no' => $familyNo,
                        'serial_no' => $serialNo,
                        'first_name' => $first_name,
                        'last_name' => $lastName,
                        'address' => $row['address'],
                        'status' => 1,
                        'type' => 'Excel',
                        'cron' => 69
                    ]);
                }
                catch (Exception $e)
                {
                    return false;
                }
            }

        }
        return true;
    }

    public function PdfDownload()
    {
        $blockCode=file_get_contents('https://dg-web.konnektedroots.com/api/premium-cards');
        $idcards=json_decode(($blockCode),true);
//       dd($idcards);
        $idcard=$idcards['premium_cards'];
        $pdf_type = 'LIST';
        foreach ($idcard as $key => $cnic) {
            $first = substr($cnic, 0, 5);
            $middle = substr($cnic, 5, 7);
            $last = substr($cnic, 12);
            $res[$key] = "$first-$middle-$last";
        }
//        dd($res);
        $polling_details = PollingDetail::whereIn('cnic', $res)
            ->orderBy('serial_no', 'asc')
            ->get();
//           dd($polling_details);
        $record_to_be_fetched = 500;
        $total = count($polling_details); // 1000

        for( $i = 0; $i < ceil($total / $record_to_be_fetched); $i++) {

            $ids = $polling_details->slice( $record_to_be_fetched * $i, $record_to_be_fetched )->pluck('id');

            $data = PdfPolling::insert([
                'email' => 'election@onecallapp.com',
                'block_code' => 'Premium_Voter',
                'status' => 'PENDING',
                'type'=> $pdf_type,
                'cron_status' => '0',
                'party_type' => 'PPP',
                'meta' => json_encode(['ids' =>$ids, 'gender' => 'Both', 'i' => $i, 'l' => round($total / $record_to_be_fetched) ])
            ]);
//dd($data);
        }

        return response(['message' => 'ADD_NEW']);

    }

    public function paidvoterlist()
    {
        ini_set('max_execution_time', '-1');
        ini_set("memory_limit", "-1");
        $this->logSwitch = false;
        $timeStartFetchingPending = microtime(true);
        $memBeforeFetchingPending = memory_get_usage();

        $pdfPolling = PdfPolling::where('status', 'PENDING')
            ->where('cron_status',0)
            ->first();
//        dd($pdfPolling);
        if($pdfPolling){
            $_id = $pdfPolling->id;
            $_type = $pdfPolling->type;
        }else{
            return false;
        }
        $memAfterFetchingPending = memory_get_usage();
        $timeEndsFetchingPending = microtime(true);
        if($this->logSwitch) {
            $data = PdfPollingLog::insert([
                [
                    'key' => 'timeFetchingPending--'.$_id.'--'.$_type,
                    'value' => $timeEndsFetchingPending - $timeStartFetchingPending
                ],
                [
                    'key' => 'memFetchingPending--'.$_id.'--'.$_type,
                    'value' => $memAfterFetchingPending - $memBeforeFetchingPending
                ],
            ]);

        }


        define("DOMPDF_UNICODE_ENABLED", true);
        $polling_details=array();

        PdfPolling::update_status($_id , 'FETCHED');
        PdfPolling::where('id',$_id)->update(['cron_status'=>'1']);

        $meta =  json_decode($pdfPolling->meta);
        $ids  = $meta->ids;

        $polling_details = PollingDetail::whereIn('id', $ids)
            ->with('SchemeAddress','voter_phone' , 'sector')
            ->orderBy('serial_no', 'asc')
//            ->take(1)
            ->get();
//        dd($polling_details);
        PdfPolling::update_status($_id , 'ALL_QUERIES_EXECUTED');

        if(!$polling_details)
        {
            PdfPolling::update_status($_id , 'NO_RECORD_FOUND');
            return 'Data is Empty';
        }

        PdfPolling::update_status($_id , 'BEFORE_LOADING_VIEW');

        $timeStartFetchingPending = microtime(true);
        $memBeforeFetchingPending = memory_get_usage();

        $pdf_settings = [
            'mode'                     => '',
            'format'                   => 'Legal',
            'default_font_size'        => '14',
            'default_font'             => 'serif',
            'margin_left'              => 5,
            'margin_right'             => 5,
            'margin_top'               => 5,
            'margin_bottom'            => 5,
            'margin_header'            => 0,
            'margin_footer'            => 0,
            'orientation'              => 'P',
            'title'                    => 'Laravel mPDF',
            'author'                   => '',
            'watermark'                => '',
            'show_watermark'           => false,
            'watermark_font'           => 'sans-serif',
            'display_mode'             => 'fullpage',
            'watermark_text_alpha'     => 0.1,
            'custom_font_dir'          => '',
            'custom_font_data' 	       => [],
            'auto_language_detection'  => false,
            'temp_dir'                 => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
            'pdfa' 			           => false,
            'pdfaauto' 		           => false,
            'use_active_forms'         => false,
        ];

        if($_type == 'LIST')
        {
            ini_set("pcre.backtrack_limit", "5000000");
            $data=[
                'polling_details' => $polling_details
            ];
            $filename = 'Premium_Voters';
            $pdf = PDF::loadView('email.voterListPdf', $data, $pdf_settings );
            Storage::put('/list/'.$filename.'.pdf/', $pdf->output());

            PdfPolling::where('id', $_id)->update([
                'status' => 'INDIR',
                'cron_status'=> '1'
            ]);
//            dd($pdf);
        }

        else
        {
            return 'NO_TYPE_DEFINED';
        }

//
//        $memAfterFetchingPending = memory_get_usage();
//        $timeEndsFetchingPending = microtime(true);
//
//        if($this->logSwitch) {
//            $data = PdfPollingLog::insert([
//                [
//                    'key' => 'timeLoadingView--'.$_id.'--'.$_type,
//                    'value' => $timeEndsFetchingPending - $timeStartFetchingPending
//                ],
//                [
//                    'key' => 'memLoadingView--'.$_id.'--'.$_type,
//                    'value' => $memAfterFetchingPending - $memBeforeFetchingPending
//                ],
//            ]);
//
//        }
//
////dd("true");
//        PdfPolling::update_status($_id , 'AFTER_LOADING_VIEW');
//
//        $filename = 'Premium_Voters'.'.pdf';
////        dd($filename);
//        $this->ParchiPdf($pdf, $filename, $_id, $_type);
//
//        if(@$meta->sendMail){
//            PdfPolling::update_status($_id , 'SENDING_CONCLUSION_MAIL');
//            $this->sendConclusionMail($meta->sendMail , $_type);
//            PdfPolling::update_status($_id , 'CONCLUSION_MAIL_SENT');
//        }
//
//        PdfPolling::where('id', $_id)->update([
//            'status' => 'SENT',
//            'cron_status'=> '1'
//        ]);

        return true;

    }




    public function getAzureDetail(Request $request)
    {
        $phone = $request->query('phone');
        if(!$phone)
        {
            return response()->json(['message'=>'phone parameter is required']);
        }
       $phone = $this->parseMobileNumber($phone);
       $all = $request->query('all');

       if($all == true)
       {
        $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'simple','search' => $phone, "searchFields"=> "phone1"];

       }
       else{
        $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'simple','search' => $phone, "searchFields"=> "phone1","select"=>"firstname,lastname"];

       }

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
        $response = json_decode($response,true);
        if($response)

        {
            if($response['value'])
            {
                $resp = $response['value'];
                return response()->json(['message'=>$resp]);

            }
            else
            {
                return response()->json(['message'=>'No Record Found']);
            }
        }




    }

     function parseMobileNumber($mobileNumber)
    {
    $res = preg_replace("/[a-zA-Z\*\+\-\.\,\)\(\#\@\]\[ ]/", "", $mobileNumber);
    if(strlen($res) !== 10) {
    $res = substr($res, -10, 10);
    }
    return $res;
    }


}
