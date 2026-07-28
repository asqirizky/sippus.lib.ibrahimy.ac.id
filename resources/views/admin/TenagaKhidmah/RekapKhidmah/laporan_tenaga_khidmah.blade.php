<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran Pustakawan Khidmah Bulan {{ $namaBulan }} {{ $tahun }}</title>
    <style>
        /* Mengatur halaman cetak menjadi LANDSCAPE agar melebar seperti Excel */
        @page {
            size: A4 landscape;
            margin: 50px 40px 25px px;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            color: #000;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        .page {
            page-break-after: always;
            position: relative;
        }
        .page:last-child {
            page-break-after: avoid;
        }

        /* Header Style Minimalis */
        .header-container {
            width: 100%;
            margin-bottom: 12px;
            position: relative;
        }
        .header-text {
            width: 100%;
            text-align: center
        }
        .header-text h2 {
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-text h3 {
            margin: 2px 0 0 0;
            font-weight: normal;
            font-weight: bold;
            text-transform: uppercase;

        }
        .header-text p {
            margin: 1px 0 0 0;
            font-weight: bold;
            text-transform: uppercase;

        }

        .qr-box {
            width: 25%;
            float: right;
            text-align: right;
        }
        .qr-box img {
            width: 85px;
            height: 85px;
        }
        .clear {
            clear: both;
        }

        /* Table Style Standar Excel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 9px;
        }
        table, thead {
            border: none;
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
        th {
            background-color: #f2f2f2;
            color: #000;
            font-size: 9px;
            font-weight: bold;
            padding: 4px 1px;
            border: 1px solid #000;
            text-align: center;
        }
        td {
            padding: 4px 1px;
            border: 1px solid #000;
            text-align: center;
            background-color: #fff;
        }

        .text-left {
            text-align: left;
            padding-left: 4px;
        }
        .fw-bold {
            font-weight: bold;
        }

        .header tr th  {
            background-color: rgb(242, 242, 242);
        }

        /* Pewarnaan cell */
        .cell-alfa {
            background-color: orange !important; /* Warna Orange Soft */
            color: black;
            font-weight: bold;
        }
        .cell-izin {
            background-color: steelblue !important; /* Warna Biru Soft */
            color: black;
            font-weight: bold;
        }
        .cell-tugas {
            background-color: skyblue !important; /* Warna Biru Soft */
            color: black;
            font-weight: bold;
        }
        .cell-sakit {
            background-color: yellowgreen !important; /* Warna Biru Soft */
            color: black;
            font-weight: bold;
        }
        .cell-libur {
            background-color: yellow !important; /* Warna Biru Soft */

        }
        .cell-kuning {
            background-color: yellow !important; /* Warna Kuning Libur Hari Jumat */
        }

        .bg-summary {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .bg-rekap-orang {
            background-color: #f5f5f5;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div style="position: fixed; bottom: 10px; left: 20px; font-size: 8pt; color: #555;">
        <b>Download: {{ \Carbon\Carbon::parse($periode)->isoFormat('MMMM YYYY') }},</b>
        <b>URL:</b> {{ url()->current() }}
    </div>

    <div style="position: fixed; bottom: 10px; right: 20px;">
        <div style="width: 60px; height: 60px;">
            {!! DNS2D::getBarcodeHTML($qrUrl, 'QRCODE', 2, 2) !!}
        </div>
    </div>

    <div class="page">
        <div class="header-container">
            <div class="header-text">
                <h2>Rekapitulasi Konsumsi Penjagaan</h2>
                <h3>Perpustakaan Ibrahimy </h3>
                <h3>Tahun {{ $tahun }}</h3>
            </div>
        </div>

        <table class="bulan">
            <tr>
                <td class="nama-bulan">Bulan {{ $namaBulan }}</td>
            </tr>
        </table>

        <table>
            <thead class="header">
                <tr>
                    <th rowspan="2" style="width: 3%">No</th>
                    <th rowspan="2" class="text-center" style="width: 22%;">Nama</th>
                    <th colspan="3">Kehadiran</th>
                    <th rowspan="2">Persentase</th>
                    <th rowspan="2">Jumlah</th>
                    <th rowspan="2" style="width: 15%">Tanda Tangan</th>
                </tr>
                <tr>
                    <th>Siang</th>
                    <th>Malam</th>
                    <th>Jumlah Hadir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekapPersonil as $index => $rekap)
                    <tr>
                        <td style="text-align: center">{{ $index + 1 }}</td>
                        <td style="width: 25%" class="text-left">{{ $rekap['personil']->nama_pustakawan }}</td>
                        <td>{{ $rekap['siang'] }}</td>
                        <td>{{ $rekap['malam'] }}</td>
                        <td>{{ $rekap['jumlah_hadir'] }}</td>
                        <td>{{ $rekap['persentase'] }}%</td>
                        <td>Rp. {{ number_format($rekap['jumlah_barokah'], 0, ',', '.') }}</td>
                        <td style="font-size: 9px; vertical-align: bottom; height: 22px; padding: 3px 15px; border-top: none !important; border-left: none !important; border-bottom: none !important; border-right: 1px solid #000 !important;">
                            @if(($index + 1) % 2 != 0)
                                <div style="text-align: left;">
                                    {{ $index + 1 }}....................
                                </div>
                            @else
                                <div style="text-align: right;">
                                    {{ $index + 1 }}....................
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="background-color: paleturquoise">Total</td>
                    <td style="background-color: paleturquoise">{{ $totalRekap['siang'] }}</td>
                    <td style="background-color: paleturquoise">{{ $totalRekap['malam'] }}</td>
                    <td style="background-color: paleturquoise">{{ $totalRekap['jumlah_hadir'] }}</td>
                    <td style="background-color: paleturquoise">Total</td>
                    <td style="background-color: paleturquoise">Rp. {{ number_format($totalRekap['jumlah_barokah'], 0, ',', '.') }}</td>
                    <td style="border-top: none"></td>
                </tr>
            </tbody>
        </table>
    </div>

   <div class="page">
    <div class="header-container">
        <div class="header-text">
            <h2>Daftar Hadir Tenaga Khidmah</h2>
            <h3>Perpustakaan Ibrahimy</h3>
            <p>Tahun: <strong>{{ $tahun }}</strong> </p>
        </div>
        <div class="clear"></div>
    </div>

    <table class="bulan">
        <tr>
            <td class="nama-bulan">Bulan {{ $namaBulan }}</td>
            <td class="nama-shift">Shift Siang</td>
        </tr>
    </table>

    <table>
        <thead class="header">
            <tr>
                <th rowspan="2" style="width: 25px;">No.</th>
                <th rowspan="2" class="text-left" style="width: 140px;">Nama</th>
                <th colspan="{{ count($dates) }}">Tanggal</th>
                <th colspan="6">TOTAL</th>
            </tr>
            <tr>
                @foreach($dates as $date)
                    <th style="font-size: 7.5px; padding: 2px 0; width: 16px;">{{ Carbon\Carbon::parse($date)->format('d') }}</th>
                @endforeach
                <th style="width: 18px;">H</th>
                <th style="width: 18px;">I</th>
                <th style="width: 18px;">T</th>
                <th style="width: 18px;">S</th>
                <th style="width: 18px;">A</th>
                <th style="width: 32px; font-size: 8px;">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pustakawan as $index => $p)
                @php
                    $pHadir = 0; $pIzin = 0; $pTugas = 0; $pSakit = 0; $pAlfa = 0; $plibur = 0;
                    $totalHariKerjaShift = 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left" style="font-size: 8.5px;">{{ $p->nama_pustakawan }}</td>

                    @foreach($dates as $date)
                        @php
                            $adaJadwalShift = $matriksJadwal[$p->id][$date]['siang'] ?? false;

                            // LOGIKA BARU BERDASARKAN ATTACH JADWAL (SHIFT SIANG)
                            $isLiburResmi = isset($liburs) ? $liburs->contains(function ($value) use ($date, $p) {
                                // 1. Cek kecocokan tanggal
                                $matchTanggal = $value->tanggal == $date;
                                // 2. Cek kecocokan ruang (cocok id-nya ATAU null berarti semua ruang)
                                $matchRuang = is_null($value->ruang_id) || $value->ruang_id == $p->ruang_id;
                                // 3. Cek apakah di relasi jadwals-nya mengandung kata 'SIANG'
                                $matchShift = $value->jadwals->contains(function($j) {
                                    return strtoupper(trim($j->jadwal)) == 'SIANG';
                                });

                                return $matchTanggal && $matchRuang && $matchShift;
                            }) : false;

                            $statusAbsen = $absensiData[$p->id][$date]['siang'] ?? null;
                            $statusIzin = $izinKhidmah[$p->id][$date]['siang'] ?? null;

                            $display = '';
                            $tdClass = '';

                            if ($isLiburResmi) {
                                $tdClass = 'cell-kuning';
                                $display = '';
                            } elseif (!$adaJadwalShift) {
                                $tdClass = 'cell-kuning';
                                $display = '';
                            } else {
                                $totalHariKerjaShift++;

                                $rawStatus = $statusAbsen ? $statusAbsen : ($statusIzin ? $statusIzin : '');
                                $checkStatus = strtolower(substr(trim($rawStatus), 0, 1));

                                if ($checkStatus == '') {
                                    $checkStatus = 'a';
                                }

                                if ($checkStatus == 'h') {
                                    $display = 'H'; $pHadir++;
                                } elseif ($checkStatus == 'a') {
                                    $display = 'A'; $tdClass = 'cell-alfa'; $pAlfa++;
                                } elseif ($checkStatus == 'i') {
                                    $display = 'I'; $tdClass = 'cell-izin'; $pIzin++;
                                } elseif ($checkStatus == 't') {
                                    $display = 'T'; $tdClass = 'cell-tugas'; $pTugas++;
                                } elseif ($checkStatus == 's') {
                                    $display = 'S'; $tdClass = 'cell-sakit'; $pSakit++;
                                } elseif ($checkStatus == 'l') {
                                    $display = '' ; $tdClass = 'cell-libur'; $plibur++;
                                }
                            }
                        @endphp
                        <td class="{{ $tdClass }}">{{ $display }}</td>
                    @endforeach

                    <td class="bg-rekap-orang">{{ $pHadir }}</td>
                    <td class="bg-rekap-orang cell-izin" style="color:black;">{{ $pIzin }}</td>
                    <td class="bg-rekap-orang cell-tugas" style="color:black;">{{ $pTugas }}</td>
                    <td class="bg-rekap-orang cell-sakit" style="color: black;">{{ $pSakit }}</td>
                    <td class="bg-rekap-orang cell-alfa" style="background-color: white; color:black;">{{ $pAlfa }}</td>

                    @php
                        $persenHadir = $totalHariKerjaShift > 0 ? round(($pHadir / $totalHariKerjaShift) * 100) : 0;
                    @endphp
                    <td class="bg-rekap-orang fw-bold" style="color: black; font-size: 8.5px;">
                        {{ $persenHadir }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- KETERANGAN LIBUR SHIFT SIANG --}}
    <div style="margin-top: 20px;">
        <table class="keterangan" style="width: 100%; border: none; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: top; padding-left: 40px; border: none;">
                    <table class="libur" style="width: 100%; border-collapse: collapse; border: none; text-align:center;">
                        <thead>
                            <tr class="header-row">
                                <th style="width: 80px; padding: 6px; border-bottom: 1px solid #000000; border-top:none; border-right:none; border-left:none; text-align: center; font-weight: bold;">Tanggal</th>
                                <th style="width: 150px; padding: 6px; border-bottom: 1px solid #000000; border-top:none; border-right:none; border-left:none; text-align: center; font-weight: bold;">Ruang</th>
                                <th style="padding: 6px; border-bottom: 1px solid #000000; border-top:none; border-right:none; border-left:none; text-align: center; font-weight: bold;">Acara</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $adaLiburResmi = false; @endphp

                            @if(isset($liburs))
                                @foreach ($liburs as $item)
                                    @php
                                        // Filter agar baris keterangan hanya muncul jika data libur ini meng-attach shift 'SIANG'
                                        $isSiang = $item->jadwals->contains(function($j) {
                                            return strtoupper(trim($j->jadwal)) == 'SIANG';
                                        });
                                    @endphp

                                    @if($isSiang)
                                        @php $adaLiburResmi = true; @endphp
                                        <tr style="text-align: center;">
                                            <td style="text-align: center; padding: 6px; border: none;">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('DD MMM') }}
                                            </td>
                                            <td style="text-align: left; padding: 6px; border:none; ">
                                                @php
                                                    $namaRuang = 'Semua Ruang';
                                                    if (!empty($item->ruang_id)) {
                                                        $cariRuang = DB::table('ruangs')->where('id', $item->ruang_id)->first();
                                                        $namaRuang = $cariRuang?->ruang_pustakawans ?? 'Ruang Tidak Diketahui';
                                                    }
                                                @endphp
                                                {{ $namaRuang }}
                                            </td>
                                            <td style="text-align: left; padding: 6px; border : none; ">
                                                {{ $item->libur }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif

                            @if (!$adaLiburResmi)
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #555; font-style: italic; padding: 15px 0; border:none;">
                                        Tidak ada agenda libur resmi untuk Shift Siang pada bulan ini
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</div>

    <div class="page">
        <div class="header-container">
            <div class="header-text">
                <h2>Daftar Hadir Tenaga Khidmah</h2>
                <h3>Perpustakaan Ibrahimy</h3>
                <p>Tahun: <strong>{{ $tahun }}</strong> </p>
            </div>
            <div class="clear"></div>
        </div>

        <table class="bulan">
            <tr>
                <td class="nama-bulan">Bulan {{ $namaBulan }}</td>
                <td class="nama-shift">Shift Malam</td>
            </tr>
        </table>

        <table>
        <thead class="header">
            <tr>
                <th rowspan="2" style="width: 25px;">No.</th>
                <th rowspan="2" class="text-left" style="width: 140px;">Nama</th>
                <th colspan="{{ count($dates) }}">Tanggal</th>
                <th colspan="6">TOTAL</th>
            </tr>
            <tr>
                @foreach($dates as $date)
                    <th style="font-size: 7.5px; padding: 2px 0; width: 16px;">{{ Carbon\Carbon::parse($date)->format('d') }}</th>
                @endforeach
                <th style="width: 18px;">H</th>
                <th style="width: 18px;">I</th>
                <th style="width: 18px;">T</th>
                <th style="width: 18px;">S</th>
                <th style="width: 18px;">A</th>
                <th style="width: 32px; font-size: 8px;">%</th>
            </tr>
        </thead>
       <tbody>
            @foreach($pustakawan as $index => $p)
                @php
                    $pHadirM = 0; $pIzinM = 0; $pTugasM = 0; $pSakitM = 0; $pAlfaM = 0; $pLiburM = 0;
                    $totalHariKerjaMalam = 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left" style="font-size: 8.5px;">{{ $p->nama_pustakawan }}</td>

                    @foreach($dates as $date)
                        @php
                            $adaJadwalShift = $matriksJadwal[$p->id][$date]['malam'] ?? false;

                            // LOGIKA BARU BERDASARKAN ATTACH JADWAL (SHIFT MALAM)
                            $isLiburResmi = isset($liburs) ? $liburs->contains(function ($value) use ($date, $p) {
                                // 1. Cek kecocokan tanggal
                                $matchTanggal = $value->tanggal == $date;
                                // 2. Cek kecocokan ruang (cocok id-nya ATAU null berarti semua ruang)
                                $matchRuang = is_null($value->ruang_id) || $value->ruang_id == $p->ruang_id;
                                // 3. Cek apakah di relasi jadwals-nya mengandung kata 'MALAM'
                                $matchShift = $value->jadwals->contains(function($j) {
                                    return strtoupper(trim($j->jadwal)) == 'MALAM';
                                });

                                return $matchTanggal && $matchRuang && $matchShift;
                            }) : false;

                            $statusAbsen = $absensiData[$p->id][$date]['malam'] ?? null;
                            $statusIzin = $izinKhidmah[$p->id][$date]['malam'] ?? null;

                            $display = '';
                            $tdClass = '';

                            if ($isLiburResmi) {
                                $tdClass = 'cell-kuning';
                                $display = '';
                            } elseif (!$adaJadwalShift) {
                                $tdClass = 'cell-kuning';
                                $display = '';
                            } else {
                                $totalHariKerjaMalam++;

                                $rawStatus = $statusAbsen ? $statusAbsen : ($statusIzin ? $statusIzin : '');
                                $checkStatus = strtolower(substr(trim($rawStatus), 0, 1));

                                if ($checkStatus == '') {
                                    $checkStatus = 'a';
                                }

                                if ($checkStatus == 'h') {
                                    $display = 'H'; $pHadirM++;
                                } elseif ($checkStatus == 'a') {
                                    $display = 'A'; $tdClass = 'cell-alfa'; $pAlfaM++;
                                } elseif ($checkStatus == 'i') {
                                    $display = 'I'; $tdClass = 'cell-izin'; $pIzinM++;
                                } elseif ($checkStatus == 't') {
                                    $display = 'T'; $tdClass = 'cell-tugas'; $pTugasM++;
                                } elseif ($checkStatus == 's') {
                                    $display = 'S'; $tdClass = 'cell-sakit'; $pSakitM++;
                                } elseif ($checkStatus == 'l') {
                                    $display = ''; $tdClass = 'cell-libur'; $pLiburM++;
                                }
                            }
                        @endphp
                        <td class="{{ $tdClass }}">{{ $display }}</td>
                    @endforeach

                    <td class="bg-rekap-orang">{{ $pHadirM }}</td>
                    <td class="bg-rekap-orang cell-izin" style="color:black;">{{ $pIzinM }}</td>
                    <td class="bg-rekap-orang cell-tugas" style="color:black;">{{ $pTugasM }}</td>
                    <td class="bg-rekap-orang cell-sakit" style="color: black;">{{ $pSakitM }}</td>
                    <td class="bg-rekap-orang cell-alfa" style="background-color: white; color:black;">{{ $pAlfaM }}</td>

                    @php
                        $persenHadirM = $totalHariKerjaMalam > 0 ? round(($pHadirM / $totalHariKerjaMalam) * 100) : 0;
                    @endphp
                    <td class="bg-rekap-orang fw-bold" style="color: black; font-size: 8.5px;">
                        {{ $persenHadirM }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- KETERANGAN LIBUR SHIFT MALAM --}}
    <div style="margin-top: 20px;">
        <table class="keterangan" style="width: 100%; border: none; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: top; padding-left: 40px; border: none; ">
                    <table class="libur" style="width: 100%; border-collapse: collapse; border: none;">
                        <thead>
                            <tr class="header-row">
                                <th style="width: 80px; padding: 6px; border-bottom: 1px  solid #000000; border-top: none; border-right: none; border-left: none; text-align: center; font-weight: bold;">Tanggal</th>
                                <th style="width: 150px; padding: 6px; border-bottom: 1px solid #000000; border-top: none; border-right: none; border-left: none; text-align: center; font-weight: bold;">Ruang</th>
                                <th style="padding: 6px; border-bottom: 1px solid #000000; border-top: none; border-right: none; border-left: none; text-align: center; font-weight: bold;">Acara</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $adaLiburResmi = false; @endphp

                            @if(isset($liburs))
                                @foreach ($liburs as $item)
                                    @php
                                        // Filter agar baris keterangan hanya muncul jika data libur ini meng-attach shift 'MALAM'
                                        $isMalam = $item->jadwals->contains(function($j) {
                                            return strtoupper(trim($j->jadwal)) == 'MALAM';
                                        });
                                    @endphp

                                    @if($isMalam)
                                        @php $adaLiburResmi = true; @endphp
                                        <tr>
                                            <td style="text-align: center; padding: 6px; border:none;">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('DD MMM') }}
                                            </td>
                                            <td style="text-align: left; padding: 6px; border:none;">
                                                @php
                                                    $namaRuang = 'Semua Ruang';
                                                    if (!empty($item->ruang_id)) {
                                                        $cariRuang = DB::table('ruangs')->where('id', $item->ruang_id)->first();
                                                        $namaRuang = $cariRuang?->ruang_pustakawans ?? 'Ruang Tidak Diketahui';
                                                    }
                                                @endphp
                                                {{ $namaRuang }}
                                            </td>
                                            <td style="text-align: left; padding: 6px; border:none;">
                                                {{ $item->libur }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif

                            @if (!$adaLiburResmi)
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #555; font-style: italic; padding: 15px 0; border:none;">
                                        Tidak ada agenda libur resmi untuk Shift Malam pada bulan ini
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
