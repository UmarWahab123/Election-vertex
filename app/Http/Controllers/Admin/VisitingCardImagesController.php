<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VisitingCardImage\BulkDestroyVisitingCardImage;
use App\Http\Requests\Admin\VisitingCardImage\DestroyVisitingCardImage;
use App\Http\Requests\Admin\VisitingCardImage\IndexVisitingCardImage;
use App\Http\Requests\Admin\VisitingCardImage\StoreVisitingCardImage;
use App\Http\Requests\Admin\VisitingCardImage\UpdateVisitingCardImage;
use App\Models\VisitingCardImage;
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

class VisitingCardImagesController extends Controller
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
     * @param IndexVisitingCardImage $request
     * @return array|Factory|View
     */
    public function index(IndexVisitingCardImage $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(VisitingCardImage::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'visiting_card_id', 'image_link'],

            // set columns to searchIn
            ['id', 'image_link']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.visiting-card-image.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.visiting-card-image.create');

        return view('admin.visiting-card-image.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVisitingCardImage $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreVisitingCardImage $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the VisitingCardImage
        $visitingCardImage = VisitingCardImage::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/visiting-card-images'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/visiting-card-images');
    }

    /**
     * Display the specified resource.
     *
     * @param VisitingCardImage $visitingCardImage
     * @throws AuthorizationException
     * @return void
     */
    public function show(VisitingCardImage $visitingCardImage)
    {
        $this->authorize('admin.visiting-card-image.show', $visitingCardImage);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param VisitingCardImage $visitingCardImage
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(VisitingCardImage $visitingCardImage)
    {
        $this->authorize('admin.visiting-card-image.edit', $visitingCardImage);


        return view('admin.visiting-card-image.edit', [
            'visitingCardImage' => $visitingCardImage,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateVisitingCardImage $request
     * @param VisitingCardImage $visitingCardImage
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateVisitingCardImage $request, VisitingCardImage $visitingCardImage)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values VisitingCardImage
        $visitingCardImage->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/visiting-card-images'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/visiting-card-images');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyVisitingCardImage $request
     * @param VisitingCardImage $visitingCardImage
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyVisitingCardImage $request, VisitingCardImage $visitingCardImage)
    {
        $visitingCardImage->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyVisitingCardImage $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyVisitingCardImage $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    VisitingCardImage::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
