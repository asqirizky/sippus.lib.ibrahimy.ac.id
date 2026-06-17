<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Kehadiran Pustakawan Struktural Bulan <?php echo e($namaBulan); ?> <?php echo e($tahun); ?></title>
    <style>
        h2 {
            text-align: center;
            font-size: 16px;
        }

        p.rekap.nama-bulan {
            font-size: 10px;
            font-weight: bold;
        }

        table.rekap {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px;
            padding: 0px;
            border: 1px solid-black;
        }

        table.daftar {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px;
            padding: 0px;
            border: 1px solid-black;
            white-space: nowrap;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        table,
        th {
            background: #c7ecfa;
        }

        table.rekap,
        tr.pertama {
            background: #ffffff;
        }

        th.ket {
            width: 3%;
        }

        th {
            padding: 4px;
            text-align: center;
            font-size: 10px;
        }

        td {
            padding: 4px;
            text-align: right;
            font-size: 10px;
        }

        td.tengah {
            text-align: center;
        }

        td.keterangan {
            text-align: center;
            white-space: nowrap;
        }

        td.kiri {
            text-align: left;
            padding: 4px 6px;
            white-space: nowrap;
        }

        td.ttd {
            border: none;
            text-align: center;
            width: 10%;
        }

        td.ket {
            text-align: center;
        }

        td.pustakawan {
            text-align: left;
        }

        td.totalA {
            text-align: center;
            background: #FF8C00;
        }

        td.totalS {
            text-align: center;
            background: #9ACD32;
        }

        td.totalT {
            text-align: center;
            background: #00FFFF;
        }

        td.totalI {
            text-align: center;
            background: #81b2da;
        }

        td.totalH {
            text-align: center;
            background: #e7e7e7;
        }

        th.tanggal {
            text-align: center;
            width: 15px;
            white-space: nowrap;
        }

        table.bulan {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
            border: none;
            background: #ffffff;
        }

        td.nama-bulan {
            text-align: left;
            border: none;
            font-size: 14px;
        }

        td.nama-shift {
            text-align: right;
            border: none;
            font-size: 14px;
        }

        tbody.rekap {
            background: #ffffff;
        }

        span.ttd {
            font-size: 12px;
        }

        .page-break {
            page-break-before: always;
        }

        table.keterangan {
            border: none;
            background: #ffffff;
            width: 100%;
            border-collapse: collapse;
        }

        /* Garis putus-putus antar kolom (bukan bawah baris) */
        table.libur td:first-child,
        table.libur th:first-child {
            border-right: 1px dashed #000;
        }

        table.libur td:nth-child(2),
        table.libur th:nth-child(2) {
            border-right: 1px dashed #000;
        }

        /* Hilangkan border lainnya */
        table.libur,
        table.libur th,
        table.libur td {
            border: none !important;
            background: #fff !important;
        }

        /* Garis header bawah tetap solid */
        table.libur tr.header-row th {
            border-bottom: 1px solid #000 !important;
        }
    </style>
</head>


<body>
    <h2>BAROKAH UMANA <br>PERPPUSTAKAAN IBRAHIMY <br>TAHUN <?php echo e($tahun); ?></h2>
    <p class="nama-bulan">Bulan : <?php echo e($namaBulan); ?></p>

    <table class="rekap">
        <thead>
            <tr class="pertama">
                <th rowspan="2">NO</th>
                <th rowspan="2">NAMA</th>
                <th rowspan="2">JABATAN</th>
                <th rowspan="2">TMT</th>
                <th rowspan="2">MP</th>

                <th colspan="7" style="border:1px solid #000;">BAROKAH</th>

                <th rowspan="2" style="border:1px solid #000;">JUMLAH</th>
                <th rowspan="2" class="ket">H</th>
                <th rowspan="2" class="ket">I</th>
                <th rowspan="2" class="ket">T</th>
                <th rowspan="2" class="ket">S</th>
                <th rowspan="2" class="ket">A</th>
                <th rowspan="2" class="ket">%</th>

            </tr>

            <tr class="kedua">
                <th>Jabatan</th>
                <th>Pengabdian</th>
                <th>Kehadiran</th>
                <th>Tunkel</th>
                <th>Anak</th>
                <th>TBK</th>
                <th>Kehormatan</th>
            </tr>
        </thead>
        <tbody>
            <?php
                use Carbon\Carbon;
                use App\Models\Absen\AbsenStruktural;

                $jumlahHadirPagi = 0;
                $jumlahHadirSiang = 0;
                $jumlahHadirMalam = 0;

                $totalBarokahJabatan = 0;
                $barokahJabatanTotal = 0;
                $totalSemuaKehadiran = 0;
                $totalPengabdian = 0;
                $t_kehormatan_id = 0;
                $jumlahKehadiran = 0;

                $totalRankDosen = 0;
                $totalKehormatan = 0;

                $periodeTanggal = Carbon::parse($startDate);

                $totalHari = count($dates);
            ?>
            <?php $__currentLoopData = $pustakawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $jumlahHadir = AbsenStruktural::where('pustakawan_id', $item->id)
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->count();

                    $jadwalPustakawan = $jadwalGroup[$item->id] ?? collect();

                    $totalShiftEfektif = 0;

                    $totalI = 0;
                    $totalS = 0;
                    $totalT = 0;
                    $totalA = 0;
                    $totalL = 0;

                    $jadwal = $jadwalPustakawan->first();

                    $shiftAktif = [
                        'pagi' => $jadwal ? (int) $jadwal->pagi : 0,
                        'siang' => $jadwal ? (int) $jadwal->siang : 0,
                        'malam' => $jadwal ? (int) $jadwal->malam : 0,
                    ];

                    $tidakPunyaShift = [
                        'pagi' => $shiftAktif['pagi'] ? 0 : $totalHari,
                        'siang' => $shiftAktif['siang'] ? 0 : $totalHari,
                        'malam' => $shiftAktif['malam'] ? 0 : $totalHari,
                    ];

                    $liburShift = [
                        'pagi' => 0,
                        'siang' => 0,
                        'malam' => 0,
                    ];

                    foreach ($dates as $tanggal) {
                        $hari = Carbon::parse($tanggal)->translatedFormat('l');
                        $jadwalHari = $jadwalPustakawan->firstWhere('hari', $hari);

                        if (!$jadwalHari) {
                            continue;
                        }

                        foreach (['pagi', 'siang', 'malam'] as $shift) {
                            if ($jadwalHari->$shift != 1) {
                                continue;
                            }

                            if ($shift == 'pagi' && $hari != 'Jumat') {
                                continue;
                            }

                            if ($shift == 'siang' && $hari == 'Jumat') {
                                continue;
                            }

                            if ($shift == 'malam' && $hari == 'Kamis') {
                                continue;
                            }

                            $totalShiftEfektif++;

                            $isLibur = false;
                            foreach ($liburs as $liburItem) {
                                // 1. Cek apakah tanggalnya sama
                                if ($liburItem->tanggal != $tanggal) {
                                    continue;
                                }

                                // 2. Cek apakah ruang_id null (Semua Ruang) atau cocok dengan ruang pustakawan
                                if (!is_null($liburItem->ruang_id) && $liburItem->ruang_id != $item->ruang_id) {
                                    continue;
                                }

                                // 3. Cek apakah shiftnya (pagi/siang/malam) diliburkan
                                foreach ($liburItem->jadwals as $jadwalLibur) {
                                    if (strtolower(trim($jadwalLibur->jadwal)) == $shift) {
                                        $isLibur = true;
                                        break 2; // Keluar dari 2 loop sekaligus
                                    }
                                }
                            }

                            if ($isLibur) {
                                $liburShift[$shift]++;
                                continue;
                            }

                            $hadir = AbsenStruktural::where('pustakawan_id', $item->id)
                                ->whereDate('tanggal', $tanggal)
                                ->whereHas('jadwal', function ($q) use ($shift) {
                                    $q->whereRaw('LOWER(jadwal) = ?', [$shift]);
                                })
                                ->exists();

                            $izinShift = $izinStruktural[$item->id][$tanggal][$shift]['ket'] ?? null;

                            if ($hadir) {
                                continue;
                            }

                            if ($izinShift) {
                                if ($izinShift == 'I') {
                                    $totalI++;
                                } elseif ($izinShift == 'S') {
                                    $totalS++;
                                } elseif ($izinShift == 'T') {
                                    $totalT++;
                                } elseif ($izinShift == 'L') {
                                    $totalL++;
                                }
                            } else {
                                $totalA++;
                            }
                        }
                    }

                    $TI = $totalI / 2;
                    $TS = $totalS / 2;

                    $jumlahHadirEfektif = min($jumlahHadir, $totalShiftEfektif);

                    $totalPotongan = $TI + $TS + $totalT + $totalA;

                    $persentase =
                        $totalShiftEfektif > 0
                            ? round((($totalShiftEfektif - $totalPotongan) / $totalShiftEfektif) * 100)
                            : 0;

                    if ($persentase < 50) {
                        $totalBarokahJabatan = round($item->t_jabatan_id * ($persentase / 100));
                    } else {
                        $totalBarokahJabatan = $item->t_jabatan_id;
                    }

                    $barokahJabatanTotal += $totalBarokahJabatan;

                    $totalKehadiran = $item->t_kehadiran_id * $jumlahHadir;
                    $totalSemuaKehadiran += $totalKehadiran;
                    $jumlahKehadiran += $totalKehadiran;

                    $tahunPeriode = Carbon::parse($periode)->year;
                    $mp = $tahunPeriode - Carbon::parse($item->tmt)->year;

                    $TMT = floor($mp / 3);
                    $TMT2 = $TMT * $item->t_pengabdian_id;
                    $kehormatan = floor($mp / 5) * $item->t_kehormatan_id;

                    $rankDosen = $item->rank_dosen_id * $item->sks;

                    $totalRankDosen += $rankDosen;
                    $totalKehormatan += $kehormatan;
                    $totalPengabdian = $totalPengabdian + $TMT2;

                    $jumlahKeseluruhan =
                        $totalBarokahJabatan +
                        $TMT2 +
                        $totalKehadiran +
                        $item->t_tunkel_id +
                        $item->t_anak_id +
                        $rankDosen +
                        $kehormatan;

                    $totalJumlahSemua =
                        $barokahJabatanTotal +
                        $totalPengabdian +
                        $jumlahKehadiran +
                        $pustakawan->sum('t_tunkel_id') +
                        $pustakawan->sum('t_anak_id') +
                        $totalRankDosen +
                        $totalKehormatan;
                ?>
                <tr>
                    <td class="tengah"><?php echo e($index + 1); ?></td>
                    <td class="kiri"><?php echo e($item->nama_pustakawan); ?></td>
                    <td class="kiri"><?php echo e($item->jabatan->nama_jabatan); ?></td>
                    <td class="tengah"><?php echo e(Carbon::parse($item->tmt)->isoFormat('Y')); ?></td>
                    <td class="tengah"><?php echo e($mp); ?></td>

                    <td><?php echo e(number_format($totalBarokahJabatan ?? 0, 0, ',', '.')); ?></td>
                    <td><?php echo e(number_format($TMT2 ?? 0, 0, ',', '.')); ?></td>
                    <td><?php echo e(number_format($totalKehadiran ?? 0, 0, ',', '.')); ?></td>
                    <td><?php echo e(number_format($item->t_tunkel_id ?? 0, 0, ',', '.')); ?></td>
                    <td><?php echo e(number_format($item->t_anak_id ?? 0, 0, ',', '.')); ?></td>
                    <td><?php echo e(number_format($rankDosen ?? 0, 0, ',', '.')); ?></td>
                    <td><?php echo e(number_format($kehormatan ?? 0, 0, ',', '.')); ?></td>

                    <td><?php echo e(number_format($jumlahKeseluruhan ?? 0, 0, ',', '.')); ?></td>
                    <td class="keterangan"><?php echo e($jumlahHadir); ?></td>
                    <td class="keterangan"><?php echo e($totalI); ?></td>
                    <td class="keterangan"><?php echo e($totalT); ?></td>
                    <td class="keterangan"><?php echo e($totalS); ?></td>
                    <td class="keterangan"><?php echo e($totalA); ?></td>
                    <td class="keterangan"><?php echo e($persentase); ?>%</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <tr style="background:#eef1f7; font-weight:bold;">
                <td style="border:1px solid #000; text-align: center;" colspan="5">Jumlah</td>

                <td><?php echo e(number_format($barokahJabatanTotal ?? 0, 0, ',', '.')); ?></td>
                <td><?php echo e(number_format($totalPengabdian ?? 0, 0, ',', '.')); ?></td>
                <td><?php echo e(number_format($jumlahKehadiran ?? 0, 0, ',', '.')); ?></td>
                <td><?php echo e(number_format($pustakawan->sum('t_tunkel_id'), 0, ',', '.')); ?></td>
                <td><?php echo e(number_format($pustakawan->sum('t_anak_id'), 0, ',', '.')); ?></td>
                <td><?php echo e(number_format($totalRankDosen ?? 0, 0, ',', '.')); ?></td>
                <td><?php echo e(number_format($totalKehormatan ?? 0, 0, ',', '.')); ?></td>

                <td><?php echo e(number_format($totalJumlahSemua ?? 0, 0, ',', '.')); ?></td>
                <td style="border:1px solid #000; text-align: center;" colspan="6">TOTAL
                    <?php echo e(number_format($totalJumlahSemua ?? 0, 0, ',', '.')); ?></td>
            </tr>
        </tbody>
    </table>

    <div style="position: fixed; bottom: 10px; left: 20px; font-size: 8pt;">
        <b>Download: <?php echo e(\Carbon\Carbon::parse($periode)->isoFormat('MMMM YYYY')); ?>,</b>
        <b>URL:</b> <?php echo e(url()->current()); ?>

    </div>

    <div style="position: fixed; bottom: 50px; right: 20px;">
        <div style="width: 60px; height: 30px;">
            <?php echo DNS2D::getBarcodeHTML(request()->fullUrl(), 'QRCODE', 3, 3); ?>

        </div>
    </div>

    <div class="page-break">
        <h2>REKAPITULASI DAFTAR HADIR UMANA' <br> PERPUSTAKAAN IBRAHIMY <br> TAHUN <?php echo e($tahun); ?></h2>
    </div>

    <table class="bulan">
        <tr>
            <td class="nama-bulan">Bulan <?php echo e($namaBulan); ?></td>
            <td class="nama-shift">Shift Pagi</td>
        </tr>
    </table>

    <table class="daftar">
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2" style="width: 135px; text-align: center;">Nama</th>
                <th colspan="<?php echo e(count($dates)); ?>">Tanggal</th>
                <th colspan="6">Total</th>
            </tr>
            <tr>
                <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tgl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="tanggal"><?php echo e(\Carbon\Carbon::parse($tgl)->format('d')); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th>H</th>
                <th>I</th>
                <th>T</th>
                <th>S</th>
                <th>A</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody class="rekap">
            <?php $__currentLoopData = $hadirPagi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $AbsenStruktural = collect($absensi['Pagi'][$item->id] ?? [])
                        ->pluck('tanggal')
                        ->map(fn($tgl) => \Carbon\Carbon::parse($tgl)->format('Y-m-d'))
                        ->toArray();

                    $totalH = 0;
                    $totalI = 0;
                    $totalT = 0;
                    $totalS = 0;
                    $totalA = 0;
                    $hariAktif = 0;
                ?>
                <tr>
                    <td class="tengah"><?php echo e($loop->iteration); ?></td>
                    <td class="pustakawan"><?php echo e($item->nama_pustakawan); ?></td>
                    <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tgl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $tglFormat = \Carbon\Carbon::parse($tgl)->format('Y-m-d');
                            $hari = \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('l');

                            $jadwalHari = $jadwalGroup[$item->id]->firstWhere('hari', $hari);

                            $isActiveShift = $jadwalHari ? $jadwalHari->pagi == 1 : false;

                            $isHadir = in_array($tglFormat, $AbsenStruktural);

                            $izinKet = $izinStruktural[$item->id][$tglFormat]['pagi']['ket'] ?? null;

                            $isLibur = false;
                            foreach ($liburs as $liburItem) {
                                // 1. Cek tanggal
                                if ($liburItem->tanggal != $tglFormat) {
                                    continue;
                                }

                                // 2. Cek ruang
                                if (!is_null($liburItem->ruang_id) && $liburItem->ruang_id != $item->ruang_id) {
                                    continue;
                                }

                                // 3. Cek shift Pagi
                                foreach ($liburItem->jadwals as $jadwalLibur) {
                                    if (strtolower(trim($jadwalLibur->jadwal)) == 'pagi') {
                                        $isLibur = true;
                                        break 2;
                                    }
                                }
                            }

                            if ($isLibur) {
                                $liburShift['pagi'] = ($liburShift['pagi'] ?? 0) + 1;
                            }

                            $totalShiftEfektif++;

                            $cellStyle = '';
                            if ($isLibur || !$isActiveShift) {
                                $cellStyle = 'background:#ffff00;';
                            } elseif ($izinKet) {
                                $cellStyle = match ($izinKet) {
                                    'T' => 'background:#00ffff;',
                                    'S' => 'background:#9acd32;',
                                    'I' => 'background:#81b2da;',
                                    'L' => 'background:#ffff00;',
                                    default => '',
                                };
                            } elseif (!$isHadir) {
                                $cellStyle = 'background:#ff8c00;';
                            }

                            if ($isActiveShift && !$isLibur && $izinKet !== 'L') {
                                $hariAktif++;
                                if ($isHadir) {
                                    $totalH++;
                                } elseif ($izinKet === 'I') {
                                    $totalI++;
                                } elseif ($izinKet === 'T') {
                                    $totalT++;
                                } elseif ($izinKet === 'S') {
                                    $totalS++;
                                } else {
                                    $totalA++;
                                }
                            }
                        ?>
                        <td style="text-align:center; <?php echo e($cellStyle); ?>">
                            <?php if($isLibur): ?>
                            <?php elseif($isHadir): ?>
                                H
                            <?php elseif($izinKet && $izinKet != 'L'): ?>
                                <?php echo e($izinKet); ?>

                            <?php elseif($isActiveShift): ?>
                                A
                            <?php endif; ?>
                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $persen = $hariAktif > 0 ? round(($totalH / $hariAktif) * 100) : 0;
                    ?>
                    <td class="totalH"><?php echo e($totalH); ?></td>
                    <td class="totalI"><?php echo e($totalI); ?></td>
                    <td class="totalT"><?php echo e($totalT); ?></td>
                    <td class="totalS"><?php echo e($totalS); ?></td>
                    <td class="totalA"><?php echo e($totalA); ?></td>
                    <td class="total"><?php echo e($persen); ?>%</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <br>
    <?php echo renderLiburTable($liburs, $bulan, $tahun, 'PAGI'); ?>


    <div class="page-break">
        <h2>REKAPITULASI DAFTAR HADIR UMANA <br> PERPUSTAKAAN IBRAHIMY <br> TAHUN <?php echo e($tahun); ?></h2>
    </div>

    <table class="bulan">
        <tr>
            <td class="nama-bulan">Bulan <?php echo e($namaBulan); ?></td>
            <td class="nama-shift">Shift Siang</td>
        </tr>
    </table>

    <table class="daftar">
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2" style="width: 135px; text-align: center;">Nama</th>
                <th colspan="<?php echo e(count($dates)); ?>">Tanggal</th>
                <th colspan="6">Total</th>
            </tr>
            <tr>
                <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tgl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="tanggal"><?php echo e(\Carbon\Carbon::parse($tgl)->format('d')); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th>H</th>
                <th>I</th>
                <th>T</th>
                <th>S</th>
                <th>A</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody class="rekap">
            <?php $__currentLoopData = $hadirSiang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $AbsenStruktural = collect($absensi['Siang'][$item->id] ?? [])
                        ->pluck('tanggal')
                        ->map(fn($tgl) => \Carbon\Carbon::parse($tgl)->format('Y-m-d'))
                        ->toArray();

                    $totalH = 0;
                    $totalI = 0;
                    $totalT = 0;
                    $totalS = 0;
                    $totalA = 0;
                    $hariAktif = 0;
                ?>
                <tr>
                    <td class="tengah"><?php echo e($loop->iteration); ?></td>
                    <td class="pustakawan"><?php echo e($item->nama_pustakawan); ?></td>
                    <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tgl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $tglFormat = \Carbon\Carbon::parse($tgl)->format('Y-m-d');

                            $hari = \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('l');

                            $jadwalHari = $jadwalGroup[$item->id]->firstWhere('hari', $hari);

                            $isActiveShift = $jadwalHari ? $jadwalHari->siang == 1 : false;

                            $isHadir = in_array($tglFormat, $AbsenStruktural);

                            $izinKet = $izinStruktural[$item->id][$tglFormat]['siang']['ket'] ?? null;

                            $isLibur = false;

                            foreach ($liburs as $liburItem) {
                                if ($liburItem->tanggal != $tglFormat) {
                                    continue;
                                }

                                if (!is_null($liburItem->ruang_id) && $liburItem->ruang_id != $item->ruang_id) {
                                    continue;
                                }

                                foreach ($liburItem->jadwals as $jadwalLibur) {
                                    if (strtolower(trim($jadwalLibur->jadwal)) == 'siang') {
                                        $isLibur = true;
                                        break 2;
                                    }
                                }
                            }

                            $cellStyle = '';

                            if ($isLibur) {
                                $cellStyle = 'background:#ffff00; color:black;';
                            } elseif (!$isActiveShift) {
                                $cellStyle = 'background:#ffff00;';
                            } elseif ($izinKet) {
                                $cellStyle = match ($izinKet) {
                                    'T' => 'background:#00ffff;',
                                    'S' => 'background:#9acd32;',
                                    'I' => 'background:#81b2da;',
                                    'L' => 'background:#ffff00;',
                                    default => '',
                                };
                            } elseif (!$isHadir) {
                                $cellStyle = 'background:#ff8c00;';
                            }

                            // Hitung total hanya hari aktif
                            if ($isActiveShift && !$isLibur && $izinKet !== 'L') {
                                $hariAktif++;

                                if ($isHadir) {
                                    $totalH++;
                                } elseif ($izinKet === 'I') {
                                    $totalI++;
                                } elseif ($izinKet === 'T') {
                                    $totalT++;
                                } elseif ($izinKet === 'S') {
                                    $totalS++;
                                } else {
                                    $totalA++;
                                }
                            }
                        ?>
                        <td style="text-align:center; <?php echo e($cellStyle); ?>">
                            <?php if($isLibur): ?>
                            <?php elseif($isHadir): ?>
                                H
                            <?php elseif($izinKet && $izinKet != 'L'): ?>
                                <?php echo e($izinKet); ?>

                            <?php elseif($isActiveShift): ?>
                                A
                            <?php endif; ?>
                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $persen = $hariAktif > 0 ? round(($totalH / $hariAktif) * 100) : 0;
                    ?>
                    <td class="totalH"><?php echo e($totalH); ?></td>
                    <td class="totalI"><?php echo e($totalI); ?></td>
                    <td class="totalT"><?php echo e($totalT); ?></td>
                    <td class="totalS"><?php echo e($totalS); ?></td>
                    <td class="totalA"><?php echo e($totalA); ?></td>
                    <td class="total"><?php echo e($persen); ?>%</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <br>
    <?php echo renderLiburTable($liburs, $bulan, $tahun, 'SIANG'); ?>


    <div class="page-break">
        <h2>REKAPITULASI DAFTAR HADIR UMANA <br> PERPUSTAKAAN IBRAHIMY <br> TAHUN <?php echo e($tahun); ?></h2>
    </div>

    <table class="bulan">
        <tr>
            <td class="nama-bulan">Bulan <?php echo e($namaBulan); ?></td>
            <td class="nama-shift">Shift Malam</td>
        </tr>
    </table>

    <table class="daftar">
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2" style="width: 135px; text-align: center;">Nama</th>
                <th colspan="<?php echo e(count($dates)); ?>">Tanggal</th>
                <th colspan="6">Total</th>
            </tr>
            <tr>
                <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tgl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="tanggal"><?php echo e(\Carbon\Carbon::parse($tgl)->format('d')); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th>H</th>
                <th>I</th>
                <th>T</th>
                <th>S</th>
                <th>A</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody class="rekap">
            <?php $__currentLoopData = $hadirMalam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $AbsenStruktural = collect($absensi['Malam'][$item->id] ?? [])
                        ->pluck('tanggal')
                        ->map(fn($tgl) => \Carbon\Carbon::parse($tgl)->format('Y-m-d'))
                        ->toArray();

                    $totalH = 0;
                    $totalI = 0;
                    $totalT = 0;
                    $totalS = 0;
                    $totalA = 0;
                    $hariAktif = 0;
                ?>
                <tr>
                    <td class="tengah"><?php echo e($loop->iteration); ?></td>
                    <td class="pustakawan"><?php echo e($item->nama_pustakawan); ?></td>
                    <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tgl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $tglFormat = \Carbon\Carbon::parse($tgl)->format('Y-m-d');
                            $hari = \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('l');

                            $jadwalHari = $jadwalGroup[$item->id]->firstWhere('hari', $hari);

                            $isActiveShift = $jadwalHari ? $jadwalHari->malam == 1 : false;

                            $isHadir = in_array($tglFormat, $AbsenStruktural);

                            $izinKet = $izinStruktural[$item->id][$tglFormat]['malam']['ket'] ?? null;

                            $isLibur = false;

                            foreach ($liburs as $liburItem) {
                                if ($liburItem->tanggal != $tglFormat) {
                                    continue;
                                }

                                if (!is_null($liburItem->ruang_id) && $liburItem->ruang_id != $item->ruang_id) {
                                    continue;
                                }

                                foreach ($liburItem->jadwals as $jadwalLibur) {
                                    if (strtolower(trim($jadwalLibur->jadwal)) == 'malam') {
                                        $isLibur = true;
                                        break 2;
                                    }
                                }
                            }

                            $cellStyle = '';

                            if ($isLibur) {
                                $cellStyle = 'background:#ffff00; color:black;';
                            } elseif (!$isActiveShift) {
                                $cellStyle = 'background:#ffff00;';
                            } elseif ($izinKet) {
                                $cellStyle = match ($izinKet) {
                                    'T' => 'background:#00ffff;',
                                    'S' => 'background:#9acd32;',
                                    'I' => 'background:#81b2da;',
                                    'L' => 'background:#ffff00;',
                                    default => '',
                                };
                            } elseif (!$isHadir) {
                                $cellStyle = 'background:#ff8c00;';
                            }

                            // Hitung total hanya hari aktif
                            if ($isActiveShift && !$isLibur && $izinKet !== 'L') {
                                $hariAktif++;

                                if ($isHadir) {
                                    $totalH++;
                                } elseif ($izinKet === 'I') {
                                    $totalI++;
                                } elseif ($izinKet === 'T') {
                                    $totalT++;
                                } elseif ($izinKet === 'S') {
                                    $totalS++;
                                } else {
                                    $totalA++;
                                }
                            }
                        ?>
                        <td style="text-align:center; <?php echo e($cellStyle); ?>">
                            <?php if($isLibur): ?>
                            <?php elseif($isHadir): ?>
                                H
                            <?php elseif($izinKet && $izinKet != 'L'): ?>
                                <?php echo e($izinKet); ?>

                            <?php elseif($isActiveShift): ?>
                                A
                            <?php endif; ?>
                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $persen = $hariAktif > 0 ? round(($totalH / $hariAktif) * 100) : 0;
                    ?>
                    <td class="totalH"><?php echo e($totalH); ?></td>
                    <td class="totalI"><?php echo e($totalI); ?></td>
                    <td class="totalT"><?php echo e($totalT); ?></td>
                    <td class="totalS"><?php echo e($totalS); ?></td>
                    <td class="totalA"><?php echo e($totalA); ?></td>
                    <td class="total"><?php echo e($persen); ?>%</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
        </tbody>
    </table>

    <br>

    <?php echo renderLiburTable($liburs, $bulan, $tahun, 'MALAM'); ?>


</body>

</html>

<?php
    function renderLiburTable($liburs, $bulan, $tahun, $shiftName)
    {
        $html = '
    <table class="keterangan">
        <tr>
            <td style="vertical-align:top;padding-left:40px;border:none;">
                <table class="libur" style="width:100%;">
                    <tr class="header-row">
                        <th style="width:80px;">Tanggal</th>
                        <th style="width:150px;">Ruang</th>
                        <th>Acara</th>
                    </tr>';

        $ada = false;

        foreach ($liburs as $item) {
            $tanggal = \Carbon\Carbon::parse($item->tanggal);

            if ($tanggal->month != $bulan || $tanggal->year != $tahun) {
                continue;
            }

            foreach ($item->jadwals as $jadwal) {
                if (strtoupper($jadwal->jadwal) == strtoupper($shiftName)) {
                    $ada = true;

                    $html .=
                        '
                <tr>
                    <td style="text-align: center">' .
                        $tanggal->isoFormat('DD MMM') .
                        '</td>
                    <td style="text-align: left">' .
                        ($item->ruang->ruang_pustakawans ?? 'Semua Ruang') .
                        '</td>
                    <td style="text-align: left">' .
                        $item->libur .
                        '</td>
                </tr>';

                    break;
                }
            }
        }

        if (!$ada) {
            $html .= '
        <tr>
            <td colspan="3" style="text-align:center;">
                Tidak ada libur bulan ini
            </td>
        </tr>';
        }

        $html .= '
                </table>
            </td>
        </tr>
    </table>';

        return $html;
    }
?>
<?php /**PATH /var/www/vhosts/sippus.lib.ibrahimy.ac.id/html/resources/views/admin/Struktural/RekapStruktural/laporan_struktural.blade.php ENDPATH**/ ?>