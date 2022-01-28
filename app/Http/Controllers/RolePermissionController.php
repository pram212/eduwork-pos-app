<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('lihat role');

        $permissions = Permission::all();
        $users = User::doesntHave('roles')->get();
        $roles = Role::all()->pluck('name');
        return view('roles_permissions.index', compact('permissions', 'users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('tambah role');

        $request->validate([
            'name' => 'required'
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->save();

        return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('edit role');
        $request->validate([
            'name' => 'required'
        ]);

        $role = Role::find($id);
        $role->name = $request->name;

        $role->save();
        return response()->json($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('hapus role');
        $role = Role::find($id);
        $role->delete();
        return response()->json($role);
    }

    public function getPermission($id)
    {
        $this->authorize('atur izin');
        $role = Role::find($id);
        $permission = $role->permissions->pluck('name');
        return $permission;
    }

    public function updatePermission(Request $request, $id)
    {
        $this->authorize('atur izin');
        $role = Role::find($id);
        $role->syncPermissions($request->permissions);
        return response("Permission Berhasil Diubah");
    }

    public function asignRoleUser(Request $request)
    {
        $this->authorize('assign user');

        $request->validate([
            'role' => 'required',
            'users' => 'required'
        ]);

        foreach ($request->users as $user_id) {

            $user = User::find($user_id);

            $user->assignRole($request->role);
        }

        return response("User telah memiliki role");
    }

}
