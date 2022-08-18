<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VisitingCardController;
use App\Http\Requests\Admin\FirebaseUrl\BulkDestroyFirebaseUrl;
use App\Http\Requests\Admin\FirebaseUrl\DestroyFirebaseUrl;
use App\Http\Requests\Admin\FirebaseUrl\IndexFirebaseUrl;
use App\Http\Requests\Admin\FirebaseUrl\StoreFirebaseUrl;
use App\Http\Requests\Admin\FirebaseUrl\UpdateFirebaseUrl;
use App\Models\DumpData;
use App\Models\FirebaseUrl;
use App\Models\PollingDetail;
use App\Models\PollingStation;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Aws\Textract\TextractClient;
use Auth;

class FirebaseUrlsController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @param IndexFirebaseUrl $request
     * @return array|Factory|View
     */
    public function index(IndexFirebaseUrl $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(FirebaseUrl::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'status', 'cron', 'image_url'],

            // set columns to searchIn
            ['id', 'image_url', 'cron']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data , 'status' => 'allRecords'];
        }

        return view('admin.firebase-url.index', ['data' => $data , 'status' => 'allRecords']);
    }

    public function remaining(IndexFirebaseUrl $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(FirebaseUrl::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'status', 'cron', 'image_url', 'log_state'],

            // set columns to searchIn
            ['id', 'status', 'image_url', 'cron', 'log_state'],

            function ($query) use ($request) {
                $query->where('cron', 0);
            }
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data , 'status' => 'remaining'];
        }

        return view('admin.firebase-url.index', ['data' => $data , 'status' => 'remaining']);
    }

    public function invalid(IndexFirebaseUrl $request)
    {
        if(@$_GET['ps_no']){
            $data = AdminListing::create(FirebaseUrl::class)->processRequestAndGet(
            // pass the request with params
                $request,

                // set columns to query
                ['id', 'status', 'cron', 'image_url', 'log_state'],

                // set columns to searchIn
                ['id', 'status', 'image_url', 'cron', 'log_state'],

                function ($query) use ($request) {
                    $query->where('import_ps_number' ,$_GET['ps_no'] )->whereIn('status', [404 , 3, 500])->where('cron', 1);
                }
            );
        }else{
            $data = AdminListing::create(FirebaseUrl::class)->processRequestAndGet(
            // pass the request with params
                $request,

                // set columns to query
                ['id', 'status', 'cron', 'image_url', 'log_state'],

                // set columns to searchIn
                ['id', 'status', 'image_url', 'cron', 'log_state'],

                function ($query) use ($request) {
                    $query->whereIn('status', [3 , 404, 500])->where('cron', 1);
                }
            );
        }
        // create and AdminListing instance for a specific model and


        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.firebase-url.invalid-index', ['data' => $data]);
    }

    public function invalid_by_block_code(Request $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(FirebaseUrl::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'status', 'cron', 'image_url', 'log_state'],

            // set columns to searchIn
            ['id', 'status', 'image_url', 'cron', 'log_state'],

            function ($query) use ($request) {
                $query->where('status', 3)->where('cron', 1)->where('import_ps_number' , $request->block_code);
            }
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.firebase-url.invalid-index', ['data' => $data]);
    }

    public function checkManually ($id)
    {
        $url = FirebaseUrl::where('id' , $id)->first();
        $url_link = $url->image_url;
        $url_id = $url->id;
        $obj = new VisitingCardController();
        $obj->manual_textract($url_link , $url_id);

    }

    public function per_page_entities()
    {
        $data = PollingDetail::select('id', 'url', 'url_id', 'polling_station_number', 'polling_station_id')
            ->where('polling_station_id', null)
            ->where('polling_station_number' , null)
            ->orderBy('id' , 'desc')
            ->get()
            ->groupBy('url_id');

        $count = count($data);

        return view('admin.firebase-url.manual-entry' , compact('data' , 'count'));
    }

    public function save_polling_number(Request $request)
    {
        $polling_station = PollingStation::where('polling_station_number' , $request->polling_number)->first();

        if ($polling_station) {
            $polling_station_id = $polling_station->id;
        }

        else{
            $new_polling_station = PollingStation::insertGetId(['polling_station_number' => $request->polling_number, 'meta' => null, 'url_id' => $request->url_id]);
            $polling_station_id = $new_polling_station;
        }

        if ($polling_station_id){
            $polling_details = PollingDetail::where('polling_station_id', null)
                ->where('polling_station_number' , null)
                ->where('url_id' , $request->url_id)
                ->get();
        }

        if ($polling_details){
            foreach ($polling_details as $key => $value) {
                try
                {
                    $value->polling_station_id = $polling_station_id;
                    $value->polling_station_number = $request->polling_number;
                    $value->save();
                }
                catch (Exception $e)
                {
                    if ($e){
                        $value->polling_station_id = 0;
                        $firebase_url = FirebaseUrl::where('id' , $request->url_id)->first();
                        $firebase_url->status = 2;
                        $firebase_url->save();
                    }
                }
            }
            return response(['message' => 'ok']);

        }
    }

    public function create_manual_entry_member($url_id){
        $img = FirebaseUrl::where('id' , $url_id)
            ->select('id', 'image_url', 'import_ps_number')
            ->first();

        return view('admin.firebase-url.manual-entery-member' , [
                'url_id' => $url_id,
                'img' => $img->image_url,
                'block_code' => $img->import_ps_number
            ]);
    }

    public function store_manual_entry_member(Request $request){

        $url = $request->url;
        $url_id = $request->url_id;

        $firebase_url = FirebaseUrl::where('id' , $url_id)->first();

        $polling_station_number = $request->block_code;

        $polling_station = PollingStation::where('polling_station_number', $polling_station_number)->first();

        if ($polling_station)
        {
            $polling_station_id = $polling_station->id;
        }
        else{
            $new_polling_station = DB::table('polling_station')->insertGetId([
                'polling_station_number' => $polling_station_number,
                'meta' => $firebase_url->link_meta,
                'url_id' => $request->url_id
            ]);
            $polling_station_id = $new_polling_station;
        }


        foreach ($request->s_no as $key => $value){
            $status = PollingDetail::where('cnic' ,$request->cnic[$key])->first();
            if(!$status) {
                $polling_detail = new PollingDetail();
                $polling_detail->polling_station_id = $polling_station_id;
                $polling_detail->polling_station_number = $polling_station_number;
                $polling_detail->cnic = $request->cnic[$key];
                $polling_detail->age = $request->age[$key];
                $polling_detail->family_no = $request->fam_no[$key];
                $polling_detail->serial_no = $value;
                $polling_detail->page_no = null;
                $polling_detail->url = $url;
                $polling_detail->url_id = $url_id;
                $polling_detail->boundingBox = null;
                $polling_detail->polygon = null;
                $polling_detail->status = 0;
                $polling_detail->save();
            }
        }

        $firebase_url->log_state  = 'added_manually | complete';
        $firebase_url->status  = 7;
        $firebase_url->import_ps_number = $polling_station_number;
        $firebase_url->update();

        return redirect('/admin/firebase-urls/invalid');
    }

    public function manually_entered(IndexFirebaseUrl $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(FirebaseUrl::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'status', 'cron', 'image_url', 'log_state'],

            // set columns to searchIn
            ['id', 'status', 'image_url', 'cron', 'log_state'],

            function ($query) use ($request) {
                $query->where('status', 7)->where('cron', 1);
            }
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.firebase-url.manually-entered-index', ['data' => $data]);
    }

    public function edit_manually_entered($url_id)
    {
        $img = FirebaseUrl::where('id' , $url_id)->first();
        $polling_details = PollingDetail::where('url_id' , $url_id)->get();
        $serial_no = $polling_details[0]->serial_no;
        $polling_station_number = $polling_details[0]->polling_station_number;
        $entries = count($polling_details);
        return view('admin.firebase-url.edit-manually-entered', ['polling_details' => $polling_details, 'polling_station_number' => $polling_station_number , 'url_id' => $url_id , 'serial_no' => $serial_no ,'entries' => $entries , 'img' => $img->image_url]);
    }

    public function update_manually_entered(Request $request){

        $url = $request->url;
        $url_id = $request->url_id;
        $polling_station_number = $request->block_code;

        $polling_station = PollingStation::where('polling_station_number', $polling_station_number)->first();

        if ($polling_station) {
            $polling_station_id = $polling_station->id;
        }
        else{
            $firebase_url = FirebaseUrl::where('id' , $url_id)->first();
            $new_polling_station = DB::table('polling_station')->insertGetId([
                'polling_station_number' => $polling_station_number,
                'meta' => $firebase_url->link_meta,
                'url_id' => $request->url_id
            ]);

            $polling_station_id = $new_polling_station;
        }

        foreach ($request->id as $key => $value){
            $item = PollingDetail::where('id' , $value)->first();
            $item->polling_station_id = $polling_station_id;
            $item->polling_station_number = $polling_station_number;
            $item->cnic = $request->cnic[$key];
            $item->age = $request->age[$key];
            $item->family_no = $request->fam_no[$key];
            $item->serial_no = $request->s_no[$key];
            $item->page_no = null;
            $item->url = $url;
            $item->url_id = $url_id;
            $item->boundingBox = null;
            $item->polygon = null;
            $item->status = 0;
            $item->update();
        }

        return redirect('/admin/firebase-urls/manually-entered');
    }

    public function delete_manually_entered($url_id)
    {
        $polling_details = PollingDetail::where('url_id' , $url_id)->get();
        foreach ($polling_details as $detail){
            $detail->delete();
        }

        return redirect('/manually-entered');
    }

    public function textract($image_url){
        header("Access-Control-Allow-Origin: *");
        $image = file_get_contents($image_url);

        $client = new TextractClient([
            'version'     => '2018-06-27',
            'region'      => 'us-east-1',
            'credentials' => [
                'key'    => 'AKIAXWVDESS7ZUKLBS7A',
                'secret' => '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'
            ]

        ]);
        $options = [
            'Document' => [
                'Bytes' => $image
            ],
            'FeatureTypes' => ['FORMS', 'TABLES'],
        ];

        $response = array();

        $result = $client->analyzeDocument($options);

        $blocks = $result['Blocks'];

        return $blocks;
    }

    public function filterBlocks($blocks, $confidence)
    {
        $new_blocks = [];
        foreach ($blocks as $key => $value) {
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if (isset($value['Text']) && $value['Text'] && $value['Confidence'] > $confidence) {
                    $text = $value['Text'];
                    $new_blocks[] = $value;
                }
            }
        }
        return $new_blocks;
    }

    public function changeContrast(Request $request){

        $old_image = $request->firebase_image;
        $contrast_ratio = $request->contrast_ratio;
        $url_id = $request->url_id;
        $data = urlencode($old_image);
        $cloudinary_url = 'https://res.cloudinary.com/one-call-app/image/fetch/e_contrast:'.$contrast_ratio.'/' . $data;
        $blocks = $this->textract($cloudinary_url);
        $meta = json_encode($blocks);
        $cnic = array();
        $polling_station_number = '';
        $page_number = '';

        $cnic_pattern = '/^([0-9]{5})[\-]([0-9]{7})[\-]([0-9]{1})/';
        $polling_station_number_pattern = '/^\d{5,20}$/';
        $page_number_pattern = '/^(Page)+/';

        foreach ($blocks as $key => $value) {
            if (isset($value['BlockType']) && $value['BlockType'] &&  $value['BlockType'] == 'LINE') {
                $blockType = $value['BlockType'];
                if (isset($value['Text']) && $value['Text']) {
                    $text = $value['Text'];
                    if ($blockType == 'LINE' && preg_match_all($cnic_pattern, $text, $matches)) {
                        $BoundingBox = json_encode($value['Geometry']['BoundingBox']);
                        $Polygon = json_encode($value['Geometry']['Polygon']);
                        $cnic[] = ['cnic' => $matches[0][0], 'BoundingBox' => $BoundingBox, 'Polygon' => $Polygon];
                    }

                    if ($blockType == 'LINE' &&  preg_match_all($polling_station_number_pattern, $text, $matches) && ($value['Confidence'] > 85)) {
                        $polling_station_number = $text;
                    } else {
                        $polling_station_number = null;
                    }

                    if ($blockType == 'LINE' &&  preg_match_all($page_number_pattern, $text, $matches)) {
                        try {
                            $page_number = $text;
                            $temp = explode(' ', $page_number);
                            $temp = explode('/', $temp[1]);
                            $page_number = $temp[0];
                        } catch (\Exception $exception) {
                            $page_number = null;
                        }
                    } else {
                        $page_number = null;
                    }

                }
            }
        }

        $response = ['cnic' => $cnic, 'polling_station_number' => $polling_station_number, 'page_number' => $page_number, 'meta' => $meta];

        $_cnic = array_column($cnic, 'cnic');

        $cnic_count = count($_cnic );

        if ($cnic_count > 0){
            return response([
                'message' => 'record_found',
                'img_url' => $cloudinary_url,
                'cnic_count' => $cnic_count,
                'url_id' => $url_id,
                'response' => $response

            ]);
        }else{
            return response([
                'message' => 'not_found',
                'img_url' => $cloudinary_url,
                'cnic_count' => $cnic_count
            ]);
        }


    }


    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.firebase-url.create');

        return view('admin.firebase-url.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFirebaseUrl $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreFirebaseUrl $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the FirebaseUrl
        $firebaseUrl = FirebaseUrl::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/firebase-urls'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/firebase-urls');
    }

    /**
     * Display the specified resource.
     *
     * @param FirebaseUrl $firebaseUrl
     * @throws AuthorizationException
     * @return void
     */
    public function show(FirebaseUrl $firebaseUrl)
    {
        $this->authorize('admin.firebase-url.show', $firebaseUrl);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param FirebaseUrl $firebaseUrl
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(FirebaseUrl $firebaseUrl)
    {
        $this->authorize('admin.firebase-url.edit', $firebaseUrl);


        return view('admin.firebase-url.edit', [
            'firebaseUrl' => $firebaseUrl,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFirebaseUrl $request
     * @param FirebaseUrl $firebaseUrl
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateFirebaseUrl $request, FirebaseUrl $firebaseUrl)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values FirebaseUrl
        $firebaseUrl->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/firebase-urls'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/firebase-urls');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyFirebaseUrl $request
     * @param FirebaseUrl $firebaseUrl
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyFirebaseUrl $request, FirebaseUrl $firebaseUrl)
    {

        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from remote address
        else
        {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        $firebase_url = FirebaseUrl::where('id' , $firebaseUrl->id)->with('polling_details')->with('polling_station')->first();
        $meta = json_encode($firebase_url);
        $table_row_id = $firebase_url->id;
        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->first_name.' '.\Auth::user()->last_name;
        $table_name = 'firebase_urls';

        DumpData::insert([
            'user_id' => $user_id,
            'ip_address' => $ip_address,
            'user_name' => $user_name,
            'table_row_id' => $table_row_id,
            'table_name' => $table_name,
            'meta' => $meta
        ]);

        if ($firebase_url->polling_station){
            PollingStation::where('url_id' , $firebase_url->polling_station->id)->delete();
        }

        if ($firebase_url->polling_details){
            foreach ($firebase_url->polling_details as $detail){
                $detail->delete();
            }
        }

        $firebaseUrl->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyFirebaseUrl $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyFirebaseUrl $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    FirebaseUrl::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
