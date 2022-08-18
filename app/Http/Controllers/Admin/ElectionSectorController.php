<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ElectionSector\BulkDestroyElectionSector;
use App\Http\Requests\Admin\ElectionSector\DestroyElectionSector;
use App\Http\Requests\Admin\ElectionSector\IndexElectionSector;
use App\Http\Requests\Admin\ElectionSector\StoreElectionSector;
use App\Http\Requests\Admin\ElectionSector\UpdateElectionSector;
use App\Models\ElectionSector;
use Brackets\AdminListing\Facades\AdminListing;
use Carbon\Carbon;
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
use function Sodium\compare;

class ElectionSectorController extends Controller
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

    public function azzureForm(){
        return view('Azzure.azzure');
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexElectionSector $request
     * @return array|Factory|View
     */
    public function index(IndexElectionSector $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(ElectionSector::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'sector', 'block_code', 'male_vote', 'female_vote', 'total_vote', 'status','created_at'],

            // set columns to searchIn
            ['id', 'sector', 'block_code', 'male_vote', 'female_vote', 'total_vote', 'status','created_at'],
            function ($query) {

                    if(Auth::user()->id == 52)
                    {
                        $query->where('id', '>',2796)->orderBy('id', 'DESC');

                    }else{
                        $query->orderBy('id', 'DESC');
                    }
            }
        );
        foreach($data as $row)
        {
            $row->premiumtime=Carbon::parse($row->created_at)->timezone('Asia/Karachi')->toDateTimeString();
        }
        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

//        $d = ElectionSector::where('id', '>',2796)->orderBy('id', 'DESC')->get()->pluck('block_code')->toJson();
//
//        return $d;

         return view('admin.election-sector.index', ['data' => $data]);
    }

    public function getDownloadJsonFiles(){
        $wards = ElectionSector::select('sector')->get()->groupBy('sector');
        return view('download-json-files' , compact('wards'));
    }

    public function downloadJsonFile($ward){

        $type = $_GET['type'];
        $ward_name = preg_replace("/[^a-zA-Z]+/", "", $ward);
        $ward_no = preg_replace("/[^0-9]+/", "", $ward);

        $file = base_path('public/'.$ward_name.'_'.$ward_no.'_'.$type.'.json');

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
        else{
            return redirect()->back()->with(['error' => 'No file found yet for '.$ward]);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.election-sector.create');

        return view('admin.election-sector.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreElectionSector $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreElectionSector $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the ElectionSector
        $electionSector = ElectionSector::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/election-sectors'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/election-sectors');
    }

    /**
     * Display the specified resource.
     *
     * @param ElectionSector $electionSector
     * @throws AuthorizationException
     * @return void
     */
    public function show(ElectionSector $electionSector)
    {
        $this->authorize('admin.election-sector.show', $electionSector);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ElectionSector $electionSector
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(ElectionSector $electionSector)
    {
        $this->authorize('admin.election-sector.edit', $electionSector);


        return view('admin.election-sector.edit', [
            'electionSector' => $electionSector,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateElectionSector $request
     * @param ElectionSector $electionSector
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateElectionSector $request, ElectionSector $electionSector)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values ElectionSector
        $electionSector->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/election-sectors'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/election-sectors');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyElectionSector $request
     * @param ElectionSector $electionSector
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyElectionSector $request, ElectionSector $electionSector)
    {
        $electionSector->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyElectionSector $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyElectionSector $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    ElectionSector::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
