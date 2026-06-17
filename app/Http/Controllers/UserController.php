<?php

namespace App\Http\Controllers;

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
        $user = User::latest()->get();
        $jumlah = User::count();

        return view('admin.user.index', compact('user', 'jumlah'));
    }

    public function store(Request $request)
    {
        $foto = $request->file('foto');
        $foto->storeAs('public/foto', $foto->hashName());

        $user = User::create([

            'name' => $request->name,
            'foto' => $foto->hashName(),
            'jabatan' => $request->jabatan,
            'idstaf' => $request->idstaf,
            'username' => $request->idstaf,
            'password' => Hash::make($request->idstaf),
            'tempat' => $request->tempat,
        ]);

        return back()->with('success', 'Alhamdulillah, data berhasil tersimpan');
    }

    public function update(Request $request, $id) : RedirectResponse
    {

        $user = User::findOrFail($id);

        if ($request->hasFile('foto')) {

            $foto = $request->file('foto');
            $foto->storeAs('public/foto', $foto->hashName());

            Storage::delete('public/foto/'.$user->foto);

            $user->update([

                'foto' => $foto->hashName(),
                'name' => $request->name,
                'idstaf' => $request->idstaf,
                'jabatan' => $request->jabatan,
                'username' => $request->username,
                'tempat' => $request->tempat,
            ]);

        }else{

            $user->update([
                'name' => $request->name,
                'idstaf' => $request->idstaf,
                'jabatan' => $request->jabatan,
                'username' => $request->username,
                'tempat' => $request->tempat,
            ]);


        }

        return redirect(route('pengguna.index'))->with('success', 'Alhamdulillah, data berhasil diperbarui!');
    }

    public function ubahpassword(Request $request, $id) : RedirectResponse
    {

        $user = User::findOrFail($id);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect(route('pengguna.index'))->with('success', 'Alhamdulillah, password berhasil diperbarui!');
    }

    public function hapus($id): RedirectResponse
    {
        //get post by ID
        $user = User::findOrFail($id);

        //delete image
        Storage::delete('public/foto/'. $user->foto);

        //delete post
        $user->delete();

        $title = 'Hapus Data!';
        $text = "Apakah anda yakin menghapus data terpilih?";
        confirmDelete($title, $text);

        //redirect to index
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
