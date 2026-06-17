<?php

namespace App\Http\Controllers\TenagaKhidmah;

use Illuminate\Http\Request;
use App\Models\Barokah\BarokahKhidmah; // Model master nominal
use App\Http\Controllers\Controller;

class BarokahTenagaKhidmahController extends Controller
{
    public function index()
    {
        $barokah = BarokahKhidmah::where('tipe_barokah', 'tenaga_khidmah')
            ->latest()
            ->get();

        return view('admin.TenagaKhidmah.BarokahKhidmah.BarokahTenagaKhidmah', compact('barokah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barokah' => 'required|numeric|min:0',
        ]);

        BarokahKhidmah::create([
            'tipe_barokah' => 'tenaga_khidmah', // Mengunci kategori untuk view ini
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
            'tipe_barokah' => 'tenaga_khidmah',
            'barokah' => $request->barokah,
        ]);

        return back()->with('success', 'Data berhasil diperbarui');

    }

    public function destroy($id)
    {
        $barokah = BarokahKhidmah::findOrFail($id);
        $barokah->delete();

        return back()->with('success', 'Alhamdulillah, data nominal master berhasil dihapus');
    }
}
