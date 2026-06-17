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
					<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Data Akses</h1>
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
						<li class="breadcrumb-item text-muted">Akses</li>
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
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header mt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1 me-5">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                <input type="text" data-kt-permissions-table-filter="search" class="form-control w-250px ps-13" placeholder="Search Akses" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        @if(auth()->user()->hasPermissionTo('akses pengguna-tambah'))
                        <div class="card-toolbar">
                            <!--begin::Button-->
                            <button type="button" class="btn btn-light-danger" data-bs-toggle="modal" data-bs-target="#kt_modal_add_permission">
                            <i class="ki-outline ki-plus-square fs-3"></i>Tambah Akses</button>
                            <!--end::Button-->
                        </div>
                        @endif
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    @include('admin.user.permission.tambah')
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_permissions_table">
                            <thead class="fw-bold fs-5 bg-danger">
                                <tr class="text-start text-white fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px ps-4 rounded-start">Akses</th>
                                    <th class="min-w-250px">Ditugaskan ke</th>
                                    <th></th>
                                    <th class="text-end min-w-100px pe-4 rounded-end">Dibuat</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($permissions as $item)
                                <tr>
                                    <td class="ps-4">{{ $item->name }}</td>
                                    <td>
                                        <!-- Menampilkan pengguna yang memiliki permission ini -->
                                        @if ($item->users->isEmpty())
                                            <div class="badge badge-light-danger">Tidak ada pengguna yang memiliki hak akses ini.</div>
                                        @else
                                            @foreach ($item->users as $user)
                                                <div class="badge py-3 px-4 fs-7 badge-light-primary my-1">{{ $user->name }}</div>
                                            @endforeach
                                        @endif
                                    </td>
                                    {{-- <td>{{ Carbon\Carbon::parse($item->created_at)->isoFormat('D MMMM Y - h:m') }}</td>
                                    <td class="text-end pe-4">
                                        @if(auth()->user()->hasPermissionTo('akses pengguna-edit'))
                                        <button class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_update_permission{{ $item->id }}">
                                            <i class="ki-outline ki-setting-3 fs-3"></i>
                                        </button>
                                        @endif
                                        @if(auth()->user()->hasPermissionTo('akses pengguna-hapus'))
                                        <a href="{{ asset('/admin/pengguna-akses/'.$item->id.'/hapus') }}" class="btn btn-icon btn-active-light-primary w-30px h-30px delete-button"><i class="ki-outline ki-trash fs-3"></i></a>
                                        @endif
                                    </td> --}}
                                    @include('admin.user.permission.edit')
                                    <td></td>
                                    <td class="text-end pe-4"><div class="badge py-3 px-4 fs-7 badge-danger">{{ Carbon\Carbon::parse($item->created_at)->isoFormat('D MMMM Y') }}</div></td>
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
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="admin/assets/plugins/global/plugins.bundle.js"></script>
		<script src="admin/assets/js/scripts.bundle.js"></script>
		<!--end::Globadmin/al Javascript Bundle-->
		<!--begin::Veadmin/ndors Javascript(used for this page only)-->
		<script src="admin/assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<!--end::Vendadmin/ors Javascript-->
		<!--begin::Cuadmin/stom Javascript(used for this page only)-->
		<script src="admin/assets/js/custom/apps/user-management/permissions/list.js"></script>
		<script src="admin/assets/js/custom/apps/user-management/permissions/add-permission.js"></script>
		<script src="admin/assets/js/custom/apps/user-management/permissions/update-permission.js"></script>
		<script src="admin/assets/js/widgets.bundle.js"></script>
		<script src="admin/assets/js/custom/widgets.js"></script>
		<script src="admin/assets/js/custom/apps/chat/chat.js"></script>
		<script src="admin/assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="admin/assets/js/custom/utilities/modals/create-app.js"></script>
		<script src="admin/assets/js/custom/utilities/modals/users-search.js"></script>
		<!--end::Custom Javascript-->
@endsection

