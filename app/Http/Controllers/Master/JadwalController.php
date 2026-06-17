<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index() {

        $jadwal = Jadwal::get();

        return view('admin.Master.jadwal.jadwal', compact('jadwal'));
    }

    public function store(Request $request) {

        Jadwal::create([
            'jadwal' => $request->jadwal,
            'jamMasuk' => $request->jamMasuk,
            'jamPulang' => $request->jamPulang,
            'shift' => $request->shift,
        ]);

        return back()->with('success', 'Alhamdulillah, data berhasil ditambahkan');
    }

    public function update(Request $request, $id) {

        $jadwal = Jadwal::findOrFail($id);

        $jadwal->update([
            'jadwal' => $request->jadwal,
            'jamMasuk' => $request->jamMasuk,
            'jamPulang' => $request->jamPulang,
        ]);

        return back()->with('success', 'Alhamdulillah, data berhasil diperbarui');
    }

    public function destroy($id) {

        $jadwal = Jadwal::findOrFail($id);

        $jadwal->delete();

        return back()->with('success', 'Alhamdulillah, data berhasil dihapus');
    }
}
