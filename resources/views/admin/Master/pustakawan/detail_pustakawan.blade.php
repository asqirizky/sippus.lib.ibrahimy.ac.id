@extends('layout.sidebarnavbar')
@section('admin-konten')

{{-- content --}}
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="py-3 app-toolbar py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="flex-wrap page-title d-flex flex-column justify-content-center me-3">
                    <!--begin::Title-->
                    <h1 class="my-0 text-gray-900 page-heading d-flex fw-bold fs-3 flex-column justify-content-center">Data Pustakawan</h1>
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
                        <li class="breadcrumb-item text-muted">
                            <a href="/admin/master-pustakawan" class="text-muted text-hover-primary">Pustakawan</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bg-gray-500 bullet w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Detail</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Basic info-->
                <div class="row g-xxl-9">
                    <div class="col-xl-4">
                        <div class="card card-flush h-xl-100">
                            <!--begin::Card header-->
                            <div class="card-header pt-7 bg-success" style="background-image:url('admin/assets//media/pattern.png'); background-size: 300px; background-position: right; background-repeat: no-repeat;">
                                <!--begin::Title-->
                                <h3 class="mb-4 card-title justify-content-center flex-column">
                                    <span class="text-white card-label fw-bold">Detail Pegawai</span>
                                    <span class="mt-1 text-gray-500 fw-semibold fs-6"></span>
                                </h3>
                                <!--end::Title-->
                                <!--begin::Actions-->
                                <div class="card-toolbar">
                                    <!--begin::Filters-->
                                    <div class="flex-wrap gap-4 d-flex flex-stack">
                                        <!--begin::Destination-->
                                        <!--begin::Search-->
                                        <button type="submit" class="mb-4 btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_edit{{ $pustakawan->id }}">Edit</button>
                                        <!--end::Search-->
                                        @include('admin.Master.pustakawan.pustakawan_edit')
                                    </div>
                                    <!--begin::Filters-->
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body">
                                <div class="pb-5 fs-6">
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Nomor Pustakawan</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ $pustakawan->nik }}</span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Nama</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ $pustakawan->nama_pustakawan }}</span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Tempat Lahir</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ $pustakawan->tempat_lahir }}</span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Tanggal Lahir</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ Carbon\Carbon::parse($pustakawan->tgl_lahir)->isoFormat('dddd, D MMMM Y') }}</span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">TMT</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ $pustakawan->tmt ? Carbon\Carbon::parse($pustakawan->tmt)->isoFormat('dddd, D MMMM Y') : '-' }} </span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">TMT Mengajar</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ $pustakawan->tmt_mengajar ? Carbon\Carbon::parse($pustakawan->tmt_mengajar)->isoFormat('dddd, D MMMM Y') : '-' }}</span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Domisili</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ ucwords(strtolower($pustakawan->domisili)) }}</span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Pend. Terakhir</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ ($pustakawan->pend_terakhir ?? '-' ) }}</span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ $pustakawan->jk }}</span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Status Perkawinan</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ $pustakawan->status_perkawinan }}</span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Jabatan</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ $pustakawan->jabatan->nama_jabatan }}</span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Ruang</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ $pustakawan->ruang?->ruang_pustakawans}} </span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    @php
                                        $no = preg_replace('/^62/', '', $pustakawan->no_wa); 
                                        $no = preg_replace('/^0/', '', $no); 

                                        $formatted = preg_replace('/(\d{3})(\d{4})(\d{4})/', '$1-$2-$3', $no);
                                    @endphp
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Nomor HP</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">+62 {{ $formatted }} </span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Asrama</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ $pustakawan->asrama ?? '-' }} </span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Pendidikan Pagi</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ $pustakawan->sekolah_pagi ?? '-' }} </span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Pendidikan Sore</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6">{{ $pustakawan->sekolah_sore ?? '-' }} </span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                    <!--begin::Row-->
									<div class="row mb-7 mt-7">
										<!--begin::Label-->
										<label class="col-lg-4 fw-semibold text-muted">Status</label>
										<!--end::Label-->
										<!--begin::Col-->
										<div class="col-lg-8">
											<span class="text-gray-800 fw-bold fs-6 badge {{ $pustakawan->status ? 'bg-primary' : 'bg-danger' }}">
                                                {{ $pustakawan->status ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                    </div>
                    <!--begin::Col-->
                    <div class="col-xl-8">
                        {{-- Form Tambah Shift --}}
                        <form class="form" enctype="multipart/form-data" action="{{ route('pustakawan.kelolah_jadwal', $pustakawan->id) }}" method="POST">
                            @csrf
                            <div class="mb-5 shadow-sm card card-flush h-xl-100">
                                <!-- Header -->
                                <div class="pb-3 card-header pt-7 bg-success" style="background-image:url('admin/assets//media/pattern.png'); background-size: 500px; background-position: right; background-repeat: no-repeat;">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="text-gray-900 card-label fw-bold">Tambah Shift Untuk</span>
                                        <span class="mt-1 text-gray-500 fw-semibold fs-6">{{ $pustakawan->nama }}</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <div class="gap-4 d-flex flex-stack">
                                            <button type="submit" class="btn btn-light-warning btn-sm">Simpan</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Body -->
                                <div class="card-body">
                                    <table class="table align-middle table-row-dashed fs-6 gy-3">
                                        <thead>
                                            <tr class="text-white fw-bold bg-success">
                                                <th class="rounded-start ps-4">Hari</th>
                                                <th colspan="{{ count($shiftList) }}" class="text-center">Pilih Shift</th>
                                                <th class="rounded-end"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-700 fw-semibold">
                                            @foreach ($hariList as $hari)
                                                <tr>
                                                    <td class="ps-4">
                                                        <span class="px-4 py-2 badge badge-light-primary fs-7">{{ $hari }}</span>
                                                    </td>
                                                    @foreach ($shiftList as $shiftId => $shiftNama)
                                                        @php
                                                            $shiftMap = [
                                                                1 => 'pagi',
                                                                2 => 'siang',
                                                                3 => 'malam'
                                                            ];
                                                            $shiftKey = $shiftMap[$shiftId];
                                                            $aktif = isset($jadwalAktif[$hari]) && $jadwalAktif[$hari][$shiftKey] == 1;
                                                        @endphp
                                                        <td class="text-center">
                                                            <div class="form-check form-check-custom d-flex justify-content-center">
                                                                <input class="form-check-input"
                                                                    type="checkbox"
                                                                    id="shift-{{ $hari }}-{{ $shiftId }}"
                                                                    name="aktif[{{ $hari }}][{{ $shiftId }}]"
                                                                    value="1"
                                                                    @checked($aktif)>
                                                                <label class="form-check-label ms-2"
                                                                    for="shift-{{ $hari }}-{{ $shiftId }}">
                                                                    {{ $shiftNama }}
                                                                </label>
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                        {{-- Section Recent Followers --}}
                        <div class="mb-5 row g-5 g-xl-10">
                            <div class="col-xl-12">
                                <div class="shadow-sm card card-flush h-xl-100">
                                    <div class="pb-3 card-header pt-7">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="text-gray-900 card-label fw-bold">Berkas Pustakawan</span>
                                        </h3>
                                        <div class="card-toolbar">
                                            <a href="" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target" class="btn btn-sm btn-primary">Tambah Berkas</a>
                                        </div>
                                    </div>
                                    @include('admin.Master.pustakawan.tambah_berkas')
                                    <div class="card-body pt-7">
                                        <div class="row align-items-end h-100 gx-5 gx-xl-10">
                                            @if ($pustakawan->berkas)
                                                @php
                                                    $berkasUrl = asset('storage/berkas/' . $pustakawan->berkas);
                                                @endphp

                                                <div class="mb-5 col-md-4">
                                                    <a class="d-block overlay" data-fslightbox="lightbox-berkas" href="{{ asset('storage/berkas/' . $pustakawan->berkas) }}" target="_blank">
                                                        <div class="overlay-wrapper bgi-position-center bgi-no-repeat bgi-size-cover card-rounded h-200px"
                                                            style="background-image:url('{{ asset('storage/berkas/' . $pustakawan->berkas) }}'); height:200px;">
                                                            <img src="{{ asset('storage/' . $pustakawan->berkas) }}" alt="image" class="d-none">
                                                        </div>
                                                        <div class="bg-opacity-25 overlay-layer card-rounded bg-dark">
                                                            <i class="text-white ki-duotone ki-eye fs-3x"></i>
                                                        </div>
                                                    </a>

                                                    <div class="mt-3">
                                                        <a class="mb-2 text-gray-800 text-hover-primary fs-3 fw-bold d-block">
                                                            Nama Berkas : {{ $pustakawan->keterangan }}
                                                        </a>
                                                        <span class="text-gray-500 fw-bold fs-6 d-block lh-1">
                                                            Diupload pada: {{ $pustakawan->updated_at->format('d M Y H:i') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <p class="text-muted">Belum ada berkas yang diunggah. <br> Unggah berkas seperti ijazah atau sertifikat dan berkas lainnya</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Basic info-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
</div>
@include('layout.footer')

<!--begin::Javascript-->
<script>
    var hostUrl = "admin/assets/";
</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="admin/assets/plugins/global/plugins.bundle.js"></script>
<script src="admin/assets/js/scripts.bundle.js"></script>
<script src="assets/plugins/global/plugins.bundle.js"></script>

<!--end::Global Javascript Bundle-->

@endsection()