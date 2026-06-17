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
                        Data Pustkawan</h1>
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
                        <li class="breadcrumb-item text-muted">Pustakawan</li>
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
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="pt-6 border-0 card-header bg-success" style="background-image:url('admin/assets//media/pattern.png'); background-size: 300px; background-position: right; background-repeat: no-repeat;">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="my-1 d-flex align-items-center position-relative">
                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="text" data-kt-ecommerce-product-filter="search" class="form-control w-250px ps-12" placeholder="Cari" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--begin::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            @if (auth()->user()->hasPermissionTo('master pustakawan-tambah'))
                            <div class="flex-wrap gap-3 d-flex justify-content-between align-items-end" data-kt-user-table-toolbar="base">
                                <a href="{{ route('master-tambah.pustakawan') }}" type="button" class="btn btn-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i> Tambah Pustakawan
                                </a>
                            </div>
                            @endif
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="py-4 card-body">
                        <!--begin::Table-->
                        <table class="table align-middle table-striped fs-6 gy-5" id="kt_ecommerce_products_table">
                            <thead class="fw-bold fs-5 bg-success">
                                <tr class="text-white text-start fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center rounded-start w-125px pe-2">nik</th>
                                    <th class="text-start min-w-250px">Nama</th>
                                    <th></th>
                                    <th class="text-center min-w-80px">Ruang</th>
                                    <th></th>
                                    <th class="text-center min-w-125px">Jabatan</th>
                                    <th class="text-center min-w-125px">Status</th>
                                    <th class="text-center rounded-end min-w-100px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach ($pustakawan as $item)
                                <tr>
                                    <td class="text-center">
                                        <div class="px-4 py-3 badge fs-7 badge-light-primary">
                                            {{ $item->nik }}
                                        </div>
                                    </td>
                                    <td class="d-flex align-items-center">
                                        <!--begin:: Avatar -->
                                        <div class="overflow-hidden symbol symbol-circle symbol-50px me-3">
                                            <div class="symbol-label">
                                                <img src="{{ asset('admin/assets/media/'.$item->foto) }}" class="w-100" />
                                            </div>
                                        </div>
                                        <!--end::Avatar-->
                                        <!--begin::User details-->
                                        <div class="d-flex flex-column">
                                            <div class="mb-1 text-gray-800">{{ $item->nama_pustakawan }}</div>
                                        </div>
                                        <!--begin::User details-->
                                    </td>
                                    <td class="text-center"></td>
                                    <td class="text-center">
                                        @if ($item->ruang && $item->ruang->ruang_pustakawans == "Perpustakaan Pusat")
                                            <div class="px-4 py-3 badge fs-6 badge-light-warning">
                                                {{ $item->ruang->ruang_pustakawans }}
                                            </div>
                                        @elseif ($item->ruang && $item->ruang->ruang_pustakawans == "Perpustakaan Putri Pusat")
                                            <div class="px-4 py-3 badge fs-6 badge-light-primary">
                                                {{ $item->ruang->ruang_pustakawans }}
                                            </div>
                                        @elseif ($item->ruang && $item->ruang->ruang_pustakawans == "Perpustakaan Fakultas Syariah & Ekonomi Islam")
                                            <div class="div px-4 py-3 badge fs-6 badge-light-info">
                                                {{ $item->ruang->ruang_pustakawans }}
                                            </div>
                                        @elseif ($item->ruang && $item->ruang->ruang_pustakawans == "Perpustakaan Fakultas Ilmu Kebidanan")
                                            <div class="div px-4 py-3 badge fs-6 badge-light-danger">
                                                {{ $item->ruang->ruang_pustakawans }}
                                            </div>
                                        @elseif ($item->ruang && $item->ruang->ruang_pustakawans == "Viar A")
                                            <div class="div px-4 py-3 badge fs-6 badge-light-success">
                                                {{ $item->ruang->ruang_pustakawans }}
                                            </div>
                                        @elseif ($item->ruang && $item->ruang->ruang_pustakawans == "Viar B")
                                            <div class="div px-4 py-3 badge fs-6 badge-light-success">
                                                {{ $item->ruang->ruang_pustakawans }}
                                            </div>
                                        @endif
                                    </td>
                                    <td></td>
                                    <td class="text-center">
                                        <div class="px-4 py-3 badge fs-7 badge-light-success">
                                            {{ $item->jabatan->nama_jabatan }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->status == "0")
                                            <div class="badge py-3 px-4 fs-7 badge-light-danger">
                                                {{ $item->status ? 'Aktif' : 'Tidak Aktif' }}
                                            </div>
                                        @elseif ($item->status == "1")
                                            <div class="badge py-3 px-4 fs-7 badge-light-primary">
                                                {{ $item->status ? 'Aktif' : 'Tidak Aktif' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="#" class="btn btn-sm btn-light-primary btn-active-primary btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Opsi
                                        <i class="ki-outline ki-down fs-5 ms-1"></i></a>
                                        <!--begin::Menu-->
                                        <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px" data-kt-menu="true">
                                            @if (auth()->user()->hasPermissionTo('master pustakawan-detail'))
                                            <!--begin::Menu item-->
                                            <div class="px-3 menu-item">
                                                <a class="px-3 menu-link" href="{{ url('admin/master-detail_pustakawan=' . $item->id) }}">Detail</a>
                                            </div>
                                            <!--end::Menu item-->
                                            @endif
                                            <!--begin::Menu item-->
                                            <div class="px-3 menu-item">
                                                <a href="{{ route('master-pustakawan.hapus', $item->id) }}" class="px-3 menu-link delete-button" data-kt-users-table-filter="delete_row" data-confirm-delete="true">Hapus</a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
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
<script src="{{ asset('admin/assets/js/scripts.bundle.js') }}"></script>
<!--begin::Veadmin/ndors Javascript(used for this page only)-->
<script src="{{ asset('admin/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
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
