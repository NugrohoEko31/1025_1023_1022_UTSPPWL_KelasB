<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:create-role', 'permission:edit-role', 'permission:delete-role'], ['only' => ['index', 'show']]);
        $this->middleware('permission:create-role', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-role', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-role', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        return view('roles.index', [
            'roles' => Role::orderBy('id', 'DESC')->paginate(5)
        ]);
    }

    public function create(): View
    {
        return view('roles.create', [
            'permissions' => Permission::all()
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        
        return redirect()->route('roles.index')->withSuccess('New role added successfully.');
    }

    public function edit(Role $role): View
    {
        if (strtolower($role->name) === 'super admin') {
            abort(403, 'SUPER ADMIN ROLE CANNOT BE EDITED');
        }

        $rolePermissions = $role->permissions()->pluck('id')->toArray();

        return view('roles.edit', [
            'role' => $role,
            'permissions' => Permission::all(),
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->withSuccess('Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if (strtolower($role->name) === 'super admin') {
            abort(403, 'SUPER ADMIN ROLE CANNOT BE DELETED');
        }

        if (auth('web')->check() && auth('web')->user()->hasRole($role->name)) {
            abort(403, 'CANNOT DELETE A ROLE YOU ARE CURRENTLY ASSIGNED TO');
        }

        $role->delete();
        return redirect()->route('roles.index')->withSuccess('Role deleted successfully.');
    }
}