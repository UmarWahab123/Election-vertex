<?php

namespace App\Http\Controllers;
use App\Models\PdfPolling;
use Illuminate\Http\Request;
use App\Mail\OfferMail;
use Mail;
use PDF;
use MPDF;
use DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\FontMetrics;

class MailController extends Controller
{
    public function sendOfferMail()
    {
        $result =new PdfPolling();
        $result = PdfPolling::where('status', 'PENDING')->where('cron_status',0)->first();
        $result->cron_status=1;
        $result->update();
        $status=$this->azzureApiResponseA($result);
        if ($status == true)
        {
            $result = PdfPolling::where('id',$result->id)->update(['status'=>'SENT']);
        }
    }

        public function azzureApiResponseA($result)
    {
        $blockcode=$result->block_code;
        $email=$result->email;
//        $email='muhammad.yaqoob180@gmail.com';
        $blocknumber = '';
        $pdfdata = array();
        if ($result){
            $blocknumber = DB::table('polling_details')->where('polling_station_number', $result->block_code)->get();
            foreach ($blocknumber as $key => $value) {
                $idcard[$key] = $value->cnic;

                $explode[$key] = explode('-', $idcard[$key]);
                $cnic[$key] = $explode[$key][0] . $explode[$key][1] . $explode[$key][2];
              dd($cnic[$key]);
                $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'search' => $cnic[$key]];

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
                $response = $result[0] ?? "";
                $pdfdata[$key]['fname'] = $response->firstname ?? "";
                $pdfdata[$key]['lname'] = $response->lastname ?? "";
                $pdfdata[$key]['idcard'] = $response->idcard ?? $cnic[$key];
                $pdfdata[$key]['Mobile'] = $response->phone1 ?? "";
                $pdfdata[$key]['address1'] = $response->address1 ?? "";
                $pdfdata[$key]['address2'] = $response->address2 ?? "";
                $pdfdata[$key]['address3'] = $response->address3 ?? "";
                $pdfdata[$key]['name'] = $pdfdata[$key]['fname'] . $pdfdata[$key]['lname'];
                $pdfdata[$key]['address'] = $pdfdata[$key]['address1'] . ' ' . $pdfdata[$key]['address2'] . ' ' . $pdfdata[$key]['address3'];
                $pdfdata[$key]['block'] =  $value->polling_station_number ?? "";

            }
        }
        $pdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
        $pdf = PDF::loadView('blockPdfDownload', compact('pdfdata'));

        $dataa["email"]='muhammad.yaqoob180@gmail.com';
        $dataa["client_name"]=$email;
        $dataa["subject"]='Vertex Report';
        $message = 'Vertex Expert';
        try{
            Mail::send('email.mail', $dataa, function($message)use($dataa,$pdf) {
                $message->to($dataa["email"], $dataa["client_name"])
                    ->subject($dataa["subject"])
                    ->attachData($pdf->output(), "vertex.pdf");
            });
        }catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (Mail::failures()) {
            $this->statusdesc  =   "Error sending mail";
            $this->statuscode  =   "0";
        }else{
            $this->statusdesc  =   "Message sent Succesfully";
            $this->statuscode  =   "1";
        }
        return true;




//        dd($data);
    }



    public function testsendPdf()
    {
//                $pdfdata=DB::table('temp_pdf_record')->where('pdf_poll_id','16')->update(['status'=>'1']);
//dd('submit');
        ini_set("pcre.backtrack_limit", "5000000");
        $email='muhammad.yaqoob180@gmail.com';
        $block = '188110704';
        $pdfdata=array();
        $pdfdata=DB::table('temp_pdf_record')->where('pdf_poll_id','21')->get();
        $pdf = MPDF::loadView('blockPdfDownload', compact('pdfdata','block'));
        $dataa["email"]=$email;
        $dataa["client_name"]='Election Experts';
        $dataa["subject"]='Vertex Report';
        $message = 'Vertex Expert';
        try{
            Mail::send('email.mail', $dataa, function($message)use($dataa,$pdf) {
                $message->to($dataa["email"], $dataa["client_name"])
                    ->subject($dataa["subject"])
                    ->attachData($pdf->output(), "vertex.pdf");
            });
        }catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (Mail::failures()) {
            $this->statusdesc  =   "Error sending mail";
            $this->statuscode  =   "0";
            return false;
        }else{
            $this->statusdesc  =   "Message sent Succesfully";
            $this->statuscode  =   "1";
            return true;
        }
    }
}
