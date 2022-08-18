<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataSet\BulkDestroyDataSet;
use App\Http\Requests\Admin\DataSet\DestroyDataSet;
use App\Http\Requests\Admin\DataSet\IndexDataSet;
use App\Http\Requests\Admin\DataSet\StoreDataSet;
use App\Http\Requests\Admin\DataSet\UpdateDataSet;
use App\Models\DataSet;
use App\Models\ElectionSector;
use App\Models\PdfPolling;
use App\Models\PollingDetail;
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

class DataSetController extends Controller
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
     * @param IndexDataSet $request
     * @return array|Factory|View
     */
    public function index(IndexDataSet $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(DataSet::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'phone', 'tag', 'status','address'],

            // set columns to searchIn
            ['id', 'address', 'tag', 'phone', 'status']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.data-set.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.data-set.create');

        return view('admin.data-set.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDataSet $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreDataSet $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the DataSet
        $dataSet = DataSet::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/data-sets'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/data-sets');
    }

    /**
     * Display the specified resource.
     *
     * @param DataSet $dataSet
     * @throws AuthorizationException
     * @return void
     */
    public function show(DataSet $dataSet)
    {
        $this->authorize('admin.data-set.show', $dataSet);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param DataSet $dataSet
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(DataSet $dataSet)
    {
        $this->authorize('admin.data-set.edit', $dataSet);


        return view('admin.data-set.edit', [
            'dataSet' => $dataSet,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDataSet $request
     * @param DataSet $dataSet
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateDataSet $request, DataSet $dataSet)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values DataSet
        $dataSet->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/data-sets'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/data-sets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyDataSet $request
     * @param DataSet $dataSet
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyDataSet $request, DataSet $dataSet)
    {
        $dataSet->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyDataSet $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyDataSet $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    DataSet::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

//broadcast api function

    public function broadcastQuery($tag)
    {
        $dataset=DataSet::where('status','ACTIVE')->where('tag',$tag)->get('phone');
        return $dataset;
    }

    public function getRecordDHALahore($blockcode)
    {
        $electionsector=ElectionSector::where('sector','WCB 3')->get('block_code');
        $queryCnicString = '';

        $pollingdetails = PollingDetail::where('polling_station_number', $blockcode)->get('cnic');

        foreach ($pollingdetails as $key => $value) {
            $cnic = str_replace('-', '', $value->cnic);
            $value->cnic_number=$cnic;
            $queryCnicString = $queryCnicString . $cnic . ' ';
        }
//        }

        $azzure_result = $this->azzureApiResponse($queryCnicString);
        foreach ($pollingdetails as $key => $value)
        {
            $search_result = $this->search_by_cnic($azzure_result, $value->cnic_number);
            if ($search_result) {
                try {
                    $dataset = DataSet::where('phone', 0 . $search_result['phone1'])->first();
                    if ($dataset) {
                        $datasetinsert = DataSet::where('id',$dataset->id)->update([
                            'meta' => $blockcode .','.'WCB 5' ,
                        ]);
                        return 'update';
                    }
                    else
                    {
                        $datasetinsert = DataSet::create([
                            'phone' => 0 . $search_result['phone1'],
                            'address' => $search_result['address1'] . $search_result['address2'] . $search_result['address3'],
                            'latlng' => '31.4699, 74.4391',
                            'tag' => 'DHA_Lahore_Custom',
                            'status' => 'ACTIVE',
                            'meta'=> $blockcode .','.'WCB 5',

                        ]);
                    }
                }
                catch (Exception $e)
                {

                }

            }
        }
        return true;
    }


    public function azzureApiResponse($queryCnicString)
    {
        $post = ['count' => 'true', 'minimumCoverage' => 100, 'queryType' => 'full', 'top' => 5000, 'search' => $queryCnicString];
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
        $response = json_decode($response,true);
        $resp = $response['value'];
        return $resp;
    }

    public  function search_by_cnic($response,$search_string)
    {
        foreach ($response as $res)
        {
            if($res['idcard'] == $search_string)
            {
                return $res;
            }
        }
        return 0;
    }


}
