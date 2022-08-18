<?php

namespace App\Http\Controllers\HrServices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HrController extends Controller
{
    public function chooseCategory($bid,$uid,$category)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://hr.plabesk.com/api/HR_Buisness/getCategoryRecord/'.$category,
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
//        $data=json_decode(json_encode($response),true);
        $data=json_decode($response);
//        dd($data);
        return view('MobileScreen/HrServices/job',compact('uid','bid','data'));
    }

    public function employeeSignup($bid,$uid)
    {
        return view('MobileScreen/HrServices/employeesignUp',compact('uid','bid'));
    }
}
