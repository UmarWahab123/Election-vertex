<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tag\BulkDestroyTag;
use App\Http\Requests\Admin\Tag\DestroyTag;
use App\Http\Requests\Admin\Tag\IndexTag;
use App\Http\Requests\Admin\Tag\StoreTag;
use App\Http\Requests\Admin\Tag\UpdateTag;
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

class TagsController extends Controller
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
     * @param IndexTag $request
     * @return array|Factory|View
     */
    public function index(IndexTag $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Tag::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'business_id', 'tag_name', 'status'],

            // set columns to searchIn
            ['id', 'business_id', 'tag_name', 'status'],

            function ($query) use ($request) {
                $query->where('business_id', Auth::user()->business_id);
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

        return view('admin.tag.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.tag.create');

        return view('admin.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTag $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreTag $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Tag
        $tag = Tag::create($sanitized);
        $tag->business_id = Auth::user()->business_id;
        $tag->update();
        if ($request->ajax()) {
            return ['redirect' => url('admin/tags'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/tags');
    }

    /**
     * Display the specified resource.
     *
     * @param Tag $tag
     * @throws AuthorizationException
     * @return void
     */
    public function show(Tag $tag)
    {
        $this->authorize('admin.tag.show', $tag);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Tag $tag
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Tag $tag)
    {
        $this->authorize('admin.tag.edit', $tag);


        return view('admin.tag.edit', [
            'tag' => $tag,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTag $request
     * @param Tag $tag
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateTag $request, Tag $tag)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Tag
        $tag->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/tags'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/tags');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyTag $request
     * @param Tag $tag
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyTag $request, Tag $tag)
    {
        $tag->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyTag $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyTag $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Tag::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
