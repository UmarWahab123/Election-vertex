<?php

namespace App\Http\Controllers\politicsreport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UsersChat;
use App\Models\User;
use App\Models\UserMessage;
use App\Models\BusinessProfile;
Use Redirect;

class politicsController extends Controller
{

    public function idCardView()
    {
        dd("sfdasfdas");
        $details = array();
        $polling_station = DB::table('polling_details')->get();
        foreach ($polling_station as $key => $value) {
            $post = ['count' =>'true','minimumCoverage'=> 100, 'queryType' => 'full','search'=>$value->cnic];
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
            $response=json_decode($response);
            $result = $response->value;

            if ($result)
            {
                $response=$result[0];
                $fname= $response->firstname;
                $lname= $response->lastname;
                $idcard= $response->idcard;
                $Mobile= $response->phone1;
                $address1= $response->address1;
                $address2= $response->address2;
                $address3= $response->address3;
                $name=$fname.$lname;
                $address =$address1 .' '. $address2 .' '. $address3;
                $polling ="We are processing on data , It will be live soon ..";
                $details[] = ['name' => $name , 'idcard'=> $idcard ,'address'=> $address,'polling'=> $polling, 'Mobile'=>$Mobile , 'polling_station' => $value->polling_station_number];
            }
        }
        return view('politics_view.test');
    }
}
