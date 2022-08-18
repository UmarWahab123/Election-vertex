<?php

use App\Http\Controllers\Admin\PoliticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitingCardController;
use App\Http\Controllers\MobileViewController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\TestingControllerTwo;
use App\Http\Controllers\firebase\firebaseController;
use App\Http\Controllers\Admin\ParchiImageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TGCTestingController;
use App\Http\Controllers\ElectionExpertCoreController;
use App\Http\Controllers\Admin\VoterDetailsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/download_pdf_cron', function() {
    Artisan::call('pendingpdf:run');
    return true;
});

//Cron API's
Route::get('/cron_textract_api',                    [ElectionExpertCoreController::class , 'textractMultipleUrl'])->name('cron_textract_api');
Route::get('/cron_cloudinary_api',                  [ElectionExpertCoreController::class , 'cloudinaryOCR'])->name('cron_cloudinary_api');
Route::get('/cron_googleVision_api',                [ElectionExpertCoreController::class , 'googleVisionOCR'])->name('cron_googleVision_api');
Route::get('/cron_assignGender',                    [ElectionExpertCoreController::class , 'assignGender'])->name('assignGender');
Route::get('/cron_getFamilyAndSerialNo',            [ElectionExpertCoreController::class , 'getFamilyAndSerialNo'])->name('getFamilyAndSerialNo');
Route::get('/cron_crop_image_textract',             [ElectionExpertCoreController::class , 'cropImageTextract'])->name('crop_image_textract');
Route::get('/cron_crop_image_cloudinary',           [ElectionExpertCoreController::class , 'cropImageCloudinary'])->name('crop_image_cloudinary');
Route::get('/cron_convert_base64',                  [ElectionExpertCoreController::class , 'convertBase64'])->name('convertBase64');
Route::get('/cron_get_phone_number',                [ElectionExpertCoreController::class , 'getPhoneNumber'])->name('getPhoneNumber');
Route::get('/cron_get_phone_number_from_lambda',    [ElectionExpertCoreController::class , 'getPhoneLambda'])->name('getPhoneLambda');
Route::get('/cron_get_phone_number_backup',         [ElectionExpertCoreController::class , 'getPhoneNumberBackUp'])->name('getPhoneNumberBackUp');
Route::get('/generate_blockcode_report',         [ElectionExpertCoreController::class , 'getBlockcodeReport'])->name('getBlockcodeReport');
Route::get('/cron_generate_json_files',             [ElectionExpertCoreController::class , 'cron_generate_json_files'])->name('cron_generate_json_files');

Route::get('/download_json_files',                  [ElectionExpertCoreController::class , 'download_json_files'])->name('download_json_files');


Route::post('/check_firebase_url',                          [VisitingCardController::class , 'check_and_save_firebase_url'])->name('check_and_save_firebase_url');
Route::post('/check_sector_status',                         [VisitingCardController::class , 'check_sector_status'])->name('check_sector_status');
Route::post('/verify-payment',                              [VisitingCardController::class , 'verifyPaymentFromScreenshot'])->name('verifyPaymentFromScreenshot');
Route::get('/get-cards-per-user/{user_id}',                 [VisitingCardController::class , 'get_cards_per_user'])->name('get_cards_per_user');


Route::get('/Sector-detail-graph',                          [PoliticsController::class , 'Sectordetails'])->name('Sectordetails');
Route::get('/search_download_block',                        [PoliticsController::class , 'searchDownloadBlock'])->name('searchDownloadBlock');
Route::get('/download_block_pdf',                           [PoliticsController::class , 'sendPdfToMail'])->name('sendPdfToMail');


Route::get('/parchi-pdf-download/{idcard}/{party}',         [ParchiImageController::class , 'parchiPdf'])->name('parchiPdfDownload');
Route::get('/parchi-pdf-print/{idcard}/{party}',            [ParchiImageController::class , 'parchiPdfPrint'])->name('parchiPdfPrint');



//Route::get('/voter-parchi-view/{block_code}',               [firebaseController::class,'voterParchiView']);
//Route::get('/parchi-logo-pdf',                              [firebaseController::class,'voterParchiPdf']);
//Route::get('/cut_slice_from_pic/{blockcode}',           [VisitingCardController::class , 'cut_slice_from_pic'])->name('cut_slice_from_pic_cloudiery');
//Route::get('/cron-textract',                            [VisitingCardController::class , 'textract_multiple_url'])->name('textract_multiple_url');
//Route::get('/cron-cloudinery-extraction',               [VisitingCardController::class , 'process_invalid_page'])->name('process_invalid_page_api');
//Route::get('/cron-google-vision-extraction',            [VisitingCardController::class , 'process_invalid_page_404'])->name('process_invalid_page_404');
//Route::get('/cron-assign-gender',                       [VisitingCardController::class , 'assignGender'])->name('assignGender');
//Route::get('/crop-and-save-crop-image/{blockcode}',     [VisitingCardController::class , 'crop_and_save_crop_image'])->name('crop_and_save_crop_image');
//Route::get('/extract-urdu',                             [VisitingCardController::class , 'google_vision_API'])->name('extract_urdu');
//Route::get('/getExtraDetails',                          [VisitingCardController::class , 'getExtraDetails'])->name('getExtraDetailsApi');
//Route::get('/get_phone_number/{blockcode}',             [VisitingCardController::class , 'get_phone_number_block_code'])->name('get_phone_number_block_code');
//Route::get('/get_phone_number_202',                     [VisitingCardController::class , 'get_phone_number_202'])->name('get_phone_number_202');
// Route::get('/cut_slice_from_pic',                       [VisitingCardController::class , 'stop_cron'])->name('cut_slice_from_pic_cloudiery');
// Route::get('/get_phone_number',                         [VisitingCardController::class , 'stop_cron'])->name('get_phone_number');
// Route::get('/cron-api',                                 [VisitingCardController::class , 'stop_cron'])->name('textract_multiple_url');
//Route::get('/save_extra_details/{block_code}',          [TestingController::class , 'save_extra_details'])->name('save_extra_details');
Route::get('/checkFamilyAndSerialNo',                   [TestingController::class , 'checkFamilyAndSerialNo'])->name('checkFamilyAndSerialNo');
Route::get('/testing-usman',                   [TestingController::class , 'testingUsman'])->name('testingUsman');
Route::get('/get-detail',                   [TestingController::class , 'getAzureDetail'])->name('getAzureDetail');

