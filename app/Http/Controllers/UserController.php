<?php

namespace App\Http\Controllers;

use App\Models\Master\Pustakawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{
    public function index()
    {
        $pustakawan = Pustakawan::select('pustakawans.*')
            ->join('jabatans', 'pustakawans.jabatan_id', '=', 'jabatans.id')
            ->where('pustakawans.status', 1)
            ->where('jabatans.nama_jabatan', '!=', 'Tenaga Khidmah')
            ->orderBy('jabatans.eselon', 'asc')
            ->get();

        $user = User::with([
            'pustakawan.jabatan',
            'pustakawan.ruang'
        ])
        ->whereHas('pustakawan')
        ->whereHas('pustakawan.jabatan')
        ->get();

        $jumlah = $user->count();

        return view('admin.user.index', compact('user', 'jumlah', 'pustakawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pustakawan_id' => 'required',
            'username'      => 'required|unique:users,username',
            'password'      => 'required|min:8|confirmed',
        ], [
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'pustakawan_id' => $request->pustakawan_id,
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function update(Request $request, $id) : RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->update([
            'username' => $request->username,
        ]);

        return back()->with('success', 'User anda berhasil diperbarui');
    }

    public function ubahpassword(Request $request, $id) : RedirectResponse
    {
        $user = User::findOrFail($id);

        $authUser = auth()->user();

        if ($authUser->id !== $user->id && !$authUser->hasPermissionTo('hak akses-lihat')) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah password pengguna lain.');
        }

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui');
    }

    public function hapus($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->delete();

        $title = 'Hapus Data!';
        $text = "Apakah anda yakin menghapus data terpilih?";
        confirmDelete($title, $text);

        return redirect(route('pengguna.index'))->with('success', 'Alhamdulillah, data berhasil dihapus!');
    }

    public function pengelola()
    {
        $user = User::all();

        return view('informasi.pengelola', compact('user'));
    }

    public function akses($id)
    {
        $user = User::findOrFail($id);
        $akses = Permission::all()->groupBy('category');;

        return view('admin.user.editakses', compact('user' , 'akses'));
    }

    public function updateAkses(Request $request, $id)
    {
        $user = User::findOrFail($id); // Cari user berdasarkan ID
        $selectedPermissions = $request->permissions ?? []; // Ambil izin dari checkbox
        $user->syncPermissions($selectedPermissions); // Sinkronisasi izin user

        return back()->with('success', 'Alhamdulillah, hak akses pengguna berhasil diperbarui');
    }
}
