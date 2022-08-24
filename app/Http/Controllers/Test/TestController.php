<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ElectionSector;
use App\Models\ParchiImage;
use App\Models\PdfPolling;
use App\Models\PollingScheme;
use App\Models\PollingStation;
use App\Models\PollingDetail;
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

class TestController extends Controller
{
 public function voterParchi($blockcode,$type)
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

    // $meta =  $pdfPolling->meta;
    // dd($meta);
    // $meta =  json_decode($meta);
    // $ids = $meta->ids;
    $block_code = $pdfPolling->block_code;
    $electionSector = ElectionSector::where('block_code', $block_code)->first();

    $parchiImages = ParchiImage::where('block_code', $block_code)->where('Party', $pdfPolling->party_type)->first();
    // dd($parchiImages);
    $polling_details = PollingDetail::where('polling_station_number', $block_code)
        ->where('gender', $meta->gender)
        ->with('SchemeAddress' , 'voter_phone' , 'sector')
        ->whereIn('id',$ids)
        ->get();
  }
  
}
