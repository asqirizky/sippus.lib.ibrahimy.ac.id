@extends('layout.sidebarnavbar')
@section('admin-konten')

{{-- konten --}}
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
					<h1 class="my-0 text-gray-900 page-heading d-flex fw-bold fs-3 flex-column justify-content-center">Tambah Pustakawan</h1>
					<!--end::Title-->
					<!--begin::Breadcrumb-->
					<ul class="pt-1 my-0 breadcrumb breadcrumb-separatorless fw-semibold fs-7">
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">
							<a href="admin/master-pustakawan" class="text-muted text-hover-primary">Home</a>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="breadcrumb-item">
							<span class="bg-gray-500 bullet w-5px h-2px"></span>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
							<li class="breadcrumb-item text-muted">Tambah Pustakawan</li>
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
				<!--begin::Form-->
				<form class="form d-flex flex-column flex-lg-row" method="POST" enctype="multipart/form-data" action="admin/master-pustakawan">
					@csrf
                    <!--begin::Aside column-->
					<div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
						<!--begin::Thumbnail settings-->
						<div class="py-4 card card-flush">
							<!--begin::Card header-->
							<div class="card-header">
								<!--begin::Card title-->
								<div class="card-title">
									<h2>Foto</h2>
								</div>
								<!--end::Card title-->
							</div>
							<!--end::Card header-->
							<!--begin::Card body-->
							<div class="pt-0 text-center card-body">
                                <!--begin::Image placeholder-->
                                <style>
                                    .image-input-placeholder {
                                        background-image: url('{{ asset('admin/assets/media/svg/files/blank-image.svg') }}');
                                    }
                                    [data-bs-theme="dark"] .image-input-placeholder {
                                        background-image: url('{{ asset('admin/assets/media/svg/files/blank-image-dark.svg') }}');
                                    }
                                </style>
                                <!--end::Image placeholder-->
                                <!--begin::Image input-->
                                <div class="me-4 image-input image-input-outline image-input-placeholder image-input-empty" data-kt-image-input="true">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: none;"></div>
                                    <!--end::Preview existing avatar-->
                                    <!--begin::Label-->
                                    <label class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Upload Foto" data-bs-original-title="Upload Foto" data-kt-initialized="1">
                                        <i class="ki-duotone ki-pencil fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <!--begin::Inputs-->
                                        <input type="file" name="foto" value="blank.png" accept=".png, .jpg, .jpeg" required="" autocomplete="foto">
                                        <input type="hidden" name="avatar_remove" value="1">
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Cancel-->
                                    <span class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel Foto" data-bs-original-title="Cancel Foto" data-kt-initialized="1">
                                        <i class="ki-duotone ki-cross fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <!--end::Cancel-->
                                    <!--begin::Remove-->
                                    <span class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Hapus foto" data-bs-original-title="Hapus foto" data-kt-initialized="1">
                                        <i class="ki-duotone ki-cross fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <!--end::Remove-->
                                </div>
                                <!--begin::Hint-->
                                <div class="form-text">File memiliki format: png, jpg, jpeg. Maksimal ukuran
                                    1MB(1024kb)</div>
                                @error('foto')
                                <div class="form-text text-danger">Gambar terlalu besar</div>
                                @enderror
                                <!--end::Hint-->
                                <!--end::Image input-->
                            </div>
							<!--end::Card body-->
						</div>
						<!--end::Thumbnail settings-->
						<!--begin::Status-->
						<div class="py-4 card card-flush">
							<!--begin::Card header-->
							<div class=" card-header">
								<!--begin::Card title-->
								<div class="required card-title">
									<h2>Domisili</h2>
								</div>
								<!--end::Card title-->
							</div>
							<!--end::Card header-->
							<!--begin::Card body-->
							<div class="pt-0 card-body">
								<!--begin::Select2-->
                                <label for="province" class="require form-label">Provinsi</label>
                                <select id="province" name="province" class="mb-2 form-select" data-hide-search="true">
                                    <option value="" selected disabled>Pilih Provinsi</option>
                                </select>
                                <!--end::Select2-->
							</div>
							<!--end::Card body-->
							<!--begin::Card body-->
							<div class="pt-0 card-body">
								<!--begin::Select2-->
								<label for="regency" class="require form-label">Kabupaten/Kota</label>
                                <select id="regency" name="regency" class="mb-2 form-select" disabled>
                                    <option value="" selected disabled>Pilih Kabupaten/Kota</option>
                                </select>
								<!--end::Select2-->
							</div>
							<!--end::Card body-->
							<!--begin::Card body-->
							<div class="pt-0 card-body">
								<!--begin::Select2-->
								<label for="district" class="require form-label">Kecamatan</label>
                                <select id="district" name="district" class="mb-2 form-select" disabled>
                                    <option value="" selected disabled>Pilih Kecamatan</option>
                                </select>
								<!--end::Select2-->
							</div>
							<!--end::Card body-->
							<!--begin::Card body-->
							<div class="pt-0 card-body">
								<!--begin::Select2-->
								<label for="village" class="require form-label">Desa/Kelurahan</label>
                                <select id="village" name="village" class="mb-2 form-select" disabled>
                                    <option value="" selected disabled>Pilih Desa/Kelurahan</option>
                                </select>
								<!--end::Select2-->
							</div>
							<!--end::Card body-->
						</div>
						<!--begin::Input Hidden-->
                        <input type="hidden" name="domisili" id="domisili">
						<!--end::Input Hidden-->
						<!--end::Status-->
					</div>
					<!--end::Aside column-->
					<!--begin::Main column-->
					<div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
						<!--begin::Tab content-->
						<div class="tab-content">
							<!--begin::Tab pane-->
							<div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
								<div class="d-flex flex-column gap-7 gap-lg-10">
									<!--begin::General options-->
									<div class="py-4 card card-flush">
										<!--begin::Card header-->
										<div class="card-header">
											<div class="card-title">
												<h2>Biodata Pustakawan</h2>
											</div>
										</div>
										<!--end::Card header-->
										<!--begin::Card body-->
										<div class="pt-0 card-body">
											<!--begin::Input group-->
											<div class="mb-10 fv-row">
												<!--begin::Label-->
												<label class="required form-label">Nama</label>
												<!--end::Label-->
												<!--begin::Input-->
												<input type="text" name="nama_pustakawan" class="mb-2 form-control" placeholder="Nama" required/>
												<!--end::Input-->
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="mb-10 fv-row">
												<!--begin::Label-->
												<label class="required form-label">Tempat Lahir</label>
												<!--end::Label-->
												<!--begin::Input-->
												<input type="text" name="tempat_lahir" class="mb-2 form-control" placeholder="Tempat Lahir" required/>
												<!--end::Input-->
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="mb-10 fv-row">
												<!--begin::Label-->
												<label class="required form-label">Tanggal Lahir</label>
												<!--end::Label-->
												<!--begin::Input-->
												<input type="date" name="tgl_lahir" class="mb-2 form-control" placeholder="Tanggal Lahir" required/>
												<!--end::Input-->
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="mb-10 fv-row">
												<!--begin::Label-->
												<label class="form-label">Terhitung Mulai Tanggal</label>
												<!--end::Label-->
												<!--begin::Input-->
												<input type="date" name="tmt" class="mb-2 form-control" placeholder=" Tanggal Masuk"/>
												<!--end::Input-->
												<div class="class form-text">diisi khusus untuk pustakawan struktural</div>
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="mb-10 fv-row">
												<!--begin::Label-->
												<label class="form-label">Terhitung Mulai Tanggal Mengajar</label>
												<!--end::Label-->
												<!--begin::Input-->
												<input type="date" name="tmt_mengajar" class="mb-2 form-control" placeholder="Tanggal Masuk"/>
												<!--end::Input-->
												<div class="div form-text">diisi khusus struktural yang menjadi dosen</div>
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="mb-10 fv-row">
												<!--begin::Label-->
												<label class="form-label">Tanggal Khidmah</label>
												<!--end::Label-->
												<!--begin::Input-->
												<input type="date" name="tgl_khidmah" class="mb-2 form-control" placeholder="Tanggal Masuk"/>
												<!--end::Input-->
												<div class="class form-text" style="color">diisi khusus untuk pustakawan tenaga khidmah</div>
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="mb-10 fv-row">
												<!--begin::Label-->
												<label class="form-label">Pendidikan Terakhir</label>
												<!--end::Label-->
												<!--begin::Input-->
												<select name="pend_terakhir" class="form-select" data-control="select2" data-placeholder="Pilih Pendidikan Terakhir" data-hide-search="true" autocomplete="lokasi">
                                                    <option value="" disabled selected>Pilih Pendidikan Terakhir</option>
                                                    <option value="SD">SD</option>
                                                    <option value="SMP/SLTP">SMP/SLTP</option>
                                                    <option value="SMA/SLTA">SMA/SLTA</option>
                                                    <option value="D3">D3</option>
                                                    <option value="S1">S1</option>
                                                    <option value="S2">S2</option>
                                                    <option value="S3">S3</option>
                                                </select>
												<!--end::Input-->
												<div class="class form-text">diisi khusus untuk pustakawan struktural</div>
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="mb-10 fv-row">
												<!--begin::Label-->
												<label class="required form-label">Jenis Kelamin</label>
												<!--end::Label-->
												<!--begin::Input-->
												<select name="jk" class="form-select" data-control="select2" data-placeholder="Pilih Jenis Kelamin" data-hide-search="true" required autocomplete="lokasi">
                                                    <option disabled selected>Pilih Jenis Kelamin</option>
													<option value="Laki-laki">Laki-laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
												<!--end::Input-->
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="mb-10 fv-row">
												<!--begin::Label-->
												<label class="required form-label">Status Perkawinan</label>
												<!--end::Label-->
												<!--begin::Input-->
												<select name="status_perkawinan" class="form-select" data-control="select2" data-placeholder="Pilih Status Perkawinan" data-hide-search="true" required autocomplete="lokasi">
                                                    <option disabled selected>Pilih Status Perkawinan</option>
													<option value="Belum Menikah">Belum Menikah</option>
                                                    <option value="Menikah">Menikah</option>
                                                </select>
												<!--end::Input-->
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="mb-10 fv-row">
												<!--begin::Label-->
												<label class="required form-label">Jabatan</label>
												<!--end::Label-->
												<!--begin::Input-->
												<select name="jabatan_id" class="form-select" data-control="select2" data-hide-search="true" required>
                                                    <option disabled selected>Pilih Jabatan</option>
                                                    @foreach ($jabatan as $item)
														<option value="{{ $item->id }}"> {{ $item->nama_jabatan }} </option>
													@endforeach
                                                </select>
												<!--end::Input-->
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="mb-10 fv-row">
												<!--begin::Label-->
												<label class="required form-label">Ruang</label>
												<!--end::Label-->
												<!--begin::Input-->
												<select name="ruang_id" class="form-select" data-control="select2" data-placeholder="Pilih Ruang" data-hide-search="true" required autocomplete="lokasi">
                                                    <option disabled selected>Pilih Ruang</option>
													@foreach ($ruang as $item)
														<option value="{{ $item->id }}"> {{ $item->ruang_pustakawans }} </option>
													@endforeach
                                                </select>
												<!--end::Input-->
											</div>
											<!--end::Input group-->
										</div>
										<!--end::Card header-->
									</div>
									<!--end::General options-->
								</div>
							</div>
							<!--end::Tab pane-->
						</div>
						<!--end::Tab content-->
						<div class="d-flex justify-content-end">
							<!--begin::Button-->
							<!--end::Button-->
							<!--begin::Button-->
							<button type="submit" class="btn btn-primary" id="submitTambahBtn">
								<span class="indicator-label">Simpan</span>
								<span class="indicator-progress">Please wait...
								<span class="align-middle spinner-border spinner-border-sm ms-2"></span></span>
							</button>
							<!--end::Button-->
						</div>
					</div>
					<!--end::Main column-->
				</form>
				<!--end::Form-->
			</div>
			<!--end::Content container-->
		</div>
		<!--end::Content-->
	</div>
	<!--end::Content wrapper-->
	<!--begin::Footer-->
	<div id="kt_app_footer" class="app-footer">
		<!--begin::Footer container-->
		<div class="py-3 app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack">
			<!--begin::Copyright-->
			<div class="order-2 text-gray-900 order-md-1">
				<span class="text-muted fw-semibold me-1">2024&copy;</span>
				<a href="https://keenthemes.com" target="_blank" class="text-gray-800 text-hover-primary">Keenthemes</a>
			</div>
			<!--end::Copyright-->
			<!--begin::Menu-->
			<ul class="order-1 menu menu-gray-600 menu-hover-primary fw-semibold">
				<li class="menu-item">
					<a href="https://keenthemes.com" target="_blank" class="px-2 menu-link">About</a>
				</li>
				<li class="menu-item">
					<a href="https://devs.keenthemes.com" target="_blank" class="px-2 menu-link">Support</a>
				</li>
				<li class="menu-item">
					<a href="https://1.envato.market/EA4JP" target="_blank" class="px-2 menu-link">Purchase</a>
				</li>
			</ul>
			<!--end::Menu-->
		</div>
		<!--end::Footer container-->
	</div>
	<!--end::Footer-->
