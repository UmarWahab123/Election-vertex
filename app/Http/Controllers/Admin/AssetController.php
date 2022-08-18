<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\BulkDestroyAsset;
use App\Http\Requests\Admin\Asset\DestroyAsset;
use App\Http\Requests\Admin\Asset\IndexAsset;
use App\Http\Requests\Admin\Asset\StoreAsset;
use App\Http\Requests\Admin\Asset\UpdateAsset;
use App\Models\Asset;
use App\Models\Tag;
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

class AssetController extends Controller
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
     * @param IndexAsset $request
     * @return array|Factory|View
     */
    public function index(IndexAsset $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Asset::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'tag_id', 'url', 'title', 'content','htmlload', 'status'],

            // set columns to searchIn
            ['id', 'tag_id', 'url', 'title', 'content','htmlload', 'status'],

            function ($query) use ($request) {
                $query->join('tags','tags.id','asset.tag_id');
                $query->where('asset.business_id', Auth::user()->business_id);
                $query->select('tags.tag_name','asset.*');
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

        return view('admin.asset.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.asset.create');
        $auth_id = Auth::user()->business_id;
        $tags = Tag::where('business_id' , $auth_id)->get();
        return view('admin.asset.create', ['tags' => $tags]);
    }

    public function createhtml()
    {
        $this->authorize('admin.asset.create');
        $auth_id = Auth::user()->business_id;
        $tags = Tag::where('business_id' , $auth_id)->get();
        return view('admin.asset.htmltab',['tags'=>$tags]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAsset $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreAsset $request)
    {
        $sanitized = $request->getSanitized();
        // Store the Asset
        $asset = Asset::create($sanitized);
        $asset->business_id = Auth::user()->business_id;
        $asset->update();

        if ($request->ajax()) {
            return ['redirect' => url('admin/assets'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/assets');
    }

    /**
     * Display the specified resource.
     *
     * @param Asset $asset
     * @throws AuthorizationException
     * @return void
     */
    public function show(Asset $asset)
    {
        $this->authorize('admin.asset.show', $asset);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Asset $asset
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Asset $asset)
    {
        $this->authorize('admin.asset.edit', $asset);
        $auth_id = Auth::user()->business_id;
        $tags = Tag::where('business_id' , $auth_id)->get();

        return view('admin.asset.edit', [
            'asset' => $asset, 'tags' => $tags
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAsset $request
     * @param Asset $asset
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateAsset $request, Asset $asset)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Asset
        $asset->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/assets'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/assets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyAsset $request
     * @param Asset $asset
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyAsset $request, Asset $asset)
    {
        $asset->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyAsset $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyAsset $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Asset::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
