<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectionSector;
use App\Models\FirebaseUrl;
use App\Models\PdfPolling;
use App\Models\PollingDetail;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\GeneralNotice;
use PDF;
use MPDF;
use App\Mail\OfferMail;
use Mail;


class PoliticsController extends Controller
{
    public function __construct()
    {
        $this->guard = config('admin-auth.defaults.guard');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            if (Auth::user()->forbidden == 1){
                abort(404);
            }
            return $next($request);

        });
    }

    public function idCardView()
    {
        $details = array();
        $polling_station = DB::table('polling_details')->get();
        foreach ($polling_station as $key => $value) {
            $cnic_number = preg_replace('/[^0-9]/', '', $value->cnic);


            $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'search' => $cnic_number];
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
            $response = json_decode($response);

            $result = $response->value;

            if ($result) {
                $response = $result[0];
                $fname = $response->firstname;
                $lname = $response->lastname;
                $idcard = $response->idcard;
                $Mobile = $response->phone1;
                $address1 = $response->address1;
                $address2 = $response->address2;
                $address3 = $response->address3;
                $name = $fname . $lname;
                $address = $address1 . ' ' . $address2 . ' ' . $address3;
                $polling = "We are processing on data , It will be live soon ..";
                $details[] = ['name' => $name, 'idcard' => $idcard, 'address' => $address, 'polling' => $polling, 'Mobile' => $Mobile, 'polling_station' => $value->polling_station_number];
            }
        }
        return view('poltitcs_view.dataView', compact('details'));
    }

    public function idCardreport()
    {
        return view('poltitcs_view.dataViewCnic');
    }

    public function searchIdCardParchi()
    {
        return view('Parchi.searchVoterParchi');
    }

    public function idCardpolling()
    {
        $email = Auth::user()->email;
        return view('poltitcs_view.dataViewPolling', compact('email'));
    }

    public function getidcard($card)
    {

        $first = substr($card, 0, 5);
        $middle = substr($card, 5, 7);
        $last = substr($card, 12);

        $res = "$first-$middle-$last";

        $pollingresult = DB::table('polling_details')->orwhere('cnic', $res)->orwhere('cnic',$card)->first();
        if ($pollingresult) {
            $polling = $pollingresult->polling_station_number;
            $age = $pollingresult->age;
            $family_no = $pollingresult->family_no;
        }
        else
        {
            $polling = "We are processing on data , It will be live soon ..";
            $age = '20 + year old';
            $family_no = 'we are working on gharana number';
        }

        $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'search' => $card];
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
        $response = json_decode($response);

        $result = $response->value;
//        dd($result);
        if ($result) {
            $response = $result[0];
            $fname = $response->firstname;
            $lname = $response->lastname;
            $idcard = $response->idcard;
            $Mobile = '0' . $response->phone1;
            $address1 = $response->address1;
            $address2 = $response->address2;
            $address3 = $response->address3;

            $name = $fname . $lname;
            $address = $address1 . ' ' . $address2 . ' ' . $address3;
//            $polling ="We are processing on data , It will be live soon ..";

            return response(['name' => $name, 'idcard' => $idcard, 'address' => $address, 'polling' => $polling, 'message' => 'R', 'Mobile' => $Mobile,'Age'=>$age,'Family'=>$family_no]);

        } else {
            return response(['idcard' => $card, 'polling' => $polling, 'message' => 'N']);

        }
    }

    public function downloadPdfuser($card)
    {
//        dd($idcard);
        $first = substr($card, 0, 5);
        $middle = substr($card, 5, 7);
        $last = substr($card, 12);
        $res = "$first-$middle-$last";

        $pollingresult = DB::table('polling_details')->where('cnic', $res)->first();
        if ($pollingresult) {
            $polling = $pollingresult->polling_station_number;
            $age = $pollingresult->age;
            $family_no = $pollingresult->age;
        } else {
            $polling = "We are processing on data , It will be live soon ..";
            $age = '20 + ';
            $family_no = 'we are working on gharana number';
        }

        $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'search' => $card];
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
        $response = json_decode($response);

        $result = $response->value;
