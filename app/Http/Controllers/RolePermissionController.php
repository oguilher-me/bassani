<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.roles-permissions.index', compact('roles', 'permissions'));
    }

    public function create()
    {
        return view('admin.roles-permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:255',
            'description' => 'nullable|string',
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')
                         ->with('success', 'Nível de Acesso cadastrado com sucesso.');
    }

    public function show(Role $role)
    {
        return view('admin.roles-permissions.show', compact('role'));
    }

    public function edit(Role $role)
    {
        return view('admin.roles-permissions.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id . '|max:255',
            'description' => 'nullable|string',
        ]);

        $role->update($request->all());

        return redirect()->route('roles.index')
                         ->with('success', 'Nível de Acesso editado com sucesso');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')
                         ->with('success', 'Nível de acesso excluído com sucesso');
    }

    // Permission methods (similar to Role methods, but for permissions)
    public function permissionIndex()
    {
        $permissions = Permission::all();
        return view('admin.roles-permissions.permissions.index', compact('permissions'));
    }

    public function permissionCreate()
    {
        return view('admin.roles-permissions.permissions.create');
    }

    public function permissionStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions|max:255',
            'description' => 'nullable|string',
        ]);

        Permission::create($request->all());

        return redirect()->route('permissions.index')
                         ->with('success', 'Permissão cadastrada com sucesso.');
    }

    public function permissionShow(Permission $permission)
    {
        return view('admin.roles-permissions.permissions.show', compact('permission'));
    }

    public function permissionEdit(Permission $permission)
    {
        return view('admin.roles-permissions.permissions.edit', compact('permission'));
    }

    public function permissionUpdate(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id . '|max:255',
            'description' => 'nullable|string',
        ]);

        $permission->update($request->all());

        return redirect()->route('permissions.index')
                         ->with('success', 'Permissão editada com sucesso');
    }

    public function permissionDestroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permissions.index')
                         ->with('success', 'Permissão excluída com sucesso');
    }
}
