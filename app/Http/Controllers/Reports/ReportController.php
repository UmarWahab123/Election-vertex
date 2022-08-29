<?php

namespace App\Http\Controllers\Reports;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ElectionSector;
use App\Models\FirebaseUrl;
use App\Models\PollingDetail;
use App\Models\OfflineDataFile;
use App\Models\PdfPolling;
use Carbon\Carbon;
class ReportController extends Controller
{
  public function reports()
    {
     return view('reports.graph.processingreport');
    }

  public function filterProcessing($start_date,$end_date){
    $pending =  FirebaseUrl::whereBetween('created_at', [$start_date, $end_date])
    ->whereCron(0)
    ->count();
     
    $text_ract_middle =   FirebaseUrl::whereBetween('created_at', [$start_date, $end_date])
    ->whereCron(2)
    ->count(); 

    $pending_for_cloudnary =   FirebaseUrl::whereBetween('created_at', [$start_date, $end_date])
    ->whereStatus(3)
    ->where('image_url','not like','%main%')
    ->count();

    $cloudnary_middle =   FirebaseUrl::whereBetween('created_at', [$start_date, $end_date])
    ->whereStatus(19)
    ->where('image_url','not like','%main%')
    ->count();

    $pending_for_vision =   FirebaseUrl::whereBetween('created_at', [$start_date, $end_date])
        ->where('image_url','not like','%main%')
    ->whereStatus(404)
     ->count();

    $blockcode =   FirebaseUrl::whereBetween('created_at', [$start_date, $end_date])
     ->where('image_url','like','%main%')
    ->count();


    $total_pages =   FirebaseUrl::whereBetween('created_at', [$start_date, $end_date])

        ->count();


    $data = PollingDetail::whereBetween('created_at', [$start_date, $end_date])
        ->count();

    $pending_phone = PollingDetail::whereBetween('created_at', [$start_date, $end_date])
        ->whereIn('status',[0,1])
        ->count();

    $found_phone = PollingDetail::whereBetween('created_at', [$start_date, $end_date])
        ->whereStatus(3)
        ->count();

    $notfound_phone = PollingDetail::whereBetween('created_at', [$start_date, $end_date])
        ->whereStatus(2)
        ->count();

    $pending_picslice = PollingDetail::whereBetween('created_at', [$start_date, $end_date])
    ->where('pic_slice',null)
    ->count();

    $pending_serial = PollingDetail::whereBetween('created_at', [$start_date, $end_date])
    ->where('pic_slice','!=',null)
    ->where('serial_cron',0)
    ->count();
    $serial_done = PollingDetail::whereBetween('created_at', [$start_date, $end_date])
    ->where('serial_cron',1)
    ->count();

    $serial_rejected = PollingDetail::whereBetween('created_at', [$start_date, $end_date])
    ->where('serial_cron',207)
    ->count();

    $pending_gender = PollingDetail::whereBetween('created_at', [$start_date, $end_date])
        ->where('gender','undefined')
        ->count();

    $gender_done = PollingDetail::whereBetween('created_at', [$start_date, $end_date])
        ->where('gender','!=','undefined')
        ->count();

    $mail_sent = PdfPolling::whereBetween('created_at', [$start_date, $end_date])
    ->where('status','SENT')
    ->get()->unique('block_code')
    ->count();

    $mail_pending = PdfPolling::whereBetween('created_at', [$start_date, $end_date])
    ->where('status','PENDING')
     ->count();

    $mail_middle_state = PdfPolling::whereBetween('created_at', [$start_date, $end_date])
    ->where('status','!=','PENDING')
    ->where('status','!=','SENT')
     ->count();

      $processingDataset = array(
        array("y" => $pending, "label" => 'Pending'),
        array("y" => $text_ract_middle, "label" => 'TextRact Middle State (2)'),
        array("y" => ($total_pages - $blockcode ), "label" => 'Total Processed Pages'),
        array("y" => $pending_for_cloudnary, "label" => 'Pending For Cloudnary (Invalid)'),
        array("y" => $cloudnary_middle, "label" => 'Cloudnary Middle (19)'),
        array("y" => $pending_for_vision, "label" => 'Pending For Vision Api (404)'),
        array("y" => $data, "label" => 'Processed Voters'),
        array("y" => $blockcode, "label" => 'Total Blockcodes'),
        array("y" => $pending_phone, "label" => 'Pending Phone'),
        array("y" => $found_phone, "label" => 'Phone Found'),
        array("y" => $found_phone + $notfound_phone, "label" => 'Phone Processed'),
        array("y" => $notfound_phone, "label" => 'Phone Not Found'),
        array("y" => $pending_picslice, "label" => 'Pending Pic Slice'),
        array("y" => $pending_serial, "label" => 'Pending Serial'),
        array("y" => $serial_rejected, "label" => 'Rejected Serial & F'),
        array("y" => $serial_done, "label" => 'Serial Processed'),
        array("y" => $pending_gender, "label" => 'Gender Pending'),
        array("y" => $mail_sent, "label" => 'Blockcode Dispatched by Mail'),
        array("y" => $mail_pending, "label" => 'Blockcode Files Pending Mail'),
        array("y" => $mail_middle_state, "label" => 'Blockcode Files In Processing Mail'),
    );
    // dd($processingDataset);
    // $response = view('reports.graph.components.processing-graph', compact('processingDataset'))->render();
    $response = array('response' =>$processingDataset);
    return json_encode($response);
    // return view('reports.graph.components.processing-graph',compact('processingDataset'));
   }
}