//        dd($result);
        if ($result) {
            $response = $result[0];
            $fname = $response->firstname;
            $lname = $response->lastname;
            $idcard = $response->idcard;
            $Mobile = $response->phone1;
            $address1 = $response->address1;
            $address2 = $response->address2;
            $address3 = $response->address3;

            $name = $fname . $lname;
            $address = $address1 . ' ' . $address2 . ' ' . $address3;
            $polling = "We are processing on data , It will be live soon ..";

            $pdfdata = ['name' => $name, 'idcard' => $idcard, 'address' => $address, 'polling' => $polling, 'Mobile' => $Mobile,'Age'=>$age,'Family'=>$family_no];

            $pdf = PDF::loadView('poltitcs_view.userpdfdownload', compact('pdfdata'));
            return $pdf->download('userCnic.pdf');
        } else {
            return response(['message' => 'N']);

        }

    }

    public function blockDetailSearchPdf(Request $request)
    {

        $pollingresult = PollingDetail::where('polling_station_number', $request->block)->first();
        if ($pollingresult) {
            $data = PdfPolling::create([
                'email' => $request->email,
                'block_code' => $request->block,
                'record_type' => $request->record_type,
                'status' => 'PENDING',
                'type' => 'LIST',
                'cron_status' => '0'
            ]);
            return response(['message' => 'R']);
        } else {
            return response(['message' => 'N']);
        }

    }

    public function searchDownloadBlock()
    {
        ini_set("pcre.backtrack_limit", "5000000000");
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "0");
        $PdfPolling = new PdfPolling();
        $PdfPolling = PdfPolling::where('type','List')->where('cron_status', 0)->first();
