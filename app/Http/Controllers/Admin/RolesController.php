<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\BulkDestroyRole;
use App\Http\Requests\Admin\Role\DestroyRole;
use App\Http\Requests\Admin\Role\IndexRole;
use App\Http\Requests\Admin\Role\StoreRole;
use App\Http\Requests\Admin\Role\UpdateRole;
use App\Models\Role;
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
use Spatie\Permission\Models\Permission;
use Auth;
class RolesController extends Controller
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
     * @param IndexRole $request
     * @return array|Factory|View
     */
    public function index(IndexRole $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Role::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'guard_name'],

            // set columns to searchIn
            ['id', 'name', 'guard_name']
        );
        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.role.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.role.create');

        $permissions = Permission::orderBy('name','asc')->get();
        $assignPermission = [];
        return view('admin.role.create',compact('permissions','assignPermission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRole $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreRole $request)
    {
        $permissions = $request->permissions;
        // Sanitize input
        $sanitized = $request->getSanitized();

        $sanitized['guard_name'] = 'admin';

        // Store the Role
        $role = \Spatie\Permission\Models\Role::create($sanitized);

        //assign select permission
        if (!empty($permissions) && is_array($permissions) && count($permissions) > 0)
        {
            $permissionArray = [];
            foreach ($permissions as $id)
            {
                $permission= Permission::findById($id);
                array_push($permissionArray,$permission->name);
            }

            $role->syncPermissions($permissionArray);
        }

        if ($request->ajax()) {
            return ['redirect' => url('admin/roles'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/roles');
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @throws AuthorizationException
     * @return void
     */
    public function show(Role $role)
    {
        $this->authorize('admin.role.show', $role);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Role $role)
    {
        $this->authorize('admin.role.edit', $role);

        $newRole = \Spatie\Permission\Models\Role::findById($role->id);
        //retrieve all permission
        $permissions = Permission::orderBy('name','asc')->get();

        //find assigned permission
        $assignPermission = $newRole->getAllPermissions();
        if (!empty($assignPermission) &&  count($assignPermission) > 0)
        {
            $assignPermission = $assignPermission->pluck('id')->toArray();
        }
        return view('admin.role.edit', compact('role','permissions','assignPermission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRole $request
     * @param Role $role
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateRole $request, \Spatie\Permission\Models\Role $role)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['guard_name'] = 'admin';
        $permissions = $request->permissions;

        // Update changed values Role
        $role->update($sanitized);

        //assign select permission
        if (!empty($permissions) && is_array($permissions) && count($permissions) > 0)
        {
            $permissionArray = [];
            foreach ($permissions as $id)
            {
                $permission= Permission::findById($id);
                array_push($permissionArray,$permission->name);
            }

            $role->syncPermissions($permissionArray);
        }

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/roles'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyRole $request
     * @param Role $role
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyRole $request, Role $role)
    {
        $role->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyRole $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyRole $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Role::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
