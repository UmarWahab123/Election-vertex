<?php

namespace App\Http\Controllers\firebase;

use App\Http\Controllers\Controller;
use App\Models\ElectionSector;
use App\Models\ParchiImage;
use App\Models\PdfPolling;
use App\Models\PollingScheme;
use App\Models\PollingStation;
use App\Models\PollingDetail;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Models\UsersChat;
use App\Models\User;
use App\Models\PartyName;
use App\Models\UserMessage;
use App\Models\BusinessProfile;
use App\Models\FirebaseUrl;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
Use Redirect;
use Illuminate\Support\Facades\Storage;
use Auth;
use PDF;
use MPDF;
use App\Mail\OfferMail;
use Mail;
use Exception;
use function GuzzleHttp\Promise\all;

class firebaseController extends Controller
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

    public function categorizeImageUpload()
    {
//        $this->authorize('admin.politics.imageuplaod');
        $userId = Auth::user()->id;
        if ($userId == 49) {
            return view('firebaseImage.categorizeImageUpload', compact('userId'));
        } else {
             return view('firebaseImage.categorizeImageUpload', compact('userId'));
//        $bId=Auth::user()->business_id;
            $bId = 4771;

            $date = date('Y-m-d');
            $paymentGateway = PaymentGateway::where('business_id', $bId)->first();
            if ($paymentGateway->on_demand_cloud_computing == 1 && $paymentGateway->multi_bit_visual_redux == 1 && $paymentGateway->scan_reading == 1 && $paymentGateway->googly == 1 && $paymentGateway->status == 1 && $paymentGateway->expiry_date >= $date) {
                return view('firebaseImage.categorizeImageUpload', compact('userId'));
            } 

            // else {
            //     if ($paymentGateway->on_demand_cloud_computing != 1) {
            //         $msg = 'Your bill is $713.25 . Kindly Pay bill "On Demand Cloud Computing service". This service is temporary close. ';
            //         return view('firebaseImage.billMsg', compact('msg'));
            //     } else if ($paymentGateway->multi_bit_visual_redux != 1) {
            //         $msg = 'Your bill is $713.25 . Kindly Pay bill "Multi Bit Visual Redux service". This service is temporary close.';
            //         return view('firebaseImage.billMsg', compact('msg'));
            //     } else if ($paymentGateway->scan_reading != 1) {
            //         $msg = 'Your bill is $713.25 . Kindly Pay bill "Scan Reading service". This service is temporary close.';
            //         return view('firebaseImage.billMsg', compact('msg'));
            //     } else if ($paymentGateway->googly != 1) {
            //         $msg = 'Your bill is $713.25 . Kindly Pay bill "googly service". This service is temporary close.';
            //         return view('firebaseImage.billMsg', compact('msg'));
            //     } else if ($paymentGateway->status != 1) {
            //         $msg = 'Services charges is $2853. Kindly Pay bill "All services". This service is temporary close.';
            //         return view('firebaseImage.billMsg', compact('msg'));
            //     } else {
            //         $msg = 'Package is expire, if resume your services to pay Service charges $2853. This service is temporary close.';
            //         return view('firebaseImage.billMsg', compact('msg'));
            //     }
            // }
        }
    }

    public function index()
    {
        $this->authorize('admin');
        $bId=4771;

        $userId=Auth::user()->id;
        $paymentGateway=PaymentGateway::where('business_id',$bId)->first();
        if ($paymentGateway->on_demand_cloud_computing == 1 && $paymentGateway->multi_bit_visual_redux == 1 && $paymentGateway->scan_reading == 1 && $paymentGateway->googly == 1 && $paymentGateway->status == 1 && $paymentGateway->expiry_date >= $date)
        {
        return view('firebaseImage.imageUpload',compact('userId'));
        }
        else
        {
            if ($paymentGateway->on_demand_cloud_computing != 1)
            {
                $msg='Your bill is $713.25 . Kindly Pay bill "On Demand Cloud Computing service". This service is temporary close. ';
                return view('firebaseImage.billMsg', compact('msg'));
            }
            else if ($paymentGateway->multi_bit_visual_redux != 1)
            {
                $msg='Your bill is $713.25 . Kindly Pay bill "Multi Bit Visual Redux service". This service is temporary close.';
                return view('firebaseImage.billMsg', compact('msg'));
            }
            else if ($paymentGateway->scan_reading != 1)
            {
                $msg='Your bill is $713.25 . Kindly Pay bill "Scan Reading service". This service is temporary close.';
                return view('firebaseImage.billMsg', compact('msg'));
            }
            else if ($paymentGateway->googly != 1)
            {
                $msg='Your bill is $713.25 . Kindly Pay bill "googly service". This service is temporary close.';
                return view('firebaseImage.billMsg', compact('msg'));
            }
            else if ($paymentGateway->status != 1)
            {
                $msg='Services charges is $2853. Kindly Pay bill "All services". This service is temporary close.';
                return view('firebaseImage.billMsg', compact('msg'));
            }
            else
            {
                $msg='Package is expire, if resume your services to pay Service charges $2853. This service is temporary close.';
                return view('firebaseImage.billMsg', compact('msg'));
            }
        }
    }

    public function ParchiImage()
    {
        $electionsector= ElectionSector::groupBy('sector')->get();
        $data['parties'] = PartyName::get();
        return view('firebaseImage.ParchiImage',compact('electionsector','data'));
    }

    public function parchiimgupload(Request $request)
    {
//        dd($request->all());
        $userId=Auth::user()->id;
        $sector =$request->sector;
        $electionsector = ElectionSector::where('sector',$sector)->get();
//        dd($electionsector[0]->block_code);
        $parchimage = ParchiImage::where('block_Code', $electionsector[0]->block_code)->where('block_Code', $electionsector[0]->Party)->first();
        if ($parchimage) {
            return back()->with('message', 'Block Code Already Exist!');

        }
        $parchiImageData = [];
        foreach ($electionsector as $row)
        {
            $parchiImageData[] = [
                'user_id' => $userId,
                'image_url' => $request->images,
                'Party' => $request->party,
                'status' => 'PENDING',
                'candidate_name' => $request->candidate_name,
                'block_code' => $row->block_code,
            ];
        }

        ParchiImage::insert($parchiImageData);

        return back()->with('message', 'Block code Upload successfully!');

    }

