<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Jadwal;
use App\Models\Master\Libur;
use App\Models\Master\Ruang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LiburController extends Controller
{
    public function index(Request$request) {

        $bulanInput = $request->bulan ?? now()->format('Y-m');

        $bulanAngka = $request->bulan ?? now()->month;
        $tahunAngka = $request->tahun ?? now()->year;

        $bulan = Carbon::create($tahunAngka, $bulanAngka)
                    ->isoFormat('MMMM YYYY');

        $query = Libur::orderBy('tanggal', 'asc')
            ->whereMonth('tanggal', $bulanAngka)
            ->whereYear('tanggal', $tahunAngka);

        $jadwals = Jadwal::get();
        $ruangs = Ruang::get();

        $libur = $query->get();

        return view('admin.Master.libur.libur', compact(
            'jadwals',
            'libur',
            'ruangs',
            'bulan',
            'bulanInput'
        ));
    }

    public function store(Request $request)
    {

        $libur = Libur::create([
            'tanggal' => $request->tanggal,
            'ruang_id' => $request->ruang_id,
            'libur' => $request->libur,
        ]);

        $libur->jadwals()->attach($request->jadwals);

        return back()->with('success', 'data berhasil ditambahkan');
    }

    public function update(Request $request, $id) {

        $libur = Libur::findOrFail($id);

        $libur->update([
            'tanggal' => $request->tanggal,
            'ruang' => $request->ruang,
            'acara' => $request->acara,
        ]);

        $libur->jadwals()->sync($request->jadwals);

        return back()->with('success', 'Data berhasil di perbarui');
    }

    public function destroy ($id)
    {
        $libur = Libur::findOrFail($id);

        $libur->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}
