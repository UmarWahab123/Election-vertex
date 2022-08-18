<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OfflineDataFile\BulkDestroyOfflineDataFile;
use App\Http\Requests\Admin\OfflineDataFile\DestroyOfflineDataFile;
use App\Http\Requests\Admin\OfflineDataFile\IndexOfflineDataFile;
use App\Http\Requests\Admin\OfflineDataFile\StoreOfflineDataFile;
use App\Http\Requests\Admin\OfflineDataFile\UpdateOfflineDataFile;
use App\Models\OfflineDataFile;
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

class OfflineDataFilesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexOfflineDataFile $request
     * @return array|Factory|View
     */
    public function index(IndexOfflineDataFile $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(OfflineDataFile::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            [''],

            // set columns to searchIn
            ['']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.offline-data-file.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.offline-data-file.create');

        return view('admin.offline-data-file.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOfflineDataFile $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreOfflineDataFile $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the OfflineDataFile
        $offlineDataFile = OfflineDataFile::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/offline-data-files'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/offline-data-files');
    }

    /**
     * Display the specified resource.
     *
     * @param OfflineDataFile $offlineDataFile
     * @throws AuthorizationException
     * @return void
     */
    public function show(OfflineDataFile $offlineDataFile)
    {
        $this->authorize('admin.offline-data-file.show', $offlineDataFile);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param OfflineDataFile $offlineDataFile
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(OfflineDataFile $offlineDataFile)
    {
        $this->authorize('admin.offline-data-file.edit', $offlineDataFile);


        return view('admin.offline-data-file.edit', [
            'offlineDataFile' => $offlineDataFile,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOfflineDataFile $request
     * @param OfflineDataFile $offlineDataFile
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateOfflineDataFile $request, OfflineDataFile $offlineDataFile)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values OfflineDataFile
        $offlineDataFile->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/offline-data-files'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/offline-data-files');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyOfflineDataFile $request
     * @param OfflineDataFile $offlineDataFile
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyOfflineDataFile $request, OfflineDataFile $offlineDataFile)
    {
        $offlineDataFile->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyOfflineDataFile $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyOfflineDataFile $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    OfflineDataFile::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
