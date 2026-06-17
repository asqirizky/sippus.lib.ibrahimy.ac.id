<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Master\Jabatan;
use App\Models\Model\Payroll\PayrollDosen as PayrollPayrollDosen;
use App\Models\Payroll\PayrollDosen;
use App\Models\Payroll\PayrollAnak;
use App\Models\Payroll\PayrollDosen as ModelsPayrollPayrollDosen;
use App\Models\Payroll\PayrollJabatan;
use App\Models\Payroll\PayrollKehadiran;
use App\Models\Payroll\PayrollKehormatan;
use App\Models\Payroll\PayrollPengabdian;
use App\Models\Payroll\PayrollTunkel;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index () {

        if (request()->is('admin/payroll-kehadiran')) {

            $kehadiran = PayrollKehadiran::get();
            
            return view('admin.Payroll.kehadiran.kehadiran', compact(
                'kehadiran'
            ));
        }

        if (request()->is('admin/payroll-jabatan')) {

            $jabatan = Jabatan::where('status', 1)->get();
            $payJabatan = PayrollJabatan::with('jabatan')->get();
            
            return view('admin.Payroll.jabatan.jabatan', compact(
                'payJabatan',
                'jabatan',
            ));
        }
        
        if (request()->is('admin/payroll-pengabdian')) {

            $pengabdian = PayrollPengabdian::get();
            
            return view('admin.Payroll.pengabdian.pengabdian', compact(
                'pengabdian',
            ));
        }

        if (request()->is('admin/payroll-tunkel')) {

            $tunkel = PayrollTunkel::get();
            
            return view('admin.Payroll.tunkel.tunkel', compact(
                'tunkel',
            ));
        }

        if (request()->is('admin/payroll-kehormatan')) {
            
            $kehormatan = PayrollKehormatan::get();

            return view('admin.Payroll.kehormatan.kehormatan', compact(
                'kehormatan',
            ));
        }

        if (request()->is('admin/payroll-anak')) {

            $anak = PayrollAnak::get();
            
            return view('admin.Payroll.anak.anak', compact(
                'anak',
            ));
        }

        if (request()->is('admin/payroll-rankDosen')) {

            $rank = PayrollDosen::get();
            
            return view('admin.Payroll.rank_dosen.rank_dosen', compact(
                'rank',
            ));
        }

    }

    public function store (Request $request) {

        if (request()->is('admin/payroll-kehadiran')) {
            $request->validate([
                'tempatTinggal' => 'required|string|max:255',
                'tunjangan'     => 'required|string|max:255',
            ]);

            // Simpan data
            PayrollKehadiran::create([
                'tempatTinggal' => $request->tempatTinggal,
                'tunjangan'     => $request->tunjangan,
                'APBM'          => $request->APBM,
            ]);

            return back()->with('success', 'Data berhasil ditambahkan');
        }

        if (request()->is('admin/payroll-pengabdian')) {

            PayrollPengabdian::create([
                'tunjangan_pengabdian' => $request->tunjangan_pengabdian,
                'APBM' => $request->APBM,
            ]);

            return back()->with('success', 'Data berhasil disimpan');
        }

        if (request()->is('admin/payroll-tunkel')) {
            
            PayrollTunkel::create([
                'tunkel' => $request->tunkel,
                'APBM' => $request->APBM,
            ]);

            return back()->with('success', 'Data berhasil disimpan');
        }

        if (request()->is('admin/payroll-kehormatan')) {
            
            PayrollKehormatan::create([
                'tunjangan_kehormatan' => $request->tunjangan_kehormatan,
                'APBM' => $request->APBM,
            ]);

            return back()->with('success', 'Data berhasil disimpan');
        }

        if (request()->is('admin/payroll-anak/*')) {
            
            PayrollAnak::create([
                'tunjangan_anak' => $request->tunjangan_anak,
                'APBM' => $request->APBM,
            ]);

            return back()->with('success', 'Data berhasil disimpan');
        }

        if (request()->is('admin/payroll-rankDosen')) {
            
            PayrollDosen::create([
                'tahun' => $request->tahun,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                't_rank_dosen' => $request->t_rank_dosen,
                'APBM' => $request->APBM,
            ]);

            return back()->with('success', 'Data berhasil disimpan');
        }
    }

    public function update (Request $request, $id) {

        if (request()->is('admin/payroll-kehadiran/*')) {

            $kehadiran = PayrollKehadiran::findOrFail($id);

            $request->validate([
                'tunjangan' => 'required|string|max:255',
            ]);

            $kehadiran->update([
                'tunjangan' => $request->tunjangan,
                'APBM' => $request->APBM,
            ]);

            return back()->with('success', 'Data berhasil diperbarui');
        }

        if (request()->is('admin/payroll-jabatan/*')) {
            
            $payJabatan = PayrollJabatan::findOrFail($id);

            $payJabatan->update([
                'jabatan_id' => $request->jabatan_id,
                'tunjangan_jabatan' => $request->tunjangan_jabatan,
                'APBM' => $request->APBM,
            ]);

            return back()->with('success', 'Data berhasil diperbarui');
        }

        if (request()->is('admin/payroll-pengabdian/*')) {
            
            $pengabdian = PayrollPengabdian::findOrFail($id);

            $pengabdian->update([
                'tunjangan_pengabdian' => $request->tunjangan_pengabdian,
                'APBM' => $request->APBM,
            ]);

            return back()->with('success', 'Data berhasil disimpan');
        }

        if (request()->is('admin/payroll-tunkel/*')) {
            
            $tunkel = PayrollTunkel::findOrFail($id);

            $tunkel->update([
                'tunkel' => $request->tunkel,
                'APBM' => $request->APBM,
            ]);

            return back()->with('success', 'Data berhasil diperbarui');
        }

        if (request()->is('admin/payroll-kehormatan/*')) {
            
            $kehormatan = PayrollKehormatan::findOrFail($id);

            $kehormatan->update([
                'tunjangan_kehormatan' => $request->tunjangan_kehormatan,
                'APBM' => $request->APBM,
            ]);

            return back()->with('success', 'Data berhasil diperbarui');
        }

        if (request()->is('admin/payroll-anak/*')) {

            $anak = PayrollAnak::findOrFail($id);

            $anak->update([
                'tunjangan_anak' => $request->tunjangan_anak,
                'APBM' => $request->APBM,
            ]);

            return back()->with('success', 'Data berhasil diperbarui');
        }

        if (request()->is('admin/payroll-rankDosen/*')) {
            
            $rank = PayrollDosen::findOrFail($id); 

            $rank->update([
                'tahun' => $request->tahun,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                't_rank_dosen' => $request->t_rank_dosen,
                'APBM' => $request->APBM,
            ]);

            return back('')->with('success', 'Data berhasil diperbarui');
        }
    }

    public function destroy ($id) {

        if (request()->is('admin/payroll-kehadiran/*/hapus')) {
            
            $kehadiran = PayrollKehadiran::findOrFail($id);   

            $kehadiran->delete();

            return back()->with('success', 'Data berhasil dihapus');
        }

        if (request()->is('admin/payroll-jabatan/*/hapus')) {
            
            $payJabatan = PayrollJabatan::findOrFail($id);

            $payJabatan->delete();

            return back()->with('success', 'Data berhasil dihapus');
        }

        if (request()->is('admin/payroll-pengabdian/*/hapus')) {
            
            $pengabdian = PayrollPengabdian::findOrFail($id);

            $pengabdian->delete();

            return back()->with('success', 'Data berhasil dihapus');
        }

        if (request()->is('admin/payroll-tunkel/*/hapus')) {
            
            $tunkel = PayrollTunkel::findOrFail($id);

            $tunkel->delete();

            return back()->with('success', 'Data berhasil dihapus');
        }

        if (request()->is('admin/payroll-kehormatan/*/hapus')) {
            
            $kehormatan = PayrollKehormatan::findOrFail($id);

            $kehormatan->delete();

            return back()->with('success', 'Data berhasil dihapus');
        }

        if (request()->is('admin/payroll-anak/*/hapus')) {
            
            $anak = PayrollAnak::findOrFail($id);

            $anak->delete();

            return back('success', 'Data berhasil dihapus');
        }

        if (request()->is('admin/payroll-rankDosen')) {
            
            $rank = PayrollDosen::findOrFail($id);

            $rank->delete();

            return back()->with('success', 'Data berhasil dihapus');
        }
    }
}
