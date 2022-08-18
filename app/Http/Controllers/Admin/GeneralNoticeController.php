<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GeneralNotice\BulkDestroyGeneralNotice;
use App\Http\Requests\Admin\GeneralNotice\DestroyGeneralNotice;
use App\Http\Requests\Admin\GeneralNotice\IndexGeneralNotice;
use App\Http\Requests\Admin\GeneralNotice\StoreGeneralNotice;
use App\Http\Requests\Admin\GeneralNotice\UpdateGeneralNotice;
use App\Models\GeneralNotice;
use App\Models\Tag;
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

class GeneralNoticeController extends Controller
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
     * @param IndexGeneralNotice $request
     * @return array|Factory|View
     */
    public function index(IndexGeneralNotice $request)
    {
        if(!Auth::user()->can('admin.super-admin')) {
            // create and AdminListing instance for a specific model and
            $data = AdminListing::create(GeneralNotice::class)->processRequestAndGet(
            // pass the request with params
                $request,

                // set columns to query
                ['id', 'bussiness_id', 'title', 'content', 'html_tag'],

                // set columns to searchIn
                ['id', 'bussiness_id', 'title', 'content', 'html_tag'],

                function ($query) use ($request) {
                    $query->where('bussiness_id', Auth::user()->business_id);
                }
            );
        }
        else
        {
            $data = AdminListing::create(GeneralNotice::class)->processRequestAndGet(
            // pass the request with params
                $request,

                // set columns to query
                ['id', 'bussiness_id', 'title', 'content', 'html_tag'],

                // set columns to searchIn
                ['id', 'bussiness_id', 'title', 'content', 'html_tag']

            );
        }
        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.general-notice.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.general-notice.create');

        return view('admin.general-notice.create');
    }

    public function createhtml()
    {
        $this->authorize('admin.asset.create');
        $auth_id = Auth::user()->business_id;
       return view('admin.general-notice.htmlTag');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreGeneralNotice $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreGeneralNotice $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the GeneralNotice
        $generalNotice = GeneralNotice::create($sanitized);
        $generalNotice->bussiness_id = Auth::user()->business_id;
        $generalNotice->update();

        if ($request->ajax()) {
            return ['redirect' => url('admin/general-notices'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/general-notices');
    }

    /**
     * Display the specified resource.
     *
     * @param GeneralNotice $generalNotice
     * @throws AuthorizationException
     * @return void
     */
    public function show(GeneralNotice $generalNotice)
    {
        $this->authorize('admin.general-notice.show', $generalNotice);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param GeneralNotice $generalNotice
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(GeneralNotice $generalNotice)
    {
        $this->authorize('admin.general-notice.edit', $generalNotice);


        return view('admin.general-notice.edit', [
            'generalNotice' => $generalNotice,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateGeneralNotice $request
     * @param GeneralNotice $generalNotice
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateGeneralNotice $request, GeneralNotice $generalNotice)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values GeneralNotice
        $generalNotice->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/general-notices'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/general-notices');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyGeneralNotice $request
     * @param GeneralNotice $generalNotice
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyGeneralNotice $request, GeneralNotice $generalNotice)
    {
        $generalNotice->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyGeneralNotice $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyGeneralNotice $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    GeneralNotice::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