//        dd($PdfPolling);
        if ($PdfPolling) {
//            $PdfPolling->cron_status = 1;
//            $PdfPolling->update();
            $block_code=$PdfPolling->block_code;

            $polling_details = PollingDetail::where('polling_station_number', $block_code)
                ->orderBy('serial_no', 'asc')
                ->where('gender', 'male')
                ->with('voter_phone','SchemeAddress','sector')
                ->take(1)
                ->get();
            $electionSector=ElectionSector::where('block_code',$block_code)->first();
            dd($PdfPolling,$polling_details,$electionSector);
            $pdf = MPDF::loadView('google-vision-api', compact('polling_details','electionSector','block_code'));

//            Get Result to Azzure Cognitive Search
            $this->sendPdfToMail($pdf);

            // $status = $this->azzureApiResponse($result);
            if ($status == true) {
                return 'Sent Successfully';
            }
        }
        else
        {
            return 'empty';
        }
    }

    public function azzureApiResponse($result)
    {
        $email = $result->email;
        $blockcode = $result->block_code;
        $blocknumber = '';
        $queryCnicString = '';
        $pdfdata = array();
        $queryCnicarray = '';
        $blocknumber = DB::table('polling_details')->where('polling_station_number', $result->block_code)->get();
        foreach ($blocknumber as $key => $value) {
            $cnic = str_replace('-', '', $value->cnic);
            $value->cnic_number=$cnic;
            $queryCnicString = $queryCnicString . $cnic . ' ';
        }
        $queryCnicarray = explode(' ', $queryCnicString);
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
        $response = json_decode($response,true);
        $resp = $response['value'];
        if ($resp) {
            foreach ($blocknumber as $key => $value) {
                //                calling function to check the record of idcard record
                $search_result= $this->search_by_cnic($resp,$value->cnic_number);
                if ($search_result) {
                    $value->valid_status = 1;
                    $value->name = $search_result['firstname'] . $search_result['lastname'];
                    $value->mobile_number = $search_result['phone1'];
                    $value->address = $search_result['address1'] . $search_result['address2'] . $search_result['address3'];
                }
                else
                {
                    $value->valid_staus = 0;
                    $value->name = '';
                    $value->mobile_number = '';
                    $value->address = '';
                }
            }

            //            $pdfpublish = PdfPolling::where('id', $result->id)->update(['status' => 'PUBLISHED']);
            //            calling function to create pdf and send to email
            $this->sendPdfToMail($blocknumber,$result);
            return true;
        }
        else
        {
            foreach ($blocknumber as $key => $value) {
                //                calling function to check the record of idcard record
                $value->valid_staus = 0;
                $value->name = '';
                $value->mobile_number = '';
                $value->address = '';

            }
            $pdfpublish = PdfPolling::where('id', $result->id)->update(['status' => 'PUBLISHED']);
            //            calling function to create pdf and send to email
            $this->sendPdfToMail($blocknumber,$result);
            return true;
        }
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

    public function sendPdfToMail($pdf)
    {

        $email='muhammad.yaqoob180@gmail.com';

        $dataa["email"] = $email;
        $dataa["client_name"] = 'Election Experts';
        $dataa["subject"] = 'Vertex Election Report';
        $message = 'Vertex Election Expert Details';
        try {
            Mail::send('email.mail', $dataa, function ($message) use ($dataa, $pdf) {
                $message->to($dataa["email"], $dataa["client_name"])
                    ->subject($dataa["subject"])
                    ->attachData($pdf->output(), "vertex.pdf");
            });
        } catch (JWTException $exception) {
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (Mail::failures()) {
            $this->statusdesc = "Error sending mail";
            $this->statuscode = "0";
            return false;
        } else {
            $this->statusdesc = "Message sent Succesfully";
            $this->statuscode = "1";
            $result = PdfPolling::where('id', $result->id)->update(['status' => 'SENT']);
            return true;
        }
    }

    public function datalistview()
    {
        ini_set('max_execution_time', 1000);
//        $blocknumber = DB::table('polling_details')->where('polling_station_number', '188110704')->get('cnic');
        $string=
//        dd($blocknumber);
        $queryCnicString='DHA LAHORE';
//        foreach ($blocknumber as $key => $value) {
//            $cnic = str_replace('-', '', $value->cnic);
//            $queryCnicString= $queryCnicString .$cnic. ' ' ;
//
//        }
//        dd($queryCnicString);
        $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'top' => 5000, 'skip'=> 10000, 'search' => $queryCnicString];
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
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        $result = $response->value;
        if ($result) {
            foreach ($result as $key => $value)
            {
                $Mobile[$key] = '0' . $value->phone1;

            }
//            $Mobile='03249574957';

//            $Mobile='03094222265';
        }
//        dd($Mobile);
        $details = ['phone' => $Mobile];
        $ch = curl_init('https://onecall.plabesk.com/api/crm/hr-services-message-load');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Mobile);
        $response = curl_exec($ch);
        curl_close($ch);
        return true;

    }

    public function updateMobile()
    {
        ini_set('max_execution_time', 1000);

        $id=DB::table('temp_send_sms')->where('status',0)->get()->take(5);
//dd($id);
        foreach ($id as $key => $value) {
            $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'top' => 1, 'search' => $value->idcard];
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
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response);
            $result = $response->value;
            if ($result) {
                $response = $result[0];
                $Mobile = '0' . $response->phone1;
            }

            $id=DB::table('temp_send_sms')->where('id',$value->id)->update([
                'phone' => $Mobile,
                'status'=>1
            ]);
            dd($id);
        }

    }

    public function Sectordetails()
    {
        $electionSector=ElectionSector::get();
        foreach ($electionSector as $key => $electionSector)
        {
            $sector[$key] = $electionSector->sector;
            $block_code[$key] = $electionSector->block_code;
            $male_vote[$key] = $electionSector->male_vote;
            $female_vote[$key] = $electionSector->female_vote;
            $total_vote[$key] = $electionSector->total_vote;
        }
        $response = [
            'sector' => $sector,
            'block_code' => $block_code,
            'male_vote' => $male_vote,
            'female_vote' => $female_vote,
            'total_vote' => $total_vote,
        ];
        return $response;

    }
}

