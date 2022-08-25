<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auditable\BulkDestroyAuditable;
use App\Http\Requests\Admin\Auditable\DestroyAuditable;
use App\Http\Requests\Admin\Auditable\IndexAuditable;
use App\Http\Requests\Admin\Auditable\StoreAuditable;
use App\Http\Requests\Admin\Auditable\UpdateAuditable;
use App\Models\TestAuditable;
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

class AuditableController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexAuditable $request
     * @return array|Factory|View
     */
    public function index(IndexAuditable $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(TestAuditable::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'status'],

            // set columns to searchIn
            ['id', 'name', 'email', 'phone', 'city', 'status']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.auditable.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.auditable.create');

        return view('admin.auditable.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAuditable $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreAuditable $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Auditable
        $auditable = TestAuditable::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/auditables'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/auditables');
    }

    /**
     * Display the specified resource.
     *
     * @param TestAuditable $auditable
     * @throws AuthorizationException
     * @return void
     */
    public function show(TestAuditable $auditable)
    {
        $this->authorize('admin.auditable.show', $auditable);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Auditable $auditable
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(TestAuditable $auditable)
    {
        $this->authorize('admin.auditable.edit', $auditable);


        return view('admin.auditable.edit', [
            'auditable' => $auditable,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAuditable $request
     * @param TestAuditable $auditable
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateAuditable $request, TestAuditable $auditable)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Auditable
        $auditable->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/auditables'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/auditables');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyAuditable $request
     * @param TestAuditable $auditable
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyAuditable $request, TestAuditable $auditable)
    {
        $auditable->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyAuditable $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyAuditable $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    TestAuditable::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
