<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('name')->paginate(10);
        return view('administration.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('administration.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50']);

        // Ubah spasi menjadi underscore
        $base = strtolower(str_replace(' ', '_', $request->name));
        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($actions as $action) {
            $permName = $base . '_' . $action;

            // Cek permission dengan guard_name 'web'
            if (!Permission::where('name', $permName)->where('guard_name', 'web')->exists()) {
                Permission::create([
                    'name' => $permName,
                    'guard_name' => 'web', // pastikan guard_name
                ]);
            }
        }

        return redirect()->route('mgpermissions.index')
            ->with('success', 'Permissions for ' . $base . ' created successfully.');
    }




    public function edit(Permission $mgpermission)
    {
        return view('administration.permissions.edit', ['permission' => $mgpermission]);
    }

    public function update(Request $request, Permission $mgpermission)
    {
        $request->validate(['name' => 'required|unique:permissions,name,' . $mgpermission->id]);
        $mgpermission->update(['name' => $request->name]);
        return redirect()->route('mgpermissions.index')->with('success', 'Permission updated.');
    }

    public function destroy(Permission $mgpermission)
    {
        $mgpermission->delete();
        return redirect()->route('mgpermissions.index')->with('success', 'Permission deleted.');
    }
}
