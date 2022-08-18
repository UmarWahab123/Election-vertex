<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUser\DestroyAdminUser;
use App\Http\Requests\Admin\AdminUser\ImpersonalLoginAdminUser;
use App\Http\Requests\Admin\AdminUser\IndexAdminUser;
use App\Http\Requests\Admin\AdminUser\StoreAdminUser;
use App\Http\Requests\Admin\AdminUser\UpdateAdminUser;
use App\Models\ElectionSector;
use App\Models\FirebaseUrl;
use App\Models\PollingStation;
use App\Models\User;
use Brackets\AdminAuth\Models\AdminUser;
use Spatie\Permission\Models\Role;
use Brackets\AdminAuth\Activation\Facades\Activation;
use Brackets\AdminAuth\Services\ActivationService;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;
use App\Models\GeneralNotice;
use DB;
use Illuminate\Support\Facades\Redirect;


class AdminUsersController extends Controller
{
    /**
     * Guard used for admin user
     *
     * @var string
     */
    protected $guard = 'admin';

    /**
     * AdminUsersController constructor.
     *
     * @return void
     */
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
     * @param IndexAdminUser $request
     * @return Factory|View
     */

    public function sectorReportDetail(Request $request){
        $election_sector = ElectionSector::where('sector' , $request->sector)
            ->orderby('block_code')
            ->get();
        $sector = $request->sector;

        return view('admin.sector_report' , compact('sector' , 'election_sector'));
    }

    public function dashboard(){

        $block_codes = PollingStation::with('sector')
            ->withCount('firebase_urls')
            ->withCount('polling_details')
            ->paginate(10);

        $response = $this->block_code_links_details($block_codes);
        $block_codes = $response['block_codes'];

        return view('admin.dashboard', compact('block_codes'));

    }

    public function search_block_code(Request $request){
        $block_code = $request->block_code;
        $block_codes = PollingStation::with('sector')
            ->withCount('firebase_urls')
            ->withCount('polling_details')
            ->where('polling_station_number' , $block_code)
            ->paginate(10);
        if (count($block_codes) > 0){
            $response = $this->block_code_links_details($block_codes);
            $block_codes = $response['block_codes'];

            return view('admin.dashboard', compact('block_codes'));
        }else{
            return redirect()->back()->with('error', 'Invalid Code Block');
        }

    }

    public function block_code_graph(){
        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', '0'); // for infinite time of execution
        $block_codes = PollingStation::with('sector')
            ->withCount('firebase_urls')
            ->withCount('polling_details')
//            ->take(10)
            ->get();

        $response = $this->block_code_links_details($block_codes);
        return $this->get_data_points($response);
    }

    public function get_data_points($response){
        $total_urls = $response['total_urls'];
        $block_codes = $response['block_codes'];
        $block_code_dataPoints = $response['block_code_dataPoints'];
        $invalid_urls_dataPoints = $response['invalid_urls_dataPoints'];
        $complete_urls_dataPoints = $response['complete_urls_dataPoints'];
        $pending_urls_dataPoints = $response['pending_urls_dataPoints'];
        $manually_entered_dataPoints = $response['manually_entered_dataPoints'];

        $total_urls = json_encode($total_urls);
        $dp_blockCode = json_encode($block_code_dataPoints);
        $invalid_urls = json_encode($invalid_urls_dataPoints);
        $complete_urls = json_encode($complete_urls_dataPoints);
        $pending_urls = json_encode($pending_urls_dataPoints);
        $manually_entered = json_encode($manually_entered_dataPoints);

        return view('admin.block_code_graph', compact('block_codes' , 'dp_blockCode' , 'invalid_urls', 'complete_urls', 'pending_urls', 'total_urls', 'manually_entered'));
    }

    public function block_code_links_details($block_codes){

//        $response = [];
        $block_code_dataPoints = array();
        foreach ($block_codes as $key => $block_code){
            $pending_urls = FirebaseUrl::where('import_ps_number' , $block_code->polling_station_number)->where('status' , 0)->count();
            $complete_urls = FirebaseUrl::where('import_ps_number' , $block_code->polling_station_number)->where('status' , 1)->count();
            $invalid_urls = FirebaseUrl::where('import_ps_number' , $block_code->polling_station_number)->where('status' , 3)->count();
            $duplicate_entery = FirebaseUrl::where('import_ps_number' , $block_code->polling_station_number)->where('status' , 4)->count();
            $manually_entered = FirebaseUrl::where('import_ps_number' , $block_code->polling_station_number)->where('status' , 6)->count();
            $block_code['invalid_urls'] = $invalid_urls ? $invalid_urls : 0;
            $block_code['pending_urls'] = $pending_urls ? $pending_urls : 0;
            $block_code['complete_urls'] = $complete_urls ? $complete_urls : 0;
            $block_code['manually_entered'] = $manually_entered ? $manually_entered : 0;
            $block_code['duplicate_entery'] = $duplicate_entery ? $duplicate_entery : 0;
//            dd($block_code);
            $total_dataPoints[$key] = $block_code->firebase_urls_count;
            $block_code_dataPoints[$key] = $block_code->polling_station_number;
            $invalid_urls_dataPoints[$key] = $block_code->invalid_urls;
            $complete_urls_dataPoints[$key] = $block_code->complete_urls;
            $pending_urls_dataPoints[$key] = $block_code->pending_urls;
            $manually_entered_dataPoints[$key] = $block_code->manually_entered;

        }

        $response = [
            'total_urls' => $total_dataPoints,
            'block_codes' => $block_codes,
            'block_code_dataPoints' => $block_code_dataPoints,
            'invalid_urls_dataPoints' => $invalid_urls_dataPoints,
            'complete_urls_dataPoints' => $complete_urls_dataPoints,
            'manually_entered_dataPoints' => $manually_entered_dataPoints,
            'pending_urls_dataPoints' => $pending_urls_dataPoints
        ];

        return $response;
    }

