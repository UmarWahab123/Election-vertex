<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateCnicDetailTable\BulkDestroyCreateCnicDetailTable;
use App\Http\Requests\Admin\CreateCnicDetailTable\DestroyCreateCnicDetailTable;
use App\Http\Requests\Admin\CreateCnicDetailTable\IndexCreateCnicDetailTable;
use App\Http\Requests\Admin\CreateCnicDetailTable\StoreCreateCnicDetailTable;
use App\Http\Requests\Admin\CreateCnicDetailTable\UpdateCreateCnicDetailTable;
use App\Models\CreateCnicDetailTable;
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

class CreateCnicDetailTableController extends Controller
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
     * @param IndexCreateCnicDetailTable $request
     * @return array|Factory|View
     */
    public function index(IndexCreateCnicDetailTable $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(CreateCnicDetailTable::class)->processRequestAndGet(
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

        return view('admin.create-cnic-detail-table.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.create-cnic-detail-table.create');

        return view('admin.create-cnic-detail-table.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCreateCnicDetailTable $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreCreateCnicDetailTable $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the CreateCnicDetailTable
        $createCnicDetailTable = CreateCnicDetailTable::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/create-cnic-detail-tables'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/create-cnic-detail-tables');
    }

    /**
     * Display the specified resource.
     *
     * @param CreateCnicDetailTable $createCnicDetailTable
     * @throws AuthorizationException
     * @return void
     */
    public function show(CreateCnicDetailTable $createCnicDetailTable)
    {
        $this->authorize('admin.create-cnic-detail-table.show', $createCnicDetailTable);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CreateCnicDetailTable $createCnicDetailTable
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(CreateCnicDetailTable $createCnicDetailTable)
    {
        $this->authorize('admin.create-cnic-detail-table.edit', $createCnicDetailTable);


        return view('admin.create-cnic-detail-table.edit', [
            'createCnicDetailTable' => $createCnicDetailTable,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCreateCnicDetailTable $request
     * @param CreateCnicDetailTable $createCnicDetailTable
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateCreateCnicDetailTable $request, CreateCnicDetailTable $createCnicDetailTable)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values CreateCnicDetailTable
        $createCnicDetailTable->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/create-cnic-detail-tables'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/create-cnic-detail-tables');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCreateCnicDetailTable $request
     * @param CreateCnicDetailTable $createCnicDetailTable
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyCreateCnicDetailTable $request, CreateCnicDetailTable $createCnicDetailTable)
    {
        $createCnicDetailTable->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyCreateCnicDetailTable $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyCreateCnicDetailTable $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    CreateCnicDetailTable::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
