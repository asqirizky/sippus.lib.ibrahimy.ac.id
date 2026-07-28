<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Master\Pustakawan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PustakawanController extends Controller
{
    /**
     * Daftar data master pustakawan.
     *
     * Endpoint ini sengaja tidak menyertakan data presensi, izin, payroll,
     * maupun nomor WhatsApp.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['nullable', 'in:aktif,nonaktif,all'],
            'search' => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'between:1,100'],
        ]);

        $pustakawan = Pustakawan::query()
            ->select([
                'id',
                'nik',
                'nama_pustakawan',
                'jabatan_id',
                'ruang_id',
                'tmt',
                'pend_terakhir',
                'status',
                'foto',
            ])
            ->with([
                'jabatan:id,nama_jabatan,eselon',
                'ruang:id,ruang_pustakawans',
            ])
            ->when($validated['status'] ?? null, function ($query, $requestedStatus) {
                if ($requestedStatus === 'all') {
                    return;
                }

                $status = $requestedStatus === 'aktif' ? 1 : 0;

                $query->where('status', $status);
            })
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama_pustakawan', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama_pustakawan')
            ->paginate($validated['per_page'] ?? 50)
            ->through(function (Pustakawan $item) {
                return [
                    'id' => $item->id,
                    'nik' => $item->nik,
                    'nama' => $item->nama_pustakawan,
                    'tmt' => $item->tmt,
                    'pendidikan_terakhir' => $item->pend_terakhir,
                    'status' => $item->status == 1 ? 'aktif' : 'nonaktif',
                    'jabatan' => $item->jabatan ? [
                        'id' => $item->jabatan->id,
                        'nama' => $item->jabatan->nama_jabatan,
                        'eselon' => $item->jabatan->eselon,
                    ] : null,
                    'ruang' => $item->ruang ? [
                        'id' => $item->ruang->id,
                        'nama' => $item->ruang->ruang_pustakawans,
                    ] : null,
                    'foto_url' => $item->foto
                        ? url('admin/assets/media/' . $item->foto)
                        : null,
                ];
            });

        return response()->json($pustakawan);
    }
}
