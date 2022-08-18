<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\VisitingCardController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\TestingControllerTwo;

/*
use App\Http\Controllers\Admin\RolePermissionController;*/
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('admin.login');
});


Route::get('/get-login', function () {
    return view('admin.login');
});

Route::get('/visiting-card-textract',                       [VisitingCardController::class , 'visiting_card_textract'])->name('card-details-textract');
Route::post('/visiting-card',                               [VisitingCardController::class , 'saveCardDetails'])->name('saveCardDetails');
Route::get('/visiting-card',                                [VisitingCardController::class , 'saveCardDetails']);
Route::get('/visiting-card-details',                        [VisitingCardController::class , 'getCardDetails'])->name('getCardDetails');
Route::get('/get-cnic-with-age',                            [VisitingCardController::class , 'get_cnic_with_age'])->name('get_cnic_with_age');
Route::get('/get-cnic-with-family',                         [VisitingCardController::class , 'get_cnic_with_family'])->name('get_cnic_with_family');
Route::get('/getExtraDetails',                              [VisitingCardController::class , 'getExtraDetails'])->name('getExtraDetails');
Route::get('/get_url_from_log',                             [VisitingCardController::class , 'get_url_from_log'])->name('get_url_from_log');
Route::get('/cut-slice-from-pic',                           [VisitingCardController::class , 'cut_slice_from_pic'])->name('cut_slice_from_pic');
Route::get('/cut_slice_of_invalid',                         [VisitingCardController::class , 'cut_slice_of_invalid'])->name('cut_slice_of_invalid');
Route::post('/auto_textract_cloudinery',                    [VisitingCardController::class , 'auto_textract_cloudinery'])->name('auto_textract_cloudinery');
Route::get('/process_invalid_page',                         [VisitingCardController::class , 'process_invalid_page'])->name('process_invalid_page');
Route::get('/json-data-entry',                              [TestingController::class , 'testingJson'])->name('testingJson');
Route::get('/pdf-download-list',                            [TestingController::class , 'PdfDownload'])->name('PdfDownloadList');
Route::get('/pdf-download-list-cron',                       [TestingController::class , 'paidvoterlist'])->name('paidvoterlist');


Route::get('/election-expert-voter-detail',                 [TestingController::class , 'crop_and_save_crop_image'])->name('google_vision_API');
Route::get('/crop-image',                                   [TestingController::class , 'crop_and_save_crop_image'])->name('crop_image');
Route::get('/voter-card',                                   [TestingController::class , 'voter_card'])->name('voter_card');
Route::get('/urdu-keyboard',                                [TestingController::class , 'urdu_keyboard'])->name('urdu_keyboard');
Route::get('/voterDetailEntry/{blockcode}',                 [TestingController::class , 'voterDetailEntry'])->name('voterDetailEntry');
Route::get('/voterDetailEntryRecheck/{blockcode}',           [TestingControllerTwo::class , 'voterDetailEntryRecheck'])->name('voterDetailEntryRecheck');

Route::namespace('App\Http\Controllers\HrServices')->group(static function() {
    Route::prefix('hrservices')->group(static function() {
        Route::get('/chooseCategory/{bid}/{uid}/{category}',   'HrController@chooseCategory')->name('chooseCategory');
        Route::get('/employeeSignup/{bid}/{uid}',              'HrController@employeeSignup')->name('employeeSignup');
    });
});

Route::namespace('App\Http\Controllers')->group(static function() {
    Route::prefix('verification')->group(static function() {
        Route::post('/otp',                             'VerificationController@verify_user')->name('verify-user');
        Route::post('/verify-otp',                      'VerificationController@verify_otp')->name('verify-otp');
        Route::post('/register',                        'VerificationController@register')->name('verify-register');
    });
});

/* Auto-generated admin routes */
/*Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin.')->group(static function() {
        Route::resource('role-permission', RolePermissionController::class);
    });
});*/
Route::get('/cache-clear', function() {
    Artisan::call('cache:clear');
    return "Application cache cleared!";
});
Route::get('/route-clear', function() {
    Artisan::call('route:cache');
    return "Route cache cleared!";
});
Route::get('/optimize', function() {
    Artisan::call('optimize');
    return "optimized!";
});

