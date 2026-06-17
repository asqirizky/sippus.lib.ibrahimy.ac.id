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
                        Barokah Tenaga Khidmah</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="pt-1 my-0 breadcrumb breadcrumb-separatorless fw-semibold fs-7">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="admin/home" class="text-muted text-hover-primary">Beranda</a>
                        </li>
                        <!--end::Item-->
                        <li class="breadcrumb-item">
                            <span class="bg-gray-500 bullet w-5px h-2px"></span>
                        </li>
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Barokah Tenaga Khidmah</li>
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
                <div class="card">
                    <!--begin::Card header-->
                    <div class="pt-6 border-0 card-header align-items-center gap-md-5 bg-success" style="background-image: url('/admin/assets/media/pattern.png'); background-size: 350px; background-position: right; background-repeat: no-repeat;">
                        <!--begin::Card title-->
                        <div class="gap-2 card-toolbar d-flex justify-content-end align-items-center">
                            <!--begin::Card toolbar-->
                            <div class="card-title">
                                <!--begin::Select-->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_update_details">
                                    <i class="ki-outline ki-plus fs-2"></i>Tambah
                                </button>
                                <!--end::Select-->
                                @include('admin.TenagaKhidmah.BarokahKhidmah.TambahBarokahKhidmah')
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="py-4 card-body">
                        <!--begin::Table-->
                        <table class="table align-middle table-striped table-row-dashed fs-6 gy-5" >
                            <thead class="fw-bold fs-5 bg-success">
                                <tr class="text-white text-start fw-bold fs-7 text-uppercase gs-0">
                                    <th class="rounded-start ps-4 text-center w-50px">NO</th>
                                    <th class="min-w-150px">Kategori</th>
                                    <th class="text-center min-w-150px">Barokah</th>
                                    <th class="text-center min-w-100px rounded-end">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                            @forelse ($barokah as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class=" text-gray-800 fw-bold">
                                        <!-- Menampilkan label yang rapi berdasarkan string ENUM -->
                                        @if($item->tipe_barokah === 'tenaga_khidmah')
                                            <span class="badge badge-light-success fs-7 fw-bold">Tenaga Khidmah (Perpustakaan)</span>
                                        @else
                                            <span class="badge badge-light-primary fs-7 fw-bold">{{ ucfirst($item->tipe_barokah) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold text-gray-900">
                                        Rp {{ number_format($item->barokah, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                    <a href="#" class="btn btn-sm btn-light-primary btn-active-primary btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Opsi
                                        <i class="ki-outline ki-down fs-5 ms-1"></i></a>
                                        <!--begin::Menu-->
                                        <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="px-3 menu-item">
                                                <a class="px-3 menu-link" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target{{ $item->id }}">Edit</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="px-3 menu-item">
                                                <a href="{{ route('khidmah.barokah.destroy', $item->id) }}" class="px-3 menu-link delete-button" data-kt-users-table-filter="delete_row" data-confirm-delete="true">Hapus</a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                        @include('admin.TenagaKhidmah.BarokahKhidmah.barokah_khidmah_update')
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada data nominal master yang diatur.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
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
<script src="admin/assets/plugins/global/plugins.bundle.js"></script>
<!--end::Global Javascript Bundle-->
@endsection
