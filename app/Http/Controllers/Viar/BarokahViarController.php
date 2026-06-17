<?php

namespace App\Http\Controllers\Viar;

use App\Http\Controllers\Controller;
use App\Models\Barokah\BarokahKhidmah; // Model master nominal
use Illuminate\Http\Request;

class BarokahViarController extends Controller
{
    //
    public function index()
    {
        // 1. Ambil data nominal barokah khusus untuk kelompok 'tenaga_khidmah'
        $barokah = BarokahKhidmah::where('tipe_barokah', 'viar')
            ->latest()
            ->get();

        // 2. Kirim data ke view (listPustakawan dihapus karena modal hanya input nominal)
        return view('admin.Viar.BarokahViar.barokah_viar', compact('barokah'));
    }

    public function store(Request $request)
    {
        // Validasi input form (pustakawan_id dihapus)
        $request->validate([
            'barokah' => 'required|numeric|min:0',
        ]);

        // Simpan nominal master baru
        BarokahKhidmah::create([
            'tipe_barokah' => 'viar', // Mengunci kategori untuk view ini
            'barokah'      => $request->barokah,
        ]);

        return back()->with('success', 'Alhamdulillah, data nominal master berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barokah' => 'required|numeric|min:0',
        ]);

        $barokah = BarokahKhidmah::findOrFail($id);

        $barokah->update([
            'barokah' => $request->barokah,
        ]);

        return redirect()->back()->with('success', 'Alhamdulillah, data nominal master berhasil diperbarui');
    }

    public function destroy($id)
    {
        $barokah = BarokahKhidmah::findOrFail($id);
        $barokah->delete();

        return back()->with('success', 'Alhamdulillah, data nominal master berhasil dihapus');
    }
}