    public function index(IndexAdminUser $request)
    {
            $data = AdminListing::create(AdminUser::class)->processRequestAndGet(
                $request,
                ['id', 'first_name', 'last_name', 'email', 'phone', 'activated', 'forbidden', 'language', 'last_login_at'],
                ['id', 'first_name', 'last_name', 'email', 'phone', 'language'],
                function ($query) use ($request) {
                    if (Auth::user()->can('admin.super-admin')) {
                        $query->orderBy('id', 'DESC');
                    } else {
                        $query->where('business_id', Auth::user()->business_id);
                    }
                }
            );
        if ($request->ajax()) {
            return ['data' => $data, 'activation' => Config::get('admin-auth.activation_enabled')];
        }
        return view('admin.admin-user.index', ['data' => $data, 'activation' => Config::get('admin-auth.activation_enabled')]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.admin-user.create');
        if(Auth::user()->can('admin.superadmin')) {
            $all_roles = Role::where('guard_name', $this->guard)->get();
        }
        else
        {
            $all_roles = Role::where('guard_name', $this->guard)->where('name','!=','SuperAdmin')->get();
        }
        return view('admin.admin-user.create', [
            'activation' => Config::get('admin-auth.activation_enabled'),
            'roles' => $all_roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdminUser $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreAdminUser $request)
    {
        // Sanitize input
        $sanitized = $request->getModifiedData();

        // Store the AdminUser
        $adminUser = AdminUser::create($sanitized);
        $adminUser->business_id = Auth::user()->business_id;
        $adminUser->save();

        // But we do have a roles, so we need to attach the roles to the adminUser
        $adminUser->roles()->sync(collect($request->input('roles', []))->map->id->toArray());

        if ($request->ajax()) {
            return ['redirect' => url('admin/admin-users'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/admin-users');
    }

    /**
     * Display the specified resource.
     *
     * @param AdminUser $adminUser
     * @throws AuthorizationException
     * @return void
     */
    public function show(AdminUser $adminUser)
    {
        $this->authorize('admin.admin-user.show', $adminUser);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AdminUser $adminUser
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(AdminUser $adminUser)
    {
        $this->authorize('admin.admin-user.edit', $adminUser);

        $adminUser->load('roles');

        return view('admin.admin-user.edit', [
            'adminUser' => $adminUser,
            'activation' => Config::get('admin-auth.activation_enabled'),
            'roles' => Role::where('guard_name', $this->guard)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdminUser $request
     * @param AdminUser $adminUser
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateAdminUser $request, AdminUser $adminUser)
    {
        // Sanitize input
        $sanitized = $request->getModifiedData();

        // Update changed values AdminUser
        $adminUser->update($sanitized);

        // But we do have a roles, so we need to attach the roles to the adminUser
        if ($request->input('roles')) {
            $adminUser->roles()->sync(collect($request->input('roles', []))->map->id->toArray());
        }

        if ($request->ajax()) {
            return ['redirect' => url('admin/admin-users'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/admin-users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyAdminUser $request
     * @param AdminUser $adminUser
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyAdminUser $request, AdminUser $adminUser)
    {
        $adminUser->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Resend activation e-mail
     *
     * @param Request $request
     * @param ActivationService $activationService
     * @param AdminUser $adminUser
     * @return array|RedirectResponse
     */
    public function resendActivationEmail(Request $request, ActivationService $activationService, AdminUser $adminUser)
    {
        if (Config::get('admin-auth.activation_enabled')) {
            $response = $activationService->handle($adminUser);
            if ($response == Activation::ACTIVATION_LINK_SENT) {
                if ($request->ajax()) {
                    return ['message' => trans('brackets/admin-ui::admin.operation.succeeded')];
                }

                return redirect()->back();
            } else {
                if ($request->ajax()) {
                    abort(409, trans('brackets/admin-ui::admin.operation.failed'));
                }

                return redirect()->back();
            }
        } else {
            if ($request->ajax()) {
                abort(400, trans('brackets/admin-ui::admin.operation.not_allowed'));
            }

            return redirect()->back();
        }
    }

    /**
     * @param ImpersonalLoginAdminUser $request
     * @param AdminUser $adminUser
     * @return RedirectResponse
     * @throws  AuthorizationException
     */
    public function impersonalLogin(ImpersonalLoginAdminUser $request, AdminUser $adminUser) {
        Auth::login($adminUser);
        return redirect()->back();
    }

}
