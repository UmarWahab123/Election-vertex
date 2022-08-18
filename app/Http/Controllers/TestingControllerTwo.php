<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\ElectionSectorController;
use App\Http\Controllers\Admin\FirebaseUrlsController;
use App\Models\PollingDetail;
use App\Models\ParchiImage;

use App\Models\VoterPhone;
use Aws\Textract\TextractClient;
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
use App\Models\ElectionSector;
use Session;

use function Sodium\add;
use Cloudinary\Configuration\Configuration;

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/one-call-59851-40f314185b47.json');


class TestingControllerTwo extends Controller
{

    /*check for election multiple  cnic record expert*/
    public function identify(Request $request)
    {



        $cnic=json_decode($request->data,true);



//        $cnic=["3520226642371","3520248378198","3520227423341"];

        $not_found=[];

        foreach ($cnic as $key=> $card)
        {




            $first = substr($card, 0, 5);
            $middle = substr($card, 5, 7);
            $last = substr($card, 12);
            $res = "$first-$middle-$last";
            $check=PollingDetail::where('cnic',$res)->first();
            if(!$check)
            {
                $not_found[]=$key;

            }




        }

        return json_encode($not_found,true);

        if ($check->gender == 'male')
        {
            $pollingDetails = PollingDetail::where('cnic', $res)
                ->with('sector','voter_phone')
                ->with(['schemeAddress' =>function($query){
                    $query->where('gender_type','male')
                        ->orwhere('gender_type','combined');
                }])
                ->first();
        }
        else if ($check->gender == 'female')
        {
            $pollingDetails = PollingDetail::where('cnic', $res)
                ->with('sector','voter_phone')
                ->with(['schemeAddress' =>function($query){
                    $query->where('gender_type','female')
                        ->orwhere('gender_type','combined');
                }])
                ->first();
        }
        // dd($pollingDetails->sector->sector);
        if ($pollingDetails) {

            return response()->json(['pollingDetails' => $pollingDetails, 'Message' => 'Record_found']);

        }
        else
        {
            $check=PollingDetail::where('cnic',$card)->first();
            if ($check->gender == 'male')
            {
                $pollingDetails = PollingDetail::where('cnic', $card)
                    ->with('sector','voter_phone')
                    ->with(['schemeAddress' =>function($query){
                        $query->where('gender_type','male')
                            ->orwhere('gender_type','combined');
                    }])
                    ->first();
            }
            else if ($check->gender == 'female')
            {
                $pollingDetails = PollingDetail::where('cnic', $card)
                    ->with('sector','voter_phone')
                    ->with(['schemeAddress' =>function($query){
                        $query->where('gender_type','female')
                            ->orwhere('gender_type','combined');
                    }])
                    ->first();
            }
//            $pollingDetails = PollingDetail::where('cnic', $card)->with('sector','voter_phone','schemeAddress')->first();

            if ($pollingDetails)
            {
                return response()->json(['pollingDetails' => $pollingDetails, 'Message' => 'Record_found']);
            }
            else
            {
                return response()->json(['Message' => 'Record_Not_Found']);
            }
        }
    }


    public function rawquery()
    {



//        $electionSector=ElectionSector::select('block_code')->where('sector','NA-133')->groupby('block_code')->get();
//        dd($electionSector);

//        $id_card=25201256;
//       $phone= VoterPhone::whereHas('polling_detail',function ($query) use($id_card){
//           $query->whereIn('cnic',)
//
//       })->first();
//
//
//       dd($phone);

//

//dd($data);

//
//        $urls_ids = $data->pluck('id');
//        FirebaseUrl::whereIn('id' , $urls_ids)->update([
//            'cron' => '1',
//            'status' => '3',
//            'log_state' => 'Revert to cron cloudinary'
//        ]);


//        dd($data);
//        dd(count($data));
//
//

//            ->count();
////          ->update(['status'=> 3]);
//////
//
//
//


//
//
//



        $aa =PollingDetail::
            with('sector','voter_phone')->
            with('schemeAddress')->
//        select('id','polling_station_number','status')->
        where('crop_settings','!=',null)->
//        orwhere('pic_slice',null)->
//        where('type','textract')->
            where('cnic','17301-4351899-5')->
//            where('created_at','>' ,'2021-11-16 00:00:00')->
//            where('created_at','<' ,'2021-12-03 00:00:00')->
//            where('polling_station_number','=' ,'037090401')->
//
                where('status',3)->
//            where('serial_cron',0)->
//    take(10)->
        get();
        dd($aa);
////
//        $duplicates = DB::table('polling_details')
//            ->select('polling_station_number', DB::raw('COUNT(*) as `count`'))
//            ->where('created_at','>' ,'2021-11-01 00:00:00')
//            ->groupBy('polling_station_number')
//            ->havingRaw('COUNT(*) > 1')
//            ->get();
//


//        $data=FirebaseUrl::select('id','status','image_url','cron')
//            //          ->where('serial_cron',0)
//
//            ->where('image_url','like','%2F259120101%')
//            ->where('created_at','>' ,'2021-11-16 18:00:00')
//
////                    ->take(20)
//            ->get()->groupby('status');
//
//        dd($data);


//        $sector= ElectionSector::where('sector',"NA-133")->get()->groupby('block_code');
//
//
//
////        $data=[];
////        foreach ($sector as $sec)
////        {
////            if(count($sec) > 1)
////            {
////                $data[]=$sec;
////            }
////        }
////
//        dd($sector);

        $duplicates = DB::table('election_sector')
            ->select('block_code', DB::raw('COUNT(*) as `count`'))
            ->where('sector',"NA-133")
            ->groupBy('block_code')
            ->havingRaw('COUNT(*) > 1')
            ->get();
////
////
        dd($duplicates,$duplicates->sum('total_vote'));




        $data= PollingStation::first();

        dd($data);





    }