//    Voter Parchi Print
    public function voterParchiView($block_code)
    {
        $mpolling_details=array();
        $fpolling_details=array();
        $electionSector='';
        ini_set('max_execution_time', '-1');
        $party =$_GET['party'];
        $parchiimages=ParchiImage::where('block_code',$block_code)->where('Party',$party)->first();
// dd($parchiimages);
        $mgender="male";
        if ($parchiimages)
        {
            $mpolling_details = PollingDetail::where('polling_station_number', $block_code)
                ->orderBy('serial_no', 'asc')
                ->where('gender', 'male')
                ->with('voter_phone')
                ->with(['SchemeAddress'=>function($query) use ($mgender){
                    $query->where('gender_type',$mgender)
                        ->orwhere('gender_type','combined');
                }])
                ->select('type','crop_settings','url','serial_no','family_no','polling_station_number','cnic')
                ->paginate(50);
            $electionSector=ElectionSector::where('block_code',$block_code)->first();
            $gender='female';
            $fpolling_details = PollingDetail::where('polling_station_number', $block_code)
                ->orderBy('serial_no', 'asc')
                ->where('gender', 'female')
                ->with('voter_phone')
                ->with(['SchemeAddress'=>function($query) use ($gender){
                    $query->where('gender_type',$gender)
                        ->orwhere('gender_type','combined');
                }])
                ->select('type','crop_settings','url','serial_no','family_no','polling_station_number','cnic')
                ->paginate(50);
        }
        $dpi = 400;

        return view('email.voterParchi', compact('mpolling_details','fpolling_details','block_code','parchiimages','electionSector' , 'dpi'));
    }

    public function indexElection()
    {

//        $this->authorize('admin.politics.imageuplaod');
        $userId=Auth::user()->id;
        $blockcode=PollingStation::select('id','polling_station_number')->get();
        return view('firebaseImage.election',compact('userId','blockcode'));
    }

    public function saveElection(Request $request)
    {
        $electionsector=ElectionSector::where('block_code',$request->block_code)->first();
//        dd($electionsector);
        if ($electionsector)
        {
            return response(['MESSAGE'=>'ALREADY']);
        }
        else
        {
            $total=$request->male+$request->female;
            $data=ElectionSector::insertGetId([
                'sector'=>$request->ward,
                'user_id'=>$request->userId,
                'block_code'=>$request->block_code,
                'male_vote'=>$request->male,
                'female_vote'=>$request->female,
                'total_vote'=>$total,
                'status'=>'ACTIVE',
            ]);
            return response(['MESSAGE'=>'NEW']);
        }
    }

    public function updateAddressForm()
    {
        $polling_details = PollingDetail::where('polling_station_number' , 188110201)
            ->orderBy('serial_no')
            ->get();
//        dd($polling_details);
//        exit();
        return view('firebaseImage.addressUpdateForm' , compact('polling_details'));
    }

    public function testingUpdateAddressForm()
    {
        $polling_details = PollingDetail::where('polling_station_number' , 188110201)
            ->orderBy('serial_no')
            ->get();
        return view('firebaseImage.testingAddressUpdateForm' , compact('polling_details'));
    }

    public function updateAddress(Request $request)
    {
        $data = [
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'address'=>$request->address,
        ];

        if(@$request->serial_no and !is_null( $request->serial_no )) {
            $data['serial_no'] = $request->serial_no;
        }

        if(@$request->family_no and !is_null( $request->family_no )) {
            $data['family_no'] = $request->family_no;
        }

        $pollingDetails=PollingDetail::where('id',$request->id)->update($data);
        return $pollingDetails;
    }

    public function createFullPageEntries($urlId)
    {
        $firebaseUrl = FirebaseUrl::where('id', $urlId)->first();
//        dd(PollingDetail::where('cnic', '34201-0663919-4')->first());
//        dd(PollingDetail::find(478901));
        return view('firebaseImage.createFullPageEntries', compact('firebaseUrl'));
    }

    public function saveFullPageEntries(Request $request)
    {
        $cnic = trim( $request->cnic );
        $rowWithCnic = PollingDetail::where('cnic', $cnic)->first();
//        return $rowWithCnic;
        if($rowWithCnic) {
            $result=PollingDetail::where('cnic', $cnic)->update([
                'pic_slice' => json_encode([
                    'address'=>$request->address,
                    'age'=>$request->age,
                    'last_name'=>$request->parentName,
                    'first_name'=>$request->firstName,
                    'family_no'=>$request->familyNo,
                    'serial_no'=>$request->serialNo,
                ], JSON_UNESCAPED_UNICODE ),
                'address'=>$request->address ?? $rowWithCnic->address,
                'age'=>$request->age ?? $rowWithCnic->age,
                'last_name'=>$request->parentName ?? $rowWithCnic->last_name,
                'first_name'=>$request->firstName ?? $rowWithCnic->first_name,
                'family_no'=>$request->familyNo ?? $rowWithCnic->family_no,
                'serial_no'=>$request->serialNo ?? $rowWithCnic->serial_no,
            ]);
        }else{
            $result = PollingDetail::insert([
                'address'=>$request->address,
                'age'=>$request->age,
                'cnic'=>$request->cnic,
                'last_name'=>$request->parentName,
                'first_name'=>$request->firstName,
                'serial_no'=>$request->serialNo,
                'family_no'=>$request->familyNo,
            ]);
        }

        return $result;
    }


    //Automate Voter Parchi Methods

    public function voterParchiPdf()
    {
        ini_set('max_execution_time', '-1');
        ini_set("memory_limit", "-1");

        $pdfPolling = PdfPolling::where('status', 'PENDING')->where('type','LIST')->first();
        $_id = $pdfPolling->id;

        define("DOMPDF_UNICODE_ENABLED", true);
        $polling_details=array();
        $fpolling_details=array();
        $electionSector='';

        if (!$pdfPolling) {
            return false;
        }

        PdfPolling::update_status($_id , 'FETCHED');

        PdfPolling::where('id',$pdfPolling->id)->update(['cron_status'=>'1']);

        $meta =  $pdfPolling->meta;
        $meta =  json_decode($meta);
        $ids = $meta->ids;
        $block_code = $pdfPolling->block_code;
        $electionSector = ElectionSector::where('block_code', $block_code)->first();

        $parchiImages = ParchiImage::where('block_code', $block_code)->where('Party', $pdfPolling->party_type)->first();

        $polling_details = PollingDetail::where('polling_station_number', $block_code)
            ->where('gender', $meta->gender)
            ->with('SchemeAddress' , 'voter_phone' , 'sector')
            ->whereIn('id',$ids)
            ->get();

        $dpi = 400;

//        return view('email.voterListPdf' , compact('polling_details' , 'dpi'));

        PdfPolling::update_status($_id , 'ALL_QUERIES_EXECUTED');

        if(!$polling_details)
        {
            PdfPolling::update_status($_id , 'NO_RECORD_FOUND');
            return 'Data is Empty';
        }

        PdfPolling::update_status($_id , 'BEFORE_LOADING_VIEW');

        $format = 'legal';
        $data=[
            'polling_details' =>$polling_details,
            'block_code'=>$block_code,
            'parchiImages'=>$parchiImages,
            'electionSector'=>$electionSector,
            'dpi'=>400
        ];
        try {
            $pdf=PDF::loadView('email.voterListPdf', $data, [
                'format'                   => 'Legal',
                'default_font_size'        => '20',
                'default_font'             => 'serif',
                'margin_left'              => 5,
                'margin_right'             => 5,
                'margin_top'               => 5,
                'margin_bottom'            => 5,
                'margin_header'            => 0,
                'margin_footer'            => 0,
                'orientation'              => 'P',
                'title'                    => 'Laravel mPDF',
                'watermark_font'           => 'sans-serif',
                'display_mode'             => 'fullpage',
                'temp_dir'                 => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
            ]);

        }catch (\Exception $e){
            dd($e);
        }

        PdfPolling::update_status($_id , 'AFTER_LOADING_VIEW');

        $filename = $electionSector->sector .'-'.$block_code.'-'.$meta->gender.'-'.($meta->i + 1).'.pdf';
        $parchipdf=$this->ParchiPdf($pdf,$filename,$_id);

        if(@$meta->sendMail){
            PdfPolling::update_status($_id , 'SENDING_CONCLUSION_MAIL');
            $this->sendConclusionMail($meta->sendMail);
            PdfPolling::update_status($_id , 'CONCLUSION_MAIL_SENT');
        }

        $pdfpublish = PdfPolling::where('id', $pdfPolling->id)->update(['status' => 'SENT','cron_status'=> '1']);

        return true;


//        $pdf = $pdf->stream('voterlist.pdf');
//        return $pdf->download('voterlist.pdf');
//        return view('parchiImage', compact('polling_details','block_code','parchiimages','electionSector'));
    }

    public function ParchiPdf($pdf,$filename,$_id)
    {
        ini_set("pcre.backtrack_limit", "50000000");
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "0");
        $email='mozmbutt8@gmail.com';
        $dataa["email"] = $email;
        $dataa["client_name"] = 'Election Experts';
        $dataa["subject"] = $filename;
        $message = 'Voter Parchi';
        try {
            PdfPolling::update_status($_id , 'TRYING_SENDING_MAIL');
            Mail::send('email.mail', $dataa, function ($message) use ($dataa, $pdf,$filename) {
                $message->to($dataa["email"], $dataa["client_name"])
                    ->subject($dataa["subject"])
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

    public function sendConclusionMail($sendMailDetails){

        try {
            $email='mozmbutt8@gmail.com';
            $dataa["email"] = $email;
            $dataa["client_name"] = 'Election Experts';
            $dataa["subject"] = 'Conclusion';
            $message = 'Thanks for downloading.. Conclusion  of Block Code :'.$sendMailDetails->block_code. ' of Sector : '.$sendMailDetails->sector .' Total Expected Sent Mails : '.$sendMailDetails->total_expected_sent_mails. ' Total records found : '.$sendMailDetails->total_records_found.  '  Male Record : '.$sendMailDetails->total_male_records_found.  ' Female Record : '.$sendMailDetails->total_female_records_found;

            $dataa["messagesend"] = $message;
        }
        catch (\Exception $e) {


        }

        Mail::send('email.conclusionmail', $dataa, function ($message) use ($dataa) {
            $message->to($dataa["email"], $dataa["client_name"])
                ->subject($dataa["subject"])
                ->setBody( '<html><h1>5% off its awesome</h1><p>Go get it now !</p></html>', 'text/html' );
            ;
        });


        return true;
    }


    public function testingFunction()
    {
        $pdfPolling = PdfPolling::where('status', 'SENT')->where('type' , 'PARCHI')->first();
//        dd($pdfPolling);
        $_id = $pdfPolling->id;
        $_type = $pdfPolling->type;
        $meta =  json_decode($pdfPolling->meta);
        $ids  = $meta->ids;
        $gender  = $meta->gender;
        $block_code = $pdfPolling->block_code;

//dd($gender);
        $electionSector = ElectionSector::where('block_code', $block_code)->first();
        $parchiImages = ParchiImage::where('block_code', $block_code)->where('Party', $pdfPolling->party_type)->first();
        $gend ="male";
        $polling_details = PollingDetail::where('polling_station_number', $block_code)
            ->where('gender', $gender)
            ->with('voter_phone' , 'sector','polling_details_images')
            ->with(['SchemeAddress'=>function($query) use ($gend){
                $query->where('gender_type',$gend)
                    ->orwhere('gender_type','combined');
            }])

            ->whereIn('id',$ids)
            ->orderBy('serial_no', 'asc')
            ->take(5)
            ->get();
//        dd($polling_details);

        return view('email.voterParchiNew',compact('parchiImages','polling_details','electionSector'));
    }

}
