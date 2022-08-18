<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PollingDetail\BulkDestroyPollingDetail;
use App\Http\Requests\Admin\PollingDetail\DestroyPollingDetail;
use App\Http\Requests\Admin\PollingDetail\IndexPollingDetail;
use App\Http\Requests\Admin\PollingDetail\StorePollingDetail;
use App\Http\Requests\Admin\PollingDetail\UpdatePollingDetail;
use App\Models\PollingDetail;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Auth;

class PollingDetailsController extends Controller
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


    public function saveVoterDetails($id , $s_no , $f_no){
        $polling_detail = PollingDetail::where('id' , $id)->first();
        $polling_detail->serial_no = $s_no;
        $polling_detail->family_no = $f_no;
        $polling_detail->save();

        return response(['message' => 'saved']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexPollingDetail $request
     * @return array|Factory|View
     */
    public function index(IndexPollingDetail $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(PollingDetail::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'polling_station_id', 'polling_station_number', 'cnic', 'page_no', 'url', 'url_id', 'status', 'pic_slice' , 'crop_settings'],

            // set columns to searchIn
            ['id', 'cnic','polling_station_number','polling_station_id']

//            function ($query) use ($request) {
//                $query->where('polling_station_number', 188110704);
//            }
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.polling-detail.index', ['data' => $data]);
    }

    public function missing_entities($blockcode)
    {
        $data = DB::table('polling_details')->where('polling_station_number' , $blockcode)->where('serial_no' , null)->take(10)->get();
        $count = count($data);
//        dd($data);

        return view('admin.polling-detail.manual-entry' , compact('data' , 'count'));
    }

    public function save_details(Request $request){
        PollingDetail::where('id' , $request->detail_id)->update(['age' => $request->age , 'family_no' => $request->fam_no , 'serial_no' => $request->serial_no]);

        return response(['message' => 'ok']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.polling-detail.create');

        return view('admin.polling-detail.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePollingDetail $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StorePollingDetail $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the PollingDetail
        $pollingDetail = PollingDetail::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/polling-details'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/polling-details');
    }

    /**
     * Display the specified resource.
     *
     * @param PollingDetail $pollingDetail
     * @throws AuthorizationException
     * @return void
     */
    public function show(PollingDetail $pollingDetail)
    {
        $this->authorize('admin.polling-detail.show', $pollingDetail);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PollingDetail $pollingDetail
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(PollingDetail $pollingDetail)
    {
        $this->authorize('admin.polling-detail.edit', $pollingDetail);


        return view('admin.polling-detail.edit', [
            'pollingDetail' => $pollingDetail,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePollingDetail $request
     * @param PollingDetail $pollingDetail
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdatePollingDetail $request, PollingDetail $pollingDetail)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values PollingDetail
        $pollingDetail->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/polling-details'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/polling-details');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPollingDetail $request
     * @param PollingDetail $pollingDetail
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyPollingDetail $request, PollingDetail $pollingDetail)
    {
        $pollingDetail->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyPollingDetail $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyPollingDetail $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    PollingDetail::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

}