</div>
<!--end:::Main-->

@endsection
<!--begin::Javascript-->
<script>var hostUrl = "assets/";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
<!--end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="assets/js/custom/apps/ecommerce/catalog/save-product.js"></script>
<script src="assets/js/widgets.bundle.js"></script>
<script src="assets/js/custom/widgets.js"></script>
<script src="assets/js/custom/apps/chat/chat.js"></script>
<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="assets/js/custom/utilities/modals/create-app.js"></script>
<script src="assets/js/custom/utilities/modals/users-search.js"></script>
<!--end::Custom Javascript-->
<!--end::Javascript-->
@if (session('success'))
<script>
    Swal.fire({
        title: 'Alhamdulillah!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: 'OK',
        customClass: {
            confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
    });
</script>
@endif

<script>
document.addEventListener("DOMContentLoaded", async function () {
    const province = document.getElementById('province');
    const regency = document.getElementById('regency');
    const district = document.getElementById('district');
    const village = document.getElementById('village');
    const domisiliInput = document.getElementById('domisili');
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = true;

    try {
        // Ambil data provinsi
        const provinces = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json').then(res => res.json());
        provinces.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            province.appendChild(option);
        });

        province.addEventListener('change', async function () {
            regency.innerHTML = '<option selected disabled>Pilih Kabupaten/Kota</option>';
            regency.disabled = true;
            district.innerHTML = '<option selected disabled>Pilih Kecamatan</option>';
            district.disabled = true;
            village.innerHTML = '<option selected disabled>Pilih Desa/Kelurahan</option>';
            village.disabled = true;

            const regencies = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.value}.json`).then(res => res.json());
            regencies.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                regency.appendChild(option);
            });
            regency.disabled = false;
        });

        regency.addEventListener('change', async function () {
            district.innerHTML = '<option selected disabled>Pilih Kecamatan</option>';
            district.disabled = true;
            village.innerHTML = '<option selected disabled>Pilih Desa/Kelurahan</option>';
            village.disabled = true;

            const districts = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${this.value}.json`).then(res => res.json());
            districts.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                district.appendChild(option);
            });
            district.disabled = false;
        });

        district.addEventListener('change', async function () {
            village.innerHTML = '<option selected disabled>Pilih Desa/Kelurahan</option>';
            village.disabled = true;

            const villages = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${this.value}.json`).then(res => res.json());
            villages.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                village.appendChild(option);
            });
            village.disabled = false;
        });

        village.addEventListener('change', function () {
            domisiliInput.value = `${village.options[village.selectedIndex].text}, ${district.options[district.selectedIndex].text}, ${regency.options[regency.selectedIndex].text}, ${province.options[province.selectedIndex].text}`;
            submitBtn.disabled = false;
        });

    } catch (error) {
        Swal.fire({
            title: 'Gagal Memuat Data',
            text: 'Tidak bisa memuat daftar wilayah. Silakan cek koneksi internet Anda.',
            icon: 'error'
        });
    }
});

const tambahForm = document.querySelector('form[enctype="multipart/form-data"]');
const submitTambahBtn = document.getElementById('submitTambahBtn');

if (tambahForm && submitTambahBtn) {
    tambahForm.addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Simpan Data Pustakawan?',
            text: 'Data pustakawan baru akan ditambahkan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                submitTambahBtn.disabled = true;
                submitTambahBtn.querySelector('.indicator-label').classList.add('d-none');
                submitTambahBtn.querySelector('.indicator-progress').classList.remove('d-none');
                tambahForm.submit();
            }
        });
    });
}
</script>
