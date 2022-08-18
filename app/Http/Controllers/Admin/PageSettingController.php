<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageSetting\BulkDestroyPageSetting;
use App\Http\Requests\Admin\PageSetting\DestroyPageSetting;
use App\Http\Requests\Admin\PageSetting\IndexPageSetting;
use App\Http\Requests\Admin\PageSetting\StorePageSetting;
use App\Http\Requests\Admin\PageSetting\UpdatePageSetting;
use App\Models\PageSetting;
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

class PageSettingController extends Controller
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
     * @param IndexPageSetting $request
     * @return array|Factory|View
     */
    public function index(IndexPageSetting $request)
    {
        if(!Auth::user()->can('admin.super-admin')) {
            // create and AdminListing instance for a specific model and
        $data = AdminListing::create(PageSetting::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'business_id', 'tag_name', 'businessHome_H1', 'businessHome_H2', 'businessHome_H3','reg_title','reg_img_title', 'status'],

            // set columns to searchIn
            ['id', 'business_id', 'tag_name', 'businessHome_H1', 'businessHome_H2', 'businessHome_H3','reg_title','reg_img_title', 'status'],
               function ($query) use ($request)
                {
                    $query->where('business_id', Auth::user()->business_id);
                }

        );
            }
        else{
            $data = AdminListing::create(PageSetting::class)->processRequestAndGet(
            // pass the request with params
                $request,

                // set columns to query
                ['id', 'business_id', 'tag_name', 'businessHome_H1', 'businessHome_H2', 'businessHome_H3','reg_title','reg_img_title', 'status'],

                // set columns to searchIn
                ['id', 'business_id', 'tag_name', 'businessHome_H1', 'businessHome_H2', 'businessHome_H3','reg_title','reg_img_title', 'status']

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

        return view('admin.page-setting.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.page-setting.create');

        return view('admin.page-setting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePageSetting $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StorePageSetting $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the PageSetting
        $pageSetting = PageSetting::create($sanitized);
        $pageSetting->business_id = Auth::user()->business_id;
        $pageSetting->update();
        if ($request->ajax()) {
            return ['redirect' => url('admin/page-settings'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/page-settings');
    }

    /**
     * Display the specified resource.
     *
     * @param PageSetting $pageSetting
     * @throws AuthorizationException
     * @return void
     */
    public function show(PageSetting $pageSetting)
    {
        $this->authorize('admin.page-setting.show', $pageSetting);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PageSetting $pageSetting
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(PageSetting $pageSetting)
    {
        $this->authorize('admin.page-setting.edit', $pageSetting);


        return view('admin.page-setting.edit', [
            'pageSetting' => $pageSetting,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePageSetting $request
     * @param PageSetting $pageSetting
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdatePageSetting $request, PageSetting $pageSetting)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values PageSetting
        $pageSetting->update($sanitized);
//        $pageSetting->business_id = Auth::user()->business_id;
//        $pageSetting->update();
        if ($request->ajax()) {
            return [
                'redirect' => url('admin/page-settings'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/page-settings');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPageSetting $request
     * @param PageSetting $pageSetting
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyPageSetting $request, PageSetting $pageSetting)
    {
        $pageSetting->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyPageSetting $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyPageSetting $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    PageSetting::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
