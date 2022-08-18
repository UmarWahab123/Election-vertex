<?php

namespace App\Http\Controllers;

use Cassandra\UserTypeValue;
use Illuminate\Http\Request;
use Brackets\AdminAuth\Models\AdminUser;
use DB;
use Session;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    function sendSMSPK1($mobile_number,$message)
    {
        $api_url = 'http://lifetimesms.com/plain?username=goharsultan&password=G0harsultan&to='.urlencode($mobile_number)
            .'&message='.urlencode($message).'&from=ONECALL';
        $ch = curl_init();
        $timeout  =  30;
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $response = curl_exec($ch);
        curl_close($ch);
    }

    public function verify_user(Request $request){
        if(strlen($request->phone) < 11 && strlen($request->phone) > 12){
            return redirect('/get-login')
                ->with('error','Invalid Number !');
        }else{
            $phone = DB::table('admin_users')->where('phone', $request->phone)->first();
            if($phone){
                return redirect('/admin/login');
            }else{
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://onecall.plabesk.com/api/onecallschool/verify/'.$request->phone,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);

                if($response){
                    $business_details = json_decode($response);
                    $otp_code = mt_rand(1000,9999);
                    $message = $otp_code.' is your authentication key for One Call Business Portal. This SMS has bee sent by One Call App. Visit Now: https://www.onecallapp.com';
                    $phone_number = $request->phone;
                    $name = $business_details->name;
                    $business_id = $business_details->id;
                    $this->sendSMSPK1($phone_number , $message);
                    Session::put('otp' , $otp_code);
                    return view('admin.verifyOTP' , compact('phone_number' , 'name', 'business_id'));

                }else{
                    return redirect('/get-login')
                        ->with('error','Not Authenticated!');
                }
            }
        }

    }

    public function verify_otp(Request $request){
        if($request->otpCode ==  Session::get('otp')){
            $business_id = $request->business_id;
            $phone = $request->phone;
            $name = $request->name;
            return view('admin.register' , compact('phone', 'business_id', 'name'));
        }else{
            return redirect('/get-login')
                ->with('error','Wrong OTP , Try Again !');
        }
    }

    public function register(Request $request){

        $admin_user = new AdminUser();
        $admin_user->first_name = $request->name ;
        $admin_user->email = $request->email ;
        $admin_user->phone = $request->phone ;
        $admin_user->password = Hash::make($request->password) ;
        $admin_user->business_id = $request->business_id ;
        $admin_user->save();
        $admin_user->assignRole(1);
//        $userId=$admin_user->id;
//        $businessId=$admin_user->business_id;
//        $curl = curl_init();
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => 'http://127.0.0.1:8000/api/business/register/'.$businessId.'/'.$userId,
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => '',
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => 'GET',
//        ));
//        $response = curl_exec($curl);
//        curl_close($curl);

        $rolehaspermissions = DB::table('role_has_permissions')->select('*')->where('role_id',1)->first();
        if($rolehaspermissions){
            $permissions_id=$rolehaspermissions->permission_id;
        }
        $admin_user->givePermissionTo($permissions_id);
        Session::flush('otp');
        return redirect('admin/login');
    }
}
