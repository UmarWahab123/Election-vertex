<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BusinessAccount\BulkDestroyBusinessAccount;
use App\Http\Requests\Admin\BusinessAccount\DestroyBusinessAccount;
use App\Http\Requests\Admin\BusinessAccount\IndexBusinessAccount;
use App\Http\Requests\Admin\BusinessAccount\StoreBusinessAccount;
use App\Http\Requests\Admin\BusinessAccount\UpdateBusinessAccount;
use App\Models\BusinessAccount;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BusinessAccountController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexBusinessAccount $request
     * @return array|Factory|View
     */
    public function index(IndexBusinessAccount $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(BusinessAccount::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'business_id', 'ref_id', 'credit', 'debit', 'balance', 'status','details'],

            // set columns to searchIn
            ['id', 'business_id', 'ref_id', 'credit', 'debit', 'balance', 'img_url', 'status', 'meta','details']
//            function ($query){
//                $query->where('');
//            }
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.business-account.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.business-account.create');

        return view('admin.business-account.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBusinessAccount $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreBusinessAccount $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the BusinessAccount
        $businessAccount = BusinessAccount::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/business-accounts'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/business-accounts');
    }

    /**
     * Display the specified resource.
     *
     * @param BusinessAccount $businessAccount
     * @throws AuthorizationException
     * @return void
     */
    public function show(BusinessAccount $businessAccount)
    {
        $this->authorize('admin.business-account.show', $businessAccount);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param BusinessAccount $businessAccount
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(BusinessAccount $businessAccount)
    {
        $this->authorize('admin.business-account.edit', $businessAccount);


        return view('admin.business-account.edit', [
            'businessAccount' => $businessAccount,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBusinessAccount $request
     * @param BusinessAccount $businessAccount
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateBusinessAccount $request, BusinessAccount $businessAccount)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values BusinessAccount
        $businessAccount->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/business-accounts'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/business-accounts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyBusinessAccount $request
     * @param BusinessAccount $businessAccount
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyBusinessAccount $request, BusinessAccount $businessAccount)
    {
        $businessAccount->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyBusinessAccount $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyBusinessAccount $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    BusinessAccount::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }


    public function createAccount()
    {
        $this->authorize('admin.business-account.create');
        return view('admin.business-account.CreateAccount');
    }

    public function storeAccount(StoreBusinessAccount $request)
    {
        $sanitized = $request->getSanitized();
        // Store the BusinessAccount
        $businessAccount = BusinessAccount::create($sanitized);
        $businessAccount->business_id = Auth::user()->business_id;
        $businessAccount->status = 'ACTIVE';
        $businessAccount->save();
        if ($request->ajax()) {
            return ['redirect' => url('admin/business-accounts'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/business-accounts');
    }


}
