<?php

namespace App\Console\Commands;

use App\Models\ElectionExpertCore;
use Illuminate\Console\Command;

use App\Http\Controllers\Controller;
use App\Models\ElectionSector;
use App\Models\ParchiImage;
use App\Models\PdfPolling;
use App\Models\PollingScheme;
use App\Models\PollingStation;
use App\Models\PollingDetail;
use Illuminate\Http\Request;
use App\Models\UsersChat;
use App\Models\User;
use App\Models\UserMessage;
use App\Models\BusinessProfile;
use App\Models\PdfPollingLog;
use App\Models\FirebaseUrl;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
Use Redirect;
use Illuminate\Support\Facades\Storage;
use Auth;
use PDF;
use MPDF;
use App\Mail\OfferMail;
use Mail;
use Google\Cloud\Vision\VisionClient;
use Google\Cloud\Vision\Image;
use Cloudinary\Configuration\Configuration;
use App\Http\Traits\electionExpertTrait;

class PendingPdfSecond extends Command
{
    public $logSwitch = false;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pendingpdfsecond:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->electionExpert = new ElectionExpertCore();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){


        $this->voterParchiPdf();
        //$this->electionExpert->getFamilyAndSerialNo_api();

        return true;
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

            $link = 'https://vertex.plabesk.com/api/download-files/'.$sendMailDetails->block_code;

            $message_send = 'Click on the link for download '.$link.'  Conclusion of Block Code :'.$sendMailDetails->block_code. ' of Sector : '.$sendMailDetails->sector .' Total Expected Sent Mails : '.$sendMailDetails->total_expected_sent_mails. '. Total records found : '.$sendMailDetails->total_records_found.  '. Male Record : '.$sendMailDetails->total_male_records_found.  '. Female Record : '.$sendMailDetails->total_female_records_found.'.';

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

    //////////////////////////////////////////////////////////////////
    ///



    /// Cron Job For Serial and Family Number -> Function from Trait




}




