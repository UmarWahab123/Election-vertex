<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ElectionSetting\BulkDestroyElectionSetting;
use App\Http\Requests\Admin\ElectionSetting\DestroyElectionSetting;
use App\Http\Requests\Admin\ElectionSetting\IndexElectionSetting;
use App\Http\Requests\Admin\ElectionSetting\StoreElectionSetting;
use App\Http\Requests\Admin\ElectionSetting\UpdateElectionSetting;
use App\Models\ElectionSetting;
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
use Auth;

class ElectionSettingController extends Controller
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
     * @param IndexElectionSetting $request
     * @return array|Factory|View
     */
    public function index(IndexElectionSetting $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(ElectionSetting::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id','meta_key','meta_value'],

            // set columns to searchIn
            ['id', 'meta_key', 'meta_value']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.election-setting.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.election-setting.create');

        return view('admin.election-setting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreElectionSetting $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreElectionSetting $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the ElectionSetting
        $electionSetting = ElectionSetting::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/election-settings'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/election-settings');
    }

    /**
     * Display the specified resource.
     *
     * @param ElectionSetting $electionSetting
     * @throws AuthorizationException
     * @return void
     */
    public function show(ElectionSetting $electionSetting)
    {
        $this->authorize('admin.election-setting.show', $electionSetting);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ElectionSetting $electionSetting
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(ElectionSetting $electionSetting)
    {
        $this->authorize('admin.election-setting.edit', $electionSetting);


        return view('admin.election-setting.edit', [
            'electionSetting' => $electionSetting,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateElectionSetting $request
     * @param ElectionSetting $electionSetting
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateElectionSetting $request, ElectionSetting $electionSetting)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values ElectionSetting
        $electionSetting->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/election-settings'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/election-settings');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyElectionSetting $request
     * @param ElectionSetting $electionSetting
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyElectionSetting $request, ElectionSetting $electionSetting)
    {
        $electionSetting->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyElectionSetting $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyElectionSetting $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    ElectionSetting::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function totalParty()
    {
       $parties =  ElectionSetting::where('meta_key','party')->get();
       dd($parties);
        return view('admin.customer.customer-order-form',compact('parties'));

    }
}
