<?php

namespace App\Http\Controllers;
use App\Models\Asset;
use App\Models\GeneralNotice;
use App\Models\PageSetting;
use App\Models\PdfPolling;
use App\Models\PollingDetail;
use App\Models\Tag;
use App\Models\User;
use App\Models\VertexUser;
use App\Models\CreateCnicDetailTable;
use App\Models\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobileViewController extends Controller
{

    public function index($id)
    {
        $generalSetting = PageSetting::where('business_id', $id)->where('status','ACTIVE')->first();
//        $response = Http::get('http://test.com');
        $url= 'https://onecall.plabesk.com/api/onecallschool/checktype/'.$id;
        $type = @file_get_contents($url);
//        dd($type);
        $data = Tag::where('business_id', $id)->where('status','ACTIVE')->get();
        if($type == "lawyers and legal advisor")
        {
            return view('MobileScreen/PoliticalRegistration', compact('data', 'id','generalSetting'));
        }

        else
        {
            return view('MobileScreen/registration', compact('data', 'id','generalSetting'));
        }
    }

    public function userAppRecord($uuid,$business_id)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://onecall.plabesk.com/api/onecallschool/appuserdetails/'.$uuid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $dataresponse=json_decode($response);
        $already = User::where('app_user_id', $dataresponse->id)->where('business_id', $business_id)->where('status','ACTIVE')->first();
        $waiting = User::where('app_user_id', $dataresponse->id)->where('business_id', $business_id)->where('status','PENDING')->first();
        if($already)
        {
            $phone=$already->phone;
            $user_id=$already->app_user_id;
            $this->favouriteBusiness($user_id,$business_id);

            return response(['message'=>$phone,'mesage'=>'A']);
        }
        elseif ($waiting)
        {
            return response(['mesage'=>'W']);
        }
        else
        {
            return response(['message'=>$response,'mesage'=>'N']);
        }
    }

    public function registration(Request $request)
    {
        $business_id=$request->business_id;
        $uuid=$request->uuid;
        $user_id=$request->user_id;
        $name= $request->full_name;
        $file_url=$request->file_url;
        $phone= $request->mobile_number;
        $address = $request->address;
        if ($request->tag_name)
        {
            $tag_name = implode(",", $request->tag_name);
            $user=User::where('phone',$phone)->where('business_id',$business_id)->first();
//            dd($user);

            if ($user)
            {
                $this->favouriteBusiness($user_id,$business_id);
                return response(['message' => 'A']);
            }
            else
            {
                $createUser = User::insertGetId([
                    'business_id' => $business_id,
                    'app_user_id' => $user_id,
                    'tag_id' => $tag_name,
                    'name' => $name,
                    'phone' => $phone,
                    'latlng' => $address,
                    'status'=> 'PENDING',
                ]);
                $createUserImage = UserImage::insertGetId([
                    'user_id' => $createUser,
                    'file_url' => $file_url,
                ]);
//                Business Favourite Api
                $this->favouriteBusiness($user_id,$business_id);
                return response(['message' => 'N']);
            }
        }
        else
        {
            return response(['message' => 'E']);
        }
    }

    public function favouriteBusiness($uid,$bid)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://onecall.plabesk.com/api/onecallschool/favourites/'.$uid.'/'.$bid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return true;
    }

    public function classdetails($id,$phone)
    {

        $user = User::where('phone', $phone)->where('status', 'ACTIVE')->where('business_id',$id)->orderBy('id','DESC')->first();
        if ($user) {
            $url = 'https://onecall.plabesk.com/api/onecallschool/checktype/' . $id;
            $type = @file_get_contents($url);
            if ($type == "votervertex") {
                $uname=$user->name;
                $uid = $user->id;
                return view('MobileScreen/VoterList', compact('uid','uname'));
            } else if ($type == "hr services") {
                $bid=$id;
                $uid=$phone;
                return view('MobileScreen/HrServices/category', compact('uid','bid'));
            } else if ($type == "visiting card") {
                $bid=$id;
                $url = 'https://onecall.plabesk.com/api/onecallschool/getUserDetails/' . $phone;
                $data = @file_get_contents($url);
                $data=json_decode($data);
                $uid=$data->id;
                $type=$data->type;
//                    dd($data);
                return view('MobileScreen/VisitingCard/index', compact('bid','uid','type'));
            } else {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://onecall.plabesk.com/api/onecallschool/businessDetails/" . $user->business_id,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response);
                $generalSetting = PageSetting::where('business_id', $user->business_id)->first();
                $generalNotice = GeneralNotice::where('bussiness_id', $user->business_id)->orderBy('id', 'DESC')->get();
                $generalNoticeHtml = GeneralNotice::where('bussiness_id', $user->business_id)->where('html_tag', '!=', NULL)->first();
                $AssetHtml = Asset::where('business_id', $user->business_id)->where('htmlload', '!=', NULL)->first();
                $tag_name = explode(",", $user->tag_id);
                foreach ($tag_name as $key => $value) {
                    $data[$key] = Asset::where('tag_id', $value)->orderBy('id', 'DESC')->get();
                }
                return view('MobileScreen/businessHomePage', compact('data', 'phone', 'id', 'generalNotice', 'response', 'generalSetting', 'generalNoticeHtml', 'AssetHtml'));

            }
        }
        else
        {
            return redirect('admin/mobileView/'.$id);
        }
    }

    public function edit($id,$phone)
    {
        $user = User::where('business_id',$id)->where('phone',$phone)->first();
        $userimg=UserImage::where('user_id',$user->id)->first();

        $generalSetting = PageSetting::where('business_id', $id)->first();
        $data = Tag::where('business_id', $id)->where('status','ACTIVE')->get();
        if ($user)
        {
            $class=explode(",",$user->tag_id);
            foreach($class as $key => $value) {
                $tags[$key] = Tag::where('id', $value)->first();
            }
        }
        return view('MobileScreen/updateUserProfile', compact('data', 'id','generalSetting','user','class','tags','userimg'));
    }

    public function update(Request $request)
    {
        $tag_name = implode(",", $request->tag_name);
        $createUser = User::where('id',$request->id)->update([
            'tag_id' => $tag_name,
            'name' => $request->full_name,
            'phone' => $request->mobile_number,
            'latlng' => $request->address,
        ]);
        return response(['message' => 'N']);
    }

    function getShortDynamicLink(string $link) {
        $url = $link;
        $longDynamicLink = "https://onecall.page.link/?link={$url}&apn=com.plabesk.onecall";
        $fields = [
            "longDynamicLink" => $longDynamicLink,
            "suffix" => [
                "option" => "UNGUESSABLE"
            ]
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://firebasedynamiclinks.googleapis.com/v1/shortLinks?key=AIzaSyATBfNLiYtyA5CYzl2L-Y9Yc-LUSnGcCQM");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);
        return $response['shortLink'] ?? null;
    }

    public function searchIdCard()
    {
        $uid=1;
        return view('MobileScreen/VoterList',compact('uid'));
    }

    public function VoterList(Request $request,$card)
    {

        $uri = $_SERVER['REQUEST_URI'];
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//        dd($url); // Outputs: Full URL
        //        header("Access-Control-Allow-Origin: *");
        $first = substr($card, 0, 5);
        $middle = substr($card, 5, 7);
        $last = substr($card, 12);

        $res = "$first-$middle-$last";
        $imgslice=null;
        $imgurl=null;
        $familyNo=null;
        //idcard result with relation of male and female
        $pollingDetails = $this->cardResult($res);



        if ($pollingDetails)
        {
            $polling=$pollingDetails->polling_station_number;
            $imgslice=$pollingDetails->pic_slice;
            $imgurl=$pollingDetails->url;
            $familyNo=$pollingDetails->family_no;
            $serial=$pollingDetails->serial_no;
            $phone = $pollingDetails->voter_phone ? $pollingDetails->voter_phone->phone : "";

            $pollingArea = $pollingDetails->schemeAddress->polling_station_area_urdu ?? '0';
            $cropSettings = $pollingDetails->crop_settings;
            $type = $pollingDetails->type;
            return response([

                'serial'=>$serial,
                'crop_setting' => $cropSettings ,
                'idcard'=> $res ,
                'type'=> $type,
                'blockCode'=> $polling,
                'message'=>'RECORD_FOUND',
                'pollingArea'=>$pollingArea,
                'Mobile'=>$phone,
                'imgslice'=>$imgslice,
                'imgurl'=>$imgurl,
                'family_number'=>$familyNo
            ]);
        }
        else
        {
            $pollingDetails = $this->cardResult($card);

            if ($pollingDetails)
            {
                $polling=$pollingDetails->polling_station_number;
                $imgslice=$pollingDetails->pic_slice;
                $imgurl=$pollingDetails->url;
                $familyNo=$pollingDetails->family_no;
                $phone = $pollingDetails->voter_phone ? $pollingDetails->voter_phone->phone : "";
                $pollingArea = $pollingDetails->schemeAddress->polling_station_area_urdu ?? '0';
                $cropSettings = $pollingDetails->crop_settings;
                $type = $pollingDetails->type;
                $serial=$pollingDetails->serial_no;


                return response([
                    'serial'=>$serial,
                    'cropSetting' => $cropSettings ,
                    'idcard'=> $res ,
                    'type'=> $type,
                    'pollingArea'=>$pollingArea,
                    'blockCode'=> $polling,
                    'message'=>'RECORD_FOUND',
                    'Mobile'=>$phone,
                    'imgslice'=>$imgslice,
                    'imgurl'=>$imgurl,
                    'family_number'=>$familyNo
                ]);
            }
            else
            {
                $pollingresult ="We are processing on data , It will be live soon ..";

                return response([
                    'polling'=> $pollingresult,
                    'message'=>'N0_RECORD_FOUND',
                ]);
            }
        }

    }

    public function familyList($familyNo,$blockCode,$cnic)
    {
        $queryCnicString = '';
        $first = substr($cnic, 0, 5);
        $middle = substr($cnic, 5, 7);
        $last = substr($cnic, 12);

        $res = "$first-$middle-$last";
        $familycount=PollingDetail::where('family_no',$familyNo)->where('polling_station_number',$blockCode)->count();
        $pollingDetails=PollingDetail::where('family_no',$familyNo)
            ->where('polling_station_number',$blockCode)
            ->where('cnic','!=',$res)
            ->with('sector','voter_phone','schemeAddress')
//            ->with(['schemeAddress' =>function($query){
//                $query->where('gender_type','male')
//                    ->orwhere('gender_type','combined');
//            }])
            ->select('polling_station_number','pic_slice','url','family_no','crop_settings','type','serial_no','cnic')
            ->get();
//        dd($pollingDetails);
        if(count($pollingDetails)>0)
        {
            return response(['card'=>$pollingDetails,'Count'=>$familycount,'message'=>'RECORD_FOUND']);
        }
        else
        {
            return response(['message'=>'NoRecord']);
        }

    }

    public function azzureApiResponse($queryCnicString)
    {
        $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'top' => 500, 'search' => $queryCnicString];
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
        $resp = $response['value'];
        return $resp;
    }

    public  function search_by_cnic($response,$search_string)
    {
        foreach ($response as $res)
        {
            if($res['idcard'] == $search_string)
            {
                return $res;
            }
        }
        return 0;
    }

    function login(Request  $request)
    {
        $username=$request->username;
        $password=$request->password;
//dd($username);

        $user=DB::table('vertexuser')->where('username',$username)->where('password',$password)->where('status','ACTIVE')->first();
        if ($user)
        {
            return response(['message'=>'S']);
        }
        else
        {
            return response(['message'=>'E']);
        }

    }

    /*check for election cnic record expert*/
   public function voterParchi($card,$sector)
    {
//        $token='cVay6og30SEuM0mgjhlxgbxV7ayLbgNhwE0JQJjx';
//        dd($_SERVER['SERVER_PORT']);
//        return $request->header();

//        header("Access-Control-Allow-Origin: https://dg-web.konnektedroots.com");

//        if (isset($_SERVER['HTTP_ORIGIN'])) {
////        header("Access-Control-Allow-Origin: ");
//            $origin = $_SERVER['HTTP_ORIGIN'];
//            $uri = $_SERVER['REQUEST_URI'];
//            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
//            return $origin;
//        }
//        $method = $_SERVER['REQUEST_METHOD'];

//        return $origin;
//        $host=$_SERVER['HTTP_HOST'];
//        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
//        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//
//        if ($method === 'GET') {
//            return $url;
//            // We are a GET method
//        }
        $first = substr($card, 0, 5);
        $middle = substr($card, 5, 7);
        $last = substr($card, 12);
        $res = "$first-$middle-$last";

//        $oldDataPollingDetails=PollingDetail::where('cnic',$res)->count();
//        if ($oldDataPollingDetails > 0)
//        {
//            return response()->json(['Message' => 'Record_Not_Found']);
//        }

//       $check=PollingDetail::where('created_at','>' ,'2022-06-10 00:00:00')->where('cnic',$res)->first();

    $check=PollingDetail::where('cnic',$res)->first();

         if(!$check)
        {
          return response()->json(['Message' => 'Record_Not_Found']);
        }
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
        else
        {
            $pollingDetails = PollingDetail::where('cnic', $res)
                ->with('sector','voter_phone')
                ->with('schemeAddress')
                ->first();

        }
        if ($pollingDetails) {
            return response()->json(['pollingDetails' => $pollingDetails, 'Message' => 'Record_found']);
        }
        else
        {
            $check=PollingDetail::where('cnic',$card)->first();
            if ($check->gender == 'male')
            {
                $pollingDetails = PollingDetail::where('cnic', $card)
                    ->with('sector','voter_phone','schemeAddress')
//                    ->with(['schemeAddress' =>function($query){
//                        $query->where('gender_type','male')
//                            ->orwhere('gender_type','combined')
//                            ->where('ward','%like%','NC-48');
//                    }])
                    ->first();
            }
            else if ($check->gender == 'female')
            {
                $pollingDetails = PollingDetail::where('cnic', $card)
                    ->with('sector','voter_phone','schemeAddress')
//                    ->with(['schemeAddress' =>function($query){
//                        $query->where('gender_type','female')
//                            ->orwhere('gender_type','combined')
//                            ->where('ward','%like%','NC-48');
//
//                    }])
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
    /*check for election cnic family record expert*/

    public function familyParchi($familyNo,$blockCode,$cnic)
    {
        $first = substr($cnic, 0, 5);
        $middle = substr($cnic, 5, 7);
        $last = substr($cnic, 12);
        $res = "$first-$middle-$last";

        $pollingDetail=PollingDetail::where('family_no',$familyNo)->where('polling_station_number',$blockCode)->where('cnic','!=',$res)->with('sector','voter_phone','schemeAddress')->get();
        if (count($pollingDetail)>0)
        {
            return response()->json(['pollingDetails' => $pollingDetail, 'Message' => 'Record_found']);
        }
        else
        {
            $pollingDetail=PollingDetail::where('family_no',$familyNo)->where('polling_station_number',$blockCode)->where('cnic','!=',$cnic)->with('sector','voter_phone','schemeAddress')->get();
            if (count($pollingDetail)>0)

            {
                return response()->json(['pollingDetails' => $pollingDetail, 'Message' => 'Record_found']);

            }
            else
            {
                return response()->json(['Message' => 'Record_Not_Found']);
            }

        }

    }


    public function cardResult($card)
    {
        $check = PollingDetail::where('cnic', $card)->first();
//        dd($check);
        if ($check) {
            if ($check->gender == 'male') {
                $pollingDetails = PollingDetail::where('cnic', $card)
                    ->with('sector', 'voter_phone')
                    ->with(['schemeAddress' => function ($query) {
                        $query->where('gender_type', 'male')
                            ->orwhere('gender_type', 'combined');
                    }])
                    ->first();
            } else if ($check->gender == 'female') {
                $pollingDetails = PollingDetail::where('cnic', $card)
                    ->with('sector', 'voter_phone')
                    ->with(['schemeAddress' => function ($query) {
                        $query->where('gender_type', 'female')
                            ->orwhere('gender_type', 'combined');
                    }])
                    ->first();
            }
            return $pollingDetails;
        }
    }

    public function AzzureSearch()
    {

        return view('Azzure.search');
    }

}
