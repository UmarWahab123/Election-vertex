<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\S3uploadingMember\BulkDestroyS3uploadingMember;
use App\Http\Requests\Admin\S3uploadingMember\DestroyS3uploadingMember;
use App\Http\Requests\Admin\S3uploadingMember\IndexS3uploadingMember;
use App\Http\Requests\Admin\S3uploadingMember\StoreS3uploadingMember;
use App\Http\Requests\Admin\S3uploadingMember\UpdateS3uploadingMember;
use App\Models\S3uploadingMember;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class S3uploadingMembersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexS3uploadingMember $request
     * @return array|Factory|View
     */
    public function index(IndexS3uploadingMember $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(S3uploadingMember::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'email', 'phone', 'party', 'last_login', 'ip_address', 'is_loggedin', 'status'],

            // set columns to searchIn
            ['id', 'name', 'email', 'phone', 'party', 'last_login', 'ip_address', 'is_loggedin', 'status']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.s3uploading-member.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.s3uploading-member.create');

        return view('admin.s3uploading-member.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreS3uploadingMember $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreS3uploadingMember $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();


        // Store the S3uploadingMember
        $s3uploadingMember = S3uploadingMember::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/s3uploading-members'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/s3uploading-members');
    }

    /**
     * Display the specified resource.
     *
     * @param S3uploadingMember $s3uploadingMember
     * @throws AuthorizationException
     * @return void
     */
    public function show(S3uploadingMember $s3uploadingMember)
    {
        $this->authorize('admin.s3uploading-member.show', $s3uploadingMember);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param S3uploadingMember $s3uploadingMember
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(S3uploadingMember $s3uploadingMember)
    {
        $this->authorize('admin.s3uploading-member.edit', $s3uploadingMember);


        return view('admin.s3uploading-member.edit', [
            's3uploadingMember' => $s3uploadingMember,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateS3uploadingMember $request
     * @param S3uploadingMember $s3uploadingMember
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateS3uploadingMember $request, S3uploadingMember $s3uploadingMember)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values S3uploadingMember
        $s3uploadingMember->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/s3uploading-members'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/s3uploading-members');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyS3uploadingMember $request
     * @param S3uploadingMember $s3uploadingMember
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyS3uploadingMember $request, S3uploadingMember $s3uploadingMember)
    {
        $s3uploadingMember->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyS3uploadingMember $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyS3uploadingMember $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    S3uploadingMember::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }


    public  function  loginMember(Request $request)
    {
       if(!empty($request->email) && !empty($request->password))
       {
          $member = S3uploadingMember::whereEmail($request->email)
               ->wherePassword($request->password)
               ->whereStatus('Active')
               ->first();
          if(!$member)
          {
              return \response()->json(['error'=>true,'message'=>'User Not Found']);
          }

            $member->is_loggedin = true;
            $member->last_login = date('Y-m-d H:m:s');
            $member->ip_address = $request->ip();
            $member->save();


           return \response()->json(['error'=>false,'message'=>'Login Success','user'=>$member]);

       }


    }
}
