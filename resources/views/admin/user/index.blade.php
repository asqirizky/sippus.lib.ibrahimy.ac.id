@extends('layout.sidebarnavbar')
@section('admin-konten')
    <!--begin::Main-->
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            Data Pengguna</h1>
                        <!--end::Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">
                                <a href="admin/home" class="text-muted text-hover-primary">Beranda</a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">Data Pengguna</li>
                            <!--end::Item-->
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page title-->
                    @include('layout.preview')
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
                        <div class="card-header border-0 pt-6">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <!--begin::Search-->
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                    <input type="text" data-kt-user-table-filter="search"
                                        class="form-control w-250px ps-13" placeholder="Cari Pengguna">
                                </div>
                                <!--end::Search-->
                            </div>
                            <!--begin::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Toolbar-->
                                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                    <!--begin::Add user-->
                                    @if (auth()->user()->hasPermissionTo('hak pengguna-tambah'))
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_update_details">
                                            <i class="ki-duotone ki-plus fs-2"></i>Tambah Pengguna
                                        </button>
                                    @endif
                                    <!--end::Add user-->
                                </div>
                                <!--end::Toolbar-->
                                @include('admin.user.tambah')
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body py-4">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                <thead class="fw-bold fs-5 bg-danger">
                                    <tr class="text-start text-white fw-bold fs-7 text-uppercase gs-0">
                                        <th class="rounded-start"></th>
                                        <th class="min-w-125px">Pengguna</th>
                                        <th class="text-center">Username</th>
                                        <th></th>
                                        <th class="text-center">Ruang</th>
                                        <th class="text-center">Tanggal Dibuat</th>
                                        <th class="text-center min-w-100px rounded-end pe-4">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                    @foreach ($user as $item)
                                        <tr>
                                            <td></td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <div class="symbol-label">
                                                        <img src="{{ asset('/admin/assets/media/' . ($item->pustakawan->foto ?? 'default.png')) }}"
                                                        alt="{{ $item->pustakawan?->nama_pustakawan }}"
                                                        class="w-100">
                                                    </div>
                                                </div>
                                                <!--end::Avatar-->
                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <div class="text-gray-800 mb-1">{{ $item->pustakawan->nama_pustakawan }}</div>
                                                    <span>{{ $item->pustakawan->jabatan->nama_jabatan }}</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td class="text-center">
                                                <div class="badge py-3 px-4 fs-7 badge-light-success fw-bold">{{ $item->username }}
                                                </div>
                                            </td>
                                            <td></td>
                                            <td class="text-center">{{ $item->pustakawan->ruang->ruang_pustakawans }}</td>
                                            <td class="text-center">{{ Carbon\Carbon::parse($item->created_at)->isoFormat('D MMMM Y') }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-sm btn-light btn-flex btn-center btn-sm"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Opsi
                                                    <i class="ki-outline ki-down fs-5 ms-1"></i></a>
                                                <!--begin::Menu-->
                                                <div class="menu me nu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    @if (auth()->user()->hasPermissionTo('hak pengguna-kelola'))
                                                        <div class="menu-item px-3">
                                                            <a href="{{ url('/admin/pengguna-akses-'.$item->id.'-akses') }}"
                                                                class="menu-link px-3">Kelola Akses</a>
                                                        </div>
                                                    @endif
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    @if (auth()->user()->hasPermissionTo('hak pengguna-edit'))
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                                data-bs-target="#kt_modal_update_details{{ $item->id }}">Edit
                                                                Akun</a>
                                                        </div>
                                                    @endif
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    @if (auth()->user()->hasPermissionTo('hak pengguna-ubah'))
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                                data-bs-target="#kt_modal_ubahpassword{{ $item->id }}">Ubah
                                                                Password</a>
                                                        </div>
                                                    @endif
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    @if (auth()->user()->hasPermissionTo('hak pengguna-hapus'))
                                                        <div class="menu-item px-3">
                                                            <a href="{{ asset('/admin/pengguna(' . $item->id . ')/hapus') }}"
                                                                class="menu-link px-3 delete-button"
                                                                data-kt-users-table-filter="delete_row"
                                                                data-confirm-delete="true">Hapus</a>
                                                        </div>
                                                    @endif
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                            @include('admin.user.edit')
                                            @include('admin.user.ubahpassword')
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
        <!--end::Footer container-->
    </div>
    <!--end::Footer-->
    </div>
    <!--end:::Main-->
    <!--end::Modals-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="admin/assets/plugins/global/plugins.bundle.js"></script>
    <script src="admin/assets/js/scripts.bundle.js"></script>
    <!--end::Globadmin/al Javascript Bundle-->
    <!--begin::Veadmin/ndors Javascript(used for this page only)-->
    <script src="admin/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <!--end::Vendadmin/ors Javascript-->
    <!--begin::Cuadmin/stom Javascript(used for this page only)-->
    <script src="admin/assets/js/custom/apps/user-management/users/list/table.js"></script>
    <script src="admin/assets/js/custom/apps/user-management/users/list/export-users.js"></script>
    <script src="admin/assets/js/custom/apps/user-management/users/list/add.js"></script>
    <script src="admin/assets/js/widgets.bundle.js"></script>
    <script src="admin/assets/js/custom/widgets.js"></script>
    <script src="admin/assets/js/custom/apps/chat/chat.js"></script>
    <script src="admin/assets/js/custom/utilities/modals/upgrade-plan.js"></script>
    <script src="admin/assets/js/custom/utilities/modals/create-app.js"></script>
    <script src="admin/assets/js/custom/utilities/modals/users-search.js"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
@endsection
