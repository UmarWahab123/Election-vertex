<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserImage\BulkDestroyUserImage;
use App\Http\Requests\Admin\UserImage\DestroyUserImage;
use App\Http\Requests\Admin\UserImage\IndexUserImage;
use App\Http\Requests\Admin\UserImage\StoreUserImage;
use App\Http\Requests\Admin\UserImage\UpdateUserImage;
use App\Models\Tag;
use App\Models\UserImage;
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

class UserImageController extends Controller
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
     * @param IndexUserImage $request
     * @return array|Factory|View
     */
    public function index(IndexUserImage $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(UserImage::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'user_id', 'file_url'],

            // set columns to searchIn
            ['id', 'user_id', 'file_url'],
            function ($query) use ($request) {
                $query->where('business_id', Auth::user()->business_id);
                $query->join('users','users.id','user_image.user_id');
                $query->select('users.name','user_image.*');
            }
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.user-image.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.user-image.create');

        return view('admin.user-image.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserImage $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreUserImage $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the UserImage
        $userImage = UserImage::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/user-images'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/user-images');
    }

    /**
     * Display the specified resource.
     *
     * @param UserImage $userImage
     * @throws AuthorizationException
     * @return void
     */
    public function show(UserImage $userImage)
    {
        $this->authorize('admin.user-image.show', $userImage);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserImage $userImage
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(UserImage $userImage)
    {
        $this->authorize('admin.user-image.edit', $userImage);


        return view('admin.user-image.edit', [
            'userImage' => $userImage,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserImage $request
     * @param UserImage $userImage
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateUserImage $request, UserImage $userImage)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values UserImage
        $userImage->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/user-images'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/user-images');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyUserImage $request
     * @param UserImage $userImage
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyUserImage $request, UserImage $userImage)
    {
        $userImage->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyUserImage $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyUserImage $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    UserImage::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
