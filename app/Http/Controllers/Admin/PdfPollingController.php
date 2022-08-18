<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PdfPolling\BulkDestroyPdfPolling;
use App\Http\Requests\Admin\PdfPolling\DestroyPdfPolling;
use App\Http\Requests\Admin\PdfPolling\IndexPdfPolling;
use App\Http\Requests\Admin\PdfPolling\StorePdfPolling;
use App\Http\Requests\Admin\PdfPolling\UpdatePdfPolling;
use App\Models\PdfPolling;
use App\Models\PollingDetail;
use App\Models\ParchiImage;
use App\Models\PollingScheme;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\ElectionSector;
use DateTime;

class PdfPollingController extends Controller
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
     * @param IndexPdfPolling $request
     * @return array|Factory|View
     */
    public function index(IndexPdfPolling $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(PdfPolling::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'email', 'block_code', 'status','type','created_at'],

            // set columns to searchIn
            ['id', 'email', 'block_code', 'status','type']
        );
        $pdfPolling = PdfPolling::select(DB::raw('count(*) as total'), DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day'))
            ->where('created_at','!=',null)
            ->groupby('year', 'month', 'day')
            ->get();
        $dailyMailReport= array();
        foreach ($pdfPolling as $key => $value) {
            $monthNum = $value->month;
            $dateObj = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F');
            $dailyMailReport[$key]['label'] = $value->day . " " . $monthName . " " . $value->year;
            $dailyMailReport[$key]['y'] = $value->total;
        }
        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.pdf-polling.index', ['data' => $data,'dailyMailReport'=>$dailyMailReport]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.pdf-polling.create');

        return view('admin.pdf-polling.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePdfPolling $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StorePdfPolling $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the PdfPolling
        $pdfPolling = PdfPolling::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/pdf-pollings'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/pdf-pollings');
    }

    /**
     * Display the specified resource.
     *
     * @param PdfPolling $pdfPolling
     * @throws AuthorizationException
     * @return void
     */
    public function show(PdfPolling $pdfPolling)
    {
        $this->authorize('admin.pdf-polling.show', $pdfPolling);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PdfPolling $pdfPolling
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(PdfPolling $pdfPolling)
    {
        $this->authorize('admin.pdf-polling.edit', $pdfPolling);


        return view('admin.pdf-polling.edit', [
            'pdfPolling' => $pdfPolling,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePdfPolling $request
     * @param PdfPolling $pdfPolling
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdatePdfPolling $request, PdfPolling $pdfPolling)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values PdfPolling
        $pdfPolling->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/pdf-pollings'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/pdf-pollings');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPdfPolling $request
     * @param PdfPolling $pdfPolling
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyPdfPolling $request, PdfPolling $pdfPolling)
    {
        $pdfPolling->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyPdfPolling $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyPdfPolling $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    PdfPolling::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function ParchiIndex()
    {
        $this->authorize('admin');
        $email = Auth::user()->email;
        return view('poltitcs_view.pollingParchi', compact('email'));

    }

    public function blockDetailPdf(Request $request)
    {
        $pdf_type = $request->type;
        $electionSector = ElectionSector::where('block_code' , $request->block)->first();
        $blockCode = PollingStation::where('polling_station_number' , $request->block)->first();
        $pollingScheme = PollingScheme::where('block_code' , $request->block)->first();
        $parchiImages = ParchiImage::where('block_code', $request->block)->where('Party', $request->parchi_image)->first();

        if(!$electionSector){
            return response(['message' => 'ELECTION_SECTOR']);

//            return redirect()->back()->with('error', 'Election sector are not exist');
        }
        else if(!$blockCode){
            return response(['message' => 'BLOCK_CODE']);

//            return redirect()->back()->with('error', 'Block Code are not exist');
        }
//        else if(!$pollingScheme){
//            return response(['message' => 'POLLING_SCHEME']);
//
////            return redirect()->back()->with('error', 'Polling schemes are not exist');
//        }
//        else if(!$parchiImages){
//            return response(['message' => 'PARCHI_IMAGE']);
//
////            return redirect()->back()->with('error', 'candidate Details are not exist');
//        }
        else
        {

            $polling_details = PollingDetail::where('polling_station_number', $request->block)
                ->orderBy('serial_no', 'asc')
                ->where('gender', 'male')
                ->get();

            $fpolling_details = PollingDetail::where('polling_station_number', $request->block)
                ->orderBy('serial_no', 'asc')
                ->where('gender', 'female')
                ->get();

            $record_to_be_fetched = 100;
            if($pdf_type === 'LIST'){
                $record_to_be_fetched = 500;
            }


            $total = count($polling_details); // 500

            for( $i = 0; $i < ceil($total / $record_to_be_fetched); $i++) {

                $ids = $polling_details->slice( $record_to_be_fetched * $i, $record_to_be_fetched )->pluck('id');

                $data = PdfPolling::insert([
                    'email' => $request->email,
                    'block_code' => $request->block,
                    'status' => 'PENDING',
                    'type'=> $pdf_type,
                    'cron_status' => '0',
                    'party_type' => $request->parchi_image,
                    'meta' => json_encode(['ids' =>$ids, 'gender' => 'Male', 'i' => $i, 'l' => round($total / $record_to_be_fetched) ])
                ]);

            }


            $ftotal = count($fpolling_details); // 500

            for( $i = 0; $i < ($ftotal / $record_to_be_fetched); $i++) {

                $ids  = $fpolling_details->slice( $record_to_be_fetched * $i, $record_to_be_fetched )->pluck('id');

                $meta = ['ids' =>$ids, 'gender' => 'Female', 'i' => $i, 'l' => round($ftotal / $record_to_be_fetched) ];

                if($i == (ceil($ftotal / $record_to_be_fetched) - 1) ) {
                    $meta['sendMail'] = [
                        'total_expected_sent_mails' => $total % $record_to_be_fetched === 0 && $ftotal % $record_to_be_fetched === 0 ? ceil(($total + $ftotal) / $record_to_be_fetched) : ceil(($total + $ftotal) / $record_to_be_fetched) + 1,
                        'total_records_found' => $total + $ftotal,
                        'total_male_records_found' => $total,
                        'total_female_records_found' => $ftotal,
                        'block_code' => $request->block,
                        'sector' => $electionSector->sector
                    ];
                }

                $data = PdfPolling::insert([
                    'email' => $request->email,
                    'block_code' => $request->block,
                    'status' => 'PENDING',
                    'type'=> $pdf_type,
                    'cron_status' => '0',
                    'party_type' => $request->parchi_image,
                    'meta' => json_encode($meta)
                ]);

            }

            return response(['message' => 'ADD_NEW']);

        }

    }

    public function dailyMailGraph()
    {
        $pdfPolling = PdfPolling::select(DB::raw('count(*) as total'), DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day'))
            ->where('created_at','!=',null)
            ->groupby('year', 'month', 'day')
            ->get();
        $dailyMailReport= array();
        foreach ($pdfPolling as $key => $value) {
            $monthNum = $value->month;
            $dateObj = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F');
            $dailyMailReport[$key]['label'] = $value->day . " " . $monthName . " " . $value->year;
            $dailyMailReport[$key]['y'] = $value->total;
        }
        return view('admin.pdf-polling.dailyMailGraph',compact('dailyMailReport'));

    }

    public function voterParchiView($block_code)
    {
        $mpolling_details=array();
        $fpolling_details=array();
        $electionSector='';
        ini_set('max_execution_time', '-1');
        $party =$_GET['party'];
        if(Auth::user()->id == 49) {
            $parchiimages = ParchiImage::where('block_code', $block_code)->first();
        }
        else
        {
            $parchiimages=ParchiImage::where('block_code',$block_code)->where('Party',$party)->first();

        }
        // dd($parchiimages);
        $mgender="male";
        if ($parchiimages)
        {
            $mpolling_details = PollingDetail::where('polling_station_number', $block_code)
                ->orderBy('serial_no', 'asc')
                ->where('gender', 'male')
                ->with('voter_phone')
                ->with(['SchemeAddress'=>function($query) use ($mgender){
                    $query->where('gender_type',$mgender)
                        ->orwhere('gender_type','combined');
                }])
                ->select('id', 'type','crop_settings','url','serial_no','family_no','polling_station_number','cnic')
                ->get();
            $electionSector=ElectionSector::where('block_code',$block_code)->first();
            $gender='female';
            $fpolling_details = PollingDetail::where('polling_station_number', $block_code)
                ->orderBy('serial_no', 'asc')
                ->where('gender', 'female')
                ->with('voter_phone')
                ->with(['SchemeAddress'=>function($query) use ($gender){
                    $query->where('gender_type',$gender)
                        ->orwhere('gender_type','combined');
                }])
                ->select('id', 'type','crop_settings','url','serial_no','family_no','polling_station_number','cnic')
                ->get();
        }
        else
        {
            $mpolling_details = PollingDetail::where('polling_station_number', $block_code)
                ->orderBy('serial_no', 'asc')
                ->where('gender', 'male')
                ->with('voter_phone')
                ->with(['SchemeAddress'=>function($query) use ($mgender){
                    $query->where('gender_type',$mgender)
                        ->orwhere('gender_type','combined');
                }])
                ->select('id', 'type','crop_settings','url','serial_no','family_no','polling_station_number','cnic')
                ->get();
            $electionSector=ElectionSector::where('block_code',$block_code)->first();
            $gender='female';
            $fpolling_details = PollingDetail::where('polling_station_number', $block_code)
                ->orderBy('serial_no', 'asc')
                ->where('gender', 'female')
                ->with('voter_phone')
                ->with(['SchemeAddress'=>function($query) use ($gender){
                    $query->where('gender_type',$gender)
                        ->orwhere('gender_type','combined');
                }])
                ->select('id', 'type','crop_settings','url','serial_no','family_no','polling_station_number','cnic')
                ->get();
        }
        $dpi = 400;

        return view('email.voterParchi', compact('mpolling_details','fpolling_details','block_code','parchiimages','electionSector' , 'dpi'));
    }
}
