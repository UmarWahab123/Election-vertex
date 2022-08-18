<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CandidateWard\BulkDestroyCandidateWard;
use App\Http\Requests\Admin\CandidateWard\DestroyCandidateWard;
use App\Http\Requests\Admin\CandidateWard\IndexCandidateWard;
use App\Http\Requests\Admin\CandidateWard\StoreCandidateWard;
use App\Http\Requests\Admin\CandidateWard\UpdateCandidateWard;
use App\Models\CandidateWard;
use App\Models\ElectionSector;
use Brackets\AdminAuth\Models\AdminUser;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class CandidateWardController extends Controller
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
     * @param IndexCandidateWard $request
     * @return array|Factory|View
     */
    public function index(IndexCandidateWard $request)
    {

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(CandidateWard::class)->processRequestAndGet(
            // pass the request with params
            $request,
            // set columns to query
            ['id', 'user_id', 'ward_id', 'status'],
                // set columns to searchIn
            ['id', 'user_id', 'ward_id', 'status'],

            function ($query) use ($request) {
                $query->with('UserName');
            }
        );
//dd($data);
        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.candidate-ward.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.candidate-ward.create');
        $users = AdminUser::get();
        $sectors=ElectionSector::groupby('sector')->get();

        return view('admin.candidate-ward.create',
            [
                'users' => $users,
                'sectors' => $sectors,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCandidateWard $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreCandidateWard $request)
    {
//        dd($request->user_id['id']);
        $ward = collect($request->input('ward_id', []))->map->sector->toArray();
        $user =$request->user_id['id'];
        $sectors = implode(',' , $ward);
        $sanitized = $request->getSanitized();

        $sanitized['ward_id'] = $sectors;
        $sanitized['user_id'] = $user;
//        dd($sanitized['user_id']);

        // Store the CandidateWard
        $candidateWard = CandidateWard::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/candidate-wards'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/candidate-wards');
    }

    /**
     * Display the specified resource.
     *
     * @param CandidateWard $candidateWard
     * @throws AuthorizationException
     * @return void
     */
    public function show(CandidateWard $candidateWard)
    {
        $this->authorize('admin.candidate-ward.show', $candidateWard);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CandidateWard $candidateWard
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(CandidateWard $candidateWard)
    {
        $electionSector=ElectionSector::get('id');
        $ward_id = explode(',', $candidateWard->ward_id);
//        dd($sectors);
        foreach ($ward_id as $key => $value) {
            $search_result= $this->match_sector($electionSector,$value);
        }
//        $ward['sector']= $search_result;
//        dd($search_result);

        $this->authorize('admin.candidate-ward.edit', $candidateWard);

        $users = AdminUser::get();
        $sectors=ElectionSector::get();

        return view('admin.candidate-ward.edit',
            [
                'users' => $users,
                'sectors' => $sectors,
                'candidateWard' => $candidateWard,
            ]);
    }
    public  function match_sector($sectors,$search_string)
    {
        foreach ($sectors as $key => $res)
        {
            if($res['sector'] == $search_string)
            {
                return $res;
            }
        }
        return 0;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCandidateWard $request
     * @param CandidateWard $candidateWard
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateCandidateWard $request, CandidateWard $candidateWard)
    {
        $ward = collect($request->input('ward_id', []))->map->id->toArray();
        $user =$request->user_id['id'];
        $sectors = implode(',' , $ward);

        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['ward_id'] = $sectors;
        $sanitized['user_id'] = $user;
        // Update changed values CandidateWard
        $candidateWard->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/candidate-wards'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/candidate-wards');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCandidateWard $request
     * @param CandidateWard $candidateWard
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyCandidateWard $request, CandidateWard $candidateWard)
    {
        $candidateWard->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyCandidateWard $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyCandidateWard $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    CandidateWard::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    /*Candidate Sectors list view*/
    public function sectors()
    {
        return view('');
    }


}