Route::get('/ajax-hit', function() {
   return view('ajax-hit');

});



Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('users')->name('users/')->group(static function () {
            Route::post('send-sms', 'UsersController@sendSMS')->name('sendSMS');
            Route::post('sendNotification', 'UsersController@sendNotification')->name('sendNotification');
        });
    });
});

/* image Upload routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\firebase')->group(static function() {
        Route::prefix('firebase')->group(static function() {
            Route::get('/imageUpload',                           'firebaseController@index')->name('imageUpload');
            Route::get('/categorizeImageUpload',                 'firebaseController@categorizeImageUpload')->name('categorizeImageUpload');
            Route::get('/testingImageUpload',                    'firebaseController@testingImageUpload');
            Route::get('/parchi-logo-image',                     'firebaseController@ParchiImage')->name('ParchiImage');
            Route::get('/parchi-logo-pdf',                       'firebaseController@voterParchiPdf')->name('voterParchiPdf');
            Route::get('/voter-parchi-view/{block_code}',        'firebaseController@voterParchiView')->name('voterParchiView');
            Route::post('/parchi-image-upload',                  'firebaseController@parchiimgupload')->name('parchiimgupload');
            Route::get('/halqa-index',                           'firebaseController@indexElection')->name('indexElection');
            Route::post('/halqa-save',                           'firebaseController@saveElection')->name('saveElection');
            Route::get('/update-address-form',                   'firebaseController@updateAddressForm')->name('updateAddressForm');
            Route::post('/update-address-details',               'firebaseController@updateAddress')->name('updateAddress');
            Route::get('/testing-update-address-form',           'firebaseController@testingUpdateAddressForm')->name('testingUpdateAddressForm');
            Route::get('/create-full-page-entries/{urlId}',      'firebaseController@createFullPageEntries')->name('createFullPageEntries');
            Route::get('/check-and-update',                      'firebaseController@saveFullPageEntries')->name('saveFullPageEntries');
            Route::get('/testingFunction',                       'firebaseController@testingFunction')->name('testingFunction');


        });
    });
});
/*Data view routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->group(static function() {
        Route::prefix('election')->group(static function() {
            Route::get('/ViewReport',                       'PoliticsController@idCardView')->name('electionDataView');
            Route::get('/searchIdCardReport',               'PoliticsController@idCardreport')->name('searchIdCardReport');
            Route::get('/search-id-card-parchi',            'PoliticsController@searchIdCardParchi')->name('searchIdCardParchi');
            Route::get('/searchPollingReport',              'PoliticsController@idCardpolling')->name('searchPollingReport');
            Route::get('/getIdcardResult/{id}',             'PoliticsController@getidcard')->name('getidcardResult');
            Route::get('/downloadPdfuser/{id}',             'PoliticsController@downloadPdfuser')->name('downloadPdfuser');
            Route::post('/blockDetailSearchPdf',            'PoliticsController@blockDetailSearchPdf')->name('blockDetailSearchPdf');
            Route::get('/datalistview',                     'PoliticsController@datalistview')->name('datalistview');
            Route::get('/azzureform',                       'ElectionSectorController@azzureForm')->name('azzureform');
            Route::get('/polling-parchi-view',              'PdfPollingController@ParchiIndex')->name('ParchiIndexView');
            Route::post('/parchi-block-pdf-download',       'PdfPollingController@blockDetailPdf')->name('parchiBlockPdfDownload');
            Route::get('/daily-mail-graph',                 'PdfPollingController@dailyMailGraph')->name('dailyMailGraph');
            Route::get('/voter-parchi-view/{block_code}',   'PdfPollingController@voterParchiView');
//        Serial Update
            Route::get('/list-code-serial',               'PdfDownloadController@listViewIndex')->name('listViewSerialIndex');
            Route::post('/add-block-code-serial',         'PdfDownloadController@listViewStore')->name('listViewSerialStore');


        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('admin-users')->name('admin-users/')->group(static function() {
            Route::post('/search-sector',                               'AdminUsersController@sectorReportDetail')->name('sectorReportDetail');
            Route::post('/search-blockCode',                            'AdminUsersController@search_block_code')->name('search_block_code');
            Route::get('/block-code-graph',                              'AdminUsersController@block_code_graph')->name('block_code_graph');
            Route::get('/',                                             'AdminUsersController@index')->name('index');
            Route::get('/create',                                       'AdminUsersController@create')->name('create');
            Route::post('/',                                            'AdminUsersController@store')->name('store');
            Route::get('/{adminUser}/impersonal-login',                 'AdminUsersController@impersonalLogin')->name('impersonal-login');
            Route::get('/{adminUser}/edit',                             'AdminUsersController@edit')->name('edit');
            Route::post('/{adminUser}',                                 'AdminUsersController@update')->name('update');
            Route::delete('/{adminUser}',                               'AdminUsersController@destroy')->name('destroy');
            Route::get('/{adminUser}/resend-activation',                'AdminUsersController@resendActivationEmail')->name('resendActivationEmail');
            Route::get('/dashboard',                                    'AdminUsersController@dashboard')->name('dashboard');
            Route::get('/reporting',                                    'AdminUsersController@reporting')->name('reporting');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::get('/profile',                                      'ProfileController@editProfile')->name('edit-profile');
        Route::post('/profile',                                     'ProfileController@updateProfile')->name('update-profile');
        Route::get('/password',                                     'ProfileController@editPassword')->name('edit-password');
        Route::post('/password',                                    'ProfileController@updatePassword')->name('update-password');
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('roles')->name('roles/')->group(static function() {
            Route::get('/',                                             'RolesController@index')->name('index');
            Route::get('/create',                                       'RolesController@create')->name('create');
            Route::post('/',                                            'RolesController@store')->name('store');
            Route::get('/{role}/edit',                                  'RolesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'RolesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{role}',                                      'RolesController@update')->name('update');
            Route::delete('/{role}',                                    'RolesController@destroy')->name('destroy');
        });
    });
});

/* Mobile Screen routes */
Route::prefix('admin')->namespace('App\Http\Controllers')->group(static function() {
    Route::prefix('mobileView')->group(static function() {
        Route::get('/{id}',                         'MobileViewController@index')->name('indexMobileView');
        Route::Post('/userRegistration',            'MobileViewController@Registration')->name('userRegistration');
        Route::get('/businessPageView/{id}/{phone}','MobileViewController@classdetails')->name('classDetailView');
        Route::get('/userMobileDetails/{uuid}/{business_id}','MobileViewController@userAppRecord')->name('userMobileDetails');
        Route::get('/edit/{id}/{phone}',            'MobileViewController@edit')->name('editMobileView');
        Route::Post('/ProfileUpdate',               'MobileViewController@update')->name('ProfileUpdate');
        Route::get('/azzure/search',                'MobileViewController@AzzureSearch')->name('AzzureSearch');

    });
});

