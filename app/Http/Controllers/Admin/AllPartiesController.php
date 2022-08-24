<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AllParty\BulkDestroyAllParty;
use App\Http\Requests\Admin\AllParty\DestroyAllParty;
use App\Http\Requests\Admin\AllParty\IndexAllParty;
use App\Http\Requests\Admin\AllParty\StoreAllParty;
use App\Http\Requests\Admin\AllParty\UpdateAllParty;
use App\Models\AllParty;
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

class AllPartiesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexAllParty $request
     * @return array|Factory|View
     */
    public function index(IndexAllParty $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(AllParty::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id','party_name', 'party_image_url', 'created_by'],

            // set columns to searchIn
            ['id', 'party_name', 'party_image_url', 'created_by']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.all-party.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.all-party.create');

        return view('admin.all-party.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAllParty $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreAllParty $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['created_by'] = Auth::user()->id;
        

        // Store the AllParty
        $allParty = AllParty::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/all-parties'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/all-parties');
    }

    /**
     * Display the specified resource.
     *
     * @param AllParty $allParty
     * @throws AuthorizationException
     * @return void
     */
    public function show(AllParty $allParty)
    {
        $this->authorize('admin.all-party.show', $allParty);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AllParty $allParty
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(AllParty $allParty)
    {
        $this->authorize('admin.all-party.edit', $allParty);


        return view('admin.all-party.edit', [
            'allParty' => $allParty,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAllParty $request
     * @param AllParty $allParty
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateAllParty $request, AllParty $allParty)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values AllParty
        $allParty->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/all-parties'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/all-parties');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyAllParty $request
     * @param AllParty $allParty
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyAllParty $request, AllParty $allParty)
    {
        $allParty->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyAllParty $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyAllParty $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    AllParty::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
