<?php

namespace App\Http\Controllers\Dev;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ElectionExpertCore;
use App\Models\ElectionSector;
use App\Models\FirebaseUrl;
use App\Models\ParchiImage;
use App\Models\PdfPolling;
use App\Models\PdfPollingLog;
use App\Models\PollingDetail;
use App\Models\PollingScheme;
use Google\Cloud\Vision\Image;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\VisionClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;
use Mail;
use League\Flysystem\Filesystem;
use ZipArchive;
use File;
use DB;
use App\Exports\ElectionData;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{



    public function testRaws()
    {


   $mail_sent = PollingDetail::whereDate('created_at','>' ,'2022-07-16 00:00:00')
   ->where('cnic','42401-8859595-7')
   ->first();

   dd($mail_sent);


//
//    dd($mail_sent);



//    foreach ($mail_sent as $key=> $d)
//    {
//
//        $block_code = $d->block_code.'.zip';
//        try {
//            $files = file_get_contents('https://vertex.plabesk.com/'.$block_code);
//
//            $path = Storage::disk('s3')->put('zimni election 2022/parchi/'.$block_code, $files);
//            $path = Storage::disk('s3')->url($path);
//
//        }catch (\Exception $e)
//        {
//          // dd($e);
//        }
//
//
//    }
//        dd($mail_sent);

//        $zip = new ZipArchive;
//
//        $d->block_code = '0900';
//
//        $fileName = $d->block_code.'.zip';
//
//        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
//        {
//            $files = File::files(public_path('parchi/'.$d->block_code));
//
//            foreach ($files as $key => $value) {
//                $relativeNameInZipFile = basename($value);
//                $zip->addFile($value, $relativeNameInZipFile);
//            }
//
//            $zip->close();
//        }

//
//
//
//        $files = file_get_contents('https://vertex.plabesk.com/'.$fileName);
//        if($files)
//        {
//            $path = Storage::disk('s3')->put('zimni election 2022/parchi/'.$fileName, $files);
//            $path = Storage::disk('s3')->url($path);
//        }
//
//
//        unlink(base_path('public/'.$fileName));
//
//
//       $s3 = 'https://election-storage.s3.eu-west-1.amazonaws.com/zimni+election+2022/parchi/'.$fileName;
//
//
//        dd($path,$s3);





        exit();
//        $block_code = $d->block_code.'.zip';
//        $files = file_get_contents('https://vertex.plabesk.com/'.$block_code);
//        if($files)
//        {
//            $path = Storage::disk('s3')->put('zimni election 2022/parchi/'.$block_code, $files);
//            $path = Storage::disk('s3')->url($path);
//        }


//        $path = Storage::disk('s3')->put($d.'/'.$filename, $pdf->output());
//        $path = Storage::disk('s3')->url($path);

        //dd($path);





//
//        $file_names = Storage::disk('s3')->files('433050105/');
//
//        $zip = new Filesystem(new ZipArchiveAdapter(public_path('archive.zip')));
//
//        foreach($file_names as $file_name){
//            $file_content = Storage::disk('s3')->get($file_name);
//            $zip->put($file_name, $file_content);
//        }
//
//        $zip->getAdapter()->getArchive()->close();
//
//        return redirect('archive.zip');



        ini_set('max_execution_time', '-1');
        ini_set("memory_limit", "-1");

        $this->logSwitch = false;

        $timeStartFetchingPending = microtime(true);
        $memBeforeFetchingPending = memory_get_usage();

//        $pdfPolling = PdfPolling::where('status', 'PENDING')
//            ->where('cron_status',0)
//            ->first();


        $pdfPolling = PdfPolling::where('id', '10167')
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

//        PdfPolling::update_status($_id , 'FETCHED');
//        PdfPolling::where('id',$_id)->update(['cron_status'=>'1']);

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
            ->take(12)
            ->get();





//        PdfPolling::update_status($_id , 'ALL_QUERIES_EXECUTED');
//
//        if(!$polling_details)
//        {
//            PdfPolling::update_status($_id , 'NO_RECORD_FOUND');
//            return 'Data is Empty';
//        }
//
//        PdfPolling::update_status($_id , 'BEFORE_LOADING_VIEW');

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



        $_type = 'PARCHI';



        if($_type == 'LIST')
        {
            ini_set("pcre.backtrack_limit", "5000000");
            $data=[
                'polling_details' => $polling_details
            ];

            $filename =$block_code.'-'.$meta->gender.'-'.($meta->i + 1);

           $pdf = PDF::loadView('email.voterListPdf', $data, $pdf_settings );

            $path = Storage::disk('s3')->put($block_code.'/'.$filename, $pdf->output());
            $path = Storage::disk('s3')->url($path);

            dd($path);

            //return view('email.voterListPdf',compact('polling_details'));
            //Storage::put('/list/'.$block_code.'/'.$filename.'.pdf/', $pdf->output());

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

          $pdf = PDF::loadView('email.voterParchiNew', $data, $pdf_settings);

            return $pdf->download();
//            $filename =$block_code.'-'.$meta->gender.'-'.($meta->i + 1);

           return  view('email.voterParchiNew',compact('data','pdf_settings','polling_details','parchiImages','electionSector'));
//            return $pdf->download();
//            Storage::put('/data2/'.$block_code.'/'.$filename.'.pdf/', $pdf->output());
//
//            PdfPolling::where('id', $_id)->update([
//                'status' => 'INDIR',
//                'cron_status'=> '1'
//            ]);


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


    public function testRaw(Request $request){

        $block_code = ElectionSector::where('sector', 'LIKE', "%Na-133%")
            ->where('status','Excel')
             ->get();



        $data = [];
        foreach ($block_code as $b)
        {
            $b->status = 'EXCEL';
            $b->save();
             return Excel::download(new ElectionData($b->block_code), '259190302.xlsx');


        }
dd($data);




        dd('hello');



        ini_set('max_execution_time', '-1');
        ini_set("memory_limit", "-1");

        $this->logSwitch = false;

        $timeStartFetchingPending = microtime(true);
        $memBeforeFetchingPending = memory_get_usage();

        $pdfPolling = PdfPolling::where('id', '12673')
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

            $pdf = PDF::loadView('email.voterListPdf', $data, $pdf_settings);
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

            return view('email.voterParchiNew', $data, $pdf_settings);
            Storage::put('/parchi/'.$block_code.'/'.$filename.'.pdf/', $pdf->output());

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

   public function sendConclusionMail($sendMailDetails , $_type)
   {

        try {
            $email='uz391110dev@gmail.com';
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


    public  function testPost(Request $request){



       $cnic = json_decode($request->cnic,true);


        $p = $polling_detail = PollingDetail::whereIn('cnic', $cnic)
            ->select('id', 'cnic','type', 'serial_no', 'family_no','polling_station_number','gender','age')
            ->where('created_at','>' ,'2021-03-10 00:00:00')
             ->with('voter_phone','sector')
             ->get();




        if(count($p) > 0)

        {

            return  response(['status'=> 2,'message'=>'found','data'=>$p ]);

        }else
        {
            return  response(['status'=> 3,'message'=>'notfound','data'=>'' ]);
        }






    }







    public function testVision(Request $request){


        switch ($request->method()) {
            case 'POST':

                $url =$request->url;

                $res = $this->findCoordsOfTextFromImage($url);

                return $res;



            case 'GET':

                return view('dev.vision-test');

             default:
                // invalid request
                break;

        }
    }


    public function findCoordsOfTextFromImage($image_link)
    {
        $path = $image_link;

        $projectId = 'nlpapiupwork';
        $vision = new VisionClient([
            'projectId' => $projectId,
        ]);

        $imageData = file_get_contents($path);
        $image = new Image($imageData, [
            'TEXT_DETECTION',
        ]);

        $response = (object)$vision->annotate($image);

        if($response->info() != []){
            $info = (object)$response->info();
            $textAnnotations = (array)$info->textAnnotations;

            return $textAnnotations;
        }


    }

}
