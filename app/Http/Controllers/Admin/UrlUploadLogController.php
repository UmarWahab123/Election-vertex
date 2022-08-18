<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UrlUploadLog\BulkDestroyUrlUploadLog;
use App\Http\Requests\Admin\UrlUploadLog\DestroyUrlUploadLog;
use App\Http\Requests\Admin\UrlUploadLog\IndexUrlUploadLog;
use App\Http\Requests\Admin\UrlUploadLog\StoreUrlUploadLog;
use App\Http\Requests\Admin\UrlUploadLog\UpdateUrlUploadLog;
use App\Models\UrlUploadLog;
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

class UrlUploadLogController extends Controller
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
     * @param IndexUrlUploadLog $request
     * @return array|Factory|View
     */
    public function index(IndexUrlUploadLog $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(UrlUploadLog::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'user_id', 'files_count'],

            // set columns to searchIn
            ['id', 'url_meta']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.url-upload-log.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.url-upload-log.create');

        return view('admin.url-upload-log.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUrlUploadLog $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreUrlUploadLog $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the UrlUploadLog
        $urlUploadLog = UrlUploadLog::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/url-upload-logs'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/url-upload-logs');
    }

    /**
     * Display the specified resource.
     *
     * @param UrlUploadLog $urlUploadLog
     * @throws AuthorizationException
     * @return void
     */
    public function show(UrlUploadLog $urlUploadLog)
    {
        $this->authorize('admin.url-upload-log.show', $urlUploadLog);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UrlUploadLog $urlUploadLog
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(UrlUploadLog $urlUploadLog)
    {
        $this->authorize('admin.url-upload-log.edit', $urlUploadLog);


        return view('admin.url-upload-log.edit', [
            'urlUploadLog' => $urlUploadLog,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUrlUploadLog $request
     * @param UrlUploadLog $urlUploadLog
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateUrlUploadLog $request, UrlUploadLog $urlUploadLog)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values UrlUploadLog
        $urlUploadLog->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/url-upload-logs'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/url-upload-logs');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyUrlUploadLog $request
     * @param UrlUploadLog $urlUploadLog
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyUrlUploadLog $request, UrlUploadLog $urlUploadLog)
    {
        $urlUploadLog->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyUrlUploadLog $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyUrlUploadLog $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    UrlUploadLog::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
