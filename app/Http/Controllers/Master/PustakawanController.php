<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Jabatan;
use App\Models\Master\Pustakawan;
use App\Models\Master\PustakawanJadwal;
use App\Models\Master\Ruang;
use Carbon\Carbon;
use Google\Service\Storage;
use Symfony\Component\HttpFoundation\Request;

class PustakawanController extends Controller
{
    public function index()
    {
        $pustakawan = Pustakawan::with('jabatan', 'ruang')
            ->get()
            ->sortBy('jabatan.eselon');

        $jabatan = Jabatan::where('status', 1)->get();

        return view('admin.Master.pustakawan.pustakawan', compact(
            'pustakawan',
            'jabatan',
        ));
    }

    public function tambah () {

        $ruang = Ruang::get();
        $jabatan = Jabatan::where('status', 1)->get();

        return view('admin.Master.pustakawan.tambah_pustakawan', compact(
            'ruang',
            'jabatan',
        ));
    }

    public function detail ($id) {

        $ruangs = Ruang::get();
        $pustakawan = Pustakawan::findOrFail($id);
        $jabatan = Jabatan::where('status', 1)->get();

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $shiftList = [
            1 => 'Pagi',
            2 => 'Siang',
            3 => 'Malam'
        ];

        $jadwalAktif = PustakawanJadwal::where('pustakawan_id', $pustakawan->id)->get()->keyBy('hari');

        $shiftAktif = PustakawanJadwal::where('pustakawan_id', $pustakawan->id)->get();

        foreach ($shiftAktif as $shift) {
            $key = $shift->hari . '-' . $shift->shift_id;
            $jadwalAktif[$key] = true;
        }

        return view('admin.Master.pustakawan.detail_pustakawan', compact(
            'ruangs',
            'pustakawan',
            'jabatan',
            'jadwalAktif',
            'shiftList',
            'hariList',
        ));
    }

    public function kelolah_pustakawan(Request $request, $id) {

        $pustakawan = Pustakawan::findOrFail($id);

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $shiftMap = [
            1 => 'pagi',
            2 => 'siang',
            3 => 'malam',
        ];

        foreach ($hariList as $hari) {
            $data = [
                'pagi' => 0,
                'siang' => 0,
                'malam' => 0,
            ];

            if (isset($request->aktif[$hari])) {
                foreach ($request->aktif[$hari] as $index => $value) {
                    $shiftName = $shiftMap[$index] ?? null;
                    if ($shiftName) {
                        $data[$shiftName] = 1;
                    }
                }
            }

            if ($data['pagi'] == 0 && $data['siang'] == 0 && $data['malam'] == 0) {
                PustakawanJadwal::where('pustakawan_id', $id)
                    ->where('hari', $hari)
                    ->delete();
            } else {
                PustakawanJadwal::updateOrCreate(
                    ['pustakawan_id' => $id, 'hari' => $hari],
                    array_merge($data, ['nik' => $pustakawan->nik])
                );
            }
        }

        $jadwalAktif = [];
        $jadwalPegawai = PustakawanJadwal::where('pustakawan_id', $id)->get();

        foreach ($hariList as $hari) {
            $jadwal = $jadwalPegawai->firstWhere('hari', $hari);
            $jadwalAktif[$hari] = [
                'pagi'  => $jadwal?->pagi ?? 0,
                'siang' => $jadwal?->siang ?? 0,
                'malam' => $jadwal?->malam ?? 0,
            ];
        }

        return back()->with('success', 'Jadwal sudah diperbarui');
    }

    public function upBerkas (Request $request, $id) {

        $request->validate([
            'berkas' => 'required|file|mimes:png,jpg,jpeg|max:1024',
            'keterangan' => 'required|string|max:255',
        ]);

        $pustakawan = Pustakawan::findOrFail($id);

        $berkas = $request->file('berkas');
        $fileName = $berkas->getClientOriginalName();

        $berkas->storeAs('public/berkas', $fileName);

        $pustakawan->update([
            'berkas' => $fileName,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Berkas berhasil disimpan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:1024',
            'nama_pustakawan' => 'required|string',
            'domisili' => 'required|string',
        ]);

        $tahunTmt = Carbon::parse($request->tmt)->format('Y');

        $jabatan = Jabatan::findOrFail($request->jabatan_id);
        $eselon = $jabatan->eselon;

        $jumlah = Pustakawan::whereYear('tmt', $tahunTmt)
            ->whereHas('jabatan', function ($q) use ($eselon) {
                $q->where('eselon', $eselon);
            })
            ->count();

        $urut = str_pad($jumlah + 1, 3, '0', STR_PAD_LEFT);
        $nik = $tahunTmt . $eselon . $urut;

        // Upload foto
        $namaFile = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');

            $namaFile = time() . '_' . $foto->hashName();

            $tujuan = public_path('admin/assets/media');

            if (!file_exists($tujuan)) {
                mkdir($tujuan, 0755, true);
            }

            $foto->move($tujuan, $namaFile);
        }

