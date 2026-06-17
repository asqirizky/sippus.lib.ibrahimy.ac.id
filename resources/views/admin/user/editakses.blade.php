@extends('layout.sidebarnavbar')
@section('admin-konten')

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
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">{{ $user->name }}</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="/admin/home" class="text-muted text-hover-primary">Beranda</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="/admin/pengguna" class="text-muted text-hover-primary">Data Pengguna</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Hak Akses</li>
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
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Layout-->
			<div class="d-flex flex-column flex-lg-row">
				<!--begin::Sidebar-->
				<div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
					<!--begin::Card-->
					<div class="card mb-5 mb-xl-8">
						<!--begin::Card body-->
						<div class="card-body">
							<!--begin::Summary-->
							<!--begin::User Info-->
							<div class="d-flex flex-center flex-column py-5">
								<!--begin::Avatar-->
								<div class="symbol symbol-100px symbol-circle mb-7">
									<img src="{{ asset('/storage/foto/' . $user->foto) }}" alt="image" />
								</div>
								<!--end::Avatar-->
								<!--begin::Name-->
								<div class="fs-3 text-gray-800 fw-bold mb-3">{{ $user->name }}</div>
								<!--end::Name-->
								<!--begin::Position-->
								<div class="mb-9">
									<!--begin::Badge-->
									<div class="badge badge-lg badge-light-primary d-inline">{{ $user->username }}</div>
									<!--begin::Badge-->
								</div>
								<!--end::Position-->
							</div>
							<!--end::User Info-->
							<!--end::Summary-->
							<!--begin::Details toggle-->
							<div class="d-flex flex-stack fs-4 py-3">
								<div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">Detail
								<span class="ms-2 rotate-180">
									<i class="ki-duotone ki-down fs-3"></i>
								</span>
                            </div>
							</div>
							<!--end::Details toggle-->
							<div class="separator"></div>
							<!--begin::Details content-->
							<div id="kt_user_view_details" class="collapse show">
								<div class="pb-5 fs-6">
									<!--begin::Details item-->
									<div class="fw-bold mt-5">Nama</div>
									<div class="text-gray-600">{{ $user->name }}</div>
									<!--begin::Details item-->
									<!--begin::Details item-->
									<div class="fw-bold mt-5">Username</div>
									<div class="text-gray-600">{{ $user->username }}</div>
									<!--begin::Details item-->
									<!--begin::Details item-->
									<div class="fw-bold mt-5">ID Staff</div>
									<div class="text-gray-600">{{ $user->idstaf }}</div>
									<!--begin::Details item-->
									<!--begin::Details item-->
									<div class="fw-bold mt-5">Jabatan</div>
									<div class="text-gray-600">
										<span class="text-gray-600">{{ $user->jabatan }}</span>
									</div>
									<!--begin::Details item-->
									<!--begin::Details item-->
									<div class="fw-bold mt-5">Bergabung Pada</div>
									<div class="text-gray-600">{{ Carbon\Carbon::parse($user->created_at)->isoFormat('D MMMM Y') }}</div>
									<!--begin::Details item-->
								</div>
							</div>
							<!--end::Details content-->
						</div>
						<!--end::Card body-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Sidebar-->
				<!--begin::Content-->
				<div class="flex-lg-row-fluid ms-lg-15">
					<!--begin:::Tab content-->
					<div class="tab-content" id="myTabContent">
						<!--begin:::Tab pane-->
						<div class="tab-pane fade show active">
							<!--begin::Card-->
                            <form action="{{ asset('admin/pengguna-akses/'.$user->id.'/update') }}" method="POST">
                            @csrf
							<div class="card card-flush mb-6 mb-xl-9">
								<!--begin::Card header-->
								<div class="card-header mt-6">
									<!--begin::Card title-->
									<div class="card-title flex-column">
										<h2 class="mb-1">Hak Akses Pengguna</h2>
										<div class="fs-6 fw-semibold text-muted">memiliki {{ $user->permissions->count() }} akses sistem</div>
									</div>
									<!--end::Card title-->
                                    <div class="card-toolbar">
                                        <button type="submit" class="btn btn-primary confirm-button">
                                            <i class="ki-outline ki-sms fs-2">
                                            </i>Simpan
                                        </button>
                                    </div>
								</div>
								<!--end::Card header-->
								<!--begin::Card body-->
								<div class="card-body p-9 pt-4">
									<!--begin::Akses-->
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold form-label mb-2">Hak Akses</label>
                                    <!--end::Label-->
                                    <!--begin::Table wrapper-->

                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                                            <!--begin::Table body-->
                                            <tbody class="text-gray-600 fw-semibold">
                                                <!--begin::Table row-->
                                                <tr>
                                                    <!--begin::Select All-->
                                                    <td class="text-gray-800">Full Akses
                                                        <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Memungkinkan akses penuh ke sistem">
                                                            <i class="ki-outline ki-information fs-7"></i>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-9">
                                                            <input class="form-check-input" type="checkbox" id="checkAll"/>
                                                            <span class="form-check-label">Pilih Semua</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </td>
                                                    <!--end::Select All-->
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                @foreach ($akses as $category => $permissions)
                                                    <tr>
                                                        <!-- Nama kategori -->
                                                        <td class="text-gray-800">{{ $category }}</td>
                                                        <td>
                                                            <!-- Wrapper izin dalam kategori -->
                                                            <div class="d-flex flex-wrap">
                                                                @foreach ($permissions as $item)
                                                                    <div style="width: 25%; padding-bottom: 5px;">
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="{{ $item->name }}" name="permissions[]"
                                                                                {{ $user->hasPermissionTo($item->name) ? 'checked' : '' }}/>
                                                                            <span class="form-check-label">{{ \Illuminate\Support\Str::after($item->name, '-') }}</span>
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <!--end::Table row-->
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
									<!--end::Akses-->
								</div>
								<!--end::Card body-->
							</div>
                            </form>

							<!--end::Card-->
						</div>
						<!--end:::Tab pane-->
					</div>
					<!--end:::Tab content-->
				</div>
				<!--end::Content-->
			</div>
			<!--end::Layout-->
        </div>
    </div>
</div>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('admin/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('admin/assets/js/scripts.bundle.js') }}"></script>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.confirm-button').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // Cegah submit form langsung

            const form = this.closest('form'); // Ambil form terdekat

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Tindakan ini akan mengubah akses pengguna terhadap manajemen dan pengelolaan data dalam sistem!",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form jika dikonfirmasi
                }
            });
        });
    });
});
</script>
<!-- JavaScript untuk Full Checked -->
<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.form-check-input');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
<!--end::Globadmin/al Javascript Bundle-->
@endsection
