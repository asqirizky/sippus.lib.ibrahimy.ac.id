@extends('layout.sidebarnavbar')
@section('admin-konten')

{{-- content --}}
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
                    <h1 class="my-0 text-gray-900 page-heading d-flex fw-bold fs-3 flex-column justify-content-center">
                        Barokah Pustakawan</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="pt-1 my-0 breadcrumb breadcrumb-separatorless fw-semibold fs-7">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="admin/home" class="text-muted text-hover-primary">Beranda</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bg-gray-500 bullet w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="admin/kehadiran-rekapan" class="text-muted text-hover-primary">Rekapan</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bg-gray-500 bullet w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Barokah Pustakawan</li>
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
                <!--begin::Products-->
                <div class="card card-flush" >
                    <form method="POST" enctype="multipart/form-data" action="{{ route('barokah_struktural.store') }}">
                    @csrf

                    <input type="hidden" name="periode" value="{{ $periode }}">
                    <input type="hidden" name="apbm" value="{{ $apbm }}">

                    <!--begin::Card header-->
                    <div class="card-header bg-success align-items-center py-5 px-6"
                        style="background-image: url('/admin/assets/media/pattern.png');
                        background-size: 650px;
                        background-position: right;
                        background-repeat: no-repeat;">
                        <div class="w-100 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-danger">
                                    Simpan
                                </button>
                                <a href="#"
                                    class="btn btn-light-primary btn-active-primary d-flex align-items-center"
                                    data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">APBM {{ request('apbm', date('Y')) }}
                                    <i class="ki-outline ki-down fs-5 ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="py-4 card-body">
                        <!--begin::Table-->
                        <table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                            <thead class="fw-bold fs-5 bg-success">
                                <tr class="text-white text-start fw-bold fs-7 text-uppercase gs-0">
                                    <th class="rounded-start ps-4"></th>
                                    <th class="text-start min-w-10px">Nama</th>
                                    <th class="text-center min-w-125px">Jabatan</th>
                                    <th class="text-center min-w-125px">Pengabdian</th>
                                    <th class="text-center min-w-125px">Kehadiran</th>
                                    <th class="text-center min-w-125px">Tunkel</th>
                                    <th class="text-center min-w-120px">Anak</th>
                                    <th class="text-center min-w-125px">Kehormatan</th>
                                    <th class="text-center min-w-125px rounded-end">TBK</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach ($pustakawans as $item)
                                    @php
                                        $aktifRankDosen = !empty($item->tmt_mengajar);
                                        $rankDosen = (int) ($item->t_rank_dosen ?? 0);
                                        $dataBarokah = $barokah->get($item->id);
                                        $sksOld = (int) old(
                                            "sks_dosen.$item->id",
                                            $dataBarokah->sks ?? 0
                                        );
                                    @endphp
                                    <tr>
                                        <td></td>
                                        <!-- begin::Nama -->
                                        <td class="text-start">
                                            <span class="px-4 py-3 badge fs-7 badge-light-success">
                                                {{ $item->nama_pustakawan }}
                                            </span>
                                        </td>
                                        <!-- end::Nama -->
                                        <!-- begin::Jabatan -->
                                        <td class="text-center">
                                            <div class="form-check form-check-custom form-check-sm">
                                                <input class="form-check-input" type="checkbox" id="jabatan_{{ $item->id }}" value="{{ $item->jabatan_id }}" name="pustakawan[{{ $item->id }}][t_jabatan_id]"
                                                    {{ ($dataBarokah && $dataBarokah->t_jabatan_id > 0)
                                                        ? 'checked'
                                                        : '' }}>
                                                <label class="form-check-label" for="jabatan_{{ $item->id }}">
                                                    {{ number_format($item->jabatan, 0, ',', '.') }}
                                                </label>
                                            </div>
                                        </td>
                                        <!-- end::Jabatan -->
                                        <!-- begin::Pengabdian -->
                                        <td class="text-center">
                                            <div class="form-check form-check-custom form-check-sm">
                                                <input class="form-check-input" type="checkbox" id="pengabdian_{{ $item->id }}" value="{{ $item->pengabdian_id }}" name="pustakawan[{{ $item->id }}][t_pengabdian_id]"
                                                    {{ ($dataBarokah && $dataBarokah->t_pengabdian_id > 0)
                                                        ? 'checked'
                                                        : '' }}>
                                                <label class="form-check-label" for="pengabdian_{{ $item->id }}">
                                                    {{ number_format($item->pengabdian, 0, ',', '.') }}
                                                </label>
                                            </div>
                                        </td>
                                        <!-- end::Pengabdian -->
                                        <!-- begin::Kehadiran -->
                                        <td class="text-center">
                                            <div class="form-check form-check-custom form-check-sm">
                                                <input class="form-check-input" type="checkbox" id="kehadiran_{{ $item->id }}" value="{{ $item->kehadiran_id }}" name="pustakawan[{{ $item->id }}][t_kehadiran_id]"
                                                    {{ ($dataBarokah && $dataBarokah->t_kehadiran_id > 0)
                                                        ? 'checked'
                                                        : '' }}>
                                                <label class="form-check-label" for="kehadiran_{{ $item->id }}">
                                                    {{ number_format($item->kehadiran, 0, ',', '.') }}
                                                </label>
                                            </div>
                                        </td>
                                        <!-- end::Kehadiran -->
                                        <!-- begin::Tunkel -->
                                        <td class="text-center">
                                            <div class="form-check form-check-custom form-check-sm">
                                                <input class="form-check-input" type="checkbox" id="tunkel_{{ $item->id }}" value="{{ $item->tunkel_id }}" name="pustakawan[{{ $item->id }}][t_tunkel_id]"
                                                    {{ ($dataBarokah && $dataBarokah->t_tunkel_id > 0)
                                                        ? 'checked'
                                                        : '' }}>
                                                <label class="form-check-label" for="tunkel_{{ $item->id }}">
                                                    {{ number_format($item->tunkel, 0, ',', '.') }}
                                                </label>
                                            </div>
                                        </td>
                                        <!-- end::Tunkel -->
                                        <!-- begin::Anak -->
                                        <td class="text-center">
                                            <div class="form-check form-check-custom form-check-sm">
                                                <input class="form-check-input" type="checkbox" id="anak_{{ $item->id }}" value="{{ $item->anak_id }}" name="pustakawan[{{ $item->id }}][t_anak_id]"
                                                    {{ ($dataBarokah && $dataBarokah->t_anak_id > 0)
                                                        ? 'checked'
                                                        : '' }}>
                                                <label class="form-check-label" for="anak_{{ $item->id }}">
                                                    {{ number_format($item->anak, 0, ',', '.') }}
                                                </label>
                                            </div>
                                        </td>
                                        <!-- end::Anak -->
                                        <!-- begin::Kehormatan -->
                                        <td class="text-center">
                                            <div class="form-check form-check-custom form-check-sm">
                                                <input class="form-check-input" type="checkbox" id="kehormatan_{{ $item->id }}" value="{{ $item->kehormatan_id }}" name="pustakawan[{{ $item->id }}][t_kehormatan_id]"
                                                    {{ ($dataBarokah && $dataBarokah->t_kehormatan_id > 0)
                                                        ? 'checked'
                                                        : '' }}>
                                                <label class="form-check-label" for="kehormatan_{{ $item->id }}">
                                                    {{ number_format($item->kehormatan, 0, ',', '.') }}
                                                </label>
                                            </div>
                                        </td>
                                        <!-- end::Kehormatan -->
                                        <!-- begin::TBK -->
                                        <td class="text-center">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">SKS</span>
                                                @if ($aktifRankDosen)
                                                    <input type="number" name="sks_dosen[{{ $item->id }}]" class="form-control" min="0" value="{{ $sksOld }}">
                                                @else
                                                    <input type="number" class="form-control" value="{{ $sksOld }}" readonly>
                                                @endif
                                                <span class="input-group-text">x</span>
                                                <input type="text" class="form-control" value="{{ number_format($rankDosen) }}" readonly>
                                            </div>
                                            @if (!$aktifRankDosen)
                                                <small class="mt-1 text-muted d-block">Belum memiliki TMT Mengajar</small>
                                            @endif
                                        </td>
                                        <!-- end::TBK -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </form>
                </div>
                <!--end::Products-->
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
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="admin/assets/js/scripts.bundle.js"></script>
<script src="admin/assets/plugins/global/plugins.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--end::Custom Javascript-->
<!--end::Javascript-->

@endsection
