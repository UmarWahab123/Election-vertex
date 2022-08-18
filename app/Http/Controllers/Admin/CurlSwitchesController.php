<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CurlSwitch\BulkDestroyCurlSwitch;
use App\Http\Requests\Admin\CurlSwitch\DestroyCurlSwitch;
use App\Http\Requests\Admin\CurlSwitch\IndexCurlSwitch;
use App\Http\Requests\Admin\CurlSwitch\StoreCurlSwitch;
use App\Http\Requests\Admin\CurlSwitch\UpdateCurlSwitch;
use App\Models\CurlSwitch;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use http\Env\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CurlSwitchesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexCurlSwitch $request
     * @return array|Factory|View
     */
    public function index(IndexCurlSwitch $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(CurlSwitch::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'status'],

            // set columns to searchIn
            ['id', 'name']
        );

        $switches = CurlSwitch::all();

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.curl-switch.index', ['data' => $data , 'switches' => $switches]);
    }

    public function updateSwitchStatus($switch_id){
        $switch = CurlSwitch::where('id', $switch_id)->first();
        if($switch->status == 0){
            $switch->update(['status' => 1]);
        } else {
            $switch->update(['status' => 0]);
        }
        return response(['status' => $switch->status]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.curl-switch.create');

        return view('admin.curl-switch.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCurlSwitch $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreCurlSwitch $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the CurlSwitch
        $curlSwitch = CurlSwitch::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/curl-switches'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/curl-switches');
    }

    /**
     * Display the specified resource.
     *
     * @param CurlSwitch $curlSwitch
     * @throws AuthorizationException
     * @return void
     */
    public function show(CurlSwitch $curlSwitch)
    {
        $this->authorize('admin.curl-switch.show', $curlSwitch);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CurlSwitch $curlSwitch
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(CurlSwitch $curlSwitch)
    {
        $this->authorize('admin.curl-switch.edit', $curlSwitch);


        return view('admin.curl-switch.edit', [
            'curlSwitch' => $curlSwitch,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCurlSwitch $request
     * @param CurlSwitch $curlSwitch
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateCurlSwitch $request, CurlSwitch $curlSwitch)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values CurlSwitch
        $curlSwitch->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/curl-switches'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/curl-switches');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCurlSwitch $request
     * @param CurlSwitch $curlSwitch
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyCurlSwitch $request, CurlSwitch $curlSwitch)
    {
        $curlSwitch->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyCurlSwitch $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyCurlSwitch $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    CurlSwitch::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
