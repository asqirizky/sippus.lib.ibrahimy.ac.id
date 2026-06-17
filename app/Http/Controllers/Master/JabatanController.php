<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index() {

        $jabatan = Jabatan::orderBy('eselon', 'asc')->get();

        return view('admin.Master.jabatan.jabatan', compact(
            'jabatan'
        ));
    }

    public function store(Request $request) {

        $ranking = (int) $request->eselon;

        Jabatan::create([
            'eselon' => $request->eselon,
            'nama_jabatan' => $request->nama_jabatan,
            'status' => $request->input('status', '0'),
            'ranking' => $ranking,
        ]);

        return back()->with('success', 'Data berhasil ditambah');
    }

    public function update(Request $request, $id) {

        $jabatan = Jabatan::findOrFail($id);

        $jabatan->update([
            'eselon' => $request->eselon,
            'jabatan' => $request->nama_jabatan,
            'status' => $request->has('status') ? '1' : '0',
        ]);

        return back()->with('success', 'Data berhasil diperbarui');
    }

    public function destroy ($id) {

        $jabatan = Jabatan::findOrFail($id);

        $jabatan->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}