        Pustakawan::create([
            'nik' => $nik,
            'nama_pustakawan' => $request->nama_pustakawan,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'tmt' => $request->tmt,
            'tmt_mengajar' => $request->tmt_mengajar,
            'domisili' => $request->domisili,
            'pend_terakhir' => $request->pend_terakhir,
            'jk' => $request->jk,
            'status_perkawinan' => $request->status_perkawinan,
            'jabatan_id' => $request->jabatan_id,
            'status' => $request->input('status', '0'),
            'foto' => $namaFile,
        ]);

        return redirect()->route('master-pustakawan.index')->with('success', 'Data berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $pustakawan = Pustakawan::findOrFail($id);

        $request->validate([
            'nama_pustakawan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'status' => 'nullable|in:0,1',
            'no_wa' => 'nullable',
        ]);

        // Format nomor WhatsApp
        $noWa = $request->no_wa;

        if ($noWa) {
            $noWa = preg_replace('/[^0-9]/', '', $noWa);

            if (str_starts_with($noWa, '62')) {
                $noWa = '0' . substr($noWa, 2);
            }

            if (!str_starts_with($noWa, '0')) {
                $noWa = '0' . $noWa;
            }
        }

        // Upload foto baru
        if ($request->hasFile('foto')) {

            // Hapus foto lama jika ada
            if (
                $pustakawan->foto &&
                file_exists(public_path('admin/assets/media/' . $pustakawan->foto))
            ) {
                unlink(public_path('admin/assets/media/' . $pustakawan->foto));
            }

            $foto = $request->file('foto');

            $namaFile = time() . '_' . $foto->hashName();

            $tujuan = public_path('admin/assets/media');

            if (!file_exists($tujuan)) {
                mkdir($tujuan, 0755, true);
            }

            $foto->move($tujuan, $namaFile);

            $pustakawan->foto = $namaFile;
        }

        // Regenerate NIK jika jabatan berubah
        $newNik = $pustakawan->nik;

        if ($pustakawan->jabatan_id != $request->jabatan_id) {
            $nikLama = $pustakawan->nik;
            $tahun = substr($nikLama, 0, 4);
            $urut = substr($nikLama, -3);

            $jabatanBaru = Jabatan::findOrFail($request->jabatan_id);
            $eselonBaru = $jabatanBaru->eselon;

            $newNik = $tahun . $eselonBaru . $urut;
        }

        $pustakawan->update([
            'nik' => $newNik,
            'nama_pustakawan' => $request->nama_pustakawan,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'tmt' => $request->tmt,
            'tmt_mengajar' => $request->tmt_mengajar,
            'tgl_khidmah' => $request->tgl_khidmah,
            'pend_terakhir' => $request->pend_terakhir,
            'jk' => $request->jk,
            'status_perkawinan' => $request->status_perkawinan,
            'status' => $request->has('status') ? '1' : '0',
            'jabatan_id' => $request->jabatan_id,
            'ruang_id' => $request->ruang_id,
            'no_wa' => $noWa,
            'asrama' => $request->asrama,
            'sekolah_pagi' => $request->sekolah_pagi,
            'sekolah_sore' => $request->sekolah_sore,
            'foto' => $pustakawan->foto,
        ]);

        return redirect()->route('master-pustakawan.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pustakawan = Pustakawan::findOrFail($id);

        if (
            $pustakawan->foto &&
            file_exists(public_path('admin/assets/media/' . $pustakawan->foto))
        ) {
            unlink(public_path('admin/assets/media/' . $pustakawan->foto));
        }

        $pustakawan->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}
