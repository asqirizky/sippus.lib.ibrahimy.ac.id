<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    //
    public function index(Permission $permission)
    {
        $permissions = Permission::with('users')->latest()->get();
        return view('admin.user.permission.index', compact('permissions', 'permission'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);
        Permission::create([
            'name' => $request->name,
            'category' => $request->category
        ]);

        return back()->with('success', 'Alhamdulillah,Permission berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        $permission->update([
            'name' => $request->name,
            'category' => $request->category
        ]);

        return back()->with('success', 'Alhamdulillah,Permission berhasil diperbarui.');
    }

    public function destroy(Permission $permission, $id)
    {
        $permission = Permission::find($id);

        $permission->delete();
        return back()->with('success', 'Alhamdulillah,Permission berhasil dihapus.');
    }

    public function updatePermissions(Request $request, User $user)
    {
        // Ambil semua izin dari request
        $selectedPermissions = $request->permissions ?? [];

        // Sinkronisasi izin user (menambah & menghapus sesuai checkbox)
        $user->syncPermissions($selectedPermissions);

        return back()->with('success', 'Hak akses diperbarui.');
    }
}
