<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruang;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    public function index() {

        $ruang = Ruang::get();

        return view('admin.Master.ruang.ruang', compact(
            'ruang',
        ));
    }

    public function store(Request $request)
    {
        
        Ruang::create([
            'ruang_pustakawans' => $request->ruang_pustakawans,
        ]);

        return back()->with('success', 'Data berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $ruang = Ruang::findOrFail($id);

        $ruang->update([
            'ruang_pustakawans' => $request->ruang_pustakawans, 
        ]);

        return back()->with('success', 'Data berhasil diperbarui');

    }

    public function destroy($id)
    {
        $ruang = Ruang::findOrFail($id);

        $ruang->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}