    public function cloudinary_api(){

        $url = 'https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/politics%2F188010104%2F1628110700682.jpg?alt=media&token=926eee32-0e79-49a6-ae83-8c8caaba7839';

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => 'one-call-app',
                'api_key'  => '231534778562361',
                'api_secret' => 'V8j97Iq0SWLKCMpNg7gqUnYHxWo',
                'url' => [
                    'secure' => true]]]);

        $res = $cloudinary->uploadApi()->upload($url,
            ["ocr" => "adv_ocr"]);

        dd($res);

    }

    public function voterDetailEntry($blockcode){
        ini_set("memory_limit", -1);
        set_time_limit(0);
        $polling_details = PollingDetail::where('polling_station_number' , $blockcode)
            ->where(function($q) {
                $q->where('serial_no' , null)->orWhere('family_no' , null);
            })
//            ->where('serial_no' , null)
//            ->orWhere('family_no' , null)
            ->orderBy('url_id' , 'asc')
            ->orderBy('id' , 'asc')
            ->paginate(100);
//            ->get();

        return view('voterDetailEntry' , compact('polling_details' , 'blockcode'));
    }
    public function voterDetailEntryRecheck($blockcode)
    {
        ini_set('max_execution_time', '-1');
        $mpolling_details = PollingDetail::where('polling_station_number', $blockcode)
            ->select('id', 'cnic', 'pic_slice', 'url', 'crop_settings', 'type', 'serial_no', 'family_no','url_id','polling_station_number')
            ->where('gender', '=', "male")
//            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
//            ->take(50)
            ->with('voter_phone')
            ->with(['firebase_url'=>function($query){
                $query->select('id','import_ps_number')->withcount('polling_details');
            }])
            ->get()
            ->groupBy('serial_no');






        $fpolling_details = PollingDetail::where('polling_station_number', $blockcode)
            ->select('id', 'cnic', 'pic_slice', 'url', 'crop_settings', 'type', 'serial_no', 'family_no','url_id','polling_station_number')
            ->where('gender' , '=' , "female")
//            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
//            ->take(50)
            ->with('voter_phone')
            ->with(['firebase_url'=>function($query){
                $query->select('id','import_ps_number')->withcount('polling_details');
            }])

            ->get()
            ->groupBy('serial_no');






        return view('voterDetailEntryRecheck', compact('blockcode', 'mpolling_details','fpolling_details'));

    }


    public function discrip($blockcode)
    {
        $mpolling_details = PollingDetail::where('polling_station_number', $blockcode)
            ->where('gender', '=', "male")
//            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
//            ->take(50)
            ->with('voter_phone')
            ->get()
            ->groupBy('serial_no');

        $fpolling_details = PollingDetail::where('polling_station_number', $blockcode)
            ->where('gender', '=', "female")
//            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
//            ->take(50)
            ->with('voter_phone')
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

    public function delete_details($blockcode){
        $firebase_urls = FirebaseUrl::where('import_ps_number' , $blockcode)->get();
        foreach ($firebase_urls as $key => $value){
            PollingDetail::where('url_id' , $value->id)->delete();
            PollingStation::where('url_id' , $value->id)->delete();

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

    //Poling station details extraction and storing

    public function voter_card()
    {

        $data = $this->demo_data();
        $queryCnicString = '';
        foreach ($data as &$item) {
            $item[0] = (int) filter_var($item[0], FILTER_SANITIZE_NUMBER_INT);
            $item[1] = (int) filter_var($item[1], FILTER_SANITIZE_NUMBER_INT);
        }

        $block_code = '187070201';

        ini_set('max_execution_time', '-1');
        $polling_details = PollingDetail::where('polling_station_number', $block_code)
//            ->where('urdu_text' , '!=' , null)
//            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
            ->take(10)
            ->with('voter_phone')
            ->get();

        return view('voterCard', compact('data', 'block_code','polling_details'));
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
            ->where('serial_no', '!=', null)
            ->where('urdu_text', '!=', null)
            ->take(50)
            ->orderBy('serial_no', 'asc')
            ->orderBy('url_id', 'asc')
            ->get();

        foreach ($polling_details as $item) {
            $image = $item->pic_slice;
            $response = $this->findCoordsOfTextFromImage($image);
            $item->urdu_meta = $response['meta'];
            $item->update();
            $data[] = $response['data'];
        }

        //Fetching Phone numbers against NIC number from Azure
        $queryCnicString = '';

        //Getting all CNIC
        foreach ($data as $key => $value) {
            if (is_numeric(@$value[5])) {
                $cnic = str_replace('-', '', $value[4]);
                $queryCnicString = $queryCnicString . $cnic . ' ';
            }
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
                1,
                -717,
                "مارکت اند",
                "سایت تکلی",
                "35201-7803283-7",
                "32",
                "امکان نمبر 5 - م گلی نمبر 56 ، گلہ نیشنل روڈ عثمان اور شالیمار ایا لاہور کینٹ ، ضلع لاہور "
            ],
            [
                2,
                1,
                "ملک",
                "تدبیر حسین",
                "ملک",
                "ناظر حسین",
                "35201-1318241-7",
                "43",
                "مکان نمبر 91 - E ، سٹریٹ نمبر ہے ، محلہ شاد که اولی با شاسی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                2,
                1,
                "فوزیہ لیلی",
                "زود یہ ملک شاہد حسین",
                "37405-1394620-6",
                "29",
                "امکان نمبر 01 - 61- ، مٹر یٹ نمبر 4 ، محلہ شاہ کالی بلی کی قاہور کیشن ضلع اور "
            ],
            [
                3,
                1,
                "من",
                "مسیر پر",
                "ملک ناظر حسین",
                "35201-1317661-7",
                "40",
                "امکان نمبر 91 - .E گلی نمبر 4 ، گلہ بات اسے شاد کالونی اور کیٹ ، ضلع لاہور "
            ],
            [
                3,
                2,
                "اقبال بیگم",
                "زوجہ محمد اسلم",
                "35201-6610311-2",
                "64",
                "ن امکان کے نمبر 86 عه - 86- E ، محلہ ره نثار کانونی من ، بایت 8 ، مخامة اور ... تقسیل لاہور شام کیش ، اور منبع نسل اور اور "
            ],
            [
                4,
                11,
                "ملک",
                "شاہد حسین",
                "ملک",
                "ناظر حسین",
                "35201-1305801-7",
                "38",
                "مکان نمبر 91 91 - - E E گلی نمبر 4 محلے بااث اسے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                4,
                2,
                "کر اسلام",
                "و محمر اسلام",
                "35201-1412300-2",
                "37",
                "مکان نمبر نا 86 - E ، یا محلہ A با نشاط کا اول ، داث خانه نشاط اولی ، لاہور ،",
                "سیل لاہور "
            ],
            [
                5,
                1,
                "شاہبر تلی",
                "اصغر على",
                "35201-0158496-5",
                "34",
                "امکان نمبر 130 130 - - e e محلہ نشاط کا مونیر بالی رود . با گٹ اے ۔ لاہور کینٹ ، ضلع لاہور "
            ],
            [
                5,
                3,
                "رقیہ بیگم",
                "زوجہ محمد جمیل احمد",
                "35202-2650862-8",
                "75",
                "ال امکان م نمبر م 145 - م E گلی م نمبر 6 ، محله ما A با و کت شما شال ، کالونی اور سیل اگتا اور اس کینٹ کو ضلع ضلع لاہور "
            ],
            [
                6,
                1,
                "من علوم قره",
                "ملک ناظر حسین",
                "35201-4112582-1",
                "33",
                "امکان نمبر 91 91 - - E ، E مسٹر یٹ نمبر 4 ، محله و بالات نشاط کالونی اور کینٹ ، ضلع لاہور "
            ],
            [
                6,
                3,
                "نادره طام",
                "از وحجہ طامر جمیل",
                "35201-1384114-0",
                "51",
                "مکان اور کی 149 - E ، یا محلہ 6۔ بات A نشاطاء . ڈاک خانہ نشاط کالولی ، لاہور د . سلع "
            ],
            [
                7,
                1,
                "منوی سین",
                "ملک",
                "ناظر حسین",
                "35201-8679904-7",
                "32",
                "امکان نمبر 91 - e گلی نمبر 4. له نشاط کالونی . بات اے..اہور کنٹ ، ضلع لاہور "
            ],
            [
                7,
                0,
                "عروسے تین",
                "دخترمہ جمیل احمد",
                "35201-9932681-4",
                "28",
                "امکان نمبر 149 - E . سٹریٹ نمبر 6 ملے نشاط کالونی ، سکھر اسے ، اور کینٹ ، ضلع ہور "
            ],
            [
                0,
                1,
                "محمد زاہد",
                "امر علی",
                "35201-8489195-7",
                "32",
                "مکان نمبر 130 - E E ، گلی نمبر 5 محله با کت A ربانی روڑ نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                8,
                3,
                "از امروز ان طاسی",
                "دختر عامر جمیل",
                "35201-0299086-0",
                "23",
                "امکان نمبر 149 - E ٹریٹ نمبر 6 ، محلے نشاط کالونی ، با کٹ اے ، لاہور کینٹ ، ضلع اور "
            ],
            [
                9,
                1,
                "جعفر",
                "اصغر",
                "على",
                "35201-8609210-7",
                "31 31",
                "مکان نمبر 130 110 - - E .E ، گلی نمبر 5 محلہ م بلات خاط اور بائی روڈہ ناہور کینٹ ، ضلع اور "
            ],
            [
                9,
                3,
                "اقری شبیر طاس",
                "دختر طایر بیل",
                "35201-4148378-0",
                "21",
                "امکان نمبر 149 - e سٹریٹ نمبر 6 ، تله شال کالونی ، بالا گشاسته ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                10,
                4,
                "رب انار",
                "زوجہ محمد ریاضی",
                "35201-6014109_4",
                "55",
                "مکان نمبر 51 - E گلی نمبر 2 له شال کالونی . بلاك A اہور کینٹ ، ضلع لاہور "
            ],
            [
                11,
                2,
                "سلام",
                "محمد صادق مرحوم",
                "35201-1503708-7",
                "66",
                "مکان نمبر شای 86 - کینتاہور E . یا گلہ بايت مش طاسون ، ژاکت خانہ اطالول ، الہور ،",
                "لاہور "
            ],
            [
                11,
                4,
                "نیلم شهراد",
                "از وجه",
                "35201-3437302-6",
                "31 31",
                "امکان نمبر 51 - E ٹر یٹ نمبر ہی محلے ملک کی شادی ہوئی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                12,
                2,
                "حال مور",
                "رحمت علی",
                "33302-8955222-9",
                "49",
                "مکان نمبر 503 - E ، بال مسجد محله گل روز 2 شاطا وفی ، اور کینٹ لاہور "
            ],
            [
                12,
                6,
                "خاتون بیگم",
                "زوجہ محمد سعید احمد خان",
                "35201-1373748-6",
                "68",
                "ان امکان کے نمبر 16 رد - E ، مٹر عمل یٹ نمبر 4 ، ، محلہ مدل شادکالونی ، بات اسے ، ده لاہور راه کینٹ ، ضلع اکر لاہور "
            ],
            [
                13,
                2,
                "وسلام",
                "محمد اسلام",
                "35201-5981584-1",
                "36",
                "مکان نمبر 86 - E محلے شاط کالونی . بات اسے لاہور کی",
                "ضلع لاہور "
            ],
            [
                13,
                6,
                "روبینہ شاہد",
                "زوجه گم شامل",
                "35201-1278710-2",
                "45",
                "مکان اور می 16 - E ،",
                "له 4 - بای کت A شاطولیدات خانه نشاط اولی ، لاہور کین",
                "، سن "
            ],
            [
                14,
                2,
                "عالم اسلام",
                "اسلام",
                "35201-3841005-9",
                "35 35",
                "امکان نمبر 86 - E ملے بایانا",
                "نشاط کالونی ، لاہور کینٹ ضلع",
                "پور "
            ],
            [
                14,
                6,
                "اور شاہین",
                "محمد سعید احمد خان",
                "35201-1373749-6",
                "37",
                "امکان نمبر 16 16 - - E E گلی نمبر 4 ، له نشاط کا اونی ، بلوا ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                15,
                0,
                "مغموم",
                "اسلام",
                "35201-7338936-5",
                "32",
                "امکان نمبر 86 86 - - E E ، محلے بات شامل کالونی",
                "اور کینٹ ، ضلع لاہور "
            ],
            [
                15,
                6,
                "ارم شتراوی",
                "زوجہ عمران خان",
                "35201-5949680-6",
                "34",
                "امکان نمبر 16 نا1- سٹریٹ تمبر 4 ملہ شادی لونی ، بات A ' ہور کینٹ ، ضلع لاہور "
            ],
            [
                16,
                2,
                "نعیم اسلام",
                "35201-9223232-3",
                "31",
                "امکان نمبر 86- محلہم بات شاطها و نی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                103,
                16,
                "م",
                "بلالی",
                "منذر",
                "35201-3020540-3",
                "19",
                "نینس ہاؤسنگ اتھارٹی "
            ],
            [
                16,
                6,
                "سونیا شتراوی",
                "زوجہ محمد فیصل",
                "35201-0488902-8",
                "33 33",
                "امکان نمبر 1 / 98",
                "- E ، سٹریٹ نمبر 8 محلہ نثار کالونی . باکت کی ، لاہور کینٹ ، ضلع اور "
            ],
            [
                17,
                2,
                "قبل اسلام",
                "اسلام",
                "35201-7432986-5",
                "29",
                "امکان نمبر 86 86 - - E E ، محلہ نشاط کالونی ، بادث A. ' اہور کینٹ ، لاہور "
            ],
            [
                17,
                7,
                "فرزانہ بی بی",
                "زور سفیر اتم",
                "35201-7896844-6",
                "45",
                "امکان نمبر مردم 1231 > - E گلی نمبر 12 ملہ بلاکی F شاهد که او لاہور اداء :. کینٹ شعبور مه "
            ],
            [
                18,
                2,
                "نیم اسلام",
                "سلام",
                "35201-9222187-3",
                "26",
                "مکان نمبر 86 - -86 E محله شاط کالونی . بات اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                19,
                2,
                "کار علی خالی",
                "خالد محمود",
                "33302-0740886-1",
                "23",
                "امکان نمبر 503 - تا ، بلال محمد . حمله تنگ روڈ 2 ثار کا کوئی بات کی ، اور کینٹ ، ضلع لاہور "
            ],
            [
                19,
                8,
                "آسیہ پروین",
                "زوجہ سجاد علی",
                "35201-1995323-2",
                "35",
                "امکان نمبر 07 / 6/107 106 - E10 .E مشر یٹ نمبر 4 محله بالاناشاد کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                20,
                2,
                "انور خالد",
                "خالد محمود",
                "33302-0740981-1",
                "20",
                "امکان نمبر 503",
                "، بال مکبد ، عملے کروڑ 2 ثارلیمانی نیا پور کیند . ضلع اور "
            ],
            [
                20,
                9,
                "نور سلطن",
                "زوجہ ریاض احمد قریشی",
                "35201-5128429-6",
                "76",
                "مکان نمبر 160 160 - - E E گلی نمبر 7 ، له دم نشاط کالونی us آراے باراں ، لاہور او کینٹ ، ضلع لاہور",
                "مو "
            ],
            [
                21,
                3,
                "برایت",
                "می",
                "ظهور مسیح",
                "35201-4998879-5",
                "61",
                "مکان نمبر 1529 / c , مٹیریل نمبر 1.له شال گویی",
                "و کالونی اور کینٹ",
                "لاہور "
            ],
            [
                21,
                9,
                "شریعترین عام",
                "زوجہ عام ریانش قریشی",
                "37406-1567637-4",
                "48",
                "مکان کت نمبر شلاہور 1612 - E ، کی ممبر 7.مجله نشاط کالونی آراے بازار ،",
                "ای بلك A او اہور . ها",
                "اور "
            ],
            [
                860,
                992,
                "نمرہ محمود",
                "دختر محمود اختر",
                "35201-3550770-6",
                "21",
                "مکان نمبر 251 ، سٹریٹ نمبر 2 ، حقہ خیرالونی ، پلاکت کی ، اور کینٹ ، ضلع لاہور "
            ],
            [
                22,
                3,
                "اطار بن",
                "جمیل احمد",
                "35200-1567315-3",
                "52",
                "مکان میر 149 - E ، یا میرنا ، محلہ اے بلای شهرلولی ، لاہور ،",
                "لاہور کینٹ ، سلم "
            ],
            [
                22,
                10,
                "رضیہ بیگم",
                "زوجہ محمد اشغر علی",
                "35201-1440704-8",
                "72",
                "مکان ، نمبر41- لاہور E ، یا 10 محله 4 ، دا ثم ژانوی ، ڈاک خانه",
                "ول شادالولی ، لاہور ،",
                "لاہور "
            ],
            [
                23,
                3,
                "زاہر بیل",
                "مر جمیل احمد",
                "35200-1567336-5",
                "49",
                "مکان نمبر 149 149 - - E E ، گلی نمبر 6 محله م با",
                "نشاط کالونی ، لاہور ، تحصیل لاہور کینٹ ، ضلع لاہور "
            ],
            [
                23,
                10,
                "رجحان کوثر",
                "دختر اصغر علی",
                "35201-1440710-2",
                "48",
                "مکان در نمبر 41 - خلدبور E .",
                "خله 4. باشم تا . 2 کالونی ، ژاکت خانه نشاط کالونی ، لاہور ،",
                "لاہور "
            ],
            [
                24,
                3,
                "شاعر بمل",
                "محمد جمیل احم",
                "35200-1567264-3",
                "44",
                "امکان نمبر 149 - E گلی نمبر 6 ، محله نشاط کالونی . بات اسے لاہور کی",
                "، ضلع لاہور "
            ],
            [
                24,
                10,
                "فرزانہ کوثر",
                "دختر محمد اصغر منی",
                "35201-9440710-8",
                "46",
                "همگان کے می ضلع 41 ہوں E ، لاله 4 ، با شمشاط الوفيات خنشاط کالونی ، لاہور ، لاہور "
            ],
            [
                25,
                3,
                "ساجد باید بیل این",
                "و جمیل احمد",
                "35200-1547756-5",
                "38",
                "امکان تعمیر مکان 149 ، گلی محلے کیا بات اسے شادگانی ڈاک خان راستے بازار ، لاہور "
            ],
            [
                55,
                25,
                "نجمہ",
                "زوجہ مسلمان",
                "35201-1225637-0",
                "54",
                "امکان نمبر - 130-",
                "شر یٹ نمبر 5 محلہ شال کالونی رد",
                ": اور کینٹ ، . ضلع ای لاہور "
            ],
            [
                25,
                10,
                "تیل کوثر",
                "دختر محمد اصغر علی",
                "35201-2992023-8",
                "41",
                "مکان کالونی بارہویں نمبر 41 - E ، تیل گلی اوہ نمبر 4 ، کینٹ ، له ضلع نشاط کالونی لاہور ، بالا اسے بل گ ، ژاکت خانه نشاط "
            ],
            [
                26,
                3,
                "نیشان بھی",
                "نبیل احد",
                "35201-1472798-9",
                "38",
                "کینٹ مکان ، نمبر ضلع لاہور E149 .E . گلی محله 6 -6 بار کے اے ، ڈاک",
                "خانه شادکالونی ، لاہور ، تحصیل لاہور "
            ],
            [
                26,
                10,
                "ثمین کوثر",
                "اور محمد اصغر علی",
                "35201-7707236-4",
                "39",
                "مکان کینٹ ، نمبر ضلع 41 - لاہور E ، گلی نمبر 4 ، بات اے باری ڈاک خانہ نشاط کالونی ، لاہور ،",
                "لاہور "
            ],
            [
                27,
                3,
                "اشرف",
                "ہدایت می",
                "35201-5676510-3",
                "32",
                "امکان نمبر 1529 1529 / / E E ، گلی نمبر 1 محله نشاط کالونی ، لاہور میں لاہور کینٹ ضلع لاہور "
            ],
            [
                27,
                10,
                "مان احسان",
                "توجہ احسان الله",
                "35202-0548975-0",
                "35 امکان نمبر 41- سٹریٹ نمبر A4 ملے تشارکاوٹی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                28,
                3,
                "کی ہدایت",
                "برایت",
                "می",
                "35201-6433632-9",
                "27",
                "مکان نمبر 1529 / E ، سٹریٹ نمبر 1 ، محلہ شادکالونی ، اور کینٹ ، ضلعبور "
            ],
            [
                28,
                11,
                "منور کوثر",
                "زوجه در سدیق",
                "35201-1333716-8",
                "66",
                "مکان نمبر 49-48 / E ، سٹریٹ نمبر 2 ، محلہ بات اسے شاد کالونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                29,
                4,
                "محمد زاهر",
                "محمد اقبال",
                "35201-6500926-3",
                "60 60",
                "امکان نمبر 51 - E ، سٹریٹ نمبر 2 ، حلقہ نشاط کالونی ، بلاگ اسے لاہور کینٹ ، ضلع",
                "ہور "
            ],
            [
                29,
                11,
                "بشر ناز",
                "و",
                "مداق",
                "35200-1413343-2",
                "40",
                "مکان اور نمبر 49-48 - E ، کیا نمبر 2 ، محلہ م با کشاط کانونی ، لاہور ، سیل لاہور کیند ، نبلع "
            ],
            [
                30,
                4,
                "محہ ریاض",
                "اقبال",
                "35201-0112035-3",
                "58",
                "امکان نمبر 51 - E ، مٹر یٹ نمبر 2 محلہ بلاک ۸ نشاط کانونی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                30,
                11,
                "مد رو از",
                "اور محمد صدیق",
                "35201-7002312-8",
                "28",
                "امکان نمبر 48-49 49-48 / / E .E گلی نمبر حملہ شادکاونتی باکت A ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                31,
                4,
                "امانت امانت علی علی",
                "گم شفیع",
                "34602-4038590-9",
                "53",
                "امکان نمبر 4 / 1245 - E ، محلہ شاط کالونی ، ڈا پور کینٹ ضلع لاہور "
            ],
            [
                31,
                12,
                "رخسانہ اقبال",
                "زوجہ محمد اقبال",
                "35201-1521288-2",
                "55",
                "مکان نمبر 151-8 8-151 ، ، سٹریٹ نمبر 6 ، مجله نشاط کالونی ، داکت اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                32,
                4,
                "آصف ریاست",
                "دریای",
                "35201-8290377-5",
                "35",
                "امکان نمبر 51 - E سٹر بیٹ نمبر",
                "محله باش اسے شاور کالونیا اور کینٹ ، ضلع تور "
            ],
            [
                189,
                32,
                "اسلم می",
                "جیت می",
                "35201-7642510-3",
                "48",
                "لاہور مکان کینٹ ، نمبر 1806/3 لاہور . کیا میر کابل با کش ، گلے میر کی شادی اولی راستے بازار ، الا بود ، نیل "
            ],
            [
                32,
                13,
                "رحيله",
                "لي",
                "توجہ ارشد علی",
                "35201-0305466-2",
                "36",
                "کالونی مکان نمبر اور 38 - E ، تحصیل محلہ به مین کینٹ روڈ ، شاط ضلع کالونی ، لاہور سیر A باش ۸. ژاکت خانه شاد "
            ],
            [
                389,
                301,
                "ریحانہ کوث",
                "زوجہ طاہر اشرف",
                "35201-1182859-8",
                "57",
                "امکان نمبر 36 ای بگی گلے دبات اے ، ڈاگ خنه شال کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                33,
                4,
                "کاشف ریاض",
                "محمد ریاض",
                "35201-2898576-7",
                "33",
                "امکان نمبر 51 51 - - E E گلی نمبر 2 ، محلہ بدات ، نشاطی و فنی ، لاہور ، تحصیل لاہور کینٹ ، لاہور "
            ],
            [
                33,
                13,
                "هنا رخسار على على",
                "زوجہ راشد علی",
                "35201-7089294-6",
                "26",
                "محل نشاطها و نی مین بازار لاہور کینٹ ، ضلع لاہور "
            ],
            [
                34,
                4,
                "حسین علی",
                "گمراض",
                "35201-2526932-3",
                "25",
                "امکان نمبر 51 - E ، سٹریٹ نمبر 2 ملہ بات اے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                34,
                14,
                "نسرین بی بی",
                "زوجہ ریونی",
                "35201-0185247-8",
                "51",
                "امکان نمبر 159 159 - - E E ٹریٹ نمبر 7 محلہ نشاط کالونی . باکت A ، لاہور کی",
                "ضلع لاہور "
            ],
            [
                35,
                4,
                "عامر شهراد",
                "امانت على",
                "34602-2755177-1",
                "24",
                "امکان نمبر 4 / 1245 - E محله شال کالونی اور کینٹ ، ضلع لاہور "
            ],
            [
                18,
                8,
                "کرامت ليلي",
                "زوجہ محمد صدیق",
                "35201-1215981-0",
                "66",
                "مکان نمبر 107 نلترہور E106 ، میانہ 4- باتا",
                "نشاط اولیاث خانه را",
                "بازار ، امور "
            ],
            [
                35,
                14,
                "کومل یونسی",
                "دختر محمد یونس",
                "35201-6431461-6",
                "19 19",
                "اسٹریٹ نمبر 4 ، مطلہ",
                "آبادی کو بابا بیدیاں روڈ لاہور کینٹ ، ضلع لاہور "
            ],
            [
                36,
                5,
                "اليوسف",
                "طلعت",
                "درشن ایم الیں",
                "35201-3531021-3",
                "61 61 | مکان نمبر 30 17 - E محلہ بات ہے مریم خاط کالونی اور تحصیل لاہور کینٹ ، ضلع لاہور "
            ],
            [
                36,
                16,
                "ارشاد بیگم",
                "تزوج ظفراللہ خال",
                "34201-9758977-0",
                "77",
                "امکان نمبر 112 112 - - E ، E مشر بیٹ نمبر 4 ، محلہ باد کن اسے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                37,
                5,
                "عارف على",
                "شوکت على",
                "35201-3233110-1",
                "45",
                "مکان نمبر 22 قہ نشاط کالونی ، لاہور میل امور کینٹ",
                "ضلع لاہور "
            ],
            [
                37,
                17,
                "کنیز اختر",
                "توجہ دی ام",
                "35200-1426413-8",
                "58",
                "مکان نمبر 103 - E ، سٹریٹ نمبر 4 ، محلہ بات اسے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                38,
                5,
                "عاطف",
                "طلعت",
                "وسف طلعت",
                "35201-5545624-9",
                "29",
                "مکان نمبر 1730 - E ، محلہ مریم نشاط کالونی ، باد ، لاہور کی",
                "ضلع لاہور "
            ],
            [
                38,
                18,
                "انوری",
                "زوجہ مہ رشید",
                "35201-1216711-2",
                "56",
                "بازار مکان نمبر لاہور کینٹ 425 - ، E ، ضلع لاہور مرحله 4۔ بلاتی نثار کالونی آراے بازار ژاکت خانه آراے "
            ],
            [
                39,
                5,
                "آدنی علی",
                "عارف",
                "على",
                "35201-3875995-1",
                "28 28",
                "امکان نمبر 22 محله څاط کالونی ، لاہور کینٹ ، ضلع اور "
            ],
            [
                221,
                39,
                "سلیم",
                "دب",
                "حمید می",
                "35201-2750025-7",
                "24",
                "امکان نمبر 84 - -84 E ، سٹریٹ نمبر 4 محله شاطر اپنی باہور کینٹ ، ضلع لاہور "
            ],
            [
                39,
                18,
                "امین",
                "زوجہ محرومیه",
                "35201-0192177-2",
                "33",
                "امکان نمبر 425 ، سٹریٹ نمبر 4 ملے شام کا اونی ، بات کی ۔ لاہور کیث نلح را چون "
            ],
            [
                40,
                5,
                "محمد اعظم",
                "عارف على",
                "35201-4635045-7",
                "24",
                "| مکان نمبر 22 ، له نشاط کالونی ، لاہور کی",
                "ضلع اور "
            ],
            [
                40,
                19,
                "سعید واقبالی",
                "زوجہ محمد اقبال را",
                "35200-1368122-2",
                "66 66",
                "امکان نمبر",
                "- 142- E E : گلی محلے کا نشاط کالونی بلاگ اے ، اور "
            ],
            [
                281,
                158,
                "شوربیم",
                "زوجہ محمد طارق",
                "35201-1336827-6",
                "43",
                "امکان نمبر 124 124 - - E E گلی محله وشاطا ہوئی ، اور میلا ہور کینٹ ، ضلع اور "
            ],
            [
                41,
                5,
                "کاشف على",
                "تعرف على",
                "35201-1584488-9",
                "22",
                "مکان نمبر 22 ، محلہ شاط کالونی ، لاہور کینٹ ، ضلعبور "
            ],
            [
                41,
                19,
                "مہ مساج جبین",
                "زوجہ محمد جاوید اقبال",
                "35201-5221681-0",
                "31",
                "امکان نمبر 142 - E ، سٹریٹ نمبر 6 ، محلہ نشاط کالونی ، پلاٹ اے لاہور کینٹ ، ضلع اور "
            ],
            [
                42,
                6,
                "محمد سعید احمد خان",
                "محمد یاسین خان",
                "35201-1295811-7",
                "71",
                "مکان لاہور نمبر 16 - E ، کیا محلے 4 - بلاگ می نشاط کالونی ، رات دن آراے بازار ، لاہور کینٹ ، ضلع "
            ],
            [
                231,
                42,
                "قاسم علی",
                "نوشا",
                "35201-1293015-1",
                "45",
                "مگانی شمسیر B95 , E کی تعمیر کولے شاد کالونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                42,
                19,
                "تم عرقان",
                "زوجه زرقان مجید",
                "35201-1221466-8",
                "31",
                "مکان نمبر 142 - E ، تے بلا کی",
                "شرط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                43,
                6,
                "مشاب",
                "تعداد خان",
                "35201-1339448-5",
                "47",
                "مکان الامور نمبر 16 - E ، گلی محلے 4۔ بلا ثم نشاط کالونی ، ژاك خانه څارکالونی ، لاہور کینٹ ، ضلع "
            ],
            [
                43,
                20,
                "آسیہ ملک",
                "جاوید",
                "زوجہ شہریار خان",
                "35202-5910345-6",
                "59 59",
                "No No . H. .H . ای ۔ 44 ، گلی ، بلاگ است ، نشاط کالونی ، لاہور کینٹ "
            ],
            [
                44,
                6,
                "آمن",
                "سعید احمد خان",
                "35201-1295804-3",
                "41",
                "مکان نمبر 16 16 - - F E ، سٹریٹ نمبر 4 ، تہ نشاط کالونی ، بلاکھ اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                242,
                44,
                "عامہ حنین",
                "نق",
                "34302-0593877-7",
                "18",
                "مکان نمبر 8/0 عامه مجید روڈ لاہور کینٹ ، ضلعبور "
            ],
            [
                44,
                20,
                "عنبرین اختر عباسی",
                "زوجہ عزیز گل مشنگری",
                "35201-9944003-4",
                "53",
                "امکان نمبر 44 - E گلی نمبر 2 ، محلہ م بلاک نشاط کالونی ، لاہور ، تحصیل لاہور کینٹ ، ضلع لاہور "
            ],
            [
                45,
                6,
                "عاطف",
                "ا",
                "محمد سعید احمد خان",
                "35201-1295802-9",
                "38",
                "مکان نمبر 16 - E گلی نمبر 4 ، باش اے ، تے نشاط کالونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                45,
                20,
                "را عمیر",
                "زوجہ عمیر",
                "35201-4218260-8",
                "34",
                "امکان نمبر 44 - E گلی نمبر 2 ، محقہ نشاط اونی ، بالات A ،",
                "، لاہور تحصیل لاہور کینٹ ، ضلع اور "
            ],
            [
                46,
                6,
                "کشف معد",
                "سعید احمد خان",
                "35201-9288347-7",
                "30 30",
                "امکان نمبر 16 16 - - A A ، گلی نمبر 4 گلہ نثارکا اوئی ، بلاگ اے لاہور کینٹ ، ضلع لاہور "
            ],
            [
                46,
                20,
                "شار نیز",
                "دختر نیز گل",
                "35201-7867818-2",
                "31",
                "مکان نمبر 44 - E E ، گلی نمبر تھے نشاط کالونی . بات",
                "لاہور کینٹ ، ضلع لاہور "
            ],
            [
                47,
                6,
                "وقام على",
                "سعید احمد خان",
                "35201-4039814-1",
                "29",
                "| مکان نمبر 16 - E ، سٹریٹ نمبر 4 ، محقہ نشاط اونی ، بیات A. ہور کینٹ ، ضلع لاہور "
            ],
            [
                47,
                21,
                "پروین اختر",
                "زوجہ مہ تارگٹ شاء",
                "35201-1294991-2",
                "45",
                "مکان نمبر 118 118 - - E E ، گلی نمبر 5 ، له نشاط کالونی ، بالا کی اے.اہور کینٹ ، ضلع لاہور "
            ],
            [
                48,
                6,
                "شهباز",
                "اثر دے",
                "35201-3231277-5",
                "21",
                "اسٹریٹ نمبر 6 ، محلہ سول دینی نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                48,
                21,
                "شمارا",
                "زوجہ محمد اقبال شاه",
                "35201-1307070-6",
                "44",
                "کینٹ مکان ، ضلع لاہور نمبر118- E کیا منہ 5۔ بارش و خونی ، ژان مان شاالولی ، لاہور",
                "میل لاہور "
            ],
            [
                49,
                6,
                "نشان",
                "اشته",
                "35201-7112111-5",
                "19",
                "سٹریٹ نمبر 6 ، محلہ سول ڈین خاط اونی ، لاہور کینت ، شبلع اور "
            ],
            [
                49,
                21,
                "تسمیه اصغر",
                "زوجہ رائزر شام",
                "35201-0960141-8",
                "37",
                "امکان نمبر 118 - E ، سٹریٹ نمبر 5 محلہ باد کی اے نشاط کالونی ، باہور کینٹ ، ضلع لاہور "
            ],
            [
                50,
                7,
                "صغيرات",
                "عبدالوحید خان مرحوم",
                "35201-1494398-3",
                "45",
                "مکان",
                ", 1231 - E نمبر1231- E گلی نمبر 12 عمله این بار کے خالونی ، لاہور کی",
                "ضلع ہور "
            ],
            [
                50,
                21,
                "اسالي",
                "زوجہ محمد ساجد علی شاه",
                "35201-8094467-4",
                "29",
                "امکان نمبر 118 118 - - E ، E سٹریٹ نمبر 5 محلہ بڑا",
                "اے خط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                51,
                7,
                "فاروق",
                "ولی شم",
                "35201-4074013-7",
                "34",
                "مکان نمبر 2 ، محلہ بلاك 82 دھوبی گھاٹ مفت پره ، لاہور کینٹ ، قبل",
                "ہونے "
            ],
            [
                51,
                22,
                "راني في",
                "زوجہ حافظ امتیازاد",
                "35202-2201894-2",
                "54",
                "مکان کی نمبر ضلع لاہور 65/4 6 - E ، گلی نمبر 3 ، اردو جامعہ فاروقی ، محلے وی باہ کن نشاط کالونی ، لاہور "
            ],
            [
                52,
                7,
                "عامر صغیر",
                "میر ائم",
                "35201-8217599-7",
                "30",
                "امکان نمبر 31 12 12 - - E E ، مٹر یٹ نمبر 12 محله با",
                "این",
                "شادکالونی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                52,
                22,
                "صائمہ فیضی",
                "زوجہ فیاض احمد",
                "35201-0845439-8",
                "41",
                "مکان",
                "A نمبر / 5IA 665 - E گلی نمبر 3 محله D بل کے نشاطونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                53,
                7,
                "عاصمہ سیر",
                "صغيرا",
                "35201-2199317-3",
                "28",
                "امکان نمبر 1231 - E سٹریٹ نمبر 12 ، محقہ نشاطه لونی . با",
                "انی ، لاہور کینٹ ، ضلع اور "
            ],
            [
                53,
                23,
                "ساجدہ بی بی",
                "زوجہ محمد سلیم",
                "35201-1337236-8",
                "و",
                "امکان نمبر 120- محلہ شاذکا وتی ، باد گنائے ، لاہور کینٹ ، ضلع دا ہوید "
            ],
            [
                54,
                7,
                "تغیر",
                "صغيرا",
                "35201-5671953-1",
                "24",
                "| مکان نمبر E1231 ، سٹریٹ نمبر 12 محله با کشان",
                "خاط کالونی ، لاہور کینٹ ، ضلع ہور "
            ],
            [
                54,
                24,
                "قرر ہو تا ہے",
                "زوجه هم و تار عظیم",
                "35201-1952944-8",
                "45",
                "مکان نمبر 681 عی نمیسر هاے محلہ شاطه ای نی دار کے ڈی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                55,
                8,
                "مصدق",
                "سلطان ام روم",
                "35201-1346300-7",
                "67",
                "امکان نمبر 107-106ع ، سٹریٹ تمبر ، محلہ جا کے اے شما شاد کالونی بات اور کینٹ ضلع لاہور "
            ],
            [
                56,
                8,
                "شهباز علی",
                "محمد صدیقی",
                "35201-1347604-5",
                "43",
                "مکان کند نمبر شل اور 106107 - E ، یاگنے 4 ،",
                "، بام نشاط ܬ کالوی ' مرز ، ڈاک خانة بوده ، اے بازار ، لاہور "
            ],
            [
                56,
                26,
                "حاجه لي لي",
                "زوجہ عبد الغفور",
                "35201-1419142-8",
                "59",
                "کی مکان نمبر ، 88 - E لاہور ، اگلے 4 باکت است نشاطا اهلی ، ڈاکش خانه نشاط کا قول ، لاہوری اور "
            ],
            [
                57,
                8,
                "جهاد علی",
                "محمد صدیق",
                "35201-1346304-1",
                "38",
                "کی مکان می 07 لاہور / 106 - E .",
                "محله 4 ، بات A ، ڈا کالولی ، ڈاکش خانه آرام",
                "بازار ، باہور "
            ],
            [
                57,
                26,
                "میر ارحمت",
                "زوجہ",
                "شترد نخور",
                "35201-7793195-2",
                "34",
                "امکان نمبر 88 - E گلی نمبر 4 گلہ بات اے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                58,
                8,
                "اعجاز علی",
                "محمد صدیق",
                "35201-5632516-9",
                "33",
                "لاہور مکان نمبر کینٹ ، ضلع 107 لاہور E106 ، لی نمبر 4 . محله A با ت شا کا اونی آراے بازار . ابور "
            ],
            [
                58,
                26,
                "ام",
                "نور",
                "دختر عيد الشور",
                "35201-3789346-6",
                "27",
                "مکان نمبر 88 - E ، سشمیت نمبر 4 محلہ نشاط کالونی ، بداث اسے لاہور کینٹ ، ضلع لاہور "
            ],
            [
                59,
                8,
                "نمان",
                "محمد عمران الدين",
                "14301-6111356-1",
                "19",
                "امکان نمبر 6/2 127 1276/2 ، مسٹر یٹ نمبر 7 ملے گال کالونی . با گت بلی ( لاہور کینٹ ، ضلع لاہور "
            ],
            [
                59,
                28,
                "زوجہ محمد اخلاق",
                "35201-1178717-6",
                "62",
                "امکان نمبر 87 - E7 E گلی 51 . نمیره حتی شاید که او نی ، باش ما تور کینٹ ضلع لاہور "
            ],
            [
                60,
                9,
                "ریاض احمد قریشی",
                "فياض بخش قریشی",
                "35201-9761914-3",
                "88",
                "امکان نمبر",
                "1 - 162. E گلی نمبر 7 - F محملہ شادکالونی ، انور تقسیل لاہور کینٹ شلح او چور در شمار "
            ],
            [
                60,
                29,
                "رشیدہ لیلی",
                "زوجہ محمد شفيع",
                "35201-1337234-2",
                "85",
                "مکان کند نمبر 120 ضلعبور - E ، 5 یا مخه 5 ، یا کشم . مین بازار نشاط کالونی اور",
                ". لاہور وما "
            ],
            [
                61,
                9,
                "عاصمہ ریاض",
                "قریشی",
                "ریاض احمد قریشی",
                "35201-3978640-5",
                "50",
                "مکان اور نمبر 112 16 - E . یا ممبر 7 ، محله نشاط و",
                "راست بازار ، لاہور ، تیل اور کند . مان "
            ],
            [
                315,
                61,
                "محمد رحمان",
                "محمد عارف",
                "35201-3326156-1",
                "22",
                "امکان نمبر 11 - E گلی نمبر 8 محله بیدیاں روڈ نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                61,
                29,
                "شما میر و پر هنا",
                "زوجہ نصیر احمد",
                "35201-1260207-8",
                "56",
                "لاہور مکان نمبر کینٹ ، 120 - E ، ضلعبور امله با گرد است میان شاد که او یواش خانه نشاط کا وشی .",
                "در "
            ],
            [
                62,
                9,
                "عامر ریاض",
                "قریشی",
                "ریاض احمد قریشی",
                "35201-9335849-9",
                "49",
                "امکان نمبر 160 - E گلی نمبر 7 ، باگات له نشاط کالونی ، لاہور کی ضلع لاہور "
            ],
            [
                62,
                29,
                "شهناز بیگم",
                "زوجہ شبیر احمد ملک",
                "35201-1417047-8",
                "44",
                "امکان نمبر 120 120 - - E E محلہ بارکت اے خاد کالونی مین را بازار لاہور برای کینٹ ، ضلع ما اره لاہور "
            ],
            [
                63,
                0,
                "ناصر ریاض",
                "قریشی",
                "ریاض احمد قریشی",
                "35201-9440444-9",
                "48",
                "مکان ان نمبر نلعبور 160.E. قیافه 7 - نشاطالوی ، ژاکت خانه نشاط کا اونی ، لاہور ، لیا اور "
            ],
            [
                63,
                30,
                "اثر مابانو",
                "زوجہ مانور",
                "35201-1228877-4",
                "51",
                "مکان نمبر 153 - E کی",
                "فہ",
                "۔ با نشاط کا اولی . ژاکت خانه نشاط کا اولی ، اور کینش ، مع "
            ],
            [
                64,
                9,
                "یاسر ریاضی قریشی",
                "در پیش احمد قریشی",
                "35201-9780537-1",
                "41 امکان نمبر 160 - e گلی نمبر 7 ملے نشاطالوی آراے بازار لاہور کینٹ ، ضلع لاہور "
            ],
            [
                64,
                30,
                "مہرین انور",
                "دختر دافور",
                "35201-8515949-4",
                "33",
                "امکان نمبر 153 - E F گلی نمبر 6 ، محله شادکا اونی . با گت سے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                65,
                9,
                "قام ریاض",
                "قریشی",
                "ریاض احمد قریشی",
                "35201-1417324-7",
                "40",
                "مکان نمبر 427/16 مشر بیٹ نمبر 7 ، محلہ کالونی آراے بازار لاہور کینٹ ، ضلع لاہور "
            ],
            [
                335,
                65,
                "شان می",
                "حال می",
                "35201-8562085-9",
                "28",
                "امکان نمبر 1817 1817 - - ی e محلے . مریم نشاط کالونی ، با کت ز ، را لاہور کینٹ ، ضلع مردان اور "
            ],
            [
                65,
                31,
                "بیماری بالی",
                "زوج من",
                "امه",
                "35201-7237336-6",
                "72",
                "امکان نمبر 12 - E ، سٹریٹ نمبر یہ نشاط کالونی اور کینٹ",
                "شلح ہوں "
            ],
            [
                66,
                9,
                "ندیم و نیم",
                "بشیر ولیم",
                "42201-6495431-5",
                "31",
                "محله آره بازار تادکالونی ، لاہور کینٹ ، ضلع لاہور",
                "یمن "
            ],
            [
                66,
                32,
                "پروین اختر",
                "زوجہ عبدالقیوم",
                "35201-1286998-2",
                "67",
                "امکان نمبر 117 ، گلی نمبر a5 بات . له ت نشاط کالونی : آراستے بازار لاہور کینٹ دیا ، ضلع لاہور "
            ],
            [
                67,
                10,
                "امیر علی",
                "برکت على",
                "35201-3436878-5",
                "81",
                "کینٹ مکان ، نمبر 41 - شلاہور E E ، لاله ها بالا نشاط کا اون . ژاکت خانه نشاط کالونی ، لاہور "
            ],
            [
                67,
                32,
                "اسماء قیوم",
                "دختر عبدالقیوم",
                "35201-3340291-6",
                "34",
                "کیت مکان ، نمبر 117 شلاہور / E . ی نمی 5 مته A بانشاطالوی ڈرامے بازار اور بل",
                "إجر "
            ],
            [
                68,
                10,
                "صدق",
                "مولوی غلام جیلانی",
                "42201-9259872-5",
                "68",
                "امکان نمبر 658 6 - - B .B ملے تو قاروقیہ مہر تشار ویر و کالونی ، ملات ڈی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                68,
                32,
                "شاء قوم",
                "زوجہ یاسرعلی",
                "35201-6490173-2",
                "31",
                "امکان نمبر 17 / E117 E لے تعاونی آراسته بازار بات اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                69,
                10,
                "احسان اللہ",
                "محمد اصغر علی",
                "35201-1541322-3",
                "44",
                "کی مکان",
                "نے نمبر 41 - لاہور E ، گلی محله 4 ، A بلان 7 شرکا ( اولی • ، ذات خانه نشاط کالونی ، لاہور ، شیل لاہور "
            ],
            [
                69,
                33,
                ". علمی تیم",
                "زوجہ",
                "عیدا",
                "35201-1168567-2",
                "48",
                "مکان نمبر 14 114 - E گلی نمبر 5 محلے بات اسے شیاط م کالونی ، ة : لاہور کینٹ ، ضلع . اور "
            ],
            [
                70,
                10,
                "رقان الله",
                "محمد اصغر عفی",
                "35201-9032551-1",
                "36",
                "کینٹ مکان ، نمبر41- ضلعبور E ، کیا محلے 4۔ اے بااثاث خانه نشاط کالونی ، اور میل اور "
            ],
            [
                70,
                35,
                "ظهورا في",
                "زوجہ عبد الصمد خان",
                "38402-1530290-4",
                "58",
                "کینٹ مکان نمبر ، 56 ضلاہور ، سٹریٹ نمبر 2 ، بالی رو و امام بارگاه ، محلہ شاط الولی ، پلاکت است ، لاہور "
            ],
            [
                71,
                10,
                "رضوان اصغر",
                "محمد اصغر علی",
                "35201-1098350-1",
                "34",
                "امکان نمبر 41 41 ج سٹریٹ نمبر 4 ، محلہ گراونی . باوت ۵ ، اور کینٹ ، ضلع لاہور "
            ],
            [
                71,
                35,
                "شانه هم",
                "دختر عبدالصمد خان",
                "38402-3570939-4",
                "32 32",
                "امکان نمبر 56. مشریت نمبر جربانی و امام بارگاہ نشاط کالونی ، باہور کینٹ ، ضلع لاہور "
            ],
            [
                72,
                10,
                "ام عمران النمر",
                "محمد اصغر علی",
                "35201-2813445-1",
                "32",
                "امکان نمبر 41- گلی نمر 4 ملے ہم بات",
                "می شادکالونی اور تحصیل ... لاہور کینٹ ، ضلع لاہور "
            ],
            [
                72,
                35,
                "ارم مبشر",
                "زوجہ مبشر مم",
                "35201-6029180-4",
                "30",
                "مکان نمبر 91 91 ، تربیت نمیم 3",
                "له نشاط کالونی ، بائی روڈ بلاک اے ، اور کینٹ ، ضلع لاہور ۔ "
            ],
            [
                73,
                11,
                "مصداق",
                "می",
                "بخش",
                "35201-1963316-3",
                "70",
                "لاہور مکان کیٹ نمبر ، 49-48 / E لاہور ، کیا ملہ 2۔ بار اسے نشاط کالونی ، وای خانه نشاط کالونی ، لاہور ، شمیل "
            ],
            [
                73,
                36,
                "ساحرہ شر این",
                "دختر محمد شرف",
                "35201-1102227-4",
                "30",
                "امکان نمبر 1236 / F. ثریت نمبر 12 ملے نشاط کالونی اور کینٹ ضلع لاہور "
            ],
            [
                74,
                11,
                "شمیادین",
                "35201-7681607-7",
                "43",
                "امکان نمبر 952 - E گلی نمبر 10 لے مریم نشاط کالونی ، لاہور کینٹ ، ضلع اور "
            ],
            [
                74,
                37,
                "نسیم بیگم",
                "زوجه مه اول",
                "35201-1194266-8",
                "67 67",
                "مکان نمبر 136 136 م - ، e گلی نمبر نمبر 6 ، گله نشاط کا اوری ، باگن اے ، لاہور کینٹ ، ضلع ناہور "
            ],
            [
                75,
                11,
                "مش مل",
                "محمد صدیق",
                "35201-0151644-1",
                "38",
                "امکان نمبر 49 - e48 گلی نمبر 2 متر باشد شرکالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                75,
                37,
                "مر جاواں",
                "وحجم جاوا",
                "35201-1194263-0",
                "40",
                "مکان نمبر E136 سٹریٹ نمر 6 محله شه",
                "اونی ، بات اسے لاہور کنید . شلح",
                "و به "
            ],
            [
                76,
                11,
                "نوید",
                "ام",
                "سدیق",
                "35201-4908423-1",
                "37",
                "امکان نمبر 49-48 / E . سٹریٹ تمبر ، ملے بلاگ اسے نشاط کالونی ، لاہور کینٹ ، ضلع اور "
            ],
            [
                76,
                37,
                "مالے جانیون",
                "وخرم اول",
                "35201-8565351-6",
                "33",
                "مکان نمبر 1936- گلی نمبر 6 ، گله نشاط کالونی . پلاٹ اے ، لاہور کینٹ ، ضلع اور "
            ],
            [
                77,
                11,
                "نے عدیل",
                "صدات",
                "قد صدق",
                "35201-7180934-9",
                "30",
                "مکان نمبر 49-48 / E . سٹریٹ نمبر 2. ملے یا کٹاسقاط کالونی ، چور کینٹ ، ضلع اور "
            ],
            [
                77,
                38,
                "رسولا بیلي",
                "زوجه منظور على",
                "35201-8149140-6",
                "50 الہ امام بارگاه نشانی را بازار ، امور ، تحصیل راہور کینٹ ، ضلع لاہور "
            ],
            [
                78,
                12,
                "اقبال",
                "محبوب بخش",
                "35201-1647078-3",
                "64",
                "امکان نمبر 151 - E ، سٹریٹ نمبر 6 ، بلے بازگشاے ارکالونی ، لاہور کینٹ ، ضلع ہونے "
            ],
            [
                78,
                38,
                "عابدہ پروین",
                "زوجہ اصغر علی",
                "35201-9617367-8",
                "32",
                "اسم باران ملے نشاط کالونی آراے بازار ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                79,
                38,
                "میرا پروین",
                "زوجہ ساجد علی",
                "35201-2406108-4",
                "30",
                "آراے بازار محله امام بارگاہ نشاط کالونی ، لاہور کینٹ ، ضلع ہے ۔ "
            ],
            [
                80,
                12,
                "عروج اقبال",
                "اقبال",
                "35201-2471962-1",
                "33 33",
                "مکان نمبر 151 - E E151 گلی نمبر 6 متهم باوکت نشاط کانونی . انہوں ، میلا ہور کینٹ ، ضلع لاہور "
            ],
            [
                80,
                38,
                "روبینہ انتحار",
                "زوجہ مر التيار",
                "35201-9921212-0",
                "30",
                "امکان نمبر 237 - E .E237 گلی نمبر 1 له شارک اوقی ، سیکر لي ، لا ہور کینٹ ، ضلع لاہور "
            ],
            [
                81,
                12,
                "ثمران اقبال قریشی",
                "ماتهال",
                "35201-2957045-3",
                "31",
                "مکان نمبر 151 - E151 E گلی نمبر 6 ملم بارکٹ تشار کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                81,
                39,
                "رانی",
                "زوجہ رشید می",
                "35201-6687562-2",
                "58",
                "امکان نمبر 3. مریم کالونی مین چو گی ، باہور کینٹ ضلع لاہور "
            ],
            [
                82,
                12,
                "سفيان اقبال",
                "را قبل",
                "35201-5417240-7",
                "29",
                "مکان نمبر",
                "E 151 سٹریٹ نمبر 6 ، محلہ قال کالونی بات اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                82,
                40,
                "زیر این",
                "زوجہ احمد علی ناصر",
                "35201-1415934-0",
                "50",
                "مکان نمبر 150 - E ، محله بالا کے اسے شاد کالونی ، راہور کینٹ ضلع تور "
            ],
            [
                83,
                13,
                "اکبر على",
                "برکت على",
                "35201-1326585-7",
                "65",
                "مکان نمبر 38- زویر بیت و بکا میں ، محلہ من نشاط کالونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                83,
                41,
                "سرور بیگم",
                "زوجہ ماہ رمضان",
                "35201-7829508-0",
                "51",
                "مکان نمبر 159 گلی نمبر 7 ، له نشاط کالونی ، باٹ ، لاہور کینٹ ، ملح ابور "
            ],
            [
                84,
                13,
                "حنیف",
                "غلام قادن",
                "35202-2724825-7",
                "42",
                "مکان گرامی لاہور / نمبر 9 کی A1100",
                "ضلع لاہور سٹریٹ نمبر 6 ، لیندی 1 استیشن 1 محله زمان پارکٹ کیولری "
            ],
            [
                84,
                42,
                "پروین اختر",
                "زوجه عبد الرزات",
                "35201-7181834-4",
                "57",
                "مکان اور نمبر 136 - E ، گلی نمبر 6 ، محلہ اے باد کے نشاط کالونی ، فنا ہویی",
                "سیل لاہور کینٹ ، ضلع "
            ],
            [
                164,
                84,
                "سلائی لی لی",
                "زوجہ ظفر محمود",
                "35201-1198047-0",
                "46 46",
                "امکان نمبر 926 926 - - E . کی نمبر 4 ملے گاد کالونی ، با ای ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                85,
                13,
                "ارشد على",
                "اکبر على",
                "35201-2747301-5",
                "40",
                "مکان نمبر 38 38 - - E E محلے میٹر مشاطا وتی من روی لاہور کینٹ ، ضلع ہور "
            ],
            [
                85,
                42,
                "امین تول",
                "دختر عبدالرزاق",
                "35201-6031190-6",
                "29",
                "مکان اور نمبر E136 گلی نمبر 6 ، محلہ شادکا لونی آراستے بار ای باران است ، راہور کینٹ ، ضلع "
            ],
            [
                86,
                13,
                "راشد على",
                "اکبر علی",
                "35201-1326590-3",
                "38",
                "کی مکان ضلع نمبر 38 - E اور ، گلی ، محلہ شادالونی مین روڈ ڈاٹ خانه شال کالونی ، لاہو ہر سال لاہور "
            ],
            [
                86,
                43,
                "اروین ناز",
                "توجہ لیاقت علی",
                "35200-1454517-8",
                "50",
                "امکان نمبر",
                "- E101 گلی نمبر 5 حملے شرکالونی . بله کث اسے لاہور کینٹ ، ضلع لاہور "
            ],
            [
                87,
                13,
                "کاشف على",
                "اکبر علی",
                "35201-3748384-1",
                "37",
                "امکان نمبر 8 38 - E محلے شرکالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                87,
                44,
                "زوجہ لیاقت علی",
                "35201-7506138-6",
                "61",
                "لاہور مکان کینٹ نمبر ، 907 - E ، ضلعبور گلی محلہ 11. با ای نشاط کا ولی ، اث خانه نشاط کالونی ، لاہور ، مل "
            ],
            [
                88,
                1310,
                "اکبر على",
                "35201-7783592-5",
                "36",
                "امکان نمبر 38 - 38- E محلہ میں شادالوئی ، اور",
                "میل الپور کینٹ ، ضلع لاہور "
            ],
            [
                88,
                44,
                "رخسانہ پروین",
                "زوجہ حسن اقبال",
                "35201-8113742-6",
                "45",
                "مکان نمبر 909 909.ی گلی نمبر 11 محلہ دارد که او نیما تور کیشت ضلع لاہور "
            ],
            [
                89,
                14,
                "محمد یونس",
                "35201-8284453-7",
                "55",
                "امکان نمبر E159 E159 گلی نمبر 7 مجله نشاط کالونی ، باد گشاے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                89,
                44,
                "ارم ناتے",
                "زوجہ محمد کامران",
                "35201-6877005-4",
                "37",
                "مکان نمبر 907 - E حشرٹ نمبر 11 ملے بلاگشای شاد کالونی اور کینٹ لاہور "
            ],
            [
                90,
                14,
                "شن",
                "انور مست",
                "35201-0349220-7",
                "36",
                "محله بایو مرشان بیدیاں روڈ لاہور کینٹ ، ضلع لاہور "
            ],
            [
                405,
                90,
                "تیم مشتاق",
                "مشتاق",
                "35201-4466678-1",
                "26",
                "لاور مکان نمبر 1207 ، کیا میرے گلے آخری وین سٹاپ نشاط کا اول . بالاکشانی لاہور کیت ، مسلح "
            ],
            [
                90,
                44,
                "انالیاقت",
                "دختر لیاقت علی",
                "35201-2766310-2",
                "25",
                "امکان نمبر 907 - F , سٹریٹ نمبر 11 مجله نشاط کالولی . بلاگائی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                91,
                14,
                "محہ زیر",
                "محمد یونس",
                "35201-5776264-5",
                "33",
                "مکان اور نمبر 159 159 - e کلی میر 7 محلے اطالوی آراستے بازار ، بات اسے ، لاہور کینٹ ، ضلع "
            ],
            [
                91,
                45,
                "سکینہ بی بی",
                "زوجہ شوکت علی",
                "35201-2223824-2",
                "66",
                "امله شادونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                92,
                14,
                "احم جاء به",
                "مار میونس",
                "35201-2542657-9",
                "31",
                "امکان نمبر E159 گلی نمبر 7 ملہ شادالوئی . بات",
                "لاہور کینٹ ، ضلع لاہور "
            ],
            [
                92,
                45,
                "معافی طرق",
                "زوجہ مہ خارق",
                "35201-7011721-2",
                "26",
                "محله شاره ونی ۔ لاہور کینٹ ضلع لاہور "
            ],
            [
                93,
                14,
                "محمد عدیل یونس",
                "محمد یونس",
                "35201-0312693-3",
                "26",
                "امکان نمبر 159 - E ، مشرٹ نمبر 7 ملہ شادکالونی ، پلاکت اسے ، راہور کینٹ ، ضلع لاہور "
            ],
            [
                93,
                46,
                "زوجہ غلام رسول",
                "35201-5827320-0",
                "51",
                "لاہور مکان نمبر 1165. یا نمبر 1 ، محلہ شاط کالونی راست یاثار ، لاہور ، کمیل لاہور کینٹ ، ضلع "
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
        return $data = [
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
                1,
                -717,
                "مارکت اند",
                "سایت تکلی",
                "35201-7803283-7",
                "32",
                "امکان نمبر 5 - م گلی نمبر 56 ، گلہ نیشنل روڈ عثمان اور شالیمار ایا لاہور کینٹ ، ضلع لاہور "
            ],
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
                2,
                1,
                "فوزیہ لیلی",
                "زود یہ ملک شاہد حسین",
                "37405-1394620-6",
                "29",
                "امکان نمبر 01 - 61- ، مٹر یٹ نمبر 4 ، محلہ شاہ کالی بلی کی قاہور کیشن ضلع اور "
            ],
            [
                2,
                1,
                "ملک",
                "تدبیر حسین",
                "ملک",
                "ناظر حسین",
                "35201-1318241-7",
                "43",
                "مکان نمبر 91 - E ، سٹریٹ نمبر ہے ، محلہ شاد که اولی با شاسی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                3,
                2,
                "اقبال بیگم",
                "زوجہ محمد اسلم",
                "35201-6610311-2",
                "64",
                "ن امکان کے نمبر 86 عه - 86- E ، محلہ ره نثار کانونی من ، بایت 8 ، مخامة اور ... تقسیل لاہور شام کیش ، اور منبع نسل اور اور "
            ],
            [
                3,
                1,
                "من",
                "مسیر پر",
                "ملک ناظر حسین",
                "35201-1317661-7",
                "40",
                "امکان نمبر 91 - .E گلی نمبر 4 ، گلہ بات اسے شاد کالونی اور کیٹ ، ضلع لاہور "
            ],
            [
                4,
                2,
                "کر اسلام",
                "و محمر اسلام",
                "35201-1412300-2",
                "37",
                "مکان نمبر نا 86 - E ، یا محلہ A با نشاط کا اول ، داث خانه نشاط اولی ، لاہور ،",
                "سیل لاہور "
            ],
            [
                4,
                11,
                "ملک",
                "شاہد حسین",
                "ملک",
                "ناظر حسین",
                "35201-1305801-7",
                "38",
                "مکان نمبر 91 91 - - E E گلی نمبر 4 محلے بااث اسے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                5,
                3,
                "رقیہ بیگم",
                "زوجہ محمد جمیل احمد",
                "35202-2650862-8",
                "75",
                "ال امکان م نمبر م 145 - م E گلی م نمبر 6 ، محله ما A با و کت شما شال ، کالونی اور سیل اگتا اور اس کینٹ کو ضلع ضلع لاہور "
            ],
            [
                5,
                1,
                "شاہبر تلی",
                "اصغر على",
                "35201-0158496-5",
                "34",
                "امکان نمبر 130 130 - - e e محلہ نشاط کا مونیر بالی رود . با گٹ اے ۔ لاہور کینٹ ، ضلع لاہور "
            ],
            [
                6,
                1,
                "من علوم قره",
                "ملک ناظر حسین",
                "35201-4112582-1",
                "33",
                "امکان نمبر 91 91 - - E ، E مسٹر یٹ نمبر 4 ، محله و بالات نشاط کالونی اور کینٹ ، ضلع لاہور "
            ],
            [
                6,
                3,
                "نادره طام",
                "از وحجہ طامر جمیل",
                "35201-1384114-0",
                "51",
                "مکان اور کی 149 - E ، یا محلہ 6۔ بات A نشاطاء . ڈاک خانہ نشاط کالولی ، لاہور د . سلع "
            ],
            [
                7,
                1,
                "منوی سین",
                "ملک",
                "ناظر حسین",
                "35201-8679904-7",
                "32",
                "امکان نمبر 91 - e گلی نمبر 4. له نشاط کالونی . بات اے..اہور کنٹ ، ضلع لاہور "
            ],
            [
                7,
                0,
                "عروسے تین",
                "دخترمہ جمیل احمد",
                "35201-9932681-4",
                "28",
                "امکان نمبر 149 - E . سٹریٹ نمبر 6 ملے نشاط کالونی ، سکھر اسے ، اور کینٹ ، ضلع ہور "
            ],
            [
                0,
                1,
                "محمد زاہد",
                "امر علی",
                "35201-8489195-7",
                "32",
                "مکان نمبر 130 - E E ، گلی نمبر 5 محله با کت A ربانی روڑ نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                8,
                3,
                "از امروز ان طاسی",
                "دختر عامر جمیل",
                "35201-0299086-0",
                "23",
                "امکان نمبر 149 - E ٹریٹ نمبر 6 ، محلے نشاط کالونی ، با کٹ اے ، لاہور کینٹ ، ضلع اور "
            ],
            [
                9,
                1,
                "جعفر",
                "اصغر",
                "على",
                "35201-8609210-7",
                "31 31",
                "مکان نمبر 130 110 - - E .E ، گلی نمبر 5 محلہ م بلات خاط اور بائی روڈہ ناہور کینٹ ، ضلع اور "
            ],
            [
                9,
                3,
                "اقری شبیر طاس",
                "دختر طایر بیل",
                "35201-4148378-0",
                "21",
                "امکان نمبر 149 - e سٹریٹ نمبر 6 ، تله شال کالونی ، بالا گشاسته ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                10,
                4,
                "رب انار",
                "زوجہ محمد ریاضی",
                "35201-6014109_4",
                "55",
                "مکان نمبر 51 - E گلی نمبر 2 له شال کالونی . بلاك A اہور کینٹ ، ضلع لاہور "
            ],
            [
                11,
                2,
                "سلام",
                "محمد صادق مرحوم",
                "35201-1503708-7",
                "66",
                "مکان نمبر شای 86 - کینتاہور E . یا گلہ بايت مش طاسون ، ژاکت خانہ اطالول ، الہور ،",
                "لاہور "
            ],
            [
                11,
                4,
                "نیلم شهراد",
                "از وجه",
                "35201-3437302-6",
                "31 31",
                "امکان نمبر 51 - E ٹر یٹ نمبر ہی محلے ملک کی شادی ہوئی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                12,
                2,
                "حال مور",
                "رحمت علی",
                "33302-8955222-9",
                "49",
                "مکان نمبر 503 - E ، بال مسجد محله گل روز 2 شاطا وفی ، اور کینٹ لاہور "
            ],
            [
                12,
                6,
                "خاتون بیگم",
                "زوجہ محمد سعید احمد خان",
                "35201-1373748-6",
                "68",
                "ان امکان کے نمبر 16 رد - E ، مٹر عمل یٹ نمبر 4 ، ، محلہ مدل شادکالونی ، بات اسے ، ده لاہور راه کینٹ ، ضلع اکر لاہور "
            ],
            [
                13,
                2,
                "وسلام",
                "محمد اسلام",
                "35201-5981584-1",
                "36",
                "مکان نمبر 86 - E محلے شاط کالونی . بات اسے لاہور کی",
                "ضلع لاہور "
            ],
            [
                13,
                6,
                "روبینہ شاہد",
                "زوجه گم شامل",
                "35201-1278710-2",
                "45",
                "مکان اور می 16 - E ،",
                "له 4 - بای کت A شاطولیدات خانه نشاط اولی ، لاہور کین",
                "، سن "
            ],
            [
                14,
                2,
                "عالم اسلام",
                "اسلام",
                "35201-3841005-9",
                "35 35",
                "امکان نمبر 86 - E ملے بایانا",
                "نشاط کالونی ، لاہور کینٹ ضلع",
                "پور "
            ],
            [
                14,
                6,
                "اور شاہین",
                "محمد سعید احمد خان",
                "35201-1373749-6",
                "37",
                "امکان نمبر 16 16 - - E E گلی نمبر 4 ، له نشاط کا اونی ، بلوا ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                15,
                0,
                "مغموم",
                "اسلام",
                "35201-7338936-5",
                "32",
                "امکان نمبر 86 86 - - E E ، محلے بات شامل کالونی",
                "اور کینٹ ، ضلع لاہور "
            ],
            [
                15,
                6,
                "ارم شتراوی",
                "زوجہ عمران خان",
                "35201-5949680-6",
                "34",
                "امکان نمبر 16 نا1- سٹریٹ تمبر 4 ملہ شادی لونی ، بات A ' ہور کینٹ ، ضلع لاہور "
            ],
            [
                16,
                2,
                "نعیم اسلام",
                "35201-9223232-3",
                "31",
                "امکان نمبر 86- محلہم بات شاطها و نی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                16,
                6,
                "سونیا شتراوی",
                "زوجہ محمد فیصل",
                "35201-0488902-8",
                "33 33",
                "امکان نمبر 1 / 98",
                "- E ، سٹریٹ نمبر 8 محلہ نثار کالونی . باکت کی ، لاہور کینٹ ، ضلع اور "
            ],
            [
                103,
                16,
                "م",
                "بلالی",
                "منذر",
                "35201-3020540-3",
                "19",
                "نینس ہاؤسنگ اتھارٹی "
            ],
            [
                17,
                2,
                "قبل اسلام",
                "اسلام",
                "35201-7432986-5",
                "29",
                "امکان نمبر 86 86 - - E E ، محلہ نشاط کالونی ، بادث A. ' اہور کینٹ ، لاہور "
            ],
            [
                17,
                7,
                "فرزانہ بی بی",
                "زور سفیر اتم",
                "35201-7896844-6",
                "45",
                "امکان نمبر مردم 1231 > - E گلی نمبر 12 ملہ بلاکی F شاهد که او لاہور اداء :. کینٹ شعبور مه "
            ],
            [
                18,
                2,
                "نیم اسلام",
                "سلام",
                "35201-9222187-3",
                "26",
                "مکان نمبر 86 - -86 E محله شاط کالونی . بات اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                19,
                2,
                "کار علی خالی",
                "خالد محمود",
                "33302-0740886-1",
                "23",
                "امکان نمبر 503 - تا ، بلال محمد . حمله تنگ روڈ 2 ثار کا کوئی بات کی ، اور کینٹ ، ضلع لاہور "
            ],
            [
                19,
                8,
                "آسیہ پروین",
                "زوجہ سجاد علی",
                "35201-1995323-2",
                "35",
                "امکان نمبر 07 / 6/107 106 - E10 .E مشر یٹ نمبر 4 محله بالاناشاد کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                20,
                2,
                "انور خالد",
                "خالد محمود",
                "33302-0740981-1",
                "20",
                "امکان نمبر 503",
                "، بال مکبد ، عملے کروڑ 2 ثارلیمانی نیا پور کیند . ضلع اور "
            ],
            [
                20,
                9,
                "نور سلطن",
                "زوجہ ریاض احمد قریشی",
                "35201-5128429-6",
                "76",
                "مکان نمبر 160 160 - - E E گلی نمبر 7 ، له دم نشاط کالونی us آراے باراں ، لاہور او کینٹ ، ضلع لاہور",
                "مو "
            ],
            [
                21,
                3,
                "برایت",
                "می",
                "ظهور مسیح",
                "35201-4998879-5",
                "61",
                "مکان نمبر 1529 / c , مٹیریل نمبر 1.له شال گویی",
                "و کالونی اور کینٹ",
                "لاہور "
            ],
            [
                860,
                992,
                "نمرہ محمود",
                "دختر محمود اختر",
                "35201-3550770-6",
                "21",
                "مکان نمبر 251 ، سٹریٹ نمبر 2 ، حقہ خیرالونی ، پلاکت کی ، اور کینٹ ، ضلع لاہور "
            ],
            [
                21,
                9,
                "شریعترین عام",
                "زوجہ عام ریانش قریشی",
                "37406-1567637-4",
                "48",
                "مکان کت نمبر شلاہور 1612 - E ، کی ممبر 7.مجله نشاط کالونی آراے بازار ،",
                "ای بلك A او اہور . ها",
                "اور "
            ],
            [
                22,
                3,
                "اطار بن",
                "جمیل احمد",
                "35200-1567315-3",
                "52",
                "مکان میر 149 - E ، یا میرنا ، محلہ اے بلای شهرلولی ، لاہور ،",
                "لاہور کینٹ ، سلم "
            ],
            [
                22,
                10,
                "رضیہ بیگم",
                "زوجہ محمد اشغر علی",
                "35201-1440704-8",
                "72",
                "مکان ، نمبر41- لاہور E ، یا 10 محله 4 ، دا ثم ژانوی ، ڈاک خانه",
                "ول شادالولی ، لاہور ،",
                "لاہور "
            ],
            [
                23,
                3,
                "زاہر بیل",
                "مر جمیل احمد",
                "35200-1567336-5",
                "49",
                "مکان نمبر 149 149 - - E E ، گلی نمبر 6 محله م با",
                "نشاط کالونی ، لاہور ، تحصیل لاہور کینٹ ، ضلع لاہور "
            ],
            [
                23,
                10,
                "رجحان کوثر",
                "دختر اصغر علی",
                "35201-1440710-2",
                "48",
                "مکان در نمبر 41 - خلدبور E .",
                "خله 4. باشم تا . 2 کالونی ، ژاکت خانه نشاط کالونی ، لاہور ،",
                "لاہور "
            ],
            [
                24,
                3,
                "شاعر بمل",
                "محمد جمیل احم",
                "35200-1567264-3",
                "44",
                "امکان نمبر 149 - E گلی نمبر 6 ، محله نشاط کالونی . بات اسے لاہور کی",
                "، ضلع لاہور "
            ],
            [
                24,
                10,
                "فرزانہ کوثر",
                "دختر محمد اصغر منی",
                "35201-9440710-8",
                "46",
                "همگان کے می ضلع 41 ہوں E ، لاله 4 ، با شمشاط الوفيات خنشاط کالونی ، لاہور ، لاہور "
            ],
            [
                55,
                25,
                "نجمہ",
                "زوجہ مسلمان",
                "35201-1225637-0",
                "54",
                "امکان نمبر - 130-",
                "شر یٹ نمبر 5 محلہ شال کالونی رد",
                ": اور کینٹ ، . ضلع ای لاہور "
            ],
            [
                25,
                10,
                "تیل کوثر",
                "دختر محمد اصغر علی",
                "35201-2992023-8",
                "41",
                "مکان کالونی بارہویں نمبر 41 - E ، تیل گلی اوہ نمبر 4 ، کینٹ ، له ضلع نشاط کالونی لاہور ، بالا اسے بل گ ، ژاکت خانه نشاط "
            ],
            [
                25,
                3,
                "ساجد باید بیل این",
                "و جمیل احمد",
                "35200-1547756-5",
                "38",
                "امکان تعمیر مکان 149 ، گلی محلے کیا بات اسے شادگانی ڈاک خان راستے بازار ، لاہور "
            ],
            [
                26,
                10,
                "ثمین کوثر",
                "اور محمد اصغر علی",
                "35201-7707236-4",
                "39",
                "مکان کینٹ ، نمبر ضلع 41 - لاہور E ، گلی نمبر 4 ، بات اے باری ڈاک خانہ نشاط کالونی ، لاہور ،",
                "لاہور "
            ],
            [
                26,
                3,
                "نیشان بھی",
                "نبیل احد",
                "35201-1472798-9",
                "38",
                "کینٹ مکان ، نمبر ضلع لاہور E149 .E . گلی محله 6 -6 بار کے اے ، ڈاک",
                "خانه شادکالونی ، لاہور ، تحصیل لاہور "
            ],
            [
                27,
                10,
                "مان احسان",
                "توجہ احسان الله",
                "35202-0548975-0",
                "35 امکان نمبر 41- سٹریٹ نمبر A4 ملے تشارکاوٹی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                27,
                3,
                "اشرف",
                "ہدایت می",
                "35201-5676510-3",
                "32",
                "امکان نمبر 1529 1529 / / E E ، گلی نمبر 1 محله نشاط کالونی ، لاہور میں لاہور کینٹ ضلع لاہور "
            ],
            [
                28,
                11,
                "منور کوثر",
                "زوجه در سدیق",
                "35201-1333716-8",
                "66",
                "مکان نمبر 49-48 / E ، سٹریٹ نمبر 2 ، محلہ بات اسے شاد کالونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                28,
                3,
                "کی ہدایت",
                "برایت",
                "می",
                "35201-6433632-9",
                "27",
                "مکان نمبر 1529 / E ، سٹریٹ نمبر 1 ، محلہ شادکالونی ، اور کینٹ ، ضلعبور "
            ],
            [
                29,
                11,
                "بشر ناز",
                "و",
                "مداق",
                "35200-1413343-2",
                "40",
                "مکان اور نمبر 49-48 - E ، کیا نمبر 2 ، محلہ م با کشاط کانونی ، لاہور ، سیل لاہور کیند ، نبلع "
            ],
            [
                29,
                4,
                "محمد زاهر",
                "محمد اقبال",
                "35201-6500926-3",
                "60 60",
                "امکان نمبر 51 - E ، سٹریٹ نمبر 2 ، حلقہ نشاط کالونی ، بلاگ اسے لاہور کینٹ ، ضلع",
                "ہور "
            ],
            [
                30,
                11,
                "مد رو از",
                "اور محمد صدیق",
                "35201-7002312-8",
                "28",
                "امکان نمبر 48-49 49-48 / / E .E گلی نمبر حملہ شادکاونتی باکت A ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                30,
                4,
                "محہ ریاض",
                "اقبال",
                "35201-0112035-3",
                "58",
                "امکان نمبر 51 - E ، مٹر یٹ نمبر 2 محلہ بلاک ۸ نشاط کانونی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                31,
                12,
                "رخسانہ اقبال",
                "زوجہ محمد اقبال",
                "35201-1521288-2",
                "55",
                "مکان نمبر 151-8 8-151 ، ، سٹریٹ نمبر 6 ، مجله نشاط کالونی ، داکت اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                31,
                4,
                "امانت امانت علی علی",
                "گم شفیع",
                "34602-4038590-9",
                "53",
                "امکان نمبر 4 / 1245 - E ، محلہ شاط کالونی ، ڈا پور کینٹ ضلع لاہور "
            ],
            [
                389,
                301,
                "ریحانہ کوث",
                "زوجہ طاہر اشرف",
                "35201-1182859-8",
                "57",
                "امکان نمبر 36 ای بگی گلے دبات اے ، ڈاگ خنه شال کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                32,
                13,
                "رحيله",
                "لي",
                "توجہ ارشد علی",
                "35201-0305466-2",
                "36",
                "کالونی مکان نمبر اور 38 - E ، تحصیل محلہ به مین کینٹ روڈ ، شاط ضلع کالونی ، لاہور سیر A باش ۸. ژاکت خانه شاد "
            ],
            [
                189,
                32,
                "اسلم می",
                "جیت می",
                "35201-7642510-3",
                "48",
                "لاہور مکان کینٹ ، نمبر 1806/3 لاہور . کیا میر کابل با کش ، گلے میر کی شادی اولی راستے بازار ، الا بود ، نیل "
            ],
            [
                32,
                4,
                "آصف ریاست",
                "دریای",
                "35201-8290377-5",
                "35",
                "امکان نمبر 51 - E سٹر بیٹ نمبر",
                "محله باش اسے شاور کالونیا اور کینٹ ، ضلع تور "
            ],
            [
                33,
                13,
                "هنا رخسار على على",
                "زوجہ راشد علی",
                "35201-7089294-6",
                "26",
                "محل نشاطها و نی مین بازار لاہور کینٹ ، ضلع لاہور "
            ],
            [
                33,
                4,
                "کاشف ریاض",
                "محمد ریاض",
                "35201-2898576-7",
                "33",
                "امکان نمبر 51 51 - - E E گلی نمبر 2 ، محلہ بدات ، نشاطی و فنی ، لاہور ، تحصیل لاہور کینٹ ، لاہور "
            ],
            [
                34,
                14,
                "نسرین بی بی",
                "زوجہ ریونی",
                "35201-0185247-8",
                "51",
                "امکان نمبر 159 159 - - E E ٹریٹ نمبر 7 محلہ نشاط کالونی . باکت A ، لاہور کی",
                "ضلع لاہور "
            ],
            [
                34,
                4,
                "حسین علی",
                "گمراض",
                "35201-2526932-3",
                "25",
                "امکان نمبر 51 - E ، سٹریٹ نمبر 2 ملہ بات اے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                18,
                8,
                "کرامت ليلي",
                "زوجہ محمد صدیق",
                "35201-1215981-0",
                "66",
                "مکان نمبر 107 نلترہور E106 ، میانہ 4- باتا",
                "نشاط اولیاث خانه را",
                "بازار ، امور "
            ],
            [
                35,
                14,
                "کومل یونسی",
                "دختر محمد یونس",
                "35201-6431461-6",
                "19 19",
                "اسٹریٹ نمبر 4 ، مطلہ",
                "آبادی کو بابا بیدیاں روڈ لاہور کینٹ ، ضلع لاہور "
            ],
            [
                35,
                4,
                "عامر شهراد",
                "امانت على",
                "34602-2755177-1",
                "24",
                "امکان نمبر 4 / 1245 - E محله شال کالونی اور کینٹ ، ضلع لاہور "
            ],
            [
                36,
                16,
                "ارشاد بیگم",
                "تزوج ظفراللہ خال",
                "34201-9758977-0",
                "77",
                "امکان نمبر 112 112 - - E ، E مشر بیٹ نمبر 4 ، محلہ باد کن اسے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                36,
                5,
                "اليوسف",
                "طلعت",
                "درشن ایم الیں",
                "35201-3531021-3",
                "61 61 | مکان نمبر 30 17 - E محلہ بات ہے مریم خاط کالونی اور تحصیل لاہور کینٹ ، ضلع لاہور "
            ],
            [
                37,
                17,
                "کنیز اختر",
                "توجہ دی ام",
                "35200-1426413-8",
                "58",
                "مکان نمبر 103 - E ، سٹریٹ نمبر 4 ، محلہ بات اسے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                37,
                5,
                "عارف على",
                "شوکت على",
                "35201-3233110-1",
                "45",
                "مکان نمبر 22 قہ نشاط کالونی ، لاہور میل امور کینٹ",
                "ضلع لاہور "
            ],
            [
                38,
                18,
                "انوری",
                "زوجہ مہ رشید",
                "35201-1216711-2",
                "56",
                "بازار مکان نمبر لاہور کینٹ 425 - ، E ، ضلع لاہور مرحله 4۔ بلاتی نثار کالونی آراے بازار ژاکت خانه آراے "
            ],
            [
                38,
                5,
                "عاطف",
                "طلعت",
                "وسف طلعت",
                "35201-5545624-9",
                "29",
                "مکان نمبر 1730 - E ، محلہ مریم نشاط کالونی ، باد ، لاہور کی",
                "ضلع لاہور "
            ],
            [
                221,
                39,
                "سلیم",
                "دب",
                "حمید می",
                "35201-2750025-7",
                "24",
                "امکان نمبر 84 - -84 E ، سٹریٹ نمبر 4 محله شاطر اپنی باہور کینٹ ، ضلع لاہور "
            ],
            [
                39,
                18,
                "امین",
                "زوجہ محرومیه",
                "35201-0192177-2",
                "33",
                "امکان نمبر 425 ، سٹریٹ نمبر 4 ملے شام کا اونی ، بات کی ۔ لاہور کیث نلح را چون "
            ],
            [
                39,
                5,
                "آدنی علی",
                "عارف",
                "على",
                "35201-3875995-1",
                "28 28",
                "امکان نمبر 22 محله څاط کالونی ، لاہور کینٹ ، ضلع اور "
            ],
            [
                281,
                158,
                "شوربیم",
                "زوجہ محمد طارق",
                "35201-1336827-6",
                "43",
                "امکان نمبر 124 124 - - E E گلی محله وشاطا ہوئی ، اور میلا ہور کینٹ ، ضلع اور "
            ],
            [
                40,
                19,
                "سعید واقبالی",
                "زوجہ محمد اقبال را",
                "35200-1368122-2",
                "66 66",
                "امکان نمبر",
                "- 142- E E : گلی محلے کا نشاط کالونی بلاگ اے ، اور "
            ],
            [
                40,
                5,
                "محمد اعظم",
                "عارف على",
                "35201-4635045-7",
                "24",
                "| مکان نمبر 22 ، له نشاط کالونی ، لاہور کی",
                "ضلع اور "
            ],
            [
                41,
                19,
                "مہ مساج جبین",
                "زوجہ محمد جاوید اقبال",
                "35201-5221681-0",
                "31",
                "امکان نمبر 142 - E ، سٹریٹ نمبر 6 ، محلہ نشاط کالونی ، پلاٹ اے لاہور کینٹ ، ضلع اور "
            ],
            [
                41,
                5,
                "کاشف على",
                "تعرف على",
                "35201-1584488-9",
                "22",
                "مکان نمبر 22 ، محلہ شاط کالونی ، لاہور کینٹ ، ضلعبور "
            ],
            [
                231,
                42,
                "قاسم علی",
                "نوشا",
                "35201-1293015-1",
                "45",
                "مگانی شمسیر B95 , E کی تعمیر کولے شاد کالونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                42,
                19,
                "تم عرقان",
                "زوجه زرقان مجید",
                "35201-1221466-8",
                "31",
                "مکان نمبر 142 - E ، تے بلا کی",
                "شرط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                42,
                6,
                "محمد سعید احمد خان",
                "محمد یاسین خان",
                "35201-1295811-7",
                "71",
                "مکان لاہور نمبر 16 - E ، کیا محلے 4 - بلاگ می نشاط کالونی ، رات دن آراے بازار ، لاہور کینٹ ، ضلع "
            ],
            [
                43,
                20,
                "آسیہ ملک",
                "جاوید",
                "زوجہ شہریار خان",
                "35202-5910345-6",
                "59 59",
                "No No . H. .H . ای ۔ 44 ، گلی ، بلاگ است ، نشاط کالونی ، لاہور کینٹ "
            ],
            [
                43,
                6,
                "مشاب",
                "تعداد خان",
                "35201-1339448-5",
                "47",
                "مکان الامور نمبر 16 - E ، گلی محلے 4۔ بلا ثم نشاط کالونی ، ژاك خانه څارکالونی ، لاہور کینٹ ، ضلع "
            ],
            [
                242,
                44,
                "عامہ حنین",
                "نق",
                "34302-0593877-7",
                "18",
                "مکان نمبر 8/0 عامه مجید روڈ لاہور کینٹ ، ضلعبور "
            ],
            [
                44,
                20,
                "عنبرین اختر عباسی",
                "زوجہ عزیز گل مشنگری",
                "35201-9944003-4",
                "53",
                "امکان نمبر 44 - E گلی نمبر 2 ، محلہ م بلاک نشاط کالونی ، لاہور ، تحصیل لاہور کینٹ ، ضلع لاہور "
            ],
            [
                44,
                6,
                "آمن",
                "سعید احمد خان",
                "35201-1295804-3",
                "41",
                "مکان نمبر 16 16 - - F E ، سٹریٹ نمبر 4 ، تہ نشاط کالونی ، بلاکھ اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                45,
                20,
                "را عمیر",
                "زوجہ عمیر",
                "35201-4218260-8",
                "34",
                "امکان نمبر 44 - E گلی نمبر 2 ، محقہ نشاط اونی ، بالات A ،",
                "، لاہور تحصیل لاہور کینٹ ، ضلع اور "
            ],
            [
                45,
                6,
                "عاطف",
                "ا",
                "محمد سعید احمد خان",
                "35201-1295802-9",
                "38",
                "مکان نمبر 16 - E گلی نمبر 4 ، باش اے ، تے نشاط کالونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                46,
                20,
                "شار نیز",
                "دختر نیز گل",
                "35201-7867818-2",
                "31",
                "مکان نمبر 44 - E E ، گلی نمبر تھے نشاط کالونی . بات",
                "لاہور کینٹ ، ضلع لاہور "
            ],
            [
                46,
                6,
                "کشف معد",
                "سعید احمد خان",
                "35201-9288347-7",
                "30 30",
                "امکان نمبر 16 16 - - A A ، گلی نمبر 4 گلہ نثارکا اوئی ، بلاگ اے لاہور کینٹ ، ضلع لاہور "
            ],
            [
                47,
                21,
                "پروین اختر",
                "زوجہ مہ تارگٹ شاء",
                "35201-1294991-2",
                "45",
                "مکان نمبر 118 118 - - E E ، گلی نمبر 5 ، له نشاط کالونی ، بالا کی اے.اہور کینٹ ، ضلع لاہور "
            ],
            [
                47,
                6,
                "وقام على",
                "سعید احمد خان",
                "35201-4039814-1",
                "29",
                "| مکان نمبر 16 - E ، سٹریٹ نمبر 4 ، محقہ نشاط اونی ، بیات A. ہور کینٹ ، ضلع لاہور "
            ],
            [
                48,
                21,
                "شمارا",
                "زوجہ محمد اقبال شاه",
                "35201-1307070-6",
                "44",
                "کینٹ مکان ، ضلع لاہور نمبر118- E کیا منہ 5۔ بارش و خونی ، ژان مان شاالولی ، لاہور",
                "میل لاہور "
            ],
            [
                48,
                6,
                "شهباز",
                "اثر دے",
                "35201-3231277-5",
                "21",
                "اسٹریٹ نمبر 6 ، محلہ سول دینی نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                49,
                21,
                "تسمیه اصغر",
                "زوجہ رائزر شام",
                "35201-0960141-8",
                "37",
                "امکان نمبر 118 - E ، سٹریٹ نمبر 5 محلہ باد کی اے نشاط کالونی ، باہور کینٹ ، ضلع لاہور "
            ],
            [
                49,
                6,
                "نشان",
                "اشته",
                "35201-7112111-5",
                "19",
                "سٹریٹ نمبر 6 ، محلہ سول ڈین خاط اونی ، لاہور کینت ، شبلع اور "
            ],
            [
                50,
                21,
                "اسالي",
                "زوجہ محمد ساجد علی شاه",
                "35201-8094467-4",
                "29",
                "امکان نمبر 118 118 - - E ، E سٹریٹ نمبر 5 محلہ بڑا",
                "اے خط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                50,
                7,
                "صغيرات",
                "عبدالوحید خان مرحوم",
                "35201-1494398-3",
                "45",
                "مکان",
                ", 1231 - E نمبر1231- E گلی نمبر 12 عمله این بار کے خالونی ، لاہور کی",
                "ضلع ہور "
            ],
            [
                51,
                22,
                "راني في",
                "زوجہ حافظ امتیازاد",
                "35202-2201894-2",
                "54",
                "مکان کی نمبر ضلع لاہور 65/4 6 - E ، گلی نمبر 3 ، اردو جامعہ فاروقی ، محلے وی باہ کن نشاط کالونی ، لاہور "
            ],
            [
                51,
                7,
                "فاروق",
                "ولی شم",
                "35201-4074013-7",
                "34",
                "مکان نمبر 2 ، محلہ بلاك 82 دھوبی گھاٹ مفت پره ، لاہور کینٹ ، قبل",
                "ہونے "
            ],
            [
                52,
                22,
                "صائمہ فیضی",
                "زوجہ فیاض احمد",
                "35201-0845439-8",
                "41",
                "مکان",
                "A نمبر / 5IA 665 - E گلی نمبر 3 محله D بل کے نشاطونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                52,
                7,
                "عامر صغیر",
                "میر ائم",
                "35201-8217599-7",
                "30",
                "امکان نمبر 31 12 12 - - E E ، مٹر یٹ نمبر 12 محله با",
                "این",
                "شادکالونی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                53,
                7,
                "عاصمہ سیر",
                "صغيرا",
                "35201-2199317-3",
                "28",
                "امکان نمبر 1231 - E سٹریٹ نمبر 12 ، محقہ نشاطه لونی . با",
                "انی ، لاہور کینٹ ، ضلع اور "
            ],
            [
                53,
                23,
                "ساجدہ بی بی",
                "زوجہ محمد سلیم",
                "35201-1337236-8",
                "و",
                "امکان نمبر 120- محلہ شاذکا وتی ، باد گنائے ، لاہور کینٹ ، ضلع دا ہوید "
            ],
            [
                54,
                7,
                "تغیر",
                "صغيرا",
                "35201-5671953-1",
                "24",
                "| مکان نمبر E1231 ، سٹریٹ نمبر 12 محله با کشان",
                "خاط کالونی ، لاہور کینٹ ، ضلع ہور "
            ],
            [
                54,
                24,
                "قرر ہو تا ہے",
                "زوجه هم و تار عظیم",
                "35201-1952944-8",
                "45",
                "مکان نمبر 681 عی نمیسر هاے محلہ شاطه ای نی دار کے ڈی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                55,
                8,
                "مصدق",
                "سلطان ام روم",
                "35201-1346300-7",
                "67",
                "امکان نمبر 107-106ع ، سٹریٹ تمبر ، محلہ جا کے اے شما شاد کالونی بات اور کینٹ ضلع لاہور "
            ],
            [
                56,
                8,
                "شهباز علی",
                "محمد صدیقی",
                "35201-1347604-5",
                "43",
                "مکان کند نمبر شل اور 106107 - E ، یاگنے 4 ،",
                "، بام نشاط ܬ کالوی ' مرز ، ڈاک خانة بوده ، اے بازار ، لاہور "
            ],
            [
                56,
                26,
                "حاجه لي لي",
                "زوجہ عبد الغفور",
                "35201-1419142-8",
                "59",
                "کی مکان نمبر ، 88 - E لاہور ، اگلے 4 باکت است نشاطا اهلی ، ڈاکش خانه نشاط کا قول ، لاہوری اور "
            ],
            [
                57,
                8,
                "جهاد علی",
                "محمد صدیق",
                "35201-1346304-1",
                "38",
                "کی مکان می 07 لاہور / 106 - E .",
                "محله 4 ، بات A ، ڈا کالولی ، ڈاکش خانه آرام",
                "بازار ، باہور "
            ],
            [
                57,
                26,
                "میر ارحمت",
                "زوجہ",
                "شترد نخور",
                "35201-7793195-2",
                "34",
                "امکان نمبر 88 - E گلی نمبر 4 گلہ بات اے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                58,
                8,
                "اعجاز علی",
                "محمد صدیق",
                "35201-5632516-9",
                "33",
                "لاہور مکان نمبر کینٹ ، ضلع 107 لاہور E106 ، لی نمبر 4 . محله A با ت شا کا اونی آراے بازار . ابور "
            ],
            [
                58,
                26,
                "ام",
                "نور",
                "دختر عيد الشور",
                "35201-3789346-6",
                "27",
                "مکان نمبر 88 - E ، سشمیت نمبر 4 محلہ نشاط کالونی ، بداث اسے لاہور کینٹ ، ضلع لاہور "
            ],
            [
                59,
                8,
                "نمان",
                "محمد عمران الدين",
                "14301-6111356-1",
                "19",
                "امکان نمبر 6/2 127 1276/2 ، مسٹر یٹ نمبر 7 ملے گال کالونی . با گت بلی ( لاہور کینٹ ، ضلع لاہور "
            ],
            [
                59,
                28,
                "زوجہ محمد اخلاق",
                "35201-1178717-6",
                "62",
                "امکان نمبر 87 - E7 E گلی 51 . نمیره حتی شاید که او نی ، باش ما تور کینٹ ضلع لاہور "
            ],
            [
                60,
                9,
                "ریاض احمد قریشی",
                "فياض بخش قریشی",
                "35201-9761914-3",
                "88",
                "امکان نمبر",
                "1 - 162. E گلی نمبر 7 - F محملہ شادکالونی ، انور تقسیل لاہور کینٹ شلح او چور در شمار "
            ],
            [
                60,
                29,
                "رشیدہ لیلی",
                "زوجہ محمد شفيع",
                "35201-1337234-2",
                "85",
                "مکان کند نمبر 120 ضلعبور - E ، 5 یا مخه 5 ، یا کشم . مین بازار نشاط کالونی اور",
                ". لاہور وما "
            ],
            [
                61,
                9,
                "عاصمہ ریاض",
                "قریشی",
                "ریاض احمد قریشی",
                "35201-3978640-5",
                "50",
                "مکان اور نمبر 112 16 - E . یا ممبر 7 ، محله نشاط و",
                "راست بازار ، لاہور ، تیل اور کند . مان "
            ],
            [
                61,
                29,
                "شما میر و پر هنا",
                "زوجہ نصیر احمد",
                "35201-1260207-8",
                "56",
                "لاہور مکان نمبر کینٹ ، 120 - E ، ضلعبور امله با گرد است میان شاد که او یواش خانه نشاط کا وشی .",
                "در "
            ],
            [
                315,
                61,
                "محمد رحمان",
                "محمد عارف",
                "35201-3326156-1",
                "22",
                "امکان نمبر 11 - E گلی نمبر 8 محله بیدیاں روڈ نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                62,
                9,
                "عامر ریاض",
                "قریشی",
                "ریاض احمد قریشی",
                "35201-9335849-9",
                "49",
                "امکان نمبر 160 - E گلی نمبر 7 ، باگات له نشاط کالونی ، لاہور کی ضلع لاہور "
            ],
            [
                62,
                29,
                "شهناز بیگم",
                "زوجہ شبیر احمد ملک",
                "35201-1417047-8",
                "44",
                "امکان نمبر 120 120 - - E E محلہ بارکت اے خاد کالونی مین را بازار لاہور برای کینٹ ، ضلع ما اره لاہور "
            ],
            [
                63,
                0,
                "ناصر ریاض",
                "قریشی",
                "ریاض احمد قریشی",
                "35201-9440444-9",
                "48",
                "مکان ان نمبر نلعبور 160.E. قیافه 7 - نشاطالوی ، ژاکت خانه نشاط کا اونی ، لاہور ، لیا اور "
            ],
            [
                63,
                30,
                "اثر مابانو",
                "زوجہ مانور",
                "35201-1228877-4",
                "51",
                "مکان نمبر 153 - E کی",
                "فہ",
                "۔ با نشاط کا اولی . ژاکت خانه نشاط کا اولی ، اور کینش ، مع "
            ],
            [
                64,
                9,
                "یاسر ریاضی قریشی",
                "در پیش احمد قریشی",
                "35201-9780537-1",
                "41 امکان نمبر 160 - e گلی نمبر 7 ملے نشاطالوی آراے بازار لاہور کینٹ ، ضلع لاہور "
            ],
            [
                64,
                30,
                "مہرین انور",
                "دختر دافور",
                "35201-8515949-4",
                "33",
                "امکان نمبر 153 - E F گلی نمبر 6 ، محله شادکا اونی . با گت سے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                65,
                9,
                "قام ریاض",
                "قریشی",
                "ریاض احمد قریشی",
                "35201-1417324-7",
                "40",
                "مکان نمبر 427/16 مشر بیٹ نمبر 7 ، محلہ کالونی آراے بازار لاہور کینٹ ، ضلع لاہور "
            ],
            [
                65,
                31,
                "بیماری بالی",
                "زوج من",
                "امه",
                "35201-7237336-6",
                "72",
                "امکان نمبر 12 - E ، سٹریٹ نمبر یہ نشاط کالونی اور کینٹ",
                "شلح ہوں "
            ],
            [
                335,
                65,
                "شان می",
                "حال می",
                "35201-8562085-9",
                "28",
                "امکان نمبر 1817 1817 - - ی e محلے . مریم نشاط کالونی ، با کت ز ، را لاہور کینٹ ، ضلع مردان اور "
            ],
            [
                66,
                9,
                "ندیم و نیم",
                "بشیر ولیم",
                "42201-6495431-5",
                "31",
                "محله آره بازار تادکالونی ، لاہور کینٹ ، ضلع لاہور",
                "یمن "
            ],
            [
                66,
                32,
                "پروین اختر",
                "زوجہ عبدالقیوم",
                "35201-1286998-2",
                "67",
                "امکان نمبر 117 ، گلی نمبر a5 بات . له ت نشاط کالونی : آراستے بازار لاہور کینٹ دیا ، ضلع لاہور "
            ],
            [
                67,
                10,
                "امیر علی",
                "برکت على",
                "35201-3436878-5",
                "81",
                "کینٹ مکان ، نمبر 41 - شلاہور E E ، لاله ها بالا نشاط کا اون . ژاکت خانه نشاط کالونی ، لاہور "
            ],
            [
                67,
                32,
                "اسماء قیوم",
                "دختر عبدالقیوم",
                "35201-3340291-6",
                "34",
                "کیت مکان ، نمبر 117 شلاہور / E . ی نمی 5 مته A بانشاطالوی ڈرامے بازار اور بل",
                "إجر "
            ],
            [
                68,
                10,
                "صدق",
                "مولوی غلام جیلانی",
                "42201-9259872-5",
                "68",
                "امکان نمبر 658 6 - - B .B ملے تو قاروقیہ مہر تشار ویر و کالونی ، ملات ڈی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                68,
                32,
                "شاء قوم",
                "زوجہ یاسرعلی",
                "35201-6490173-2",
                "31",
                "امکان نمبر 17 / E117 E لے تعاونی آراسته بازار بات اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                69,
                10,
                "احسان اللہ",
                "محمد اصغر علی",
                "35201-1541322-3",
                "44",
                "کی مکان",
                "نے نمبر 41 - لاہور E ، گلی محله 4 ، A بلان 7 شرکا ( اولی • ، ذات خانه نشاط کالونی ، لاہور ، شیل لاہور "
            ],
            [
                69,
                33,
                ". علمی تیم",
                "زوجہ",
                "عیدا",
                "35201-1168567-2",
                "48",
                "مکان نمبر 14 114 - E گلی نمبر 5 محلے بات اسے شیاط م کالونی ، ة : لاہور کینٹ ، ضلع . اور "
            ],
            [
                70,
                10,
                "رقان الله",
                "محمد اصغر عفی",
                "35201-9032551-1",
                "36",
                "کینٹ مکان ، نمبر41- ضلعبور E ، کیا محلے 4۔ اے بااثاث خانه نشاط کالونی ، اور میل اور "
            ],
            [
                70,
                35,
                "ظهورا في",
                "زوجہ عبد الصمد خان",
                "38402-1530290-4",
                "58",
                "کینٹ مکان نمبر ، 56 ضلاہور ، سٹریٹ نمبر 2 ، بالی رو و امام بارگاه ، محلہ شاط الولی ، پلاکت است ، لاہور "
            ],
            [
                71,
                10,
                "رضوان اصغر",
                "محمد اصغر علی",
                "35201-1098350-1",
                "34",
                "امکان نمبر 41 41 ج سٹریٹ نمبر 4 ، محلہ گراونی . باوت ۵ ، اور کینٹ ، ضلع لاہور "
            ],
            [
                71,
                35,
                "شانه هم",
                "دختر عبدالصمد خان",
                "38402-3570939-4",
                "32 32",
                "امکان نمبر 56. مشریت نمبر جربانی و امام بارگاہ نشاط کالونی ، باہور کینٹ ، ضلع لاہور "
            ],
            [
                72,
                10,
                "ام عمران النمر",
                "محمد اصغر علی",
                "35201-2813445-1",
                "32",
                "امکان نمبر 41- گلی نمر 4 ملے ہم بات",
                "می شادکالونی اور تحصیل ... لاہور کینٹ ، ضلع لاہور "
            ],
            [
                72,
                35,
                "ارم مبشر",
                "زوجہ مبشر مم",
                "35201-6029180-4",
                "30",
                "مکان نمبر 91 91 ، تربیت نمیم 3",
                "له نشاط کالونی ، بائی روڈ بلاک اے ، اور کینٹ ، ضلع لاہور ۔ "
            ],
            [
                73,
                11,
                "مصداق",
                "می",
                "بخش",
                "35201-1963316-3",
                "70",
                "لاہور مکان کیٹ نمبر ، 49-48 / E لاہور ، کیا ملہ 2۔ بار اسے نشاط کالونی ، وای خانه نشاط کالونی ، لاہور ، شمیل "
            ],
            [
                73,
                36,
                "ساحرہ شر این",
                "دختر محمد شرف",
                "35201-1102227-4",
                "30",
                "امکان نمبر 1236 / F. ثریت نمبر 12 ملے نشاط کالونی اور کینٹ ضلع لاہور "
            ],
            [
                74,
                11,
                "شمیادین",
                "35201-7681607-7",
                "43",
                "امکان نمبر 952 - E گلی نمبر 10 لے مریم نشاط کالونی ، لاہور کینٹ ، ضلع اور "
            ],
            [
                74,
                37,
                "نسیم بیگم",
                "زوجه مه اول",
                "35201-1194266-8",
                "67 67",
                "مکان نمبر 136 136 م - ، e گلی نمبر نمبر 6 ، گله نشاط کا اوری ، باگن اے ، لاہور کینٹ ، ضلع ناہور "
            ],
            [
                75,
                11,
                "مش مل",
                "محمد صدیق",
                "35201-0151644-1",
                "38",
                "امکان نمبر 49 - e48 گلی نمبر 2 متر باشد شرکالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                75,
                37,
                "مر جاواں",
                "وحجم جاوا",
                "35201-1194263-0",
                "40",
                "مکان نمبر E136 سٹریٹ نمر 6 محله شه",
                "اونی ، بات اسے لاہور کنید . شلح",
                "و به "
            ],
            [
                76,
                11,
                "نوید",
                "ام",
                "سدیق",
                "35201-4908423-1",
                "37",
                "امکان نمبر 49-48 / E . سٹریٹ تمبر ، ملے بلاگ اسے نشاط کالونی ، لاہور کینٹ ، ضلع اور "
            ],
            [
                76,
                37,
                "مالے جانیون",
                "وخرم اول",
                "35201-8565351-6",
                "33",
                "مکان نمبر 1936- گلی نمبر 6 ، گله نشاط کالونی . پلاٹ اے ، لاہور کینٹ ، ضلع اور "
            ],
            [
                77,
                11,
                "نے عدیل",
                "صدات",
                "قد صدق",
                "35201-7180934-9",
                "30",
                "مکان نمبر 49-48 / E . سٹریٹ نمبر 2. ملے یا کٹاسقاط کالونی ، چور کینٹ ، ضلع اور "
            ],
            [
                77,
                38,
                "رسولا بیلي",
                "زوجه منظور على",
                "35201-8149140-6",
                "50 الہ امام بارگاه نشانی را بازار ، امور ، تحصیل راہور کینٹ ، ضلع لاہور "
            ],
            [
                78,
                12,
                "اقبال",
                "محبوب بخش",
                "35201-1647078-3",
                "64",
                "امکان نمبر 151 - E ، سٹریٹ نمبر 6 ، بلے بازگشاے ارکالونی ، لاہور کینٹ ، ضلع ہونے "
            ],
            [
                78,
                38,
                "عابدہ پروین",
                "زوجہ اصغر علی",
                "35201-9617367-8",
                "32",
                "اسم باران ملے نشاط کالونی آراے بازار ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                79,
                38,
                "میرا پروین",
                "زوجہ ساجد علی",
                "35201-2406108-4",
                "30",
                "آراے بازار محله امام بارگاہ نشاط کالونی ، لاہور کینٹ ، ضلع ہے ۔ "
            ],
            [
                80,
                12,
                "عروج اقبال",
                "اقبال",
                "35201-2471962-1",
                "33 33",
                "مکان نمبر 151 - E E151 گلی نمبر 6 متهم باوکت نشاط کانونی . انہوں ، میلا ہور کینٹ ، ضلع لاہور "
            ],
            [
                80,
                38,
                "روبینہ انتحار",
                "زوجہ مر التيار",
                "35201-9921212-0",
                "30",
                "امکان نمبر 237 - E .E237 گلی نمبر 1 له شارک اوقی ، سیکر لي ، لا ہور کینٹ ، ضلع لاہور "
            ],
            [
                81,
                39,
                "رانی",
                "زوجہ رشید می",
                "35201-6687562-2",
                "58",
                "امکان نمبر 3. مریم کالونی مین چو گی ، باہور کینٹ ضلع لاہور "
            ],
            [
                81,
                12,
                "ثمران اقبال قریشی",
                "ماتهال",
                "35201-2957045-3",
                "31",
                "مکان نمبر 151 - E151 E گلی نمبر 6 ملم بارکٹ تشار کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                82,
                40,
                "زیر این",
                "زوجہ احمد علی ناصر",
                "35201-1415934-0",
                "50",
                "مکان نمبر 150 - E ، محله بالا کے اسے شاد کالونی ، راہور کینٹ ضلع تور "
            ],
            [
                82,
                12,
                "سفيان اقبال",
                "را قبل",
                "35201-5417240-7",
                "29",
                "مکان نمبر",
                "E 151 سٹریٹ نمبر 6 ، محلہ قال کالونی بات اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                83,
                41,
                "سرور بیگم",
                "زوجہ ماہ رمضان",
                "35201-7829508-0",
                "51",
                "مکان نمبر 159 گلی نمبر 7 ، له نشاط کالونی ، باٹ ، لاہور کینٹ ، ملح ابور "
            ],
            [
                83,
                13,
                "اکبر على",
                "برکت على",
                "35201-1326585-7",
                "65",
                "مکان نمبر 38- زویر بیت و بکا میں ، محلہ من نشاط کالونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                164,
                84,
                "سلائی لی لی",
                "زوجہ ظفر محمود",
                "35201-1198047-0",
                "46 46",
                "امکان نمبر 926 926 - - E . کی نمبر 4 ملے گاد کالونی ، با ای ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                84,
                42,
                "پروین اختر",
                "زوجه عبد الرزات",
                "35201-7181834-4",
                "57",
                "مکان اور نمبر 136 - E ، گلی نمبر 6 ، محلہ اے باد کے نشاط کالونی ، فنا ہویی",
                "سیل لاہور کینٹ ، ضلع "
            ],
            [
                84,
                13,
                "حنیف",
                "غلام قادن",
                "35202-2724825-7",
                "42",
                "مکان گرامی لاہور / نمبر 9 کی A1100",
                "ضلع لاہور سٹریٹ نمبر 6 ، لیندی 1 استیشن 1 محله زمان پارکٹ کیولری "
            ],
            [
                85,
                42,
                "امین تول",
                "دختر عبدالرزاق",
                "35201-6031190-6",
                "29",
                "مکان اور نمبر E136 گلی نمبر 6 ، محلہ شادکا لونی آراستے بار ای باران است ، راہور کینٹ ، ضلع "
            ],
            [
                85,
                13,
                "ارشد على",
                "اکبر على",
                "35201-2747301-5",
                "40",
                "مکان نمبر 38 38 - - E E محلے میٹر مشاطا وتی من روی لاہور کینٹ ، ضلع ہور "
            ],
            [
                86,
                43,
                "اروین ناز",
                "توجہ لیاقت علی",
                "35200-1454517-8",
                "50",
                "امکان نمبر",
                "- E101 گلی نمبر 5 حملے شرکالونی . بله کث اسے لاہور کینٹ ، ضلع لاہور "
            ],
            [
                86,
                13,
                "راشد على",
                "اکبر علی",
                "35201-1326590-3",
                "38",
                "کی مکان ضلع نمبر 38 - E اور ، گلی ، محلہ شادالونی مین روڈ ڈاٹ خانه شال کالونی ، لاہو ہر سال لاہور "
            ],
            [
                87,
                44,
                "زوجہ لیاقت علی",
                "35201-7506138-6",
                "61",
                "لاہور مکان کینٹ نمبر ، 907 - E ، ضلعبور گلی محلہ 11. با ای نشاط کا ولی ، اث خانه نشاط کالونی ، لاہور ، مل "
            ],
            [
                87,
                13,
                "کاشف على",
                "اکبر علی",
                "35201-3748384-1",
                "37",
                "امکان نمبر 8 38 - E محلے شرکالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                88,
                44,
                "رخسانہ پروین",
                "زوجہ حسن اقبال",
                "35201-8113742-6",
                "45",
                "مکان نمبر 909 909.ی گلی نمبر 11 محلہ دارد که او نیما تور کیشت ضلع لاہور "
            ],
            [
                88,
                1310,
                "اکبر على",
                "35201-7783592-5",
                "36",
                "امکان نمبر 38 - 38- E محلہ میں شادالوئی ، اور",
                "میل الپور کینٹ ، ضلع لاہور "
            ],
            [
                89,
                44,
                "ارم ناتے",
                "زوجہ محمد کامران",
                "35201-6877005-4",
                "37",
                "مکان نمبر 907 - E حشرٹ نمبر 11 ملے بلاگشای شاد کالونی اور کینٹ لاہور "
            ],
            [
                89,
                14,
                "محمد یونس",
                "35201-8284453-7",
                "55",
                "امکان نمبر E159 E159 گلی نمبر 7 مجله نشاط کالونی ، باد گشاے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                405,
                90,
                "تیم مشتاق",
                "مشتاق",
                "35201-4466678-1",
                "26",
                "لاور مکان نمبر 1207 ، کیا میرے گلے آخری وین سٹاپ نشاط کا اول . بالاکشانی لاہور کیت ، مسلح "
            ],
            [
                90,
                44,
                "انالیاقت",
                "دختر لیاقت علی",
                "35201-2766310-2",
                "25",
                "امکان نمبر 907 - F , سٹریٹ نمبر 11 مجله نشاط کالولی . بلاگائی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                90,
                14,
                "شن",
                "انور مست",
                "35201-0349220-7",
                "36",
                "محله بایو مرشان بیدیاں روڈ لاہور کینٹ ، ضلع لاہور "
            ],
            [
                91,
                45,
                "سکینہ بی بی",
                "زوجہ شوکت علی",
                "35201-2223824-2",
                "66",
                "امله شادونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                91,
                14,
                "محہ زیر",
                "محمد یونس",
                "35201-5776264-5",
                "33",
                "مکان اور نمبر 159 159 - e کلی میر 7 محلے اطالوی آراستے بازار ، بات اسے ، لاہور کینٹ ، ضلع "
            ],
            [
                92,
                45,
                "معافی طرق",
                "زوجہ مہ خارق",
                "35201-7011721-2",
                "26",
                "محله شاره ونی ۔ لاہور کینٹ ضلع لاہور "
            ],
            [
                92,
                14,
                "احم جاء به",
                "مار میونس",
                "35201-2542657-9",
                "31",
                "امکان نمبر E159 گلی نمبر 7 ملہ شادالوئی . بات",
                "لاہور کینٹ ، ضلع لاہور "
            ],
            [
                93,
                46,
                "زوجہ غلام رسول",
                "35201-5827320-0",
                "51",
                "لاہور مکان نمبر 1165. یا نمبر 1 ، محلہ شاط کالونی راست یاثار ، لاہور ، کمیل لاہور کینٹ ، ضلع "
            ],
            [
                93,
                14,
                "محمد عدیل یونس",
                "محمد یونس",
                "35201-0312693-3",
                "26",
                "امکان نمبر 159 - E ، مشرٹ نمبر 7 ملہ شادکالونی ، پلاکت اسے ، راہور کینٹ ، ضلع لاہور "
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
                95,
                46,
                "تھی غلام رسول",
                "دختر غلام رسول",
                "35201-5051675-6",
                "25",
                "مکان نمبر 1165 ، سٹریٹ نمبر 1 له شال کالونی راستے بازار لاہور کینٹ ، ضلع لاہور "
            ]
        ];
        return $data = [
            [
                "25",
                "3",
                "ساير جمیل",
                "علامہ",
                "35200-1547756-5",
                "38",
                "مکان نمبر مان E149 ، گلی ، محله گلی 6 باب",
                "اسے نشاط کالونی ، ڈاک خانہ آ راستے بازار ، لاہور "
            ],
            [
                "26",
                "C 3",
                "یان بیل",
                "جمیل احمد",
                "35201-1472798-9",
                "38",
                "کینٹ مرات ، ضلع 149 لاہور",
                ". ک / گله تلہ 6۔ باالك",
                "نے شادی اور",
                "لاہور "
            ],
            [
                "27",
                "3",
                "اشرف",
                "برایت می",
                "35201-5676510-3",
                "32",
                "امکان نمبر 1529 / E ، گلی نمبر 1 محل نشاط کالونی ، لاکھوں میل لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "28",
                "3",
                "کی ہدایت",
                "برایت",
                "تا",
                "35201-6433632-9",
                "27",
                "مکان نمبر 1529 / E ، سٹریٹ نمبر 1 ، محلہ نثارکالونی ، اور کینٹ",
                "ضلع لاہور "
            ],
            [
                "29",
                "4",
                "محمد زاہد",
                "محمد اقبال",
                "35201-6500926-3",
                "60",
                "مکان نمبر 51 51 - - E E ، سٹریٹ نمبر 2 ، محله نشاط کالونی ، بات اسے لاہور کینٹ ، ضلع",
                "ہور "
            ],
            [
                "30",
                "4",
                "محمده ر یان",
                "اقبال",
                "35201-0112035-3",
                "58",
                "مکان نمبر 51 -51 - E ، مٹر یٹ نمبر 2 محلہ باٹ A شارکا دی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "31",
                "4",
                "امانت علی",
                "34602-4038590-9",
                "53",
                "امکان نمبر 4 / 1245 - E محله نشاط کالونی ، لاہور کینٹ",
                "ضلع لاہور "
            ],
            [
                "32",
                "4",
                "آمن را",
                "محمد ریاضی",
                "35201-8290377-5",
                "35",
                "امکان نمبر 51 51. ) , سٹریٹ نمبر 2 محلہ بلاش اسے شاد کالونی ، لاہور کینٹ ، ضلع",
                "تور "
            ],
            [
                "33",
                "4",
                "کاشف",
                "ریاض",
                "محمد ریاض",
                "35201-2898576-7",
                "33",
                "امکان نمبر 51 51 - - E E گلی نمبر 2 محله با ت 8 شاطه وی ، لاہور ، تحصیل لاہور کینٹ ، ضلعبور "
            ],
            [
                "34",
                "4",
                "محسنين على",
                "محمد ریاض",
                "35201-2526932-3",
                "25",
                "مکان نمبر 51 - E ، سٹر یٹ نمبر 2 ملہ بات اسے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "35",
                "4",
                "عامر شرار",
                "امانت",
                "على",
                "34602-2755177-1",
                "24",
                "امکان",
                "4 نمبر / 1245 124 - - E E مجله نشاط کالونی . ! اہور کینٹ ، ضلع لاہور "
            ],
            [
                "36",
                "5",
                "يوسف",
                "طلعت",
                "درشن انکم الی",
                "35201-3531021-3",
                "61",
                "مکان نمبر 730 1730 - E ، محله با کنجے مریم نشاط کالونی اور تحصیل اور کینٹ ، ضلع لاہور "
            ],
            [
                "37",
                "5",
                "عارف على",
                "شوکت علی",
                "35201-3233110-1",
                "45",
                "مکان نمبر 22 ، شته نشاط کالونی ، لاہور تحصیل",
                "امور کی",
                "ضلع لاہور "
            ],
            [
                "38",
                "5",
                "باطن",
                "طلعت",
                "ومن",
                "طلعه",
                "35201-5545624-9",
                "29",
                "کا نے مد",
                "میں",
                "کرنلی "
            ],
            [
                "39",
                "5",
                "عارف",
                "على",
                "35201-3875995-1",
                "28",
                "امکان نمبر 22 محله څاط کا اونی ، لاہور کینٹ لاہور "
            ],
            [
                "40",
                "5",
                "اینرو",
                "35201-4635045-7",
                "24",
                "اس",
                "دور میں ان",
                "کی",
                "ملی "
            ],
            [
                "41",
                "5",
                "کاشف على",
                "عرف على",
                "35201-1584488-9",
                "22 22",
                "مکان نمبر 22 محلہ شادکالونی ، لاہور کینٹ ، ملح ابور "
            ],
            [
                "42",
                "6",
                "یا",
                "اون",
                "وای فای",
                "35201-1295811-7",
                "71",
                "مکان نمبر 16 - E. E ، گلی محلہ 4- بلاك A ، نشاط کالونی ، ڈان",
                "نہ راے بازار ، لاہور کینٹ ، ضلع "
            ],
            [
                "43",
                "6",
                "شامل",
                "محمد سعید احمد خان",
                "35201-1339448-5",
                "47",
                "مکان الاور نمبر 16 - E ، گلی محلے 4۔ بلا ثم شاد کالونی ژاکت خانه خارکالونی ، لاہور کینٹ",
                "ضلع "
            ],
            [
                "44",
                "6",
                "دن",
                "سعید احمد خان",
                "35201-1295804-3",
                "41",
                "لاهور مکان نمبر 16 16 - - F E ، سٹریٹ نمبر 4 ، محقہ نشاط کالونی ، بالاک اے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "45",
                "6",
                "عاطف",
                "سعید احمد خان",
                "35201-1295802-9",
                "38",
                "امکان نمبر 16 16 - - E E گلی نمبر 4 ، بات اسے منہ نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "46",
                "6",
                "کشف معبد",
                "سعید احمد خان",
                "35201-9288347-7",
                "30",
                "مکان نمبر 16 - میگلی نمبر 4 ، محله نشاط کالونی ، با کی اے لاہور کنیت ضلع لاہور "
            ],
            [
                "47",
                "6",
                "وقاص على",
                "سعید احمد خان",
                "35201-4039814-1",
                "29",
                "مکان نمبر 16 - E ، سٹریٹ نمبر 4 مجله نشاطا اونی ، بات A. اہور کینٹ ، ضلع لاہور "
            ],
            [
                "48",
                "6",
                "شاز",
                "اشت",
                "35201-3231277-5",
                "21 21",
                "اسٹریٹ نمبر 6 ، محلہ سول ونف نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "49",
                "6",
                "زان",
                "اشرده",
                "35201-7112111-5",
                "19",
                "سٹریٹ نمبر 6 ، محلہ سول ڈین خاط ) الونی ، لاہور کی",
                "قبلع اور "
            ],
            [
                "50",
                "7",
                "منیر احمد",
                "عبدالوحید خان مرحوم",
                "35201-1494398-3",
                "45",
                "مکان",
                "1231 - E E نمبر1231- گلی نمبر 12 مجله این",
                "بار کی شادی لونی ، لاہور کینٹ",
                "ضلع ہور "
            ],
            [
                "51",
                "7",
                "عمر فاروق",
                "ولی شم",
                "35201-4074013-7",
                "34",
                "مکان نمبر 2 ، ملہ بلاک 82 دھوبی گھاٹ مفت پره ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "52",
                "7",
                "عامر شفر",
                "مغيرات",
                "35201-8217599-7",
                "30",
                "امکان نمبر 31 12-",
                "مٹر یٹ",
                "نمبر 12 محلے بایگانی",
                "ادکالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "193",
                "33",
                "عمر سعيد",
                "سعيدا",
                "35201-2765983-1",
                "29",
                "امکان نمبر 14 114 - E ، شیر بیٹ",
                "نمبر 5 ، محلہ بار کے اسے نشاط کالونی ، لاہور کینٹ ، ضلعبور "
            ],
            [
                "194",
                "33",
                "ابرار عيد",
                "عيدا",
                "35201-2861933-1",
                "25",
                "امکان نمبر 14 114 / E ، سٹریٹ نمبر 5 محلے بار اسے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "195",
                "33",
                "عبید الرحمن",
                "محمد اکرم",
                "34502-0632259-3",
                "19",
                "اور مکان نمبر 10222A - E ، سٹریٹ نمبر 4 ، محلہ شاط کالولی ، با کٹ الی",
                "، لاہور کینٹ ، ضلع "
            ],
            [
                "196",
                "34",
                "مخاب",
                "چوہدری حاتم على",
                "35201-7743584-5",
                "77",
                "امکان نمبر 6 ، گلی نمبرد متهم با نشاط کالونی ، اور تحصیل لاہور کینٹ",
                "لاہور "
            ],
            [
                "197",
                "34",
                "خلیلیه",
                "تقرير اتماگاز",
                "35201-5779448-1",
                "54",
                ", Lahore Shahdara Ferozwala , Colony Peoples , 2 St , 1 Scheme , 82 No H "
            ],
            [
                "198",
                "34",
                "ناصر ایاز",
                "محمد خان",
                "35201-8937962-5",
                "37",
                "امکان نمبر E6 گلی نمبر 1 محلے باتھ شادی ہوئی اور کینت ، خلع اور "
            ],
            [
                "199",
                "34",
                "ولی ایاز",
                "محمد خان",
                "35201-6566711-5",
                "27",
                "مکان نمبر 6 E6 ، شریث نمبر 1 محلہ اسے بلاکث نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "200",
                "35",
                "عبدالصمد خان",
                "برہان الدین",
                "38402-1585513-5",
                "53",
                "t مکان نمبر 56 - E ، گلی نمبر 2. محلی و بانی و ژامام بارگاه نشاط کالونی ، بل کٹ اسے لاہور کینٹ ، ضلع "
            ],
            [
                "201",
                "35",
                "مدثر عمر خان",
                "عبدالصمد خان",
                "35201-1569366-5",
                "31",
                "انه مکان کیر ضلع 2 / می 19 - E ، سٹریٹ نمبر 4 ، نزد امام بارگاہ محلہ شال کالونی ، بات ۸.اہور "
            ],
            [
                "202",
                "35",
                "مشر",
                "عبدالصمد خان",
                "35201-8152387-9",
                "30",
                "امکان نمبر 91 گلی نمبر 3 محله نشاط کا اونیر ہانی روژی بانگت A ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "203",
                "35",
                "مجریان",
                "کل م",
                "35201-5903871-5",
                "18",
                "ڈیفنس ہاؤسنگ",
                "اتہ پی "
            ],
            [
                "204",
                "36",
                "مشران",
                "عبدالله",
                "35201-1405750-1",
                "62",
                "امکان نمبر 1236 / F ، مشترین نمر 12 ملہ خاط کالونی امور کے ضلع لاہور "
            ],
            [
                "205",
                "36",
                "عبدالقیوم",
                "ام دین",
                "35201-1300959-3",
                "50",
                "امکان نمبر 140 - E . گلی نمبر 6 ، محله شادالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "206",
                "36",
                "أم",
                "سعید اختر",
                "شراف",
                "35201-6066290-1",
                "34",
                "امکان تمر 1236 / F گلی نمبر 12 ملہ خارا اونی ، لاہور کند . شلح راہور "
            ],
            [
                "207",
                "36",
                "حبیب الرحمن",
                "شريف",
                "35201-4108854-3",
                "28",
                "امکان نمبر 1236 / F ، حشریت نمبر 12 ملے نشاط کا اونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "208",
                "37",
                "مالوں",
                "عیدانی",
                "35201-1234680-3",
                "72",
                "مکان نمبر ۔ 136 ای کلی محلہ",
                "اسے بلا کے خط کالونی ، ڈاک خانه آراے بازاری لاہور "
            ],
            [
                "209",
                "37",
                "محمد عمران",
                "مجاوں",
                "35201-1234565-5",
                "44",
                "مکان نمبر ضلع 136 لاہور - E ، یا مثله 6 نشاط کالونی اے بلاك ، ڈاث خانه آراے بازار ، لاہور "
            ],
            [
                "210",
                "37",
                "عثمان والوں",
                "محاول",
                "35201-1234566-9",
                "42",
                "امکان نمبر 36",
                "- el e کلی نمیری ، محلے شادگاروقی ، لاہور کینٹ ، ضلاہور "
            ],
            [
                "211",
                "37",
                "عامر",
                "حسین",
                "امیر",
                "35201-1393460-5",
                "40",
                "کالونی مکان ، لاہور ، نمبر 1273/6/2 لاہور ، کینٹ ، محله ضلع G لاہور بابك سيدا باو نشاط کالونی ، ڈاک",
                "خانہ خاط "
            ],
            [
                "212",
                "38",
                "سید صفدر مهدی رضوی",
                "سید محمد ناظم رضوی",
                "35202-2854016-7",
                "57 57",
                "امکان نمبر 134 م ، سٹریٹ نمبر 17 حملہ کیولری گریونی کیشن",
                "لاہور لاہور کین کینٹ ، ضلع لاہور "
            ],
            [
                "213",
                "38",
                "اصغر 6 على (",
                "منظور علی",
                "35201-9638895-9",
                "امام بارگاه عملہ شادکا اور آراستے بازار ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "214",
                "38",
                "ساجد علی",
                "منظور علی",
                "35201-6580371-3",
                "39",
                "متر امام بارگاہ تارکا اونی آراستے بازار لاہور کینٹ ضلع لاہور "
            ],
            [
                "215",
                "38",
                "عاد حسین",
                "کر منظور علی لی",
                "35201-4383477-9",
                "32",
                "امام بارگاہ محلہ شادالوئی آراے بازار لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "216",
                "39",
                "حبیب الی",
                "محمد محبوب",
                "الئی",
                "35201-1391716-1",
                "78",
                "مکان نمبر 928 - E ، مسٹر یٹ نمبر 8 ، محلہ لنک",
                "روڈ نمبر 4 شاط کوفی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "217",
                "39",
                "رشیدی",
                "بركت مع",
                "35201-4876771-3",
                "63 63",
                "امکان نمبر 3 ملہ مریم کالونی جن چوکش ، اور تحصیل لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "218",
                "39",
                "حمید",
                "برکت می",
                "35201-1173079-9",
                "58",
                "مکان نمبر 84 / E ، گلی نمبر 4 ، محله نشاط کالونی ، لاہور ، تحصیل لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "219",
                "39",
                "مه ارشد",
                "رشیدی",
                "35201-8318413-7",
                "29",
                "امکان نمبر 3 ، محلہ کالونی من چوک",
                "، اور کینٹ ، ضلع لاہور "
            ],
            [
                "220",
                "39",
                "قاسم رشید",
                "شدم",
                "35201-6017879-5",
                "28",
                "امکان نمبر 3 محلے مراد فی من چوک ، اور کینٹ لاہور "
            ],
            [
                "35201-6788866-9",
                "44",
                "امکان نمی 91-",
                "سنٹر بٹ نے",
                "تے الگٹ ان قطالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "2",
                "1",
                "من ندی",
                "مین",
                "ملک ناظر حسین",
                "35201-1318241-7",
                "43",
                "مکان نمبر 91 - E ، مٹر یٹ نمبر ہی محله فشار او تی بیان نے لاہور کینٹ ، ضلع لات و در "
            ],
            [
                "3",
                "1",
                "من ظی نیس ۔",
                "ملک",
                "ناظر حسین",
                "35201-1317661-7",
                "40",
                "امکان نمبر 91 - E گلی نمبر و له بلیت اسے شاید کالونی اور کینٹ ، ضلع لاہور "
            ],
            [
                "4",
                "1",
                "ملک",
                "ناظر حسین",
                "35201-1305801-7",
                "38",
                "امکان نمبر 91 91 - - E E گلی نمبر 4 ملہ بات اسے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "os",
                "35201-0158496_5",
                "3",
                "ارکان نے 30 8.1 ومتش",
                ".. نی",
                "نی ریز بیان کیا ہے ۔ لاہور کے ضلعبور "
            ],
            [
                "6",
                "1",
                "حل",
                "تمام قریه",
                "ملک ناظر حسین",
                "35201-4112582-1",
                "33 33",
                "امکان نمبر 91 - E ، سٹریٹ نمبر 4 ، محله و بلات نشاط کالونی ، اور کینٹ ، ضلع لاہور "
            ],
            [
                "7",
                "1",
                "ممنون حسین",
                "ملی",
                "ناظر حسین",
                "35201-8679904-7",
                "32",
                "مکان نمبر دوہ ، گلی نمبر 4 ، له نشاط کالونی . بات اے لاہور کنیت ، صلح ناہور "
            ],
            [
                "8",
                "; ا",
                "امن على",
                "35201-8489195-7",
                "32",
                "مکان نمبر 130 130 - - E E ، گلی نمبر 5 ، ته بای کت A ربانی روڑ نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "9 10",
                "1 1",
                "اصغر",
                "علی",
                "35201-8609210-7",
                "31",
                "امکان ووٹر اسی نمبر 130 110 شاریان - - E .E بلاگ گلی ست نمبر زیر 5 دفعہ محله ۸ بلات 37/25 137125 الیکشن خاطکالونیر ایکٹ بالی روٹی 2017 ناہور حذف کینٹ ، منتقل ضلع ہوگیا لاہور ہے ۔ "
            ],
            [
                "11",
                "2",
                "ملام",
                "محمد صادق مرحوم",
                "35201-1503708-7",
                "66",
                "مكان ممبر شای 86 - E ، یا گلہ بات مشراون ، ژان خان شادالولی ، لاہور ،",
                "لاہور "
            ],
            [
                "12",
                "2",
                "وال",
                "رحمت علی",
                "33302-8955222-9",
                "49",
                "امکان نمبر 503 - ع ، بال مسجد محلہ مکروڑ 2 شاط کا اونی . 1 ہور کینٹ",
                "ضلع لاہور "
            ],
            [
                "كا",
                "n",
                "الده",
                "35201-5981584.1",
                "36",
                "اس نے FR6 مالونیا گیر می کند",
                "ضلع "
            ],
            [
                "14",
                "2",
                "عظیم اسلام",
                "محمد اسلام",
                "35201-3841005-9",
                "35 35",
                "امکان نمبر B6 - E محلہ بان انتشار کالونی اور کینٹ ، ضلع اور "
            ],
            [
                "15",
                "2",
                "من",
                "سلام",
                "اسلام",
                "35201-7338936-5",
                "32",
                "امکان نمبر 86 - E ، محله د برکت نشاط کالونی اور کینٹ ، ضلع لاہور "
            ],
            [
                "16",
                "2",
                "حمام",
                "واسراره",
                "35201-9223232-3",
                "31",
                "امکان نمی 86 - E ، محلہم بات نشاطا و فیہ لاہور کینٹ ، ملح ابور "
            ],
            [
                "10",
                "ہے",
                "۔ C",
                "1",
                "35201.7432086_5",
                "29",
                "اي",
                "86 نوع من متالونیا کی جان کر تل "
            ],
            [
                "18",
                "2",
                "نیم اسلام",
                "سلام",
                "35201-9222187-3",
                "26",
                "امکان نمبر 86- له نشاط کالونی بات اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "19",
                "2",
                "بار علی خالہ",
                "خالد محمود",
                "33302-0740886-1",
                "23",
                "امکان نمبر 503 503 - - تا ، .E بلاول مسجد . ملے تنگ روڈ 2 شاط کالونی . بات کی ، اور کینٹ ، ضلع لاہور "
            ],
            [
                "مه",
                "د",
                ": . :",
                "33302-0740981-1",
                "20",
                "امکان نمبر 503 - E , اہل مکند محله نگرو منشا",
                "اونی",
                "عبور کند",
                "ضلعبور "
            ],
            [
                "20",
                "2",
                "-",
                "-",
                "كاو",
                "00",
                "ا",
                "انماط",
                "61",
                "ا",
                "د",
                "دا",
                ", میں",
                "نبی اور",
                "ضلعی "
            ],
            [
                "22",
                "3",
                "بیلا",
                "35200-1567315-3",
                "52",
                "مكان مبر 149 - E ، می نمبر 6 ، گلہ اے بات څارکالونی ، لاہور ،",
                "لاہور کین",
                "، مسلح "
            ],
            [
                "23",
                "3",
                ":",
                "جمل",
                "میلا",
                "35200-1567336-5",
                "49",
                "امکان نمبر 149 - E گلی نمبر 6 محله A با کن نشاط کالونی ، لاہور تحصیلی امور کی",
                "ضلع لاہور "
            ],
            [
                "24 اد",
                "3",
                "شاعر جمیل",
                "محمد جمیل احمد",
                "35200-1567264-3",
                "44",
                "مکان نمبر 149 - E ، گلی نمبر 6 محله شال کالونی . بلاتاہے لاہور کی",
                "ضلع لاہور "
            ],
            [
                "53",
                "7",
                "عاصم سنی",
                "منیرا",
                "35201-2199317-3",
                "28",
                "امکان نمبر 1231 - E ، سٹریٹ نمبر 12 ، محقہ نشاطی لونی . با",
                "این ، لاہور کینٹ",
                "ضلع اور "
            ],
            [
                "54",
                "7",
                "کیر تسخیر",
                "غرام",
                "35201-5671953-1",
                "24",
                "مکان نمبر E1231 ، سٹریٹ نمبر 12 محلہ باگ الین",
                "خاطالوتی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "55",
                "8",
                "مصدق",
                "سلطان ام روم",
                "35201-1346300-7",
                "67",
                "مکان نمبر 107-106ع E106 ، سٹریٹ نمبر 4 محله مارکت است نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "56",
                "8",
                "شہباز",
                "علی",
                "محمد صدیق",
                "35201-1347604-5",
                "43",
                "کند مكان ، في نمی شل اور 106107 - E ، مجمع اگلے 4 ، بلا A ، شاطكاوي هرمون . فالك ارشد حانها ، آرا اے بازار بازار ، امور امور "
            ],
            [
                "57",
                "8",
                "سجاد علی",
                "محمد صدیق",
                "35201-1346304-1",
                "38",
                "مکان کنید ، نمبر ضلع 07 ہو ۔ / 106 - E کی امتہ 4. باگت A ، ڈا کالونی . ڈاث خانه آراستے بازار ، باہور "
            ],
            [
                "58",
                "8",
                "اجار على",
                "محمد صدیق",
                "35201-5632516-9",
                "33",
                "مکان لاہور نمبر کینٹ ، ضلع 6/107 لاہور E10 .",
                "4. گلہ A بات شالاوی",
                "راست بازار لاہور ، "
            ],
            [
                "E",
                "8",
                "ا . ال",
                "-",
                "14301-6111356-1",
                "19",
                "مکان نمبر 1276/2 ، مشر بین نمیر 7 مله نقاط کالونی . بلاگ",
                "بھی لاہور کینٹ ، لاہور "
            ],
            [
                "60",
                "9",
                "در",
                "احمد قریشی",
                "فیاض بخش قریشی",
                "35201-9761914-3",
                "88",
                "مکان نمبر 162 - E گلی میر 7 - E مجله نشاط کالونی ، لاہور ترسیلا ہور کینٹ ، ضلع لاہور "
            ],
            [
                "مه",
                "او",
                "ت",
                "تا",
                "35201-3978640-5",
                "50",
                "مکان نمبر 12 E16 گلی نمبر 7 محقہ نشاطه او نیا راست بازار اور",
                "سیل لاہور کیند . شاج "
            ],
            [
                "62",
                "9",
                "عامر ریاض",
                "قریشی",
                "ریاض احمد قریشی",
                "35201-9335849-9",
                "49",
                "یک مکان نمبر",
                "1 - E160 E گلی نمبر 7 ، بات اسے میلیوری محلہ :: نشاط کالونی ، لاہور نشانی کی اور ضلع نا لاہور اور "
            ],
            [
                "تارت",
                "35201-9440444-9",
                "48",
                "مکان نمبر 160.E. کلی اخہ 7",
                "نشاط کالونی ، ژاکت خانه نشاط کالونی ، لاہور ، مسیل لاہور "
            ],
            [
                "64",
                "9",
                "ہر ریاض",
                "قریشی",
                "ریاض",
                "احمد قریشی",
                "35201-9780537-1",
                "41",
                "مکان نمبر 160 - e گلی نمبر 7 محله نشاط کالونی راستے بازار لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "65",
                "9",
                "قاسم ریاض قریشی",
                "ریاض احمد قریشی",
                "35201-1417324-7",
                "40",
                "مکان نمبر 427/16",
                "شر میت نمبر 7 ، ملے شرکالونی آراے بازار لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "66",
                "9",
                "ندیم و نیم",
                "بشیر ولیم",
                "42201-6495431-5",
                "31",
                "امتلے آ را یاترار شاد کالونی ، اور کینٹ ، ضلع لاہور "
            ],
            [
                "67",
                ". 10",
                ": ایام عل",
                "35201-3436878-5",
                "81",
                "مکان نمبر 41 - E ، کیا محلہ واے بلا کی شادکالونی ، ژاکت خانه شادکالونی ، لاہور ، میلی اور "
            ],
            [
                "68",
                "10",
                "صدیق",
                "مولوی غلام جیلانی",
                "42201-9259872-5",
                "68",
                "مکان نمبر 658 - B محل نزد فاروقیه مجداد کالونی ، پلاکت",
                "کی ، لاہور کینت . خلع ہور "
            ],
            [
                "69",
                "10",
                "احسان اللہ",
                "محمد اصغر علی",
                "35201-1541322-3",
                "44",
                "کینٹ مکان ، نمبر ضلع 41 - لاہور E کیا محلے A4 A4 بانگت شهداء نیوان خانه نشاط کالونی ، لاہور",
                "میل لاہور "
            ],
            [
                "70",
                "10",
                "قان الشم",
                "مرار علي",
                "35201-9032551-1",
                "36",
                "کینٹ مکان ضلع لاہور کر41- ، لیا ۔ 4۔ اے بات.واك انہشاط کالونی ، لاہور",
                "اور "
            ],
            [
                "71",
                "10",
                "رضوان اصغر",
                "محمد اصغر عنی",
                "35201-1098350-1",
                "34",
                "امکان نمبر 41 م ، سٹریٹ نمبر 4 ، محله نشاط کالونی ، بات",
                "، اور کینٹ",
                "لاہور "
            ],
            [
                "72",
                "10",
                "عمران اصغر",
                "محمد اصغر عفی",
                "35201-2813445-1",
                "32",
                "امکان کے نمبر A9 - 41- گلی FA8 نمبر 4 کیا ملہم ارگ بات شاید کالونی او ، با لوی بورس عنوان تحصیل لاہور شرکالونی کینٹ ، اور ضلع لاہور صل "
            ],
            [
                ". 73",
                "11",
                "35201-1963316-3",
                "70",
                "مکان نمبر 49 - E148 ، کیا محلہ 2۔ بات اسے",
                "اطالونی ، ڈاث خانه نشاط کالونی ، لاہور ،",
                "میل "
            ],
            [
                "الا 74",
                "11 دا",
                "شمار یا می",
                "35201-7681607-7",
                "43",
                "اور امکان ی نمبر ، 952 - E اور گلی نمبر 10 محلے مریم نشاط کالونی ، لاہور کینٹ ضلع اور "
            ],
            [
                "75",
                "11",
                "LE",
                "35201-0151644-1",
                "38",
                "مکان نمبر 49 - e48 گلی نمبر 2 ، محله بای کت و شل کالونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                "76 ال 2",
                "11 اا",
                "3 نوید",
                "محمد صدیق",
                "35201-4908423-1",
                "37",
                "امکان نمبر 49-48 / E ، سٹریٹ نمبر 2 محلے باکی اے نشاط کالونی ، لاہور کیند . تلعبور "
            ],
            [
                "77",
                "11",
                "35201-7180934-9",
                "30",
                "امکان نمبر 49-48 / E . سٹریٹ نمبر لے جایا گیا شاد کالونی ، لاہور کینٹ ضلع ہوں "
            ],
            [
                "- 78 79",
                "12 اا",
                "اقبال",
                "محبوب بخش",
                "35201-1647078-3",
                "64",
                "موثر امکان اس نمبر شماریاتی 151- بات مثمر سے بیٹ زیر دفعہ میری محلے 3725 137125 بابا الخزاید شادکامی ، 2017 لاہور کینٹ حذف ، ضلع منتقل",
                "ہو ہوگیا ۔ ہے ۔ "
            ],
            [
                ".",
                "دد 80",
                "12",
                "عروج اقبال",
                "اقبال",
                "35201-2471962-1",
                "33 33",
                "امکان نمبر 151 - E151 E گلی نمبری متهم بان ارگانونی باہوں میل الہور کینٹ ، ضلع اور "
            ],
            [
                "221",
                "39",
                "سلیم",
                "حمید می",
                "35201-2750025-7",
                "24",
                "مکان نمبر 84 -84 - E ٹرین تیر 4. له نشاط کالونی",
                "اور",
                "gla کینٹ ، ضلع را پیو بر",
                "فله "
            ],
            [
                "222",
                "40",
                "احمد علی ناصر",
                "حامی تر",
                "35201-1508364-7",
                "و",
                "امکان نمبر 414 گلی نمبر 4 ملی بادگی شاد کا ہوئی ، اور تیل لاہور کینٹ میرم ضلع لاہور "
            ],
            [
                "223",
                "40",
                "ریال است",
                "لاہوری می",
                "35201-8345451-9",
                "51",
                "اور مردن",
                "378 "
            ],
            [
                "224",
                "40",
                "ممتاز علی",
                "احد على ناصر",
                "35201-6704774-7",
                "31",
                "ایران نمیر E150 محلہ شادکا وفی . ایکٹ اسے لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "225",
                "40",
                "ممر التيار علی",
                "احمد علی ناصر",
                "35201-9711326-1",
                "29",
                "مکان نمبر 414 گلی نمبر 4 محلے شاد کالونی . با کت c \\ ' اہور کینٹ ، ضلع لاہور "
            ],
            [
                "226",
                "41",
                "محمد رمضان",
                "زنون",
                "35201-9545714-7",
                "57",
                "مکان نمبر 159 - E گلی نمبر 7 محلے شاد کا کوئی بات اسے ، ا نہور کینٹ ، ضلع ایپور "
            ],
            [
                "227",
                "41",
                "محمد بلال",
                "محمد رمضان",
                "35201-2551878-7",
                "28",
                "امکان نمبر E159 گلی نمبر 7 مجله نشاط کالونی ، باران ۸ ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "228",
                "41",
                "محمد ارسلان فا مغل",
                "عبدالرضا مغل",
                "35201-8907058-9",
                "27 27",
                "امکان نمبر 314- ، محلے مین",
                "ہائمنگ",
                "اتھارٹی ، فیر 3 لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "229",
                "41",
                "اسرار",
                "محمد رمضان",
                "35201-4617836-1",
                "26",
                "مکان نمبر 159 - E گلی . نمبر 7 ، باد کے اسے ملے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "230",
                "41",
                "مالاک",
                "رمضان",
                "35201-4254434-9",
                "25 25",
                "امکان نمبر 159 - E ، سٹر یٹ نمبر 7 محلہ نشاط کالونی ، بان ۸ ، لاہور کینٹ ، ضلع اور "
            ],
            [
                "35201-1293015-1",
                "45",
                "F395 گلی نمو على نشاط کالونی",
                "اجور کند ضلع ضلع لاہور "
            ],
            [
                "232",
                "42",
                "اشفاق",
                "عبدالرزاق",
                "35201-9542692-9",
                "37",
                "امکان نمبر 136 136 - - E .E گلی نمبر 6 . حملے شاد کالونی ، پلاکش ، لاہور میں لاہور کینٹ ، ضلع اور "
            ],
            [
                "233",
                "42",
                "اعجاز",
                "علی",
                "عبد الرزاق",
                "35201-9197551-7",
                "30",
                "امکان نمبر 136 E136 - .E گلی نمبر 6 محله A با",
                "نشاط کالونی آراے بازار ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "234",
                "42",
                "آمن على",
                "عبدالرزاق",
                "35201-5830090-3",
                "24",
                "مکان نمبر خلع 136 - E . سشمت نمر 6 محلہ خاطالوی آراستے بازار ، بلا تاسے ، لاہور "
            ],
            [
                "235",
                "43",
                "لیاقت علی",
                "دیوان",
                "علی",
                "35201-4144287-3",
                "60",
                "امکان نمبر وو . E. گلی نمبر 5 ملہ شادکالونی",
                "اور .. نیلا ہور کینٹ لاہور "
            ],
            [
                "236",
                "43",
                "على",
                "لیاقت",
                "علی",
                "35201-5642610-3",
                "مکان نمبر 99 - E99 ، E گلی نمبر 5 ، گلے شاد کالونی ، اور کینٹ ضلع لاہور "
            ],
            [
                "237",
                "43",
                "شهباز علی",
                "لیاقت علی",
                "35201-1387193-3",
                "25",
                "امکان نمبر 99 - E ثریت نمبر 5 ، محلے اطالونی ، لاہور کینتل",
                "ہونے "
            ],
            [
                "238",
                "43",
                "نیم عفی",
                "سليمان",
                "34203-7442965-3",
                "22 22",
                "مکان نمبر",
                "l1 - B1150 B حشر بیٹ نمبر 3 محلے آخری شاپ نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "239",
                "44",
                "کامران",
                "لیاقت علی",
                "35201-1532933-3",
                "36",
                "امکان نمبر 907",
                "، سٹریٹ تمبر 11 مله با گت ای ، نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "240",
                "44",
                "م نعمان",
                "لیاقت علی",
                "35201-8477360-5",
                "28 28",
                "امکان نمبر 07 907 - E ، سٹریٹ نمبر 11 محل نشاط کالونی ، بایکٹ ای ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "•",
                "د",
                "35201-2100494-5",
                "26",
                "- نمر 97",
                "ثبت نمر",
                "1 محل نشاط کا اونی . ان ای ناجور کی",
                "لاہور "
            ],
            [
                "242",
                "44",
                "عاصم حنیف",
                "نف",
                "34302-0593877-7",
                "18",
                "مکان نمبر 10 / 8 ، عای مجیر رو دیا اور کینشل",
                "ہونه "
            ],
            [
                "243",
                "45",
                "شوکت علی",
                "غلام م",
                "35201-9802533-5",
                "69",
                "الہ شادی و فنی اور کی ضلع لاہور "
            ],
            [
                "244",
                "45",
                "مرق",
                "شوکت علی",
                "35201-8236162-9",
                "32",
                "بلے شاه ولی لاہور کی",
                "شلاہور "
            ],
            [
                "245",
                "45",
                "طاج اگانے",
                "شوکت تنی",
                "35201-1986624-9",
                "28",
                "املے شاطه ولی الاپور کن شل اور "
            ],
            [
                "246",
                "45",
                "سادس من",
                "35201-5921620-3",
                "26 26",
                "گوپادا نیو ایئر پورٹ روڑ بیٹے کوہار ، امور کینٹ ، شل اور "
            ],
            [
                "247",
                "46",
                "غلام رسول",
                "واہ کین",
                "35201-3689790-1",
                "53",
                "امکان نمبر 1165 کی نمبر 1 ، محلہ نثارکا اولیا راست بازار ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "248",
                "46",
                "غلام رسول",
                "35201-8146428-1",
                "29",
                "مکان نمبر 1165 1165 ، گلی نمی 1 مجله نشاط کالونی آراست بازار لاہور کی",
                "ضلع لاہور "
            ],
            [
                "137",
                "22",
                "فیل اشاز",
                "حافظ امتیازاد",
                "35201-8955435-9",
                "30 امکان نمیرم / 665- E ، سٹریٹ نمبر 3 محلے تارکالونی ، یاد کے ڈی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                "138",
                "22",
                "ہلال امتیاز",
                "حافظ امتیاز احمد",
                "35201-2807016-5",
                "25",
                "مروان کیٹ مکان ، : ضلع نمره / A E665 لاہور E665 شرٹ شرٹ نمبر 3 ، وروي لزوهروق كبد ، میر گلے D با کالونی شماره وی ، باری امور اور "
            ],
            [
                "139",
                "22",
                "افضال امتیاز",
                "حافتر اتازا",
                "35201-7694079-9",
                "23",
                "مکان کی نمبر ضلع لاہور 65/4 6 E ، حشرٹ نمبر 3 مله ترور وقیه مسجد شاد کالونی ، بلا کی ڈی ، ان ہور "
            ],
            [
                "140",
                "23",
                "سلیم",
                "شفيع",
                "35201-6794371-5",
                "53",
                "مکان نمبر E412 ، محلہ مین رو",
                "شاد کالونی اور کینٹ حملے اور "
            ],
            [
                "141",
                "23",
                "رضا سلیم",
                "له محمد سلیم",
                "35201-1999529-9",
                "32",
                "مکان نمبر 120 - E ، محلہ اسے بلا کے مین روقار کالونی ، لاہور کینٹ ، ضلع.اہور "
            ],
            [
                "142",
                "23",
                "عابر پر",
                "پطرس می",
                "35201-6130125-3",
                "29",
                "مکان نمبر 930 - E ، محلہ ڈیفنس ہاؤسنگ",
                "سوسا و چراہور کینٹ ، ضلع لاہور "
            ],
            [
                "143",
                "23",
                "ملك",
                "طام",
                "محمد سلیم",
                "35201-0583962-5",
                "28",
                "امکان نمبر 412 - E محلہ مین روڈ شادکالونی ، لاہور کینٹ",
                "لاہور "
            ],
            [
                "144",
                "23",
                "ملک",
                "مبشر سلیم",
                "امیر سلیم",
                "35201-0212650-3",
                "25",
                "امکان نمبر 412 - E محلے مین روڈ تشاط کالونی ، لاہور کینٹ ، ضلع باکور "
            ],
            [
                "145",
                "24",
                "وقار یم",
                "ده",
                "35201-1600122-3",
                "52 52",
                "امکان",
                "3 / نمبر 480 - E محلہ صدیقی روڈ سینما شاپ نشاط کالونی ، لاہور کینٹ ، ضلع all لاہور "
            ],
            [
                "146",
                "24",
                "مانتیر عظیم",
                "ام تعلیم",
                "35201-1600125-3",
                "49",
                "لاہور مکان نمبر کینٹ 681 ضلع - E ، جور اگلے 4 - AD بارت څاطالوی ، ژاك شنہشاط کالونی ، لاہور . مل "
            ],
            [
                "147",
                "24",
                ": والفقار",
                "35201-1600125-7",
                "47",
                "مکان نمبر 681 - E ، سٹریٹ",
                "A4 نمبر محلہ وانگ ژی نشاط کالونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                "148",
                "24",
                "هم انوار عظیم",
                "م می",
                "35201-1600129-5",
                "43",
                "لاہور مکان : کینٹ 681 ضلع - E . لاہور گالہ 4 - A بلكه څاطالوی ، ڈان",
                "نشاط کاوی ، لاہور "
            ],
            [
                "149",
                "24",
                "اعجاز عظيم",
                "35201-4451538-3",
                "41",
                "| مکان نمبر 681 ، گلی نمبر 4 4 - - A M ملے شرکالونی ، رات D ، لاہور میں لاہور کینٹ ، ضلع اور "
            ],
            [
                "150",
                "24",
                "ممتاز عظیم",
                "مو تحم مخيم",
                "35201-1624712-9",
                "37",
                "امکان نمبر 681 68 - - E .E $ گلی نمبر ملقہ نشاط کالونی ، بانڈی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "151",
                "24",
                "رحمن الته",
                "میر زمان خان",
                "35201-4427395-5",
                "29",
                "مکان نمبر 22 - E ، مشر بیٹ نمبر 2 له گلشن پارکت نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "152",
                "24",
                "مع",
                "الوقار",
                "محمد وقار عظیم",
                "35201-9374946-5",
                "22",
                "مکان نمبر 681 681 - - E E ، حشر بیٹ نمبر A4 منہ کاراونی بارگٹڈی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "153",
                "25",
                "محمد رفت",
                "محمد صادق",
                "35202-8288292-1",
                "53 53",
                "امکان نمبر 38 - 38- E ، مته گلشن پارث خط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "15A",
                "25",
                "مل",
                "علی",
                "35201_1412050",
                "51",
                "nra دد",
                "ای",
                "که "
            ],
            [
                "155",
                "25",
                "خان",
                "مسلمان",
                "35201-6432771-5",
                "29",
                "امکان",
                "8 نمبر / 130 13 - - E E گلی نمبر 5 ، مخته تر کالونی ، با A ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "156",
                "25",
                "انبوبکر",
                "محمد سلیمان",
                "35201-9449408-5",
                "24",
                "امکان",
                "A نمبر / 130",
                "، سٹریٹ نمبر 5 محلے شرکالونی ، لاہور کینت ، لاہور "
            ],
            [
                "157",
                "25",
                "عمر",
                "محمد سليمان",
                "35201-1877162-5",
                "23",
                "مکان نمبر /",
                "13 130 - E E 8- ، سٹریٹ",
                "نمبر 5 محله تشار کالونی ، پلاٹ اے ، لاہور کینٹ ، ضلع",
                "ہونے "
            ],
            [
                "158",
                "26",
                "عبد الغفور",
                "ونا",
                "35201-1513403-5",
                "65",
                "امکان نمبر 88 - - E BB E گلی نمبر 4 ملے تارکالونی ، بات اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "159",
                "26",
                "مرزاندیم و نجم",
                "مشتاق اح",
                "34501-2002049-7",
                "38",
                "| مکان نمبر 1547-2 - E ، محلہ شاطونی ، لاہور کین",
                "، ضلع اور "
            ],
            [
                "160",
                "26",
                "شتر غفور",
                "عبر الخفور",
                "35201-5548925-1",
                "34",
                "امکان نمبر 88 88 - - E ، E گلی نمبر 4 ، له بلاک ۸ نشاط کالونی ، لاہور ، تحصیل لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "161",
                "26",
                "شمشاد نخور",
                "عبدالغفور",
                "35201-7672175-5",
                "33",
                "مکان نمبر 88 88 - - E E ، گلی نمبری کے بام ارکالونی ، لاہور ، تحصیل ناہور کینٹ ، ضلع لاہور "
            ],
            [
                "162",
                "26",
                "عبدالغفور",
                "35201-7578125-5",
                "27",
                "امکان نمبر BB - E ، سٹریٹ تمبر ولے بادث اے شادالونی ، لاہور کینٹ",
                "ضلع لاہور "
            ],
            [
                "163",
                "27",
                "احسن اقبال",
                "منظوراحم",
                "35201-0394990-7",
                "41",
                "مکان نمبر 1166 - E .E گلی نمبر 1 ملہ شادکالونی . پارکت گاہور کی",
                "مشہور "
            ],
            [
                "164",
                "27",
                "ناصر عمران",
                "منظوراحم",
                "35201-1395075-7",
                "38",
                "مکان الاموں کو نمبر 1166 - ناداری E کیا ملے",
                "۔ بارے ان",
                "تارکالونی ، ڈاک",
                "خانہ شالا اونی ، لاہور ، کھیل "
            ],
            [
                "109",
                "18",
                "فجر در شیر",
                "عمر شریف مرحوم",
                "35201-1261626-3",
                "60",
                "ارکان اور مرات نمبر 22 - 425 E کلی ، شله می نمبر 2 کاشن پارک",
                "شامل کانونی ڈاک خانہ نشرة اونی ، لاہور "
            ],
            [
                "110",
                "18",
                "شیر علی",
                "فاروق خان",
                "35201-1341572-5",
                "38",
                "کینٹ مکان ، نمبر ضلع 22 - لاہور E ، گلی",
                "محلہ نمبر 2- شن پارگٹ څاطا ولی ، ڈاٹ نان شاراولی ، لاہور "
            ],
            [
                "111",
                "18",
                "محمد نور",
                "محمد رشید",
                "35201-8541423-3",
                "34",
                "امکان نمبر 425 ملے تشار کالونی ، پارٹ 4 - ی اور",
                "لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "112",
                "18",
                "محمد زاہد",
                "مرشد",
                "35201-5748484-5",
                "30 30",
                "امکان نمبر 425 425 گلی نمبر 4 ، محلہ شادکالونی ، با کت C اہور کینٹ ، ضلع اور "
            ],
            [
                "113",
                "18",
                "محمد ساجد",
                "محمد رشید",
                "35201-9658268-9",
                "28",
                "مکان نمبر 425 سٹریٹ نمبر 4 ، محلہ بارگٹ تیاط کالونی ، لاہور کینٹ ، ضلع باہور "
            ],
            [
                "114",
                "19",
                "محمد اقبال رای",
                "این خان",
                "35200-1449651-3",
                "60 60",
                "مکان نمبر 142 142 - - E E ، گلی نمبر 6 ، محله A با",
                "نشاطا ہوئی آراے بازار ، ا ہور کینٹ ، ضلع اور "
            ],
            [
                "175",
                "19",
                "منظوری",
                "کتنی",
                "35201-9387663-5",
                "52",
                "امکان نمبر 1222 - E محلے مریم شادالوئی ، اور کینٹ ، شلع مه",
                "وان "
            ],
            [
                "116",
                "19",
                "ثم جاء بیر اقبال",
                "ماقال رای",
                "35201-1225305-1",
                "37",
                "مکان نمبر 142 142 - - E .E . گلی محلہ 6 نشاطا و نی بادث 8. ہور سیل لاہور کینٹ ، ضلع اور "
            ],
            [
                "117",
                "19",
                "ائمہ شہزاد اقبال",
                "خد اقبال رای",
                "35201-6051544-1",
                "36",
                "مکان نمبر",
                "1 142 - - E .E گلی نمبر 6 ، ته نشاط کالونی ، پارکت ، لاہور",
                "میل لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "118",
                "19",
                "کاشف اقبال",
                "قال",
                "را",
                "35201-0416639-1",
                "34",
                "امکان نمبر 142 - E E142 گلی نمبر تا محله A دات نشاط کالونی ، لاہور ، تحصیل",
                "اور کینٹ ضلع لاہور "
            ],
            [
                "119",
                "20",
                "عزیز گل",
                "مہنگری",
                "مدل گل خان",
                "35201-1644177-5",
                "70",
                "مکان نمبر ۸- E44 E4 ، سٹریٹ نمبری که با کش اسے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "120",
                "20",
                "قم",
                "کے",
                "د",
                "35201-2873532-9",
                "49",
                "و مکان نمبر 952 / E . گیر محلی 10C- مریکاولی نشاط کالونی ، ژاکت خانه نشاط "
            ],
            [
                "121",
                "20",
                "اسفند از ۔",
                "عزیز گل شنگری",
                "35201-6359320-3",
                "36",
                "امکان نمبر 44 - E . سٹریٹ نمبر 2 ملہم بارگت نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "122",
                "20",
                "شهریار خان",
                "35201-7236435-7",
                "32 32",
                "امکان نمبر و4- ، مٹر یٹ نمبر 2. ملے یاد کی اے شاد کالونی ، لاہور کینٹ ضلع لاہور "
            ],
            [
                ".",
                "35201-7337690.7",
                "28",
                "او نمی 14 - دی F گئی مرحلے مبارک",
                "نام ف اور",
                "ضلع",
                "میرے "
            ],
            [
                "124",
                "21",
                "مقال",
                "نام",
                "شه",
                "35201-9850513-3",
                "50",
                "امکان نمبر 110 110-",
                "ٹر یٹ نمبر 4 ، محلہ اسے بلاث نقاط کالونی ، لاہور کینٹ ، ضلع نہ ہو ۔ "
            ],
            [
                "35201-1370371.9",
                "47",
                "مکان نمبر 118 - E ، کھلی رہ 5 - A با",
                "نشاط کالونی ، ژاکت خانه نشاط کالونی ، لاہور ، صلاہور "
            ],
            [
                "126",
                "21",
                "محمد رمضان",
                "محمد شریف",
                "ارائیں مرحوم",
                "35201-1501864-5",
                "46",
                "مکان کالونی نمبر امور - تحصیل",
                "اہور 1537- E. کین یو ، ځله ضلع 6 ، مسجد لاہور انگریم سول ژلین",
                "خاط کالونی ، ڈاک",
                "خانه شاط "
            ],
            [
                "127",
                "21",
                "شاه نواز",
                "شام",
                "35201-1361164-3",
                "43",
                "امکان نمبر 118 118 - - E .E گلی نمبری ملے نشاط کالونی ، بات اے ہور کینٹ ، ضلع لاہور "
            ],
            [
                "128",
                "21",
                "شاه",
                "35201-1370372-9",
                "40",
                "مکان نا نمبر ۔ 118 - E کلیه 5 - A با",
                "نشاط کالونی ، ڈاک خانه نشاط کالونی ، نا ہور کھیل لاہور "
            ],
            [
                "129",
                "21",
                "محمد اصغر شاه",
                "مشاه",
                "35201-5954015-7",
                "38",
                "امکان نمبر 118 - E مشر بیٹ نمبر 5 محلہ باری است ، شاید کالونی ، لاہور کینٹ ، ضلع تور "
            ],
            [
                "130",
                "21",
                "ماجد شاه",
                "شاه",
                "35201-1565982-9",
                "37 37",
                "امکان نمبر 118 11 - - E E گلی نمبرک معلم با شادکا اونیر ہانی روڈ لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "131",
                "21",
                "محمد ساجد علی شاہ",
                "شاه",
                "35201-1746071-5",
                "34",
                "امکان نمبر 118 - E گلی نمبر 5 محلے بلاتا",
                "، نشاط کا اونی . لاہور کیند . شل یا ہور "
            ],
            [
                "132",
                "21",
                "ممے والی شاه",
                "ماقال شاہ",
                "35201-6662210-1",
                "24",
                "امکان نمبر 110 - E مشرٹ نمبر 4 ملے اس بل کے نشاط کالونی ، لاہور کینٹ ، ریز ضلع چور "
            ],
            [
                "لا \" 133",
                "22",
                "انا امتاز احمد",
                "جنگی اند دی",
                "35201-6675149-1",
                "54",
                "مگان و . میسر A / نلعبور 65 6 - E کی تمیر دردو جامعہ فاروقیہ ، محلہ ڈی پارکت شاطه او لی ، اور "
            ],
            [
                "134",
                "22",
                "عمل معاق",
                "رات ولی",
                "35201-2666827-7",
                "48",
                "فلیٹ نمبر 9 ، محلہ دھوبی گھات مغلپوره ، پلاک 283.اہور کینٹ ، ضلع لاہور "
            ],
            [
                "135",
                "22",
                "فياض احمد",
                "حافظ اتيازام",
                "35200-1575680-5",
                "36",
                "امکان",
                "4 نمبر / 665 - E گلی نمبر 3 منہ باد کے ڈی نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "136",
                "22",
                "شہباز احمد",
                "حافظ اقازاد",
                "35201-8954580-9",
                "32",
                "کینن مکان نمبر 4 / شلتوو 665 / E ، حشر میٹ نمبر 3 ، نشاط کالونی نزد قارور مسجد ، بالا کی ڈی ، لاہور "
            ],
            [
                "81",
                "12",
                "مر ثمران اقبال قریشی",
                "محمد اقبال",
                "35201-2957045-3",
                "31",
                "امکان نمبر 151 - ع گلی نمبر 6 ، معلم با",
                "شادکالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "82 مه",
                "و 12",
                "سنان جا اقبال",
                "را قبل",
                "orar",
                "م - 35201-5417240-7",
                "29",
                "مکان نمبر",
                "E 151",
                "سٹریٹ نمبر 6 ، له څار کالونی بات اسے ، لاہور کینٹ ، ضلع ہو "
            ],
            [
                "83",
                "13",
                "اکبر على",
                "برکت على",
                "35201-1326585-7",
                "65.",
                "امکان نمبر 38 - E نرومیجر بث و یا نہیں ، محلہ مینشاط کالونی ، لاہور کینٹ ، ضلع راجور "
            ],
            [
                "84",
                "13 مه",
                "او حنين",
                "غلام اما قادر",
                "35202-2724825-7",
                "42",
                "گرایلی لاہور کی ، A1 تلعبور ریٹ",
                "6 ، -تي",
                "1 ، گل زمان پار "
            ],
            [
                "85",
                "13",
                "ارشد على",
                "اکبر على",
                "35201-2747301-5",
                "40",
                "مکان نمبر 38 38 - ع",
                ". محلے میٹر مشاطا ولی مین روڈ لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "86",
                "G 13",
                ". راشد على",
                "اکبر على",
                "35201-1326590-3",
                "38",
                "کی رات : ضلع 38 - اور E ، کا علمناطه ویلاؤ ، 1 واتحانہ اطالوی ، لاہور ،",
                "ناتور "
            ],
            [
                "87",
                "13",
                "کاشف علی",
                "اکبر علی",
                "35201-3748384-1",
                "37",
                "امکان نمبر 38 -38 - E ملے شرکالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "88",
                "13",
                "آصف علی",
                "اکبر على",
                "35201-7783592-5",
                "36",
                "امکان نمبر 38- مل من تشادالونی ، اور",
                "میل لاہور کینٹ",
                "ضلع لاہور "
            ],
            [
                "89",
                "14",
                "محمد یونس",
                "35201-8284453-7",
                "55",
                "امکان نمبر E159 گلی نمبر 7 محل نشاط کالونی ، باد گشاے لاہور کینٹ ضلع لاہور "
            ],
            [
                "90",
                "14",
                "کاشت",
                "انور",
                "35201-0349220-7",
                "36",
                "محله باید مرشان بیدیاں روڈ لاہور کینٹ ، ضلع // لاہور "
            ],
            [
                "91",
                "14",
                "محمد زبیر",
                "محمد یونس",
                "35201-5776264-5",
                "33",
                "مکان نمبر e159 کلی نمر 7 محله څار کالونی آراستے بازار ، بات اسے ، لاہور کینٹ ضلع "
            ],
            [
                "92",
                "14",
                "ثم جاء به",
                "محمد یونس",
                "35201-2542657-9",
                "31 31",
                "امکان نمبر 159 - E ، گلی نمبر 7 محله شادکالونی . باکت A ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "93",
                "14",
                "محمد عدیل میونس",
                "محمد یونسی",
                "35201-0312693-3",
                "26",
                "امکان نمبر و15- E",
                "شرٹ نمبر 3 محله شادکالونی ، باکتاہے ، راہور کینٹ",
                "ضلع لاہور "
            ],
            [
                "94",
                "15",
                "مرور",
                "نت بگ",
                "35202-0672089-5",
                "60",
                "لازم E40 گلی نموت",
                "، ، تن تانیال",
                "کر ضلعی "
            ],
            [
                "95",
                "15",
                "وما",
                "محمد تیم",
                "35202-0293400-1",
                "53",
                "مکان نمبر",
                "کی نمبر",
                "له خادکالونی کوٹ خواجہ سعید ، با اور",
                "لاہور ٹیلن "
            ],
            [
                "96",
                "15",
                "ام",
                "عمران",
                ".",
                "35201-5084886-9",
                "39",
                "مکان نمبر 40 - E . گلی نمبر 1",
                "محلہ گنگ",
                "روڈ شادالونی . پارکت A ، لاہور ،",
                "لاہور "
            ],
            [
                "97",
                "15",
                "عامر على",
                "محمد سرور",
                "35201-5906438-7",
                "33 33",
                "امکان نمبر 40 - ہوتا E ، گلی نمبر 1 ملہ اسے بیان گالری لونی ، لاہور کینٹ ، ضلع",
                "ہورہ "
            ],
            [
                "98",
                "15",
                "صفدر",
                "محمد سرور",
                "35202-4672318-5",
                "28",
                "امکان نمبر 40- ، گلی نمبر محله با کت و نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "99",
                "16",
                "الفراشد خان",
                "ام خان",
                "34201-2623591-9",
                "64",
                "مکان نمبر 112 - E E ، حشریث نمبر 4 له نشاط کالونی ، بلاک اے ، باہور کینٹ ، ضلع لاہور "
            ],
            [
                "100",
                "16",
                "مبشر علی",
                "ظفراللہ خاں",
                "34201-2614946-9",
                "37 37",
                "مکان نمبر 112 - E ، سٹریٹ نمبر 4 ، محله تشاط کالونی ، بلاکھ اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "101",
                "16",
                "عمر بان لتر",
                "ظفر اللہ خاں",
                "34201-5618644-9",
                "32",
                "امکان نمبر",
                "E E54 گلی نمبر لے شاد کالونی 1 اور ، تحصیل لاہور کینٹ ضلع لاہور "
            ],
            [
                "102",
                "16",
                "عشان",
                "ظفر اللہ خاں",
                "35201-6794991-1",
                "28",
                "امکان نمبر 112 - E ، سٹریٹ نمبر 2 ، علہ بار",
                "اسے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "103",
                "16",
                "مالی",
                "منذر",
                "35201-3020540-3",
                "19",
                "ڈیفنس ہاؤسنگ",
                "اتھارٹی "
            ],
            [
                "104",
                "17",
                "نراه",
                "برایت علی",
                "35201-1474920-7",
                "65",
                "مکان نمبر 103 - E ، گلی نمبر 4۔ باٹا",
                "، گلہ نشاط کالونی ، لاہور کی",
                "ضلع لاہور "
            ],
            [
                "105",
                "17",
                "افعال",
                "این",
                "35201-7755843-1",
                "47",
                "] مکان ور نمبر 130e ، سٹریٹ",
                "نمبر 5 ، له نشاط کالونیر بالیہ بیانی روڑ بل کث .1 ء ہور کینٹ ، ضلع "
            ],
            [
                "106",
                "17",
                "سجاول نذیر",
                "نذرا",
                "35200-1481888-1",
                "37",
                "] ور امکان نمبر E103 گلی محلے وشاطونی بایك A اہور "
            ],
            [
                "107",
                "17",
                "سجاد نذیے",
                "نذیر احمد",
                "35201-9198644-9",
                "35",
                "مکان نمبر 103 103 - - e .e گلی نمبر 4 علی خار کالونی ، باید کے اسے لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "108",
                "17",
                "جواد نذر",
                "نذیرا",
                "35201-8388649-1",
                "29",
                "امکان نمبر 103 - E گلی نمبر . 4 .4 ملے تار کالونی ، با ۸ ، لاہور کینٹ ، ضلع 1 ار "
            ],
            [
                "165",
                "27",
                "اکرم می",
                "باجه",
                "می",
                "35201-0729774-7",
                "37",
                "اور رکیٹ ، ضلع لاہور",
                "وا "
            ],
            [
                "166",
                "27",
                "وقا",
                "محظور",
                "منظورا",
                "35201-7664704-1",
                "33",
                "مکان نمبر a87 گلی نمبر حملے شرکالونی ، بات",
                "، لاہور ، تحصیل لاہور کینٹ ضلع",
                "پور "
            ],
            [
                "167",
                "28",
                "محمد سلیم اختر",
                "چوہدری کریم بخش",
                "91506-0152062-3",
                "66",
                "cantt lahore , Road Walton / Colony من Officers , 6 Street , 5 - E House "
            ],
            [
                "168",
                "28",
                "ماطر",
                "اخلاق",
                "35201-4323583-1",
                "30 30",
                "امکان نمبر 5472- محلہ گلشن پار گئے تک",
                "کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "169",
                "28",
                "ام",
                "عدل",
                "مداخلات",
                "35201-1855208-1",
                "28",
                "امکان نمبر 87 - E حشریت نمبر 4 محلے کا کالونی . با کت A ، اور کینٹ ، شلاہور "
            ],
            [
                "170",
                "28",
                "علی",
                "اخلاق",
                "35201-3755970-7",
                "27",
                "امکان نمبر 87 - ی ملے , گا کالونی ، رام بات a . اہور گیٹ ، ضلع لاہور",
                ". "
            ],
            [
                "171",
                "29",
                "محمد اشرف",
                "35201-1416020-5",
                "95 "
            ],
            [
                "172",
                "29",
                "تعمیرات",
                "I fo شفيع",
                "35201-1300053-1",
                "55",
                "مکان نمبر نلع 120 - E ، گلی محلہ -5 A5 بلاک",
                "مین بازار نشاط کا لو نی ، ژاکت خانه نشاط کالونی ، لاہور "
            ],
            [
                "45",
                "ملے مس",
                "3",
                "34603-1253642-5",
                "55",
                "امکان نمر 1337 - E سر پیٹ نمبر 3 مله نشاط مر م کالونی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "174",
                "29",
                "شبیر احد من",
                "شفیع",
                "35201-1300058-5",
                "48",
                "مکان نمبر نلع 120 - E . یا محلہ 5۔ بات اسے مین بازار نشاط کا ولی ، ذات خنه نشاط کالولی ، لاہور "
            ],
            [
                "AE",
                "وه",
                "2",
                "تمے 21",
                "35201-7983476-1",
                "29",
                "اما نمی 120 120 - - E E ي نمر 5 میلے مین بازار نشاط کالونی ، لان A. امور کی",
                "ضلع لاہور "
            ],
            [
                "176",
                "29",
                "مشبات",
                "نصیراحمد",
                "35201-3547704-9",
                "28",
                "امکان نمبر 20 / E ، سٹر یٹ نمبر 5 ملہ ما مین بازار من نشاط دیلی کالونی ،",
                ") بلات ۰ A ،",
                "او لاہور مل کینٹ ، کر ضلع کام لاہور "
            ],
            [
                "47",
                "مج",
                "مد FT",
                "شاد می",
                "35201-1073684-3",
                "25",
                "مکان نمبر 120 - E . سر پیٹ",
                "نمبر 5. له مین بازار شاد کالونی . بلامن اے ، لاہور کینٹ ، شلع "
            ],
            [
                "178",
                "30",
                "وقار ام اچیو",
                "مر شفيع اجود",
                "35201-0616937-1",
                "57",
                "امکان نمبر -",
                "E 1202- ملے گا",
                "اوتی ، اور",
                "میلا ہور کینٹ ، شلاہور "
            ],
            [
                "179",
                "30",
                "نسیم احمد",
                "محمد عالم",
                "35201-4031136-7",
                "55",
                "اگلی محلے یا بادی رحمانی نشاط کالونی ، لاہور قصیل لاہور کینٹ ، ضلع راجور "
            ],
            [
                "180",
                "30",
                "مقامی انور",
                "مانور",
                "35201-5186418-5",
                "30",
                "امکان نمبر 153 - E ، سٹریٹ نمبر 6 ، گلہ باد کی اے نشاط کالونی ، لاہور کینٹ ، ضلع",
                "ہوتے "
            ],
            [
                "181",
                "30",
                "حافظ عبدالرحمن انور",
                "مانور",
                "35201-1962149-9",
                "27",
                "مکان نمبر 153 / E ، سٹر یٹ نمبر 6 .6 لہ اطالونی ، بات اسے ، اور کینٹ ، ضلع ناجور "
            ],
            [
                "182",
                "30",
                "حافظ بلال انور",
                "مانور",
                "35201-1252070-3",
                "25",
                "امکان نمبر A - 153- ع ، شریث نمبر 6 ، له نشاط کالونی اور کینٹ ، ضلع لاہور "
            ],
            [
                "183",
                "31",
                "امام : کن",
                "35201-1217892-5",
                "67",
                "امکان نمبر 12 . 12. .E گلی محلے وڈات خان شرکالونی ، لاہور کینٹ ، شلع لاہور "
            ],
            [
                "184",
                "31",
                "لینا",
                "من ام",
                "35201-1217894-9",
                "52",
                "مکان نمبر 12 ای",
                "گلی",
                "محلی مات تاته",
                "د کالونی اور کینٹ شل امور "
            ],
            [
                "46",
                "24",
                "0",
                "35201-4946879-1",
                "37",
                "امکان نمبر 12 12 - ی گلی نمبر 4 ملے نشاط کالونی ۔ دوران اسے لاہور کینٹ",
                "ضلع لاہور "
            ],
            [
                "186",
                "31",
                "زاهد من",
                "مناه",
                "35201-7684204-5",
                "33",
                "امکان نمبر 12 گلی نمبر د له مبار کی شادی ونی ، لاہور ، تحصیل لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "187",
                "31",
                "واصف شريف",
                "شریف",
                "گل",
                "35201-9444952-5",
                "26",
                "مکان نمبر 30 17 / A5 گلے شرکا و نی مین",
                "روڈ لاہور کینٹ ، شلاہور "
            ],
            [
                "188",
                "32",
                "عبدالقیوم",
                "عبدالغفار",
                "35201-1350929-5",
                "73",
                "ااور مکان نمبر 117 / e ، مد کی نمبر 5 ملہ",
                "ء تشاطالوی آراے بازار . بلاگ دار اے 15 لاہور کین",
                "، ع "
            ],
            [
                "189",
                "32",
                "اسلم می",
                "جیت میں",
                "35201-7642510-3",
                "48",
                "مکان لاہور نمبر کینٹ ، ضلع 1806/3 لاہور . گلی نمبر 6 در بلاك ، محلے مرے کی نشاط کا اولی",
                "راے بازار ، لاہور ،",
                "تیل "
            ],
            [
                "190",
                "32",
                "عدالتیک",
                "عبدالقیوم",
                "35201-1350930-5",
                "40",
                "کالونی مکان ، نمبر لاہور تحصیلی E117",
                "یا اور محلہ تین 5- بنات مرة ما لاہور اسے ) شیاط اولی آراے - بازار اٹ تو دوم",
                ") شہنشاط",
                "فع "
            ],
            [
                "191",
                "32",
                "عمر اولیس",
                "عبدالقیوم",
                "35201-7846277-9",
                "29",
                "مکان اور نمبر 117 - E ،",
                "شریت نمر 5 ملے بلاک اےنشاط لوئی آراے بازار ، لاہور کینٹ ، ضلع "
            ],
            [
                "192",
                "33",
                "نتنياهم",
                "حائی عبرانکریم",
                "35201-1195975-3",
                "87",
                "مکان نمبر",
                "/ E E14 گلی نمبر 5 ، گلہ بان اے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ]
        ];
        return $data = [
            [
                25,
                3,
                "ساجد باید بیل این",
                "و جمیل احمد",
                "35200-1547756-5",
                "38",
                "امکان تعمیر مکان 149 ، گلی محلے کیا بات اسے شادگانی ڈاک خان راستے بازار ، لاہور "
            ],
            [
                26,
                3,
                "نیشان بھی",
                "نبیل احد",
                "35201-1472798-9",
                "38",
                "کینٹ مکان ، نمبر ضلع لاہور E149 .E . گلی محله 6 -6 بار کے اے ، ڈاک",
                "خانه شادکالونی ، لاہور ، تحصیل لاہور "
            ],
            [
                27,
                3,
                "اشرف",
                "ہدایت می",
                "35201-5676510-3",
                "32",
                "امکان نمبر 1529 1529 / / E E ، گلی نمبر 1 محله نشاط کالونی ، لاہور میں لاہور کینٹ ضلع لاہور "
            ],
            [
                28,
                3,
                "کی ہدایت",
                "برایت",
                "می",
                "35201-6433632-9",
                "27",
                "مکان نمبر 1529 / E ، سٹریٹ نمبر 1 ، محلہ شادکالونی ، اور کینٹ ، ضلعبور "
            ],
            [
                29,
                4,
                "محمد زاهر",
                "محمد اقبال",
                "35201-6500926-3",
                "60 60",
                "امکان نمبر 51 - E ، سٹریٹ نمبر 2 ، حلقہ نشاط کالونی ، بلاگ اسے لاہور کینٹ ، ضلع",
                "ہور "
            ],
            [
                30,
                4,
                "محہ ریاض",
                "اقبال",
                "35201-0112035-3",
                "58",
                "امکان نمبر 51 - E ، مٹر یٹ نمبر 2 محلہ بلاک ۸ نشاط کانونی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                31,
                4,
                "امانت امانت علی علی",
                "گم شفیع",
                "34602-4038590-9",
                "53",
                "امکان نمبر 4 / 1245 - E ، محلہ شاط کالونی ، ڈا پور کینٹ ضلع لاہور "
            ],
            [
                32,
                4,
                "آصف ریاست",
                "دریای",
                "35201-8290377-5",
                "35",
                "امکان نمبر 51 - E سٹر بیٹ نمبر",
                "محله باش اسے شاور کالونیا اور کینٹ ، ضلع تور "
            ],
            [
                33,
                4,
                "کاشف ریاض",
                "محمد ریاض",
                "35201-2898576-7",
                "33",
                "امکان نمبر 51 51 - - E E گلی نمبر 2 ، محلہ بدات ، نشاطی و فنی ، لاہور ، تحصیل لاہور کینٹ ، لاہور "
            ],
            [
                34,
                4,
                "حسین علی",
                "گمراض",
                "35201-2526932-3",
                "25",
                "امکان نمبر 51 - E ، سٹریٹ نمبر 2 ملہ بات اے نشاط کالونی ، لاہور کینٹ ، ضلع لاہور "
            ]
        ];
        return $data = [
            [
                "781",
                "880",
                "مهوش نذیر",
                "دختر محمد نذیر",
                "35202-5488903-0",
                "28",
                "امکان نمبر 13 گلی نمبر 1 ، محلہ ناصر کالونی مینہ چوک ٹاؤن شپ ، لاہور "
            ],
            [
                "782",
                "881",
                "ماجہ سعدی",
                "دختر میاں محمد شریف",
                "35202-2388779-6",
                "76",
                "مکان نمبر 31 - E گلی نمبر و محله فیر",
                "ڈیفنس ہاؤسنگ اتھارٹی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "783",
                "882",
                "ماما اختتام",
                "دختر سید احتشام قادر شاه",
                "35202-2177199-0",
                "22",
                "مکان نمبر 42 - H ، سٹر یٹ نمبر 11 محله فینز 5 ، ڈی ای است ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "784",
                "883",
                "شانزے صباغ می",
                "دختر مر میر محمد مر منير نے",
                "91400-0462773-4",
                "24",
                "مکان نمبر 296 - E محلہ فیز 5 ڈیفنس ہاؤسنگ",
                "اتھارٹی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "785",
                "884",
                "اروا محمود",
                "دختر م محمودا وام",
                "35302-1010175-0",
                "28",
                "بیدیاں روڈ ، No H. . الیس ڈی 252 ، فیز 5 عسکری 11 ژیانیا",
                "، لاہور کینٹ "
            ],
            [
                "786",
                "885",
                "فاطمه گل بث",
                "زوجہ سلمان شاہد ڈار",
                "35202-5516022-0",
                "23",
                "مکان نمبر 174 - ل محلہ فیز 5 ڈی ای اے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "787",
                "886",
                "ارن فاطمہ",
                "دختر فرمایا سرفراز اور خورشید یہ",
                "35202-2683695-8",
                "36",
                "لاہور مرجان",
                "461 - K ، "
            ],
            [
                "788",
                "887",
                "امام ابرار رانا",
                "دختر ابرار حسین",
                "35201-7319108-2",
                "21",
                "مکان نمبر 1013 ، محلہ فیز 6 ڈینس ہاؤسنگ",
                "اتھارٹی ، لاہور کینٹ",
                "لاہور "
            ],
            [
                "789",
                "888",
                "عن فاروق ڈار",
                "دختر فاروق مسعود",
                "35201-0923630-2",
                "23",
                "امکان نمبر 367 - A مجله فنر 5 ڑیا اے ، لاہور کینٹ",
                "لاہور "
            ],
            [
                "790",
                "889",
                "آمین ناصر",
                "دختر ناصر اقبال",
                "35201-4565185-4",
                "21",
                "مکان نمبر 438 - c ، محلہ فیز 6 ڈینینس ہاؤسنگ اتھارٹی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "791",
                "890",
                "مریم آصف",
                "دختر آصف",
                "جبار",
                "42301-2796924-4",
                "23",
                "مکان نمبر 295 محلہ فیز 5 ڈیفنس ہاؤسنگ",
                "اتھارٹی ، بلاك ان لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "792",
                "891",
                "ادیبہ فاطمہ چشتی",
                "دختر محمد عارف چشتی",
                "42000-7118587-4",
                "21",
                "| مکان نمبر 320 - H ، محله فینز 5 ڑیا جائے ، لاہور کینٹ ضلع لاہور "
            ],
            [
                "793",
                "892",
                "جہاں آرا عمر خان",
                "و معمر خان",
                "35202-9640559-2",
                "19",
                "مکان نمبر 68 - G ، سٹر یٹ نمبر 3 ، محلہ فیز 5 ڈی ای اے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "794",
                "893",
                "زارا آصف",
                "دختر شیخ محمد آصف",
                "35202-6320560-6",
                "27",
                "مکان نمبر 647-6 ، سٹریٹ کر 30 ، محله ای 5 ڈس",
                "لاہور کینٹ "
            ],
            [
                "795",
                "894",
                "علینہ حسین",
                "دختر زاہد حسین",
                "35201-8318595-8",
                "20",
                "| 218",
                "No . - .H بی ، سٹر یٹ",
                "11 ، فیز 5 ڈی ای اے ، لاہور کینٹ "
            ],
            [
                "796",
                "895",
                "شائینہ شاہد",
                "دختر شاہد اقبال",
                "35200-7294484-8",
                "23",
                "امکان نمبر 427 محلہ عسکری 11 فیز 5 ڈی ایچ اے ، بلاکھ اسے ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "797",
                "896",
                "مفتی ندیم",
                "دختر محمد ندیم عام",
                "35202-7465395-8",
                "20",
                "امکان نمبر 115-1 ، سٹر یٹ نمبر 2 محلہ فیز 5 ڈیفنس ہاؤسنگ اتھارٹی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "798",
                "897",
                "نم فضل",
                "دختر میاں فخر الزمان",
                "35202-4515549-2",
                "31",
                "مکان نمبر 72-۴ ، سٹر یٹ نمبر 2 محله فیر ڈیفنس ہاؤسنگ",
                "اتھارٹی لاہور کینٹ",
                "لاہور "
            ],
            [
                "799",
                "898",
                "نرگس آرا",
                "زوجہ محمد زاہد مرزا",
                "35201-6401792-4",
                "67",
                "امکان نمبر / 130 ، محلہ ڈیفنس ہاؤسنگ",
                "اتھارٹی ، بلاگ ایل ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "800",
                "899",
                "شماره اسلام بث",
                "دختر عبد الاسلام بٹ",
                "35201-6544485-6",
                "35",
                "مکان نمبر 144 محلہ کمرشل ایریا نینس ہاؤسنگ اتھارٹی ، با کٹائی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "801",
                "900",
                "مهر و لیسین",
                "دختر محمد یاسین",
                "35201-6893233-0",
                "24",
                "امکان نمبر 196 - E ، محلہ فیز 5 ڈینس ہاؤسنگ اتھارٹی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "802",
                "901",
                "ساریہ اقبال",
                "زوجہ محمد اقبال",
                "54400-2206205-8",
                "57",
                "مکان نمبر 313 ای با کش ، محلہ فیز کڈنینس ہاؤسنگ",
                "اتھائی ، لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "803",
                "902",
                "فریال اکرم",
                "دختر محمد اکرم بھٹی",
                "35202-7826732-0",
                "33",
                "مکان نمبر 244 - ز ، محلہ فیز یا ڈینس ہاؤسنگ اتھارٹی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "804",
                "903",
                "عانتر و ضياء",
                "دختر محمد ضیاء المعراج احمد",
                "35202-8613897-0",
                "19",
                "مکان نمبر 187 ، سٹر یٹ نمبر 8 محلہ فیز 5 لینس ہاؤسنگ اتھارتی ایرانی ، "
            ],
            [
                "805",
                "904",
                "ردا حیدر نواز",
                "زوجہ حیدر نواز گورایہ",
                "35201-5936147-2",
                "32",
                "] مکان ور نمبر 315 گلی نمبر 14 محله فینز 5 الیاس ہاؤسنگ اتھارتی سیکٹر ای ، لاہور کینٹ ، ضلع "
            ],
            [
                "806",
                "905",
                "ثمینہ جلال",
                "زوجہ صد فی انور جلال",
                "35201-6140772-2",
                "امکان نمبر 117 - c ، گلی نمبر کا محلہ فیز 5 ڈینس ہاؤسنگ اتھارٹی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "807",
                "906",
                "رخسانہ آفتاب",
                "زوجہ آفتاب عالم",
                "35201-6193573-8",
                "54",
                "مکان نمبر 285 - ل ، سٹر یٹ نمبر 8 محلہ فیز 5 ڈ لینس ہاوسنگ",
                "اتھارٹی لاہور کینٹ ، ضلع لاہور "
            ],
            [
                "808",
                "907",
                "لبنی شکوہ",
                "از وجہ",
                "سامان شکور",
                "35201-6227854-4",
                "55",
                "امکان نمبر 314 - H گلی نمبر 5 محلہ فیز 5 نینس ہاؤسنگ اتھارٹی لاہور کینٹ ، ضلع لاہور "
            ]
        ];
    }

    public function filterBlocks($blocks, $confidence)
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


    public function getPdfView($block_code)
    {
        ini_set('max_execution_time', '-1');
        $polling_details = PollingDetail::where('polling_station_number', $block_code)
            ->where('polygon' , '!=' , null)
//            ->where('crop_setting' , '!=' , null)
//            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
//            ->take(10)
            ->with('voter_phone')
            ->get();


        return view('google-vision-api', compact('block_code', 'polling_details'));
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
        $mpolling_details = PollingDetail::where('polling_station_number', $block_code)
            ->where('gender' , '=' , "male")
//            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
//            ->take(50)
            ->with('voter_phone')

            ->get()
            ->groupBy('serial_no');


        $fpolling_details = PollingDetail::where('polling_station_number', $block_code)
            ->where('gender' , '=' , "female")
//            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
//            ->take(50)
            ->with('voter_phone')

            ->get()
            ->groupBy('serial_no');


        return view('google-vision-api-backUp', compact('block_code', 'mpolling_details','fpolling_details'));
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


        $firebase_url=     PollingDetail::where('polling_station_number',188060203)->get()->groupby('url_id');

        dd($firebase_url);

        $firebase_url = FirebaseUrl::orderBy('id' , 'asc')->withCount('polling_details')
            ->whereHas('polling_details' , function ($q){
                $q->where('polling_station_number' , 188060203);
            })
            ->get();
//        dd($firebase_url);
//        135
        $i = [];
        foreach ($firebase_url as $url){
            if($url->polling_details_count > 0){
                continue;
            }else{
                $i[] = $url;
            }
        }
        dd(count($firebase_url) , $i);
    }

    public function save_firebase_url()
    {
        ini_set("memory_limit", -1);
        set_time_limit(-1);
        $firebase_url = FirebaseUrl::where('status' , 401)->orderBy('id' , 'asc')->withCount('polling_details')->paginate(20);
//        dd($firebase_url);
//        135
        $i = 0;
        foreach ($firebase_url as $url){
            if($url->polling_details_count > 0){
                $url->status = 200;
                $url->log_state = 'Finish | from_vision_api';
                $url->update();
            }else{
                $i++;
                $url->status = 404;
                $url->log_state = '404';
                $url->update();
            }
        }
        dd(count($firebase_url) , $i);
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
                $status = $this->save_polling_details($polling_station_id, $polling_station_number, $cnic, $page_number, $url, $url_id);
                $cnic = array();
                $polling_station_number = '';
                $page_number = '';

                if ($status == true) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $status = $this->save_polling_details(null, null, $cnic, $page_number, $url, $url_id);
                $cnic = array();
                $polling_station_number = '';
                $page_number = '';
                return true;
            }
        } else {

            return 'invalid_page';
        }
    }

    public function textract_multiple_url()
    {
        $urls = FirebaseUrl::where('status', '0')
            ->where('cron', '0')
            ->orderBy('id' , 'desc')
            ->take(50)
            ->get();

        foreach ($urls as $key => $value) {
            DB::table('firebase_urls')->where('id', $value->id)->update(['cron' => '2']);
        }

        foreach ($urls as $key => $value) {
            $status = $this->auto_textract($value->image_url, $value->id);


            if ($status == true) {
                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '1', 'cron' => '1']);
            } else if ($status == 'no_cnic') {
                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '2', 'cron' => '1']);
            } else if ($status == 'invalid_page') {
                DB::table('firebase_urls')->where('id', $value->id)->update(['status' => '3', 'cron' => '1']);
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
            'FeatureTypes' => ['FORMS'],
            'Languages' => [
                "LanguageCode" => "ur",
                "LanguageCode" => "ar"
            ]
        ];


        $result = $client->analyzeDocument($options);


        $blocks = $result['Blocks'];

        return $blocks;
    }

    public function save_polling_details($polling_station_id, $polling_station_number, $cnic, $page_number, $url, $url_id)
    {
        foreach ($cnic as $key => $value) {

            $status = DB::table('polling_details')->where('cnic', $value['cnic'])->first();

            if (!$status) {
                $polling_detail = new PollingDetail();

                DB::table('polling_details')->insert([
                    'polling_station_id' => $polling_station_id,
                    'polling_station_number' => $polling_station_number,
                    'cnic' => $value['cnic'],
                    'polygon' => $value['Polygon'],
                    'boundingBox' => $value['BoundingBox'],
                    'page_no' => $page_number,
                    'url' => $url,
                    'url_id' => $url_id
                ]);

                return true;
            } else {
                return false;
            }
        }
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

    public function image_textract()
    {
//        $url = 'https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/politics%2F1%2F1624642855286.jpg?alt=media&token=c6834290-9c02-4161-8120-f84036f2603d';
//        $image = file_get_contents($url);
//
//        $blocks = $this->textract_api($image);

//        $meta = json_encode($blocks);

        $f_url = FirebaseUrl::where('id' , 15990)->first();
        $blocks = json_decode($f_url->link_meta , true);

        $cnic = array();
        $polling_station_number = '';
        $page_number = '';
        $response = array();

        $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';
        $polling_station_number_pattern = '/^\d{5,20}$/';
        $page_number_pattern = '/^(Page)+/';

        $new_blocks = [];

        foreach ($blocks as $key => $value) {
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if ($blockType == 'LINE' && ($value['Confidence'] > 90)) {
                    $new_blocks[] = $value;
                }
            }
        }

        foreach ($blocks as $key => $value) {
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if (isset($value['Text']) && $value['Text']) {
                    $text = $value['Text'];
                    if ($blockType == 'LINE' && preg_match_all($cnic_pattern, $text, $matches)) {
                        $BoundingBox = json_encode($value['Geometry']['BoundingBox']);
                        $Polygon = json_encode($value['Geometry']['Polygon']);
                        $cnic[] = ['cnic' => $matches[0][0], 'BoundingBox' => $BoundingBox, 'Polygon' => $Polygon];
                    }

                    if ($blockType == 'LINE' &&  preg_match_all($polling_station_number_pattern, $text, $matches) && ($value['Confidence'] > 85)) {
                        $polling_station_number = $text;
                    } else {
                        $polling_station_number = null;
                    }

                    if ($blockType == 'LINE' &&  preg_match_all($page_number_pattern, $text, $matches)) {
                        $page_number = $text;
                        $temp = explode(' ', $page_number);
                        $temp = explode('/', $temp[1]);
                        $page_number = $temp[0];
                    } else {
                        $page_number = null;
                    }
                }
            }
        }
        $_cnic = array_column($cnic, 'cnic');

