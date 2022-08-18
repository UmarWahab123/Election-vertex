<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ParchiImage\BulkDestroyParchiImage;
use App\Http\Requests\Admin\ParchiImage\DestroyParchiImage;
use App\Http\Requests\Admin\ParchiImage\IndexParchiImage;
use App\Http\Requests\Admin\ParchiImage\StoreParchiImage;
use App\Http\Requests\Admin\ParchiImage\UpdateParchiImage;
use App\Models\ParchiImage;
use App\Models\PollingDetail;
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
use Illuminate\Http\Request;
use PDF;
use MPDF;
use Auth;

class ParchiImageController extends Controller
{
    // public function __construct()
    // {
    //     $this->guard = config('admin-auth.defaults.guard');

    //     $this->middleware(function ($request, $next) {
    //         $this->user = Auth::user();

    //         if (Auth::user()->forbidden == 1){
    //             abort(404);
    //         }
    //         return $next($request);

    //     });
    // }

    /**
     * Display a listing of the resource.
     *
     * @param IndexParchiImage $request
     * @return array|Factory|View
     */
    public function index(IndexParchiImage $request)
    {
//        dd("testing");
//        $data=DB::table('permissions')->insertGetId(['name'=>'admin.vertex-demonstration','guard_name'=>'admin']);
//        dd($data);

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(ParchiImage::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'user_id', 'Party', 'status','candidate_name','block_code', 'image_url','candidate_image_url'],

            // set columns to searchIn
            ['id', 'user_id', 'Party', 'image_url','candidate_name','block_code', 'status']


        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.parchi-image.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.parchi-image.create');

        return view('admin.parchi-image.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreParchiImage $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreParchiImage $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the ParchiImage
        $parchiImage = ParchiImage::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/parchi-images'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/parchi-images');
    }

    /**
     * Display the specified resource.
     *
     * @param ParchiImage $parchiImage
     * @throws AuthorizationException
     * @return void
     */
    public function show(ParchiImage $parchiImage)
    {
        $this->authorize('admin.parchi-image.show', $parchiImage);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ParchiImage $parchiImage
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(ParchiImage $parchiImage)
    {
        $this->authorize('admin.parchi-image.edit', $parchiImage);


        return view('admin.parchi-image.edit', [
            'parchiImage' => $parchiImage,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateParchiImage $request
     * @param ParchiImage $parchiImage
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateParchiImage $request, ParchiImage $parchiImage)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values ParchiImage
        $parchiImage->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/parchi-images'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/parchi-images');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyParchiImage $request
     * @param ParchiImage $parchiImage
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyParchiImage $request, ParchiImage $parchiImage)
    {
        $parchiImage->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyParchiImage $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyParchiImage $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    ParchiImage::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function candidatename($id, $candidatename)
    {

        return view('admin.parchi-image.candidateimage', ['candidatename' => $candidatename]);
    }

    public function postCandidateImage(Request $request)
    {

        ParchiImage::where('candidate_name',$request->image)->update(['candidate_image_url'=>$request->image_url]);

        return redirect('admin/parchi-images');
     }

     public function parchiPdf($cnic,$party)
     {
         $detail = PollingDetail::where('cnic', $cnic)->with('voter_phone','SchemeAddress')->first();
         $block_code=$detail->polling_station_number;
        $parchiImageLogo='';
         if ($party == "PMLN")
        {
            $parchiImageLogo="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/parchiLogo%2Fnull%2F1630325444137.jpg?alt=media&token=86725490-1edc-4f7d-8cf0-ef1caebeba01";
        }
        elseif ($party == "PMLQ")
        {
            $parchiImageLogo="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630513641440.png?alt=media&token=213b698f-09ac-4a3f-aad3-6d408851c5ae";
        }
       elseif ($party == "PTI")
        {
            $parchiImageLogo="https://flyclipart.com/thumbs/pti-pakistan-imrankhan-imran-khan-bat-logo-ptilogo-pti-election-sign-1172092.png";
        }
        elseif ($party == "PPP")
        {
            $parchiImageLogo="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/testing%2Fppplogo.jpg?alt=media&token=79a0beca-ca25-479e-8da0-e17772d1d876";
        }

         elseif ($party == "TLP")
         {
             $parchiImageLogo="https://www.indiablooms.com/world_pic/2021/718eafd67f1c10ae3bb5e4808c357fae.png";
         }

         elseif ($party == "JUI")
         {
             $parchiImageLogo="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/media%2F1657968592258?alt=media&token=fe7a5d90-e2fb-47da-a3b3-3217e4941a6b";
         }

         elseif ($party == "MQM")
         {
             $parchiImageLogo="https://upload.wikimedia.org/wikipedia/en/b/b3/MQMworld_%28transparented%29.png";
         }

         elseif ($party == "AllahHoAkbar")
         {
             $parchiImageLogo="https://upload.wikimedia.org/wikipedia/en/0/0a/Allah-o-Akbar_Tehreek_Logo.png";
         }

         elseif ($party == "JI")
         {
             $parchiImageLogo="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/media%2F1657968669699?alt=media&token=e018ff99-771e-42aa-811f-01ddd3c9a9ab";
         }
         elseif ($party == "AwamiDostPanel")
         {
             $parchiImageLogo="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/media%2F1660129967675?alt=media&token=6c8c3518-407c-412d-9be3-6ee65074388c";
         }
         elseif ($party == "MukhlasabadPanel")
         {
             $parchiImageLogo="https://image.shutterstock.com/image-vector/lovely-water-pot-soil-ghada-260nw-2007216089.jpg";
         }


         $parchiImages=ParchiImage::where('block_code',$block_code)->where('Party',$party)->first();
         $pdf=PDF::loadView('email.singleVoterParchiPdf',compact('parchiImages','detail','parchiImageLogo'));
         return $pdf->download('voterlist.pdf');
//        return view('email.singleVoterParchiPdf',compact('parchiImages','detail','parchiImageLogo'));
     }
   public function parchiPdfPrint($cnic,$party)
     {
//         $party=json_decode($party,true);
         $detail = PollingDetail::where('cnic', $cnic)->with('voter_phone','SchemeAddress')->first();
         $block_code=$detail->polling_station_number;

        $parchiImageLogo='';
         if ($party == "PMLN")
        {
            $parchiImageLogo="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/parchiLogo%2Fnull%2F1630325444137.jpg?alt=media&token=86725490-1edc-4f7d-8cf0-ef1caebeba01";
        }
        if ($party == "PMLQ")
        {
            $parchiImageLogo="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630513641440.png?alt=media&token=213b698f-09ac-4a3f-aad3-6d408851c5ae";
        }
        if ($party == "PTI")
        {
            $parchiImageLogo="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/parchiLogo%2Fnull%2F1630396992972.jpg?alt=media&token=988014e9-a4c1-42d5-a1bd-d0b08aa9f600";
        }
       if ($party == "PPP")
       {
           $parchiImageLogo="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/testing%2Fppplogo.jpg?alt=media&token=79a0beca-ca25-479e-8da0-e17772d1d876";
       }



         $parchiImages=ParchiImage::where('block_code',$block_code)->where('Party',$party)->first();
         $pdf=PDF::loadView('email.singleVoterParchiPrint',compact('parchiImages','detail','parchiImageLogo'));
         return $pdf->download('voterlist.pdf');
//        return view('email.singleVoterParchiPrint',compact('parchiImages','detail','parchiImageLogo'));
     }

}
