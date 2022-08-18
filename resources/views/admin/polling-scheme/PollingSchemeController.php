<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PollingScheme\BulkDestroyPollingScheme;
use App\Http\Requests\Admin\PollingScheme\DestroyPollingScheme;
use App\Http\Requests\Admin\PollingScheme\IndexPollingScheme;
use App\Http\Requests\Admin\PollingScheme\StorePollingScheme;
use App\Http\Requests\Admin\PollingScheme\UpdatePollingScheme;
use App\Imports\PollingSchemeImport;
use App\Models\PollingScheme;
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
use Maatwebsite\Excel\Facades\Excel;
use Stichoza\GoogleTranslate\GoogleTranslate;
use function Matrix\add;


class PollingSchemeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexPollingScheme $request
     * @return array|Factory|View
     */
    public function index(IndexPollingScheme $request)
    {
//        $polling_scheme=PollingScheme::truncate();
//        foreach($polling_scheme as $row)
//        {
//            $tr = new GoogleTranslate('ur');
//            $tranlater =  $tr->setSource('en')->setTarget('ur')->translate($row->polling_station_area);
//
//            PollingScheme::where('id',$row->id)->update([
//                'polling_station_area'=>$tranlater,
//            ]);
//
//        }
//        dd($polling_scheme);

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(PollingScheme::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'ward', 'block_code', 'latlng', 'status','polling_station_area_urdu','polling_station_area','serial_no','image_url'],

            // set columns to searchIn
            ['id', 'ward', 'polling_station_area','block_code', 'block_code_area', 'latlng', 'status','polling_station_area_urdu','serial_no'],

            function($query) use ($request)
            {

//            $query->where('image_url','!=',null);

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

        return view('admin.polling-scheme.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
//        return view('admin.polling-scheme.show-upload-file');
         $this->authorize('admin.polling-scheme.create');

//              $pp  = PollingScheme::groupBy('polling_station_area_urdu')->get();
//         return view('admin.polling-scheme.font',compact('pp'));

         return view('admin.polling-scheme.create');
    }

    public function updateImage($id,$urduImage)
    {

      return view('admin.polling-scheme.uploadimage',compact('urduImage'));

    }


    public function postupdateImage(Request $request)
    {
     PollingScheme::where('polling_station_area_urdu',$request->image)->update(['image_url'=>$request->image_url]);

     return redirect('admin/polling-schemes');

    }




    /**
     * Store a newly created resource in storage.
     *
     * @param StorePollingScheme $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StorePollingScheme $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the PollingScheme
        $pollingScheme = PollingScheme::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/polling-schemes'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/polling-schemes');
    }

    /**
     * Display the specified resource.
     *
     * @param PollingScheme $pollingScheme
     * @throws AuthorizationException
     * @return void
     */
    public function show(PollingScheme $pollingScheme)
    {
        $this->authorize('admin.polling-scheme.show', $pollingScheme);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PollingScheme $pollingScheme
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(PollingScheme $pollingScheme)
    {
        $this->authorize('admin.polling-scheme.edit', $pollingScheme);


        return view('admin.polling-scheme.edit', [
            'pollingScheme' => $pollingScheme,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePollingScheme $request
     * @param PollingScheme $pollingScheme
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdatePollingScheme $request, PollingScheme $pollingScheme)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values PollingScheme
        $pollingScheme->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/polling-schemes'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/polling-schemes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPollingScheme $request
     * @param PollingScheme $pollingScheme
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyPollingScheme $request, PollingScheme $pollingScheme)
    {
        $pollingScheme->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyPollingScheme $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyPollingScheme $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    PollingScheme::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function import()
    {
        return view('admin.polling-scheme.importfile');
    }

    public function importPolling(Request $request)
    {
        $data=Excel::import(new PollingSchemeImport,request()->file('myfile'));
        return redirect()->back();

    }
    public function insertPollingData()
    {
        return view('admin.polling-scheme.show-upload-file');
    }
    public function updateImageUpload(Request $request)
    {

        $address =$request->polling_station_area;
        dd($address);

        $url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($address);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $responseJson = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($responseJson);

        dd($response);

        if ($response->status == 'OK') {
            $latitude = $response->results[0]->geometry->location->lat;
            $longitude = $response->results[0]->geometry->location->lng;

            echo 'Latitude: ' . $latitude;
            echo '<br />';
            echo 'Longitude: ' . $longitude;
        } else {
            echo $response->status;
            var_dump($response);
        }
//       $data = PollingScheme::insert([
//           'ward'=>$request->wrad,
//           'serial_no'=>$request->serial_n0,
//           'polling_station_area'=>$request->polling_station_area,
//           'polling_station_area_urdu'=>$request->polling_station_area_urdu,
//           'block_code_area'=>$request->block_code_area,
//           'block_code'=>$request->block_code,
//           'latlng'=>$latlng,
//           'gender_type'=>$request->gender_type,
//           'status'=>$request->status
//           'male_both'=>$request->male_code,
//           'female_both'=>$request->female_code,
//           'total_both'=>$request->total_both,
//        ]);
    }

}