//        dd($new_blocks);

        foreach ($new_blocks as $key => $value) {
//            dd($value);
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if (isset($value['Text']) && $value['Text']) {
                    $text = $value['Text'];
                    if ($blockType == 'LINE' && preg_match_all($cnic_pattern, $text, $matches)) {
                        if(@$new_blocks[$key + 4]){
                            if(preg_match_all($cnic_pattern, $new_blocks[$key + 4]['Text'], $matches)){

                                foreach($_cnic as $c) {
                                    if($c == $text) {
                                        $cnic_to_update = $c;
                                        break;
                                    }
                                }

                                if ($cnic_to_update) {
                                    if (@$new_blocks[$key + 2] && is_numeric($new_blocks[$key + 2]['Text'])) {
                                        $s_no = $new_blocks[$key + 2]['Text'];
                                    } else {
                                        $s_no = null;
                                    }

                                    if (@$new_blocks[$key + 1] && is_numeric($new_blocks[$key + 1]['Text'])) {
                                        $f_no = $new_blocks[$key + 1]['Text'];
                                    } else {
                                        $f_no = null;
                                    }

                                    if (@$new_blocks[$key - 1] && is_numeric($new_blocks[$key - 1]['Text'])) {
                                        $age = $new_blocks[$key - 1]['Text'];
                                    } else {
                                        $age = null;
                                    }

//                                    dd($s_no , $f_no , $text , $age);

//                                    PollingDetail::where('cnic', $text)->update([
//                                        'age' => $age,
//                                        'serial_no' => $s_no,
//                                        'family_no' => $f_no
//                                    ]);
                                }
                            }
                        }elseif ( @$new_blocks[$key - 4] ){

                            if(preg_match_all($cnic_pattern, $new_blocks[$key - 4]['Text'], $matches)){

                                foreach($_cnic as $c) {
                                    if($c == $text) {
                                        $cnic_to_update = $c;
                                        break;
                                    }
                                }

                                if ($cnic_to_update) {
                                    if (@$new_blocks[$key + 2] && is_numeric($new_blocks[$key + 2]['Text'])) {
                                        $s_no = $new_blocks[$key + 2]['Text'];
                                    } else {
                                        $s_no = null;
                                    }

                                    if (@$new_blocks[$key + 1] && is_numeric($new_blocks[$key + 1]['Text'])) {
                                        $f_no = $new_blocks[$key + 1]['Text'];
                                    } else {
                                        $f_no = null;
                                    }

                                    if (@$new_blocks[$key - 1] && is_numeric($new_blocks[$key - 1]['Text'])) {
                                        $age = $new_blocks[$key - 1]['Text'];
                                    } else {
                                        $age = null;
                                    }

//                                    dd($s_no , $f_no , $text , $age);

//                                    PollingDetail::where('cnic', $text)->update([
//                                        'age' => $age,
//                                        'serial_no' => $s_no,
//                                        'family_no' => $f_no
//                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }



        $response = ['cnic' => $cnic, 'polling_station_number' => $polling_station_number, 'page_number' => $page_number];

        dd($response);
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

        $polling_details = PollingDetail::whereIn('polling_station_number', ['187020101' ,'187020102' ,'187020103' ,'187020104' ,'187020105' ,'187020106' ])
            ->where('type' , 'textract')
            ->where(function($q) {
                $q->where('serial_no' , null)->orWhere('family_no' , null);
            })
            ->get()
            ->groupBy('url_id');

        foreach ($polling_details as $url_id => $details) {

            $meta_of_page = FirebaseUrl::where('id', $url_id)->first('link_meta');
            $blocks = json_decode($meta_of_page->link_meta);

            //filter blocks greater than given confidence level
            if (is_array($blocks)) {
                $new_blocks = $this->filterBlocks($blocks, 75);
            }
            else {
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
                            }
                            else if ($type == 'OLD') {
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
                            }
                            else {
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


    public function testraw1(){
        $page_24 = [];
        $page_28 = [];
        $other = [];
        $details = FirebaseUrl::where('import_ps_number' , 188010407)->with('polling_details')->get();
//        dd($details);
        foreach ($details as $key => $item){
            if(count($item->polling_details) == 28){
                $page_28[]=$item;
            }else if(count($item->polling_details) == 24){
                $page_24[]=$item;
            }else{
                $other[] = $item;
            }
        }


    }

    public function testraw2()
    {
        $duplicates = DB::table('polling_station')
            ->select('id','polling_station_number', DB::raw('COUNT(*) as `count`'))
            ->groupBy('polling_station_number')
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

    public function testraw3(){

        $gender = 'undefined';
        $polling_details = PollingDetail::take(50000)->orderBy('id' , 'desc')->get();
        foreach ($polling_details as $key => &$item){

            $break_cnic = explode('-' , $item->cnic);
            $last_digit = $break_cnic[2];
            $last_digit = (int) $last_digit;

            if($last_digit % 2 !== 0){
                $gender = 'male';
            }else {
                $gender = 'female';
            }

            $item->gender = $gender;
            $item->update();
        }

        dd(count($polling_details));

    }

    public function rotateImage(){
        PollingDetail::where('url_id' , 16035)->update([
            'url' => 'https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630420619198.jpg?alt=media&token=4f700178-d55f-4a5c-bfa8-673b53e97f47'
        ]);
    }

    public function testraw(){
        $polling_detail = PollingDetail::whereDoesntHave('SchemeAddress')->take(1)->get();
        dd($polling_detail);

    }

    public function blockcodeExportJson()
    {
        $ward = $_GET['ward'];
        $number = $_GET['number'];

        ini_set("pcre.backtrack_limit", "50000000");
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "0");
        $sectors= ElectionSector::where('sector','like', $ward.' '.$number)
            ->get()
            ->groupby('block_code');

        foreach ($sectors as $sector)
        {
            $block_code_numbers=$sector->pluck('block_code');
            $polling_details = PollingDetail::whereIn('polling_station_number', $block_code_numbers)
                ->with('SchemeAddressmulti:block_code,ward,polling_station_area_urdu,gender_type,serial_no')
                ->with('polling_details_images:polling_detail_id,name_pic')
                ->select('id','polling_station_number','cnic','gender','serial_no', 'family_no','url')
                ->get();

            $detail_chunk=[];
            $image_chunk=[];

            $check=1;
            $temp=[];
            $temp1=[];
            foreach ($polling_details as $key => $data)
            {
                if(count($data->SchemeAddressmulti) > 1)
                {
                    if($data->SchemeAddressmulti[0]->gender_type == $data->gender)
                    {
                        $data->polling_station_area_urdu=$data->SchemeAddressmulti[0]->polling_station_area_urdu;
                        $data->poll_serial_no=$data->SchemeAddressmulti[0]->serial_no;
                        $data->ward=$data->SchemeAddressmulti[0]->ward;
                        $data->poll_gender=$data->SchemeAddressmulti[0]->gender_type;
                    }
                    if($data->SchemeAddressmulti[1]->gender_type == $data->gender)
                    {
                        $data->polling_station_area_urdu=$data->SchemeAddressmulti[1]->polling_station_area_urdu;
                        $data->poll_serial_no=$data->SchemeAddressmulti[1]->serial_no;
                        $data->ward=$data->SchemeAddressmulti[1]->ward;
                        $data->poll_gender=$data->SchemeAddressmulti[1]->gender_type;
                    }
                }
                else
                {
                    $data->polling_station_area_urdu=$data->SchemeAddressmulti[0]->polling_station_area_urdu;
                    $data->poll_serial_no=$data->SchemeAddressmulti[0]->serial_no;
                    $data->ward=$data->SchemeAddressmulti[0]->ward;
                    $data->poll_gender=$data->SchemeAddressmulti[0]->gender_type;
                }

                if($check == 100)
                {
                    $temp1[]=$data->polling_details_images;
                    unset($data->SchemeAddressmulti);
                    unset($data->url);
                    unset($data->crop_settings);
                    unset($data->polling_details_images);
                    $temp[]=$data;

                    $detail_chunk[]=$temp;
                    $image_chunk[]=$temp1;
                    $temp=[];
                    $temp1=[];
                    $check=0;
                }
                else
                {
                    $temp1[]=$data->polling_details_images;
                    unset($data->SchemeAddressmulti);
                    unset($data->url);
                    unset($data->crop_settings);
                    unset($data->polling_details_images);
                    $temp[]=$data;

                }

                $check++;
            }

            if(count($temp)> 0)
            {
                $detail_chunk[] = $temp;
                $image_chunk[] = $temp1;
            }


            if(count($detail_chunk) === count($image_chunk))
            {
                foreach ($detail_chunk as $item)
                {
                    $encode_chunk = json_encode($item,JSON_UNESCAPED_UNICODE);
                    file_put_contents(base_path('public/'.$ward.'_'.$number.'_info.json'),$encode_chunk."\n",FILE_APPEND);
                }

                foreach ($image_chunk as  $item1)
                {
                    $encode_image_chunk = json_encode($item1,JSON_UNESCAPED_UNICODE);
                    file_put_contents(base_path('public/'.$ward.'_'.$number.'_image.json'),$encode_image_chunk."\n",FILE_APPEND);
                }
            }
            else{
                return $ward.' '.$number.' not saved';
            }

        }

        return back()->with(['success' => 'Your files has been downloaded successfully! Now you can download them. Thank You!']);


    }

    public function desktopSetting($polling_details)
    {

        $name="";
        foreach($polling_details as $line)
        {
            if(count($line->SchemeAddressmulti) > 1)
            {
                if($line->SchemeAddressmulti[0]->gender_type == $line->gender)
                {
                    $line->polling_station_area_urdu=$line->SchemeAddressmulti[0]->polling_station_area_urdu;
                    $line->poll_serial_no=$line->SchemeAddressmulti[0]->serial_no;
                    $line->ward=$line->SchemeAddressmulti[0]->ward;
                    $line->poll_gender=$line->SchemeAddressmulti[0]->gender_type;
                }
                if($line->SchemeAddressmulti[1]->gender_type == $line->gender)
                {
                    $line->polling_station_area_urdu=$line->SchemeAddressmulti[1]->polling_station_area_urdu;
                    $line->poll_serial_no=$line->SchemeAddressmulti[1]->serial_no;
                    $line->ward=$line->SchemeAddressmulti[1]->ward;
                    $line->poll_gender=$line->SchemeAddressmulti[1]->gender_type;
                }
            }
            else
            {
                $line->polling_station_area_urdu=$line->SchemeAddressmulti[0]->polling_station_area_urdu;
                $line->poll_serial_no=$line->SchemeAddressmulti[0]->serial_no;
                $line->ward=$line->SchemeAddressmulti[0]->ward;
                $line->poll_gender=$line->SchemeAddressmulti[0]->gender_type;
            }


            /*if($line->type == 'cld')
            {



                $name="https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_"
                    .json_decode($line->crop_settings , true)['h'].",w_900,x_1800,y_".
                    json_decode($line->crop_settings , true)['y']."/".urlencode(@$line->url);

            }

            elseif($line->type == 'textract')
            {


                $name="https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_"
                    .json_decode($line->crop_settings , true)['h'].",w_0.24435,x_0.58,y_".
                    json_decode($line->crop_settings , true)['y']."/".urlencode(@$line->url);


            }


            $name_pic_data = file_get_contents($name);

            $line->name_base = base64_encode($name_pic_data);*/


//            echo '<img src="data:image/gif;base64,' .  $line->name_base . '" />';
//            echo '<img src="data:image/gif;base64,' .  $line->address_base . '" />';



//            $line->name_base = @$line->polling_details_images->name_pic;
//
//            unset($line->SchemeAddressmulti);
//            unset($line->url);
//            unset($line->crop_settings);

        }
    }

    public function blockcodeExportJsonall($blockcode)
    {
        ini_set('max_execution_time', '-1');

        $parchi = ParchiImage::where('block_code',$blockcode)->first();

        $mpolling_details = PollingDetail::where('polling_station_number', $blockcode)
            ->with('voter_phone')
            ->with('SchemeAddress')
            ->select('polling_station_number','cnic','family_no','gender','serial_no','url','crop_settings','type')
            ->take(100)
            ->get();

//        dd($mpolling_details);

        $img_address="";
        $name="";
        foreach($mpolling_details as $line)
        {
//            dd($line->crop_settings);

            if($line->type == 'cld')
            {
                $img_address="https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_"
                    .json_decode($line->crop_settings , true)['h'].",w_900,x_340,y_".
                    json_decode($line->crop_settings , true)['y']."/".urlencode(@$line->url);


                $name="https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_"
                    .json_decode($line->crop_settings , true)['h'].",w_900,x_1800,y_".
                    json_decode($line->crop_settings , true)['y']."/".urlencode(@$line->url);

            }

            elseif($line->type == 'textract')
            {
                $img_address="https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_"
                    .json_decode($line->crop_settings , true)['h'].",w_0.32435,x_0.07,y_".
                    json_decode($line->crop_settings , true)['y']."/".urlencode(@$line->url);

                $name="https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_"
                    .json_decode($line->crop_settings , true)['h'].",w_0.24435,x_0.58,y_".
                    json_decode($line->crop_settings , true)['y']."/".urlencode(@$line->url);


            }


            $name_pic_data = file_get_contents($name);
            $address_pic_data = file_get_contents($img_address);
            $line->name_base = base64_encode($name_pic_data);
            $line->address_base = base64_encode($address_pic_data);

//            echo '<img src="data:image/gif;base64,' .  $line->name_base . '" />';
//            echo '<img src="data:image/gif;base64,' .  $line->address_base . '" />';




            unset($line->url);
            unset($line->crop_settings);

        }

        return response()->json(['data' => $mpolling_details ,'block_code' => $blockcode,'parchi' => $parchi]);


        // return view('voterDetailEntryRecheck', compact('blockcode', 'mpolling_details','fpolling_details'));

    }

}