/* Mobile Screen routes */
Route::namespace('App\Http\Controllers')->group(static function() {
    Route::prefix('SearchList')->group(static function() {
        Route::get('/searchIdCard',         'MobileViewController@searchIdCard')->name('searchIdCard');
        Route::get('/VoterList/{card}',     'MobileViewController@VoterList')->name('VoterList');
        Route::get('/familytreeList/{familyNo}/{blockCode}/{cnic}','MobileViewController@familyList')->name('familyList');
        Route::post('/login',               'MobileViewController@login')->name('SearchListLogin');

    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('assets')->name('assets/')->group(static function() {
            Route::get('/',                                             'AssetController@index')->name('index');
            Route::get('/create',                                       'AssetController@create')->name('create');
            Route::get('/createhtml',                                   'AssetController@createhtml')->name('createhtml');
            Route::post('/',                                            'AssetController@store')->name('store');
            Route::get('/{asset}/edit',                                 'AssetController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'AssetController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{asset}',                                     'AssetController@update')->name('update');
            Route::delete('/{asset}',                                   'AssetController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('user-images')->name('user-images/')->group(static function() {
            Route::get('/',                                             'UserImageController@index')->name('index');
            Route::get('/create',                                       'UserImageController@create')->name('create');
            Route::post('/',                                            'UserImageController@store')->name('store');
            Route::get('/{userImage}/edit',                             'UserImageController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'UserImageController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{userImage}',                                 'UserImageController@update')->name('update');
            Route::delete('/{userImage}',                               'UserImageController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('tags')->name('tags/')->group(static function() {
            Route::get('/',                                             'TagsController@index')->name('index');
            Route::get('/create',                                       'TagsController@create')->name('create');
            Route::post('/',                                            'TagsController@store')->name('store');
            Route::get('/{tag}/edit',                                   'TagsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'TagsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{tag}',                                       'TagsController@update')->name('update');
            Route::delete('/{tag}',                                     'TagsController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('general-notices')->name('general-notices/')->group(static function() {
            Route::get('/',                                             'GeneralNoticeController@index')->name('index');
            Route::get('/create',                                       'GeneralNoticeController@create')->name('create');
            Route::get('/create_html',                                  'GeneralNoticeController@createhtml')->name('createHtml');
            Route::post('/',                                            'GeneralNoticeController@store')->name('store');
            Route::get('/{generalNotice}/edit',                         'GeneralNoticeController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'GeneralNoticeController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{generalNotice}',                             'GeneralNoticeController@update')->name('update');
            Route::delete('/{generalNotice}',                           'GeneralNoticeController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('users')->name('users/')->group(static function() {
            Route::get('/',                                             'UsersController@index')->name('index');
            Route::get('/create',                                       'UsersController@create')->name('create');
            Route::post('/',                                            'UsersController@store')->name('store');
            Route::get('/{user}/edit',                                  'UsersController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'UsersController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{user}',                                      'UsersController@update')->name('update');
            Route::delete('/{user}',                                    'UsersController@destroy')->name('destroy');
            Route::get('/create-sms',                                   'UsersController@createSMS')->name('createSMS');
            Route::get('/create-notification',                          'UsersController@createNotification')->name('createNotification');
            Route::get('/user-message-chat/{user-id}',                  'UsersController@usermessage');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('page-settings')->name('page-settings/')->group(static function() {
            Route::get('/',                                             'PageSettingController@index')->name('index');
            Route::get('/create',                                       'PageSettingController@create')->name('create');
            Route::post('/',                                            'PageSettingController@store')->name('store');
            Route::get('/{pageSetting}/edit',                           'PageSettingController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'PageSettingController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{pageSetting}',                               'PageSettingController@update')->name('update');
            Route::delete('/{pageSetting}',                             'PageSettingController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('visiting-cards')->name('visiting-cards/')->group(static function() {
            Route::get('/',                                             'VisitingCardsController@index')->name('index');
            Route::get('/{status}',                                       'VisitingCardsController@index')->name('indexApproved');
            Route::get('/create',                                       'VisitingCardsController@create')->name('create');
            Route::post('/',                                            'VisitingCardsController@store')->name('store');
            Route::get('/{visitingCard}/edit',                          'VisitingCardsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'VisitingCardsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{visitingCard}',                              'VisitingCardsController@update')->name('update');
            Route::delete('/{visitingCard}',                            'VisitingCardsController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('visiting-card-images')->name('visiting-card-images/')->group(static function() {
            Route::get('/',                                             'VisitingCardImagesController@index')->name('index');
            Route::get('/create',                                       'VisitingCardImagesController@create')->name('create');
            Route::post('/',                                            'VisitingCardImagesController@store')->name('store');
            Route::get('/{visitingCardImage}/edit',                     'VisitingCardImagesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'VisitingCardImagesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{visitingCardImage}',                         'VisitingCardImagesController@update')->name('update');
            Route::delete('/{visitingCardImage}',                       'VisitingCardImagesController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('firebase-urls')->name('firebase-urls/')->group(static function() {
            Route::post('/change-contrast',                  'FirebaseUrlsController@changeContrast')->name('changeContrast');
            Route::post('/invalid-by-block-code',                       'FirebaseUrlsController@invalid_by_block_code')->name('invalid_by_block_code');
            Route::post('/manual-entry-member',                         'FirebaseUrlsController@store_manual_entry_member')->name('store_manual_entry_member');
            Route::post('/update-manually-entered',                     'FirebaseUrlsController@update_manually_entered')->name('update_manually_entered');
            Route::post('/save-polling-number',                         'FirebaseUrlsController@save_polling_number')->name('save_polling_number');
            Route::get('/',                                             'FirebaseUrlsController@index')->name('index');
            Route::get('/remaining',                                    'FirebaseUrlsController@remaining')->name('remaining');
            Route::get('/invalid',                                      'FirebaseUrlsController@invalid')->name('invalid');
            Route::get('/manually-entered',                             'FirebaseUrlsController@manually_entered')->name('manually_entered');
            Route::get('{url_id}/edit-manually-entered',                'FirebaseUrlsController@edit_manually_entered')->name('edit_manually_entered');
            Route::get('{url_id}/delete-manually-entered',              'FirebaseUrlsController@delete_manually_entered')->name('delete_manually_entered');
            Route::get('/per-page-entities',                            'FirebaseUrlsController@per_page_entities')->name('per-page-entities');
            Route::get('/create',                                       'FirebaseUrlsController@create')->name('create');
            Route::post('/',                                            'FirebaseUrlsController@store')->name('store');
            Route::get('/{firebaseUrl}/edit',                           'FirebaseUrlsController@edit')->name('edit');
            Route::get('/{firebaseUrl}/check-manually',                 'FirebaseUrlsController@checkManually')->name('checkManually');
            Route::get('/{firebaseUrl}/create-manual-entry-member',     'FirebaseUrlsController@create_manual_entry_member')->name('create_manual_entry_member');
            Route::post('/bulk-destroy',                                'FirebaseUrlsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{firebaseUrl}',                               'FirebaseUrlsController@update')->name('update');
            Route::delete('/{firebaseUrl}',                             'FirebaseUrlsController@destroy')->name('destroy');

        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('polling-details')->name('polling-details/')->group(static function() {
            Route::post('/save-details',                                'PollingDetailsController@save_details')->name('save_details');
            Route::get('/',                                             'PollingDetailsController@index')->name('index');
            Route::get('/create',                                       'PollingDetailsController@create')->name('create');
            Route::post('/',                                            'PollingDetailsController@store')->name('store');
            Route::get('/{pollingDetail}/edit',                         'PollingDetailsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'PollingDetailsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{pollingDetail}',                             'PollingDetailsController@update')->name('update');
            Route::delete('/{pollingDetail}',                           'PollingDetailsController@destroy')->name('destroy');
            Route::get('/missing-entities/{blockcode}',                 'PollingDetailsController@missing_entities')->name('missing_entities');
            Route::get('/save-voter-details/{id}/{s_no}/{f_no}',        'PollingDetailsController@saveVoterDetails')->name('saveVoterDetails');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('polling-stations')->name('polling-stations/')->group(static function() {
            Route::get('/',                                             'PollingStationController@index')->name('index');
            Route::get('/create',                                       'PollingStationController@create')->name('create');
            Route::post('/',                                            'PollingStationController@store')->name('store');
            Route::get('/{pollingStation}/edit',                        'PollingStationController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'PollingStationController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{pollingStation}',                            'PollingStationController@update')->name('update');
            Route::delete('/{pollingStation}',                          'PollingStationController@destroy')->name('destroy');
            Route::get('/all-sectors',                                  'PollingStationController@allSectors')->name('allSectors');
            Route::get('/sectors-details/{sector}',                     'PollingStationController@sectorDetails')->name('sectorDetails');
            Route::get('/block_code_report/{block_code}',               'PollingStationController@block_code_report')->name('block_code_report');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('pdf-pollings')->name('pdf-pollings/')->group(static function() {
            Route::get('/',                                             'PdfPollingController@index')->name('index');
            Route::get('/create',                                       'PdfPollingController@create')->name('create');
            Route::post('/',                                            'PdfPollingController@store')->name('store');
            Route::get('/{pdfPolling}/edit',                            'PdfPollingController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'PdfPollingController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{pdfPolling}',                                'PdfPollingController@update')->name('update');
            Route::delete('/{pdfPolling}',                              'PdfPollingController@destroy')->name('destroy');
        });
    });
});

Route::get('/send-markdown-mail', [MailController::class, 'sendOfferMail']);
Route::get('/testsendPdf', [MailController::class, 'testsendPdf']);

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('election-sectors')->name('election-sectors/')->group(static function() {
            Route::get('/',                                             'ElectionSectorController@index')->name('index');
            Route::get('/create',                                       'ElectionSectorController@create')->name('create');
            Route::post('/',                                            'ElectionSectorController@store')->name('store');
            Route::get('/{electionSector}/edit',                        'ElectionSectorController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'ElectionSectorController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{electionSector}',                            'ElectionSectorController@update')->name('update');
            Route::delete('/{electionSector}',                          'ElectionSectorController@destroy')->name('destroy');
            Route::get('/get-download-json-files',                      'ElectionSectorController@getDownloadJsonFiles')->name('getDownloadJsonFiles');
            Route::get('/download-json-file/{ward}',                    'ElectionSectorController@downloadJsonFile')->name('downloadJsonFile');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('url-upload-logs')->name('url-upload-logs/')->group(static function() {
            Route::get('/',                                             'UrlUploadLogController@index')->name('index');
            Route::get('/create',                                       'UrlUploadLogController@create')->name('create');
            Route::post('/',                                            'UrlUploadLogController@store')->name('store');
            Route::get('/{urlUploadLog}/edit',                          'UrlUploadLogController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'UrlUploadLogController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{urlUploadLog}',                              'UrlUploadLogController@update')->name('update');
            Route::delete('/{urlUploadLog}',                            'UrlUploadLogController@destroy')->name('destroy');
        });
    });
});



/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('parchi-images')->name('parchi-images/')->group(static function() {
            Route::get('/',                                             'ParchiImageController@index')->name('index');
            Route::get('/create',                                       'ParchiImageController@create')->name('create');
            Route::post('/',                                            'ParchiImageController@store')->name('store');
            Route::get('/{parchiImage}/edit',                           'ParchiImageController@edit')->name('edit');
            Route::get('/{parchiImage}/candidateImage/{candidatename}',                           'ParchiImageController@candidatename')->name('candidatename');
            Route::post('/postCandidateImage',                                            'ParchiImageController@postCandidateImage')->name('postCandidateImage');

            Route::post('/bulk-destroy',                                'ParchiImageController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{parchiImage}',                               'ParchiImageController@update')->name('update');
            Route::delete('/{parchiImage}',                             'ParchiImageController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('polling-schemes')->name('polling-schemes/')->group(static function() {
            Route::get('/',                                              'PollingSchemeController@index')->name('index');
            Route::get('/import-polling-scheme',                         'PollingSchemeController@import')->name('importPollingScheme');
            Route::post('/import-polling',                               'PollingSchemeController@importPolling')->name('importPolling');
            Route::post('/update-image-upload',                          'PollingSchemeController@updateImageUpload')->name('updateImageUpload');
            Route::get('/create',                                        'PollingSchemeController@create')->name('create');
            Route::get('/{pollingScheme}/imageupload/{urduaddress}',                                       'PollingSchemeController@updateImage')->name('pollingupdateImage');
            Route::get('/insert-polling-data',                           'PollingSchemeController@InsertPollingData')->name('InsertPollingData');
            Route::post('/postupdateImage',                                            'PollingSchemeController@postupdateImage')->name('postupdateImage');
            Route::post('/',                                            'PollingSchemeController@store')->name('store');
            Route::get('/{pollingScheme}/edit',                         'PollingSchemeController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'PollingSchemeController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{pollingScheme}',                             'PollingSchemeController@update')->name('update');
            Route::delete('/{pollingScheme}',                           'PollingSchemeController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('pdf-polling-logs')->name('pdf-polling-logs/')->group(static function() {
            Route::get('/',                                             'PdfPollingLogController@index')->name('index');
            Route::get('/create',                                       'PdfPollingLogController@create')->name('create');
            Route::post('/',                                            'PdfPollingLogController@store')->name('store');
            Route::get('/{pdfPollingLog}/edit',                         'PdfPollingLogController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'PdfPollingLogController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{pdfPollingLog}',                             'PdfPollingLogController@update')->name('update');
            Route::delete('/{pdfPollingLog}',                           'PdfPollingLogController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('candidate-wards')->name('candidate-wards/')->group(static function() {
            Route::get('/',                                             'CandidateWardController@index')->name('index');
            Route::get('/create',                                       'CandidateWardController@create')->name('create');
            Route::get('/sectors',                                      'CandidateWardController@sectors')->name('sectors');
            Route::post('/',                                            'CandidateWardController@store')->name('store');
            Route::get('/{candidateWard}/edit',                         'CandidateWardController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'CandidateWardController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{candidateWard}',                             'CandidateWardController@update')->name('update');
            Route::delete('/{candidateWard}',                           'CandidateWardController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers')->name('admin/')->group(static function() {
        Route::prefix('report')->name('report')->group(static function() {
            Route::get('/',                                             'ReportController@index')->name('index');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('data-sets')->name('data-sets/')->group(static function() {
            Route::get('/',                                             'DataSetController@index')->name('index');
            Route::get('/get-record-DHA-lahore/{blockcode}',                        'DataSetController@getRecordDHALahore')->name('getRecordDHALahore');
            Route::get('/create',                                       'DataSetController@create')->name('create');
            Route::post('/',                                            'DataSetController@store')->name('store');
            Route::get('/{dataSet}/edit',                               'DataSetController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'DataSetController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{dataSet}',                                   'DataSetController@update')->name('update');
            Route::delete('/{dataSet}',                                 'DataSetController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('customers')->name('customers/')->group(static function() {
            Route::get('order',                                         'CustomerController@orderForm')->name('orderForm');
            Route::get('single-ward-user/{ward}',                       'CustomerController@singleWardUser')->name('singleWardUser');
            Route::post('insert-order',                                 'CustomerController@insertOrder')->name('insertOrder');
            Route::get('/',                                             'CustomerController@index')->name('index');
            Route::get('total-invoice',                                 'CustomerController@showInvoice')->name('showInvoice');
            Route::get('generate-invoice/{invoice_no}',                 'CustomerController@generateInvoice')->name('generateInvoice');
            Route::get('generate-custom-invoice/{id}',                  'CustomerController@generateCustomInvoice')->name('generateCustomInvoice');
            Route::get('pay-voice-price/{invoice_no}',                  'CustomerController@payVoicePrice')->name('payVoicePrice');
            Route::get('voter-inward/{sector}',                         'CustomerController@voterInWard')->name('voterInWard');
            Route::post('insert-invoice-screenshot',                    'CustomerController@insertInvoiceScreenShot')->name('insertInvoiceScreenShot');
            Route::get('/create',                                       'CustomerController@create')->name('create');
            Route::post('/',                                            'CustomerController@store')->name('store');
            Route::get('/{customer}/edit',                              'CustomerController@edit')->name('edit');
            Route::get('/check_reference_dup/{invoice_no}/{ref_no}',    'CustomerController@checkReferenceDupl')->name('checkReferenceDupl');
            Route::get('/show-customer-payments/{customer_id}',         'CustomerController@showCustomerPayments')->name('showCustomerPayments');
            Route::get('/ledger',                                       'CustomerController@ledger')->name('ledger');
            Route::get('/import-constituencies',                        'CustomerController@importConstituenties')->name('importConstituenties');
            Route::get('/get-city/{province}',                          'CustomerController@getCity')->name('getCity');
            Route::post('/upload-constituencies',                       'CustomerController@uploadConstituenties')->name('uploadConstituenties');
            Route::post('/bulk-destroy',                                'CustomerController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{customer}',                                  'CustomerController@update')->name('update');
            Route::delete('/{customer}',                                'CustomerController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('election-settings')->name('election-settings/')->group(static function() {
            Route::get('/',                                             'ElectionSettingController@index')->name('index');
            Route::get('/create',                                       'ElectionSettingController@create')->name('create');
            Route::post('/',                                            'ElectionSettingController@store')->name('store');
            Route::get('/{electionSetting}/edit',                       'ElectionSettingController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'ElectionSettingController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{electionSetting}',                           'ElectionSettingController@update')->name('update');
            Route::delete('/{electionSetting}',                         'ElectionSettingController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('curl-switches')->name('curl-switches/')->group(static function() {
            Route::get('/',                                             'CurlSwitchesController@index')->name('index');
            Route::get('/create',                                       'CurlSwitchesController@create')->name('create');
            Route::post('/',                                            'CurlSwitchesController@store')->name('store');
            Route::get('/{curlSwitch}/edit',                            'CurlSwitchesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'CurlSwitchesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{curlSwitch}',                                'CurlSwitchesController@update')->name('update');
            Route::delete('/{curlSwitch}',                              'CurlSwitchesController@destroy')->name('destroy');
            Route::get('/change-switch-status/{switch_id}',                        'CurlSwitchesController@updateSwitchStatus')->name('updateSwitchStatus');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('offline-data-files')->name('offline-data-files/')->group(static function() {
            Route::get('/',                                             'OfflineDataFilesController@index')->name('index');
            Route::get('/create',                                       'OfflineDataFilesController@create')->name('create');
            Route::post('/',                                            'OfflineDataFilesController@store')->name('store');
            Route::get('/{offlineDataFile}/edit',                       'OfflineDataFilesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'OfflineDataFilesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{offlineDataFile}',                           'OfflineDataFilesController@update')->name('update');
            Route::delete('/{offlineDataFile}',                         'OfflineDataFilesController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('voter-details')->name('voter-details/')->group(static function() {
            Route::get('/',                                             'VoterDetailsController@index')->name('index');
            Route::get('/import',                                             'VoterDetailsController@import')->name('import');
            Route::get('/create',                                       'VoterDetailsController@create')->name('create');
            Route::post('/',                                            'VoterDetailsController@store')->name('store');
            Route::post('/import-data',                                  'VoterDetailsController@importVoter')->name('importVoter');
            Route::get('/{voterDetail}/edit',                           'VoterDetailsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'VoterDetailsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{voterDetail}',                               'VoterDetailsController@update')->name('update');
            Route::delete('/{voterDetail}',                             'VoterDetailsController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('payment-gateways')->name('payment-gateways/')->group(static function() {
            Route::get('/',                                             'PaymentGatewayController@index')->name('index');
            Route::get('/change-service/{id}/{type}',                   'PaymentGatewayController@changeService')->name('changeService');
            Route::get('/create',                                       'PaymentGatewayController@create')->name('create');
            Route::get('/stripe-payment',                               'PaymentGatewayController@stripe')->name('stripePayment');
            Route::post('/stripe-payments-details',                     'PaymentGatewayController@stripePost')->name('stripePaymentDetails');
            Route::get('/paypal-payment',                               'PaymentGatewayController@paypal')->name('paypalPayment');
            Route::post('/',                                            'PaymentGatewayController@store')->name('store');
            Route::get('/{paymentGateway}/edit',                        'PaymentGatewayController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'PaymentGatewayController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{paymentGateway}',                            'PaymentGatewayController@update')->name('update');
            Route::delete('/{paymentGateway}',                          'PaymentGatewayController@destroy')->name('destroy');

        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('business-accounts')->name('business-accounts/')->group(static function() {
            Route::get('/',                                             'BusinessAccountController@index')->name('index');
            Route::get('/create',                                       'BusinessAccountController@create')->name('create');
            Route::get('/create-account',                               'BusinessAccountController@createAccount')->name('createAccount');
            Route::post('/store-account',                              'BusinessAccountController@storeAccount')->name('storeAccount');
            Route::post('/',                                            'BusinessAccountController@store')->name('store');
            Route::get('/{businessAccount}/edit',                       'BusinessAccountController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'BusinessAccountController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{businessAccount}',                           'BusinessAccountController@update')->name('update');
            Route::delete('/{businessAccount}',                         'BusinessAccountController@destroy')->name('destroy');
        });
    });
});



/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('s3uploading-members')->name('s3uploading-members/')->group(static function() {
            Route::get('/',                                             'S3uploadingMembersController@index')->name('index');
            Route::get('/create',                                       'S3uploadingMembersController@create')->name('create');
            Route::post('/',                                            'S3uploadingMembersController@store')->name('store');
            Route::get('/{s3uploadingMember}/edit',                     'S3uploadingMembersController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'S3uploadingMembersController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{s3uploadingMember}',                         'S3uploadingMembersController@update')->name('update');
            Route::delete('/{s3uploadingMember}',                       'S3uploadingMembersController@destroy')->name('destroy');
        });
    });
});



Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Reports')->name('admin/')->group(static function() {
        Route::prefix('reports')->group(static function() {
            Route::get('/data',                             'ReportController@reports')->name('sareports');
            Route::get('/filterProcessing/{start_date}/{end_date}',                             'ReportController@filterProcessing')->name('filterProcessingsData');
        });
    });
});
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\General')->name('admin/')->group(static function() {
        Route::get('/database',                             'SettingController@getTableSchema')->name('database');
    });
});
