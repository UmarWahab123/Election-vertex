<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PollingStation\BulkDestroyPollingStation;
use App\Http\Requests\Admin\PollingStation\DestroyPollingStation;
use App\Http\Requests\Admin\PollingStation\IndexPollingStation;
use App\Http\Requests\Admin\PollingStation\StorePollingStation;
use App\Http\Requests\Admin\PollingStation\UpdatePollingStation;
use App\Models\ElectionSector;
use App\Models\FirebaseUrl;
use App\Models\ParchiImage;
use App\Models\PollingDetail;
use App\Models\PollingStation;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PollingStationController extends Controller
{
    public function allSectors(){
        $sectors = ElectionSector::all()->groupBy('sector');
        return view('admin.election-sector.electionSectors' , compact('sectors'));
    }


    public function discrip($blockcode)
    {
        $mpolling_details = PollingDetail::where('polling_station_number', $blockcode)
            ->where('gender', '=', "male")
//            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
//            ->take(50)
            ->with('voter_phone')
            ->get()
            ->groupBy('serial_no');

        $fpolling_details = PollingDetail::where('polling_station_number', $blockcode)
            ->where('gender', '=', "female")
//            ->orderBy('url_id', 'asc')
            ->orderBy('serial_no', 'asc')
//            ->take(50)
            ->with('voter_phone')
            ->get()
            ->groupBy('serial_no');

        $pre_key=0;
        $pre_data=0;
        $serial_break_range = [];
        $duplicate = 0;

        $fpre_key=0;
        $fpre_data=0;
        $fserial_break_range = [];
        $fduplicate = 0;





        foreach ($mpolling_details as $key=> $detail)
        {

            foreach ($detail as $key1=> $line)
            {
                if($pre_key !=0 && $pre_key != ($key-1) )
                {

                    $serial_break_range[]=['start'=> $pre_key,'end'=>$key,'difference'=> ($key-$pre_key-1) ,'pre_data'=> $pre_data,'data'=> $line,'gender'=>'male'];
//                    dd($pre_key,$serial_break_range,$mpolling_details);
                }
                else if( count($detail) > 1)
                {
                    $duplicate=$duplicate+count($detail);
                }
            }

            $pre_key=$key;
            $pre_data=$detail;

        }


        foreach ($fpolling_details as $key=> $detail)
        {

            foreach ($detail as $key1=> $line)
            {
                if($fpre_key !=0 && $fpre_key != ($key-1) )
                {

                    $fserial_break_range[]=['start'=> $fpre_key,'end'=>$key,'difference'=> ($key-$fpre_key-1) ,'pre_data'=> $fpre_data,'data'=> $line,'gender'=>'female'];
//                    dd($pre_key,$serial_break_range,$mpolling_details);
                }
                else if( count($detail) > 1)
                {
                    $duplicate=$duplicate+count($detail);
                }
            }

            $fpre_key=$key;
            $fpre_data=$detail;

        }


        $data=[];

        $data['male_duplicate']=$duplicate;
        $data['male_serial_break']=$serial_break_range;
        $data['femail_serial_break']=$fserial_break_range;
        $data['female_duplicate']=$fduplicate;



        return $data;






    }

    public function block_code_report($block_code){

        $election_sector = ElectionSector::where('block_code' , $block_code)->first();

        $firebase_urls = FirebaseUrl::where('import_ps_number' , $block_code)
            ->withcount('polling_details')
            ->with('polling_details')
            ->get();

//        dd($firebase_urls);

        $page_24 = 0;
        $page_28 = 0;
        $other = 0;
        $male = 0;
        $female = 0;
        $invalid_pages = 0;
        $duplicate_pages = 0;
        $missing_blockcode_pages = 0;

        foreach ($firebase_urls as $key => $item){
            if($item->polling_details_count == 28){
                $page_28 ++ ;
            }else if($item->polling_details_count == 24){
                $page_24 ++;
            }else{
                $other ++;
            }

            if($item->staus === 3 || $item->staus === 500 || $item->staus === 404){
                $invalid_pages++;
            }

            if($item->staus === 4){
                $duplicate_pages++;
            }

            if($item->staus === 5){
                $missing_blockcode_pages++;
            }

            foreach($item->polling_details as $value){
                if($value->gender === 'male'){
                    $male++;
                }else{
                    $female++;
                }
            }
        }

        $response = [
            'page_24' => $page_24,
            'page_28' => $page_28,
            'other' => $other,
            'male' => $male,
            'female' => $female,
            'invalid_pages' => $invalid_pages,
            'duplicate_pages' => $duplicate_pages,
            'missing_blockcode_pages' => $missing_blockcode_pages,
        ];

        $election_sector->response = json_encode($response);
        $election_sector->update();

        $dis = $this->discrip($block_code);

        return view('admin.block_code_report' , compact('election_sector', 'firebase_urls', 'response','dis'));
    }


    public function block_code_links_details($block_codes){

//        $response = [];
        $block_code_dataPoints = array();
        foreach ($block_codes as $key => $block_code){
            $pending_urls = FirebaseUrl::where('import_ps_number' , $block_code->polling_station_number)->where('status' , 0)->count();
            $complete_urls = FirebaseUrl::where('import_ps_number' , $block_code->polling_station_number)->whereIn('status' , [1 , 20 ,200])->count();
            $invalid_urls = FirebaseUrl::where('import_ps_number' , $block_code->polling_station_number)->whereIn('status' , [3 , 404 , 500])->count();
            $duplicate_entery = FirebaseUrl::where('import_ps_number' , $block_code->polling_station_number)->where('status' , 4)->count();
            $manually_entered = FirebaseUrl::where('import_ps_number' , $block_code->polling_station_number)->where('status' , 6)->count();
            $block_code['invalid_urls'] = $invalid_urls ? $invalid_urls : 0;
            $block_code['pending_urls'] = $pending_urls ? $pending_urls : 0;
            $block_code['complete_urls'] = $complete_urls ? $complete_urls : 0;
            $block_code['manually_entered'] = $manually_entered ? $manually_entered : 0;
            $block_code['duplicate_entery'] = $duplicate_entery ? $duplicate_entery : 0;
//            dd($block_code);
            $total_dataPoints[$key] = $block_code->firebase_urls_count;
            $block_code_dataPoints[$key] = $block_code->polling_station_number;
            $invalid_urls_dataPoints[$key] = $block_code->invalid_urls;
            $complete_urls_dataPoints[$key] = $block_code->complete_urls;
            $pending_urls_dataPoints[$key] = $block_code->pending_urls;
            $manually_entered_dataPoints[$key] = $block_code->manually_entered;

        }

        $response = [
            'total_urls' => $total_dataPoints,
            'block_codes' => $block_codes,
            'block_code_dataPoints' => $block_code_dataPoints,
            'invalid_urls_dataPoints' => $invalid_urls_dataPoints,
            'complete_urls_dataPoints' => $complete_urls_dataPoints,
            'manually_entered_dataPoints' => $manually_entered_dataPoints,
            'pending_urls_dataPoints' => $pending_urls_dataPoints
        ];

        return $response;
    }

    public function sectorDetails($sector){

        ini_set('max_execution_time', '0');
        ini_set("memory_limit", "-1");

        $parchi_image = ParchiImage::all()->unique('Party');
        $parties = $parchi_image->pluck('Party');

        $block_codes = PollingStation::whereHas('sector', function($q) use($sector){
            $q->where('sector', $sector);
        })
            ->withCount('firebase_urls')
            ->withCount('polling_details')
            ->with('polling_details')
            ->paginate(100);

        $total_null_sr = 0;
        $total_null_fm = 0;
        $total_record  = 0;

        foreach ($block_codes as $block_code){
            $total_record += count($block_code->polling_details);
            $null_sr=0;
            $null_fm=0;

            foreach ($block_code->polling_details as $detail)
            {

                if($detail->serial_no == null)
                {
                    $null_sr++;
                }
                if($detail->family_no == null)
                {
                    $null_fm++;
                }

            }

            $block_code->null_serial_count = $null_sr;
            $block_code->null_family_count = $null_fm;


            $total_null_sr+=$null_sr;
            $total_null_fm+=$null_fm;
        }


        $response = $this->block_code_links_details($block_codes);
        $block_codes = $response['block_codes'];

        return view('admin.election-sector.sectorDetail' , compact('block_codes' , 'sector','total_null_sr','total_null_fm','total_record' , 'parties'));
    }


    public function attach_missing_sr_fm($polling_detail)
    {
        $null_sr=0;
        $null_fm=0;

        if($polling_detail->serial_no == null)
        {
            $null_sr++;
        }
        if($polling_detail->family_no == null)
        {
            $null_fm++;
        }

        $polling_detail->null_serial_count = $null_sr;
        $polling_detail->null_family_count = $null_fm;

    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexPollingStation $request
     * @return array|Factory|View
     */
    public function index(IndexPollingStation $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(PollingStation::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'polling_station_number', 'url_id'],

            // set columns to searchIn
            ['id', 'meta']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.polling-station.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.polling-station.create');

        return view('admin.polling-station.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePollingStation $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StorePollingStation $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the PollingStation
        $pollingStation = PollingStation::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/polling-stations'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/polling-stations');
    }

    /**
     * Display the specified resource.
     *
     * @param PollingStation $pollingStation
     * @throws AuthorizationException
     * @return void
     */
    public function show(PollingStation $pollingStation)
    {
        $this->authorize('admin.polling-station.show', $pollingStation);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PollingStation $pollingStation
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(PollingStation $pollingStation)
    {
        $this->authorize('admin.polling-station.edit', $pollingStation);


        return view('admin.polling-station.edit', [
            'pollingStation' => $pollingStation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePollingStation $request
     * @param PollingStation $pollingStation
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdatePollingStation $request, PollingStation $pollingStation)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values PollingStation
        $pollingStation->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/polling-stations'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/polling-stations');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPollingStation $request
     * @param PollingStation $pollingStation
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyPollingStation $request, PollingStation $pollingStation)
    {
        $pollingStation->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyPollingStation $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyPollingStation $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    PollingStation::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
