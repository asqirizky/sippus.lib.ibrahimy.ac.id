@extends('layout.sidebarnavbar')
@section('admin-konten')

<!--begin::Main-->
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="py-3 app-toolbar py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="flex-wrap page-title d-flex flex-column justify-content-center me-3">
                    <!--begin::Title-->
                    <h1 class="my-0 text-gray-900 page-heading d-flex fw-bold fs-3 flex-column justify-content-center">Rekap Absen Harian</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="pt-1 my-0 breadcrumb breadcrumb-separatorless fw-semibold fs-7">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="/admin/home" class="text-muted text-hover-primary">Beranda</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bg-gray-500 bullet w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Rekap</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
		<div id="kt_app_content" class="app-content flex-column-fluid">
			<!--begin::Content container-->
			<div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin: Statistics Widget-->
                <div class="mb-5 card mb-xl-10">
                    <!--begin::Body-->
                    <div class="py-10 card-body">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="mb-4 text-gray-900 fw-bold fs-2">Rekap Absensi Pustakawan Struktural Perpustakaan Ibrahimy</div>
                            <div class="gap-2 mb-6">
                                <form method="GET" action="{{ route('struktural.cetak', ['bulan' => request('bulan', now()->month), 'tahun' => request('tahun', now()->year)]) }}" target="_blank" class="mb-4 d-flex align-items-center">
                                    <div class="gap-2 d-flex align-items-end">
                                        <div>
                                            <select name="bulan" id="bulan" class="form-select" data-control="select2" data-hide-search="true">
                                                @foreach(range(1, 12) as $b)
                                                    <option value="{{ $b }}" {{ request('bulan', now()->month) == $b ? 'selected' : '' }}>
                                                        {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <select name="tahun" id="tahun" class="form-select" data-control="select2" data-hide-search="true">
                                                @foreach(range(now()->year - 5, now()->year + 1) as $t)
                                                    <option value="{{ $t }}" {{ request('tahun', now()->year) == $t ? 'selected' : '' }}>
                                                        {{ $t }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-danger btn-md fw-bold">
                                            <i class="ki-outline ki-down-square me-1"></i>Cetak
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="flex-wrap d-flex">
                            <!--begin::Stat-->
                            <div class="px-4 py-3 border border-gray-300 border-dashed rounded min-w-125px me-6">
                                <div class="text-gray-600 fw-semibold fs-6">Hadir</div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-simcard fs-3 text-success me-2"></i>
                                    <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="{{ $hadir }}">{{ $hadir }}</div>
                                </div>
                                <div class="mt-1 text-muted fs-7"></div>
                            </div>
                            <!--end::Stat-->
                            <!--begin::Stat-->
                            <div class="px-4 py-3 border border-gray-300 border-dashed rounded min-w-125px me-6">
                                <div class="text-gray-600 fw-semibold fs-6">Tanpa Keterangan</div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-simcard-2 fs-3 text-danger me-2"></i>
                                    <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="{{ $tanpaKeterangan }}">{{ $tanpaKeterangan }}</div>
                                </div>
                                <div class="mt-1 text-muted fs-7"></div>
                            </div>
                            <!--end::Stat-->
                            <!--begin::Stat-->
                            <div class="px-4 py-3 border border-gray-300 border-dashed rounded min-w-125px me-6">
                                <div class="text-gray-600 fw-semibold fs-6">Izin</div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-profile-user fs-3 text-primary me-2"></i>
                                    <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="{{ $izinJumlah }}">{{ $izinJumlah }}</div>
                                </div>
                                <div class="mt-1 text-muted fs-7"></div>
                            </div>
                            <!--end::Stat-->
                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end: Statistics Widget-->
                <!--begin::Products-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="gap-2 py-5 card-header align-items-center gap-md-5 bg-success">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--end::Button filter-->
                            <a href="{{ route('struktural-mandiri') }}" class="btn btn-warning me-4">Mandiri</a>
                            <!--end::Button filter-->
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card title-->
                        <div class="gap-2 card-toolbar d-flex justify-content-end align-items-center">
                            <form action="" method="GET" class="gap-2 d-flex align-items-center">
                                <input class="form-control mw-150px" type="date" name="tanggal" value="{{ $tanggal }}">
                                <button class="btn fw-bold btn-primary">Filter</button>
                            </form>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="py-4 card-body">
                        <!--begin::Table-->
                        <table class="table align-middle table-striped table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead class="fw-bold fs-5 bg-success">
                                <tr class="text-white text-start fw-bold fs-7 text-uppercase gs-0">
                                    <th class="rounded-start ps-4 min-w-125px">Nama</th>
                                    <th class="text-center min-w-125px">Tanggal</th>
                                    <th class="text-center min-w-125px">Jam Masuk</th>
                                    <th class="text-center min-w-125px">Shift</th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center min-w-10px rounded-end">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                            @foreach ($struktural as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="px-4 py-3 badge fs-7 badge-light-success">
                                            {{ $item->pustakawan->nik }} - {{ $item->pustakawan->nama_pustakawan }}
                                        </div>
                                    </td>
                                    <td class="text-center">{{ Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMMM Y') }}</td>
                                    <td class="text-center">{{ $item->jam_masuk }}</td>
                                    <td class="text-center">{{ $item->jadwal->jadwal ?? '-' }}</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center">
                                        <div class="px-4 py-3 badge fs-7 badge-light-primary">{{ $item->keterangan }}</div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>

                <!--end::Products-->
				<!--end::Navbar-->
			</div>
			<!--end::Content container-->
		</div>
		<!--end::Content-->
    </div>
    <!--end::Content wrapper-->
    <!--begin::Footer-->
    @include('layout.footer')
    <!--end::Footer-->
</div>
<!--end:::Main-->

<!--begin::Javascript-->
<script>
    var hostUrl = "assets/";
</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="admin/assets/js/scripts.bundle.js"></script>
<!--begin::Veadmin/ndors Javascript(used for this page only)-->
<script src="admin/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<!--end::Vendadmin/ors Javascript-->
<!--begin::Cuadmin/stom Javascript(used for this page only)-->
<script src="admin/assets/js/custom/apps/ecommerce/catalog/products.js"></script>
<script src="admin/assets/js/widgets.bundle.js"></script>
<script src="admin/assets/js/custom/widgets.js"></script>
<script src="admin/assets/js/custom/apps/chat/chat.js"></script>
<script src="admin/assets/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="admin/assets/js/custom/utilities/modals/create-app.js"></script>
<script src="admin/assets/js/custom/utilities/modals/users-search.js"></script>
<!--end::Custom Javascript-->
<!--end::Javascript-->




@endsection
