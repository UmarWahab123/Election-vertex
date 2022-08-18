<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PdfPollingLog\BulkDestroyPdfPollingLog;
use App\Http\Requests\Admin\PdfPollingLog\DestroyPdfPollingLog;
use App\Http\Requests\Admin\PdfPollingLog\IndexPdfPollingLog;
use App\Http\Requests\Admin\PdfPollingLog\StorePdfPollingLog;
use App\Http\Requests\Admin\PdfPollingLog\UpdatePdfPollingLog;
use App\Models\PdfPollingLog;
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

class PdfPollingLogController extends Controller
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
     * @param IndexPdfPollingLog $request
     * @return array|Factory|View
     */
    public function index(IndexPdfPollingLog $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(PdfPollingLog::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'key', 'value', 'meta', 'log'],

            // set columns to searchIn
            ['id', 'key', 'value', 'meta', 'log']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.pdf-polling-log.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.pdf-polling-log.create');

        return view('admin.pdf-polling-log.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePdfPollingLog $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StorePdfPollingLog $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the PdfPollingLog
        $pdfPollingLog = PdfPollingLog::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/pdf-polling-logs'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/pdf-polling-logs');
    }

    /**
     * Display the specified resource.
     *
     * @param PdfPollingLog $pdfPollingLog
     * @throws AuthorizationException
     * @return void
     */
    public function show(PdfPollingLog $pdfPollingLog)
    {
        $this->authorize('admin.pdf-polling-log.show', $pdfPollingLog);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PdfPollingLog $pdfPollingLog
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(PdfPollingLog $pdfPollingLog)
    {
        $this->authorize('admin.pdf-polling-log.edit', $pdfPollingLog);


        return view('admin.pdf-polling-log.edit', [
            'pdfPollingLog' => $pdfPollingLog,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePdfPollingLog $request
     * @param PdfPollingLog $pdfPollingLog
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdatePdfPollingLog $request, PdfPollingLog $pdfPollingLog)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values PdfPollingLog
        $pdfPollingLog->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/pdf-polling-logs'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/pdf-polling-logs');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPdfPollingLog $request
     * @param PdfPollingLog $pdfPollingLog
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyPdfPollingLog $request, PdfPollingLog $pdfPollingLog)
    {
        $pdfPollingLog->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyPdfPollingLog $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyPdfPollingLog $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    PdfPollingLog::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
