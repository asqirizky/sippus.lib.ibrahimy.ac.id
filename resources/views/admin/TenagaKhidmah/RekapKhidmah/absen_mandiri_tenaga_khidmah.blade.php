@extends('layout.sidebarnavbar')
@section('admin-konten')

<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="py-3 app-toolbar py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="flex-wrap page-title d-flex flex-column justify-content-center me-3">
                    <h1 class="my-0 text-gray-900 page-heading d-flex fw-bold fs-3 flex-column justify-content-center">Rekap Absen Harian Tenaga Khidmah</h1>
                    <ul class="pt-1 my-0 breadcrumb breadcrumb-separatorless fw-semibold fs-7">
                        <li class="breadcrumb-item text-muted">
                            <a href="/admin/home" class="text-muted text-hover-primary">Beranda</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bg-gray-500 bullet w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Rekap Tenaga Khidmah</li>
                    </ul>
                    </div>
                </div>
            </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="mb-5 card mb-xl-10">
                    <div class="py-10 card-body">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="mb-4 text-gray-900 fw-bold fs-2">Rekap Absensi Personil Tenaga Khidmah Perpustakaan Ibrahimy</div>
                        </div>
                        <div class="flex-wrap d-flex">
                            <div class="px-4 py-3 border border-gray-300 border-dashed rounded min-w-125px me-6">
                                <div class="text-gray-600 fw-semibold fs-6">Hadir</div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-simcard fs-3 text-success me-2"></i>
                                    <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="{{ $hadir ?? 0 }}">{{ $hadir ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="px-4 py-3 border border-gray-300 border-dashed rounded min-w-125px me-6">
                                <div class="text-gray-600 fw-semibold fs-6">Tanpa Keterangan</div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-simcard-2 fs-3 text-danger me-2"></i>
                                    <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="{{ $tanpaKeterangan ?? 0 }}">{{ $tanpaKeterangan ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="px-4 py-3 border border-gray-300 border-dashed rounded min-w-125px me-6">
                                <div class="text-gray-600 fw-semibold fs-6">Izin</div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-profile-user fs-3 text-primary me-2"></i>
                                    <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="{{ $izinJumlah ?? 0 }}">{{ $izinJumlah ?? 0 }}</div>
                                </div>
                            </div>
                            </div>
                    </div>
                    </div>
                <div class="card card-flush">
                    <div class="gap-2 py-5 card-header align-items-center gap-md-5 " style="background-image: url('/admin/assets/media/pattern.png'); background-size: 350px; background-position: right; background-repeat: no-repeat; background-color: #F1416C;">
                        <div class="card-title">
                            <a data-bs-toggle="modal" data-bs-target="#kt_modal_update_details" class="btn btn-danger me-4 ">Absen Mandiri</a>
                        </div>

                        {{-- Ganti modal include ini sesuai dengan file proses modal Tenaga Khidmah milikmu --}}
                        @include('admin.TenagaKhidmah.RekapKhidmah.proses_tenaga_khidmah')

                        <div class="gap-2 card-toolbar d-flex justify-content-end align-items-center">
                            <form action="" method="GET" class="gap-2 d-flex align-items-center">
                                <input class="form-control mw-150px" type="date" name="tanggal" value="{{ $tanggal ?? date('Y-m-d') }}">
                                <button class="btn fw-bold btn-primary">Filter</button>
                            </form>
                        </div>
                        </div>
                    <div class="py-4 card-body">
                        <table class="table align-middle table-striped table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead class="fw-bold fs-5 " style="background-color: #F1416C;">
                                <tr class="text-white text-start fw-bold fs-7 text-uppercase gs-0">
                                    <th class="rounded-start ps-4 min-w-125px">Nama</th>
                                    <th class="text-center min-w-125px">Tanggal</th>
                                    <th class="text-center min-w-125px">Jam Masuk</th>
                                    <th class="text-center min-w-125px">Shift</th>
                                    <th></th>
                                    <th class="text-center min-w-125px">Keterangan</th>
                                    <th class="text-center min-w-10px rounded-end">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                            {{-- DISESUAIKAN: Menggunakan $absenKhidmah dari RekapTenagaKhidmahController --}}
                            @foreach ($absenKhidmah as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="px-4 py-3 badge fs-7 badge-light-success">
                                            {{ $item->nik ?? '-' }} - {{ $item->nama_pustakawan ?? 'Tidak Diketahui' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{ $item->tanggal ? Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMMM Y') : '-' }}
                                    </td>
                                    <td class="text-center">{{ $item->jam_masuk ?? '-' }}</td>
                                    <td class="text-center">{{ $item->jadwal ?? '-' }}</td>
                                    <td></td>
                                    <td class="text-center">
                                        <div class="px-4 py-3 badge fs-7 badge-light-primary">{{ $item->keterangan ?? '-' }}</div>
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="#" class="btn btn-sm btn-light-primary btn-active-primary btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Opsi
                                        <i class="ki-outline ki-down fs-5 ms-1"></i></a>
                                        <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px" data-kt-menu="true">
                                            <div class="px-3 menu-item">
                                                {{-- Sesuaikan nama rute hapus absensi untuk Tenaga Khidmah --}}
                                                <a href="{{ route('khidmah.absen.hapus', $item->id) }}" class="px-3 menu-link delete-button">Hapus</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @include('layout.footer')
    </div>
<script>
    var hostUrl = "assets/";
</script>
<script src="admin/assets/js/scripts.bundle.js"></script>
<script src="admin/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="admin/assets/js/custom/apps/ecommerce/catalog/products.js"></script>
<script src="admin/assets/js/widgets.bundle.js"></script>
<script src="admin/assets/js/custom/widgets.js"></script>
<script src="admin/assets/js/custom/apps/chat/chat.js"></script>
<script src="admin/assets/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="admin/assets/js/custom/utilities/modals/create-app.js"></script>
<script src="admin/assets/js/custom/utilities/modals/users-search.js"></script>
@endsection
