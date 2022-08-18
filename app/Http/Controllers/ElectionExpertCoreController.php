<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Traits\electionExpertTrait;
use App\Models\ElectionExpertCore;
use App\Models\FirebaseUrl;
use App\Models\OfflineDataFile;
use App\Models\CurlSwitch;
use App\Models\PollingDetail;
use App\Models\ElectionSector;
use App\Models\PollingStation;
use App\Models\VoterPhone;
use Illuminate\Http\Request;
use Aws\Textract\TextractClient;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\VisionClient;
use Google\Cloud\Vision\Image;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Cloudinary;
use Auth;
use DB;

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/one-call-59851-40f314185b47.json');


class ElectionExpertCoreController extends Controller
{
    public $electionExpert;

    function __construct()
    {
        $this->electionExpert = new ElectionExpertCore();
        if (!@$_GET['token']) {
            abort(404);
        }
        $api_token =  Helper::GET_API_KEY();
        if (@$_GET['token'] === $api_token) {
            $this->middleware(function ($request, $next) {
                return $next($request);
            });
        } else {
            abort(404);
        }
    }

    //----------------------------------//
    //             AWS TEXTRACT         //
    //----------------------------------//

    //Cron textract API call this method which took 10 firebase urls and process them
    //Update status of page accordingly
    public function textractMultipleUrl()
    {
        return $this->electionExpert->textractMultipleUrl_api();
    }

    //---------------------------------------------------------//
    //             CLOUDINARY WITH GOOGLE VISION OCR           //
    //---------------------------------------------------------//

    //Cron Cloudinary Extraction API calls this method which took 10 firebase urls and process them
    //Select the urls/images which are marked invalid by Textract
    //Connect with cloudinary
    public function cloudinaryOCR()
    {
        return $this->electionExpert->cloudinaryOCR_api();
    }

    //----------------------------------------//
    //             GOOGLE VISION OCR          //
    //----------------------------------------//

    //Cron Google Vision Extraction API calls this method which took 5 firebase urls and process them
    //Select the urls/images which are marked rejected by Cloudinary
    public function googleVisionOCR()
    {
        return $this->electionExpert->googleVisionOCR_api();
    }

    //----------------------------------------//
    //            CROPPING OF PICTURE         //
    //----------------------------------------//

    //Crop slice from complete page picture against cnic number
    //Crop slice of pages which are read by Textract OCR
    public function cropImageTextract()
    {
        return $this->electionExpert->cropImageTextract_api();
    }

    //Crop slice from complete page picture against cnic number
    //Crop slice of pages which are read by Google Vision OCR
    public function cropImageCloudinary()
    {
        return $this->electionExpert->cropImageCloudinary_api();
    }

    //----------------------------------------//
    //             SIDE FUNCTIONALITY         //
    //----------------------------------------//

    //Assign the gender to voters on the basis of last digit of there CNIC
    public function assignGender()
    {
        return $this->electionExpert->assignGender_api();
    }

    //Read the serial number and family number using textract and google vision OCR and update against the CNIC
    public function getFamilyAndSerialNo()
    {
        return $this->electionExpert->getFamilyAndSerialNo_api();
    }


    public function getPhoneNumber()
    {
        //Get phone numbers of voter from Azure against there cnic
        //return $this->electionExpert->getPhoneNumber_api();


        //Get phone numbers of voter from Lambda S3 against there cnic
         return $this->electionExpert->getPhoneNumber_lambda();
    }


    public function getPhoneLambda()
    {
        // phone number which stay in middle state 202 fetch data from Lambda S3
        return $this->electionExpert->getPhoneNumberBackUp_api();
    }


    public  function search_by_cnic($result, $search_string)
    {
        $phone_numbers = [];

        foreach ($result as $key => $res) {


            if(count($phone_numbers) > 5){
                break;
            }
            if ($res['idcard'] == $search_string) {
                $phone_numbers[] = $res;
            }
        }


        return $phone_numbers;
    }

    //Convert images to base 64 and save in polling_details_images table in DB
    public function convertBase64()
    {
        return $this->electionExpert->convertBase64_api();
    }
//Generate Block Code Report with respect Election Sector Detail
    public function getBlockcodeReport()
    {
        return $this->electionExpert->getBlockcodeReport_api();
    }

    //----------------------------------------//
    //             Download JSON files        //
    //----------------------------------------//

    public function download_json_files()
    {

        $cron_switch = CurlSwitch::where('name', 'download_files')->first();

        if ($cron_switch->status == 1) {
            $ward = OfflineDataFile::where('cron', 0)->first();
            $type = $ward->type;
            $ward_name = preg_replace("/[^a-zA-Z]+/", "", $ward);
            $ward_no = preg_replace("/[^0-9]+/", "", $ward);

            $file = base_path('public/' . $ward_name . '_' . $ward_no . '_' . $type . '.json');

            if (file_exists($file)) {
                $ward->cron = 1;
                $ward->state = 'downloaded';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                $ward->update();
                exit;
            } else {
                $ward->cron = 2;
                $ward->state = 'file_not_found';
                $ward->update();
                return 1;
            }
        } else {
            return 0;
        }
    }