Route::get('/download-files/{block_code}/{type}',                   [TestingController::class , 'dowmloadZip'])->name('dowmloadZip');


//Testing Routes for development purpose
Route::get('/textract_multiple_url',                     [TestingController::class , 'textract_multiple_url'])->name('textract_multiple_url');
Route::get('/crop_image_textract_api',                   [TestingController::class , 'cropImageTextract_api'])->name('cropImageTextract_api');
Route::get('/crop-image-cloudinary',                         [TestingController::class , 'cropImageCloudinary_api'])->name('cropImageCloudinary_api');
Route::get('/serial-family-update',                         [TestingController::class , 'SerialFamilyUpdate'])->name('SerialFamilyUpdate');
Route::get('/delete_details/{blockcode}',               [TestingController::class , 'delete_details'])->name('delete_details');
Route::get('/test-api',                                 [TestingController::class , 'save_firebase_url'])->name('save_firebase_url');
Route::get('/get-pdf-view/{block_code}',                [TestingController::class , 'getPdfView'])->name('getPdfView');
Route::get('/get-pdf-view-backUp/{block_code}',         [TestingControllerTwo::class , 'getPdfViewBackUp'])->name('getPdfViewBackUp');
Route::get('/get-export-json-data',                     [TestingControllerTwo::class , 'blockcodeExportJson'])->name('blockcodeExportJson');
Route::get('/get-blockcode-report',                     [TestingController::class , 'wardreport'])->name('wardreport');
Route::get('/regex-test-api',                           [TestingController::class , 'regex_test'])->name('regex_test');
Route::get('/testraw',                                  [TestingController::class , 'testraw'])->name('testraw');
Route::get('/election-expert-get-sectors',              [TestingController::class , 'getElectionSector'])->name('getElectionSector');
Route::get('/voterParchiNew',                           [TestingController::class , 'voterParchiNew'])->name('voterParchiNew');
Route::get('/rotateImage',                              [TestingControllerTwo::class , 'rotateImage'])->name('rotateImage');
Route::get('/rawquery',                                 [TestingControllerTwo::class , 'rawquery'])->name('rawquery');
Route::get('/cloudinary-OCR',                           [TGCTestingController::class , 'cloudinaryOCR_api'])->name('cloudinaryOCR');
Route::get('/google-vision-OCR',                        [TGCTestingController::class , 'googleVisionOCR_api'])->name('googleVisionOCRApi');
Route::get('/assign-gender-api',                        [TGCTestingController::class , 'assignGender_api'])->name('assignGenderApi');
Route::post('identify/',                                [TestingControllerTwo::class , 'identify'])->name('identify');
Route::get('/textract_image_crop',                      [TGCTestingController::class , 'textract_multiple_url']);
Route::get('/textract_image_slice_crop',                [TGCTestingController::class , 'cropImageTextract_api']);
Route::post('/voter-details-json-record',                [VoterDetailsController::class , 'voterDataApi']);

//Election expert

Route::prefix('election-expert')->namespace('App\Http\Controllers')->group(static function() {
    Route::get('/voter-parchi/{idcard}/{sector}',                           'MobileViewController@voterParchi');
    Route::get('/family-parchi/{familyNo}/{blockCode}/{cnic}',              'MobileViewController@familyParchi');
});
//Reporting

Route::prefix('election-reports')->namespace('App\Http\Controllers')->group(static function() {
    Route::get('/pti-users',                        'ReportController@ptiUsers');
    Route::get('/ppp-users',                        'ReportController@pppUsers');
});


Route::prefix('data-set')->namespace('App\Http\Controllers\Admin')->group(static function() {
    Route::get('/broadcast-query/{tag}',                     'DataSetController@broadcastQuery')->name('broadcast-query');

});


Route::prefix('election-expert')->namespace('App\Http\Controllers\Admin')->group(static function() {
    Route::post('/login-uploading-member',                           'S3uploadingMembersController@loginMember');
 });




////////////////
Route::prefix('usman')->group(static function() {
    Route::get('/dev-test',                           'App\Http\Controllers\Dev\TestController@testRaw');
    Route::post('/test-post',                           'App\Http\Controllers\Dev\TestController@testPost');
    Route::match(['get', 'post'], '/v-test', 'App\Http\Controllers\ElectionExpertCoreController@testVision')->name('testVision');


});




