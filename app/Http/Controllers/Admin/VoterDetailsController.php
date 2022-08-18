<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VoterDetail\BulkDestroyVoterDetail;
use App\Http\Requests\Admin\VoterDetail\DestroyVoterDetail;
use App\Http\Requests\Admin\VoterDetail\IndexVoterDetail;
use App\Http\Requests\Admin\VoterDetail\StoreVoterDetail;
use App\Http\Requests\Admin\VoterDetail\UpdateVoterDetail;
use App\Imports\pollingDetailsImports;
use App\Models\VoterDetail;
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
use Maatwebsite\Excel\Facades\Excel;

class VoterDetailsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexVoterDetail $request
     * @return array|Factory|View
     */
    public function index(IndexVoterDetail $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(VoterDetail::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'id_card', 'serial_no', 'family_no', 'name', 'father_name', 'address', 'block_code', 'status', 'meta'],

            // set columns to searchIn
            ['id', 'id_card', 'serial_no', 'family_no', 'name', 'father_name', 'address', 'block_code', 'status', 'meta']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.voter-detail.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.voter-detail.create');

        return view('admin.voter-detail.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVoterDetail $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreVoterDetail $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the VoterDetail
        $voterDetail = VoterDetail::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/voter-details'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/voter-details');
    }

    /**
     * Display the specified resource.
     *
     * @param VoterDetail $voterDetail
     * @throws AuthorizationException
     * @return void
     */
    public function show(VoterDetail $voterDetail)
    {
        $this->authorize('admin.voter-detail.show', $voterDetail);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param VoterDetail $voterDetail
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(VoterDetail $voterDetail)
    {
        $this->authorize('admin.voter-detail.edit', $voterDetail);


        return view('admin.voter-detail.edit', [
            'voterDetail' => $voterDetail,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateVoterDetail $request
     * @param VoterDetail $voterDetail
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateVoterDetail $request, VoterDetail $voterDetail)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values VoterDetail
        $voterDetail->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/voter-details'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/voter-details');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyVoterDetail $request
     * @param VoterDetail $voterDetail
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyVoterDetail $request, VoterDetail $voterDetail)
    {
        $voterDetail->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyVoterDetail $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyVoterDetail $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    VoterDetail::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
    public function import()
    {
        return view('admin.voter-detail.importVoterDetails');
    }

    public function importVoter(Request $request)
    {
//        dd($request->all());
        ini_set('max_execution_time', '-1');
        $data=Excel::import(new pollingDetailsImports,request()->file('myfile'));
        return redirect()->back();

    }

    public function voterDataApi(Request $request)
    {
        $files_count = count($request->all());
        $array = $request->all();
//        $token = 'auth:token:vertex:?socho:to:naya:pakistan?123456789$12';
//        if ($request[0]->token == $token) {
        foreach ($array as $data)
        {
            $first = substr($data['cnic'], 0, 5);
            $middle = substr($data['cnic'], 5, 7);
            $last = substr($data['cnic'], 12);
            $cnic_format = "$first-$middle-$last";
            try {
                $pollingDetails = VoterDetail::create([
                    'serial_no' => $data['serialNo'],
                    'family_no' => $data['familyNo'],
                    'name' => $data['name'],
                    'father_name' => $data['parent'],
                    'gender' => $data['gender'],
                    'id_card' => $cnic_format,
                    'age' => $data['age'],
                    'address' => $data['address'],
                    'block_code' => $data['block_code'],
                ]);
            }
            catch (Exception $e)
            {
                return false;
            }
        }
        return true;

//        else {
//            return 'Token mismatch';
//        }
    }
}