    public function cron_generate_json_files()
    {
        $cron_switch = CurlSwitch::where('name', 'generate_offline_files')->first();

        if ($cron_switch->status == 1) {

            $offline_data_file = OfflineDataFile::where('cron', 0)->first();
            $ward = preg_replace("/[^a-zA-Z]+/", "", $offline_data_file->ward);
            $number = preg_replace("/[^0-9]+/", "", $offline_data_file->ward);

            ini_set("pcre.backtrack_limit", "50000000");
            ini_set("memory_limit", "-1");
            ini_set("max_execution_time", "0");
            $sectors = ElectionSector::where('sector', 'like', $ward . ' ' . $number)
                ->get()
                ->groupby('block_code');

            if (count($sectors) > 0) {
                foreach ($sectors as $sector) {
                    $block_code_numbers = $sector->pluck('block_code');
                    $polling_details = PollingDetail::whereIn('polling_station_number', $block_code_numbers)
                        ->with('SchemeAddressmulti:block_code,ward,polling_station_area_urdu,gender_type,serial_no')
                        ->with('polling_details_images:polling_detail_id,name_pic')
                        ->select('id', 'polling_station_number', 'cnic', 'gender', 'serial_no', 'family_no', 'url')
                        ->get();

                    $detail_chunk = [];
                    $image_chunk = [];

                    $check = 1;
                    $temp = [];
                    $temp1 = [];
                    foreach ($polling_details as $key => $data) {
                        if (count($data->SchemeAddressmulti) > 1) {
                            if ($data->SchemeAddressmulti[0]->gender_type == $data->gender) {
                                $data->polling_station_area_urdu = $data->SchemeAddressmulti[0]->polling_station_area_urdu;
                                $data->poll_serial_no = $data->SchemeAddressmulti[0]->serial_no;
                                $data->ward = $data->SchemeAddressmulti[0]->ward;
                                $data->poll_gender = $data->SchemeAddressmulti[0]->gender_type;
                            }
                            if ($data->SchemeAddressmulti[1]->gender_type == $data->gender) {
                                $data->polling_station_area_urdu = $data->SchemeAddressmulti[1]->polling_station_area_urdu;
                                $data->poll_serial_no = $data->SchemeAddressmulti[1]->serial_no;
                                $data->ward = $data->SchemeAddressmulti[1]->ward;
                                $data->poll_gender = $data->SchemeAddressmulti[1]->gender_type;
                            }
                        } else {
                            $data->polling_station_area_urdu = $data->SchemeAddressmulti[0]->polling_station_area_urdu;
                            $data->poll_serial_no = $data->SchemeAddressmulti[0]->serial_no;
                            $data->ward = $data->SchemeAddressmulti[0]->ward;
                            $data->poll_gender = $data->SchemeAddressmulti[0]->gender_type;
                        }

                        if ($check == 100) {
                            $temp1[] = $data->polling_details_images;
                            unset($data->SchemeAddressmulti);
                            unset($data->url);
                            unset($data->crop_settings);
                            unset($data->polling_details_images);
                            $temp[] = $data;

                            $detail_chunk[] = $temp;
                            $image_chunk[] = $temp1;
                            $temp = [];
                            $temp1 = [];
                            $check = 0;
                        } else {
                            $temp1[] = $data->polling_details_images;
                            unset($data->SchemeAddressmulti);
                            unset($data->url);
                            unset($data->crop_settings);
                            unset($data->polling_details_images);
                            $temp[] = $data;
                        }

                        $check++;
                    }

                    if (count($temp) > 0) {
                        $detail_chunk[] = $temp;
                        $image_chunk[] = $temp1;
                    }

                    if (count($detail_chunk) === count($image_chunk)) {
                        foreach ($detail_chunk as $item) {
                            $encode_chunk = json_encode($item, JSON_UNESCAPED_UNICODE);
                            file_put_contents(base_path('public/' . $ward . '_' . $number . '_info.json'), $encode_chunk . "\n", FILE_APPEND);
                        }

                        foreach ($image_chunk as  $item1) {
                            $encode_image_chunk = json_encode($item1, JSON_UNESCAPED_UNICODE);
                            file_put_contents(base_path('public/' . $ward . '_' . $number . '_image.json'), $encode_image_chunk . "\n", FILE_APPEND);
                        }
                    } else {
                        return $ward . ' ' . $number . ' not saved';
                    }
                }

                $offline_data_file->cron = 1;
                $offline_data_file->state = 'file_generated';
                $offline_data_file->update();

                return 1;
            }
            else {
                $offline_data_file->cron = 2;
                $offline_data_file->state = 'incorrect_sector_name_format';
                $offline_data_file->update();

                return 0;
            }
        } else {
            return 0;
        }
    }




    /// test vision
    ///
    ///
    ///
    ///


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
