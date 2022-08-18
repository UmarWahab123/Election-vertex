<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VisitingCard\BulkDestroyVisitingCard;
use App\Http\Requests\Admin\VisitingCard\DestroyVisitingCard;
use App\Http\Requests\Admin\VisitingCard\IndexVisitingCard;
use App\Http\Requests\Admin\VisitingCard\StoreVisitingCard;
use App\Http\Requests\Admin\VisitingCard\UpdateVisitingCard;
use App\Models\VisitingCard;
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

class VisitingCardsController extends Controller
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
     * @param IndexVisitingCard $request
     * @param string $status
     * @return array|Factory|View
     */
    public function index(IndexVisitingCard $request , $status = 'PENDING')
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(VisitingCard::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'user_id', 'name', 'phone', 'address', 'user_type', 'category', 'status'],

            // set columns to searchIn
            ['id', 'user_id', 'name', 'phone', 'address', 'user_type', 'category', 'status'],

            function ($query) use ($request , $status){
                $query->where('status' , $status);
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

        return view('admin.visiting-card.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.visiting-card.create');

        return view('admin.visiting-card.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVisitingCard $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreVisitingCard $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the VisitingCard
        $visitingCard = VisitingCard::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/visiting-cards'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/visiting-cards');
    }

    /**
     * Display the specified resource.
     *
     * @param VisitingCard $visitingCard
     * @throws AuthorizationException
     * @return void
     */
    public function show(VisitingCard $visitingCard)
    {
        $this->authorize('admin.visiting-card.show', $visitingCard);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param VisitingCard $visitingCard
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(VisitingCard $visitingCard)
    {
        $image = VisitingCardImage::where('visiting_card_id' , $visitingCard->id)->first('image_link');

        $this->authorize('admin.visiting-card.edit', $visitingCard);

        return view('admin.visiting-card.edit', [
            'visitingCard' => $visitingCard,
            'image' => $image->image_link,
        ]);
    }

    public function get_location_api($address)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key=AIzaSyA5LlIewLNxzthktvTkxwsgw_T4RilrOnM',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateVisitingCard $request
     * @param VisitingCard $visitingCard
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateVisitingCard $request, VisitingCard $visitingCard)
    {
        $location = $this->get_location_api($request->address);
//dd($location);
        if ($location->status != 'ZERO_RESULTS'){
            $lat = $location->results[0]->geometry->location->lat;
            $lng = $location->results[0]->geometry->location->lng;
        }else{
            $lat = 'N/A';
            $lng = 'N/A';
//            return redirect('/admin/visiting-cards/PENDING');
        }

        $address = $request->address;
        $user_id = $request->user_id;
        $name = $request->name;
        $phone = $request->phone;
        $type = $request->category;
        $source = $request->user_type;
        $meta = $request->meta;
//        $peram = 'user_id='.$user_id.'&name='.urlencode($name).'&phone='.urlencode($phone).'&lat='.urlencode($lat).'&lng='.urlencode($lng).'&type='.urlencode($type).'&source='.urlencode($source).'&address='.urlencode($address);
        /* $url = 'https://onecall.plabesk.com/api/crm/marker-entery-from-vertex&'.$peram;*/
        if ($request->status == 'APPROVED'){


            $post = [
                'user_id' => $user_id,
                'name'    => $name,
                'phone'   => $phone,
                'type'    => $type,
                'source'  => $source,
                'address' => $address,
                'lat'     => $lat,
                'lng'     => $lng,
                'meta'    => $meta,

            ];

            $ch = curl_init('https://onecall.plabesk.com/api/crm/marker-entery-from-vertex');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            // execute!
            $response = curl_exec($ch);

            // close the connection, release resources used
            curl_close($ch);

            // do anything you want with your response
//            var_dump($response);
            if ($response == true){
                $sanitized = $request->getSanitized();
                $status = 'APPROVED';
                $sanitized['status'] = $status;
            }else{
                $sanitized = $request->getSanitized();
                $status = 'RECHECK';
                $sanitized['status'] = $status;
            }
        }else{
            $sanitized = $request->getSanitized();
        }
        // Update changed values VisitingCard
        $visitingCard->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/visiting-cards'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/visiting-cards');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyVisitingCard $request
     * @param VisitingCard $visitingCard
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyVisitingCard $request, VisitingCard $visitingCard)
    {
        $visitingCard->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyVisitingCard $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyVisitingCard $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    VisitingCard::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
