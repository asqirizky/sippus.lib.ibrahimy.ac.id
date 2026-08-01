@extends('layout.sidebarnavbar')
{{-- @extends('layouts.app') --}}
@section('admin-konten')

<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Beranda</h1>
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
                        <li class="breadcrumb-item text-muted">Beranda</li>
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
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!--begin::KONTEN-->
                <!--begin::Row-->
                <div class="row gx-5 gx-xl-10">
                    <!--begin::Col-->
					<div class="col-xl-4">
						<!--begin::List widget 5-->
						<div class="card card-flush h-xl-100">
							<!--begin::Header-->
							<div class="card-header pt-7">
								<!--begin::Title-->
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label fw-bold text-gray-900">Persentase Kehadiran</span>
									<span class="text-gray-500 mt-1 fw-semibold fs-6">Persentase ini dihitung per bulan</span>
								</h3>
								<!--end::Title-->
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body">
								<!--begin::Scroll-->
								<div class="hover-scroll-overlay-y pe-6 me-n6" style="height: 415px">
									@foreach ( $pustakawan as $item )
									<!--begin::Item-->
									<div class="d-flex flex-stack">
										<!--begin::Wrapper-->
										<div class="d-flex align-items-center me-3">
											<!--begin::Section-->
											<div class="flex-grow-1">
												<!--begin::Text-->
												<a class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0"> {{ $item->nama_pustakawan }} </a>
												<!--end::Text-->
												<!--begin::Description-->
												<span class="text-gray-500 fw-semibold d-block fs-6"> {{ $item->jabatan?->nama_jabatan ?? '-' }} </span>
												<!--end::Description=-->
											</div>
											<!--end::Section-->
										</div>
										<!--end::Wrapper-->
										<!--begin::Statistics-->
										<div class="d-flex align-items-center w-125 mw-100px">
											<!--begin::Value-->
											<span class="text-gray-500 fw-semibold">
                                                @if ($item->persentase < 50 )
                                                    <div class="px-4 py-3 badge fs-6 badge-light-danger">{{ $item->persentase }}%</div>
                                                @elseif ($item->persentase > 50 )
                                                    <div class="px-4 py-3 badge fs-6 badge-light-success">{{ $item->persentase }}%</div>
                                                @else
                                                    <div class="px-4 py-3 badge fs-6 badge-light-warning">{{ $item->persentase }}%</div>
                                                @endif
											</span>
											<!--end::Value-->
										</div>
										<!--end::Statistics-->
									</div>
									<!--end::Item-->
									<!--begin::Separator-->
									<div class="separator separator-dashed my-3"></div>
									<!--end::Separator-->
									@endforeach
								</div>
								<!--end::Scroll-->
							</div>
							<!--end::Body-->
						</div>
						<!--end::List widget 5-->
					</div>
					<!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xl-8 mb-10">
                        <!--begin::Row-->
						<div class="row g-5 g-xl-10">
							<!--begin::Col-->
							<div class="col-xl-6 mb-xl-10">
								<!--begin::Slider Widget 1-->
								<div id="kt_sliders_widget_1_slider" class="card card-flush carousel carousel-custom carousel-stretch slide h-xl-100" data-bs-ride="carousel" data-bs-interval="5000">
									<!--begin::Header-->
									<div class="card-header pt-5">
                                        <!--begin::Title-->
										<h4 class="card-title d-flex align-items-start flex-column">
											<span class="card-label fw-bold text-gray-800">Jumlah Pustakawan</span>
											<span class="text-gray-500 mt-1 fw-bold fs-7">Statistik Pustakawan</span>
										</h4>
										<!--end::Title-->
                                        <!--begin::Toolbar-->
										<div class="card-toolbar">
											<!--begin::Carousel Indicators-->
											<ol class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-primary">
												<li data-bs-target="#kt_sliders_widget_1_slider" data-bs-slide-to="0" class="active ms-1"></li>
												<li data-bs-target="#kt_sliders_widget_1_slider" data-bs-slide-to="1" class="ms-1"></li>
												<li data-bs-target="#kt_sliders_widget_1_slider" data-bs-slide-to="2" class="ms-1"></li>
											</ol>
											<!--end::Carousel Indicators-->
										</div>
										<!--end::Toolbar-->
									</div>
									<!--end::Header-->
									<!--begin::Body-->
									<div class="card-body py-6">
										<!--begin::Carousel-->
										<div class="carousel-inner mt-n5">
											<!--begin::Item-->
											<div class="carousel-item active show">
												<!--begin::Wrapper-->
												<div class="d-flex align-items-center mb-5">
													<!--begin::Symbol-->
                                                    <div class="symbol symbol-70px symbol me-5">
                                                        <span class="symbol-label bg-light-success">
                                                            <i class="ki-outline ki-user-tick fs-3x text-success"></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->
													<!--begin::Info-->
													<div class="m-0">
														<!--begin::Subtitle-->
														<h4 class="fw-bold text-gray-800 mb-3">Pustakawan Struktural</h4>
														<!--end::Subtitle-->
														<!--begin::Items-->
														<div class="d-flex d-grid gap-5">
															<!--begin::Items-->
                                                            <div class="d-flex d-grid gap-5">
                                                                <!--begin::Item-->
                                                                <div class="m-0">
                                                                    <!--begin::Number-->
                                                                    <span class="text-gray-700 fw-bolder d-block fs-1 lh-1 ls-n1 mb-1">
                                                                        {{ App\Models\Master\Pustakawan::where('status', 1)
                                                                            ->whereHas('jabatan', function ($q) {
                                                                                $q->whereRaw('LOWER(nama_jabatan) != ?', ['tenaga khidmah']);
                                                                            })->count()
                                                                        }}
                                                                    </span>
                                                                    <!--end::Number-->
                                                                </div>
                                                                <!--end::Item-->
                                                            </div>
                                                            <!--end::Items-->
														</div>
														<!--end::Items-->
													</div>
													<!--end::Info-->
												</div>
												<!--end::Wrapper-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="carousel-item">
												<!--begin::Wrapper-->
												<div class="d-flex align-items-center mb-5">
													<!--begin::Symbol-->
                                                    <div class="symbol symbol-70px symbol me-5">
                                                        <span class="symbol-label bg-light-success">
                                                            <i class="ki-outline ki-user fs-3x text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->
													<!--begin::Info-->
													<div class="m-0">
														<!--begin::Subtitle-->
														<h4 class="fw-bold text-gray-800 mb-3">Pustakawan Khidmah</h4>
														<!--end::Subtitle-->
														<!--begin::Items-->
														<div class="d-flex d-grid gap-5">
															<!--begin::Items-->
                                                            <div class="d-flex d-grid gap-5">
                                                                <!--begin::Item-->
                                                                <div class="m-0">
                                                                    <!--begin::Number-->
                                                                    <span class="text-gray-700 fw-bolder d-block fs-1 lh-1 ls-n1 mb-1">
                                                                        {{ App\Models\Master\Pustakawan::where('status', 1)
                                                                            ->whereHas('jabatan', function ($q) {
                                                                                $q->whereRaw('LOWER(nama_jabatan) = ?', ['tenaga khidmah']);
                                                                            })
                                                                            ->count()
                                                                        }}
                                                                    </span>
                                                                    <!--end::Number-->
                                                                </div>
                                                                <!--end::Item-->
                                                            </div>
                                                            <!--end::Items-->
														</div>
														<!--end::Items-->
													</div>
													<!--end::Info-->
												</div>
												<!--end::Wrapper-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="carousel-item">
												<!--begin::Wrapper-->
												<div class="d-flex align-items-center mb-5">
													<!--begin::Symbol-->
                                                    <div class="symbol symbol-70px symbol me-5">
                                                        <span class="symbol-label bg-light-success">
                                                            <i class="ki-outline ki-people fs-3x text-info"></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->
													<!--begin::Info-->
													<div class="m-0">
														<!--begin::Subtitle-->
														<h4 class="fw-bold text-gray-800 mb-3">Total Pustakawan</h4>
														<!--end::Subtitle-->
														<!--begin::Items-->
														<div class="d-flex d-grid gap-5">
															<!--begin::Items-->
                                                            <div class="d-flex d-grid gap-5">
                                                                <!--begin::Item-->
                                                                <div class="m-0">
                                                                    <!--begin::Number-->
                                                                    <span class="text-gray-700 fw-bolder d-block fs-1 lh-1 ls-n1 mb-1">{{ App\Models\Master\Pustakawan::where('status', 1)->count() }}</span>
                                                                    <!--end::Number-->
                                                                </div>
                                                                <!--end::Item-->
                                                            </div>
                                                            <!--end::Items-->
														</div>
														<!--end::Items-->
													</div>
													<!--end::Info-->
												</div>
												<!--end::Wrapper-->
											</div>
											<!--end::Item-->
										</div>
										<!--end::Carousel-->
									</div>
									<!--end::Body-->
								</div>
								<!--end::Slider Widget 1-->
							</div>
							<!--end::Col-->
							<!--begin::Col-->
							<div class="col-xl-6 mb-5 mb-xl-10">
								<!--begin::Slider Widget 2-->
								<div id="kt_sliders_widget_2_slider" class="card card-flush carousel carousel-custom carousel-stretch slide h-xl-100" data-bs-ride="carousel" data-bs-interval="5500">
									<!--begin::Header-->
									<div class="card-header pt-5">
										<!--begin::Title-->
										<h4 class="card-title d-flex align-items-start flex-column">
											<span class="card-label fw-bold text-gray-800">Kehadiran Hari Ini</span>
											<span class="text-gray-500 mt-1 fw-bold fs-7">Statistik kehadiran hari ini</span>
										</h4>
										<!--end::Title-->
										<!--begin::Toolbar-->
										<div class="card-toolbar">
											<!--begin::Carousel Indicators-->
											<ol class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-success">
												<li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="0" class="active ms-1"></li>
												<li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="1" class="ms-1"></li>
												<li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="2" class="ms-1"></li>
											</ol>
											<!--end::Carousel Indicators-->
										</div>
										<!--end::Toolbar-->
									</div>
									<!--end::Header-->
									<!--begin::Body-->
									<div class="card-body py-6">
										<!--begin::Carousel-->
										<div class="carousel-inner">
											<!--begin::Item-->
											<div class="carousel-item active show">
												<!--begin::Wrapper-->
												<div class="d-flex align-items-center mb-9">
													<!--begin::Symbol-->
													<div class="symbol symbol-70px symbol-circle me-5">
														<span class="symbol-label bg-light-success">
															<i class="ki-duotone ki-abstract-24 fs-3x text-success">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</span>
													</div>
													<!--end::Symbol-->
													<!--begin::Info-->
													<div class="m-0">
														<!--begin::Subtitle-->
														<h4 class="fw-bold text-gray-800 mb-3">Pustakawan Hadir</h4>
														<!--end::Subtitle-->
														<!--begin::Items-->
														<div class="d-flex d-grid gap-5">
															<!--begin::Item-->
															<div class="d-flex flex-column flex-shrink-0 me-4">
																<!--begin::Section-->
																<span class="d-flex align-items-center fs-7 fw-bold text-gray-500 mb-2">
																<i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
																	<span class="path1"></span>
																	<span class="path2"></span>
																</i>Pagi : 0
                                                                </span>
																<!--end::Section-->
																<!--begin::Section-->
																<span class="d-flex align-items-center text-gray-500 fw-bold fs-7">
																<i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
																	<span class="path1"></span>
																	<span class="path2"></span>
																</i>Siang : 1
                                                                </span>
																<!--end::Section-->
															</div>
															<!--end::Item-->
															<!--begin::Item-->
															<div class="d-flex flex-column flex-shrink-0">
																<!--begin::Section-->
																<span class="d-flex align-items-center fs-7 fw-bold text-gray-500 mb-2">
																<i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
																	<span class="path1"></span>
																	<span class="path2"></span>
																</i>Malam : 1
                                                                </span>
																<!--end::Section-->
															</div>
															<!--end::Item-->
														</div>
														<!--end::Items-->
													</div>
													<!--end::Info-->
												</div>
												<!--end::Wrapper-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="carousel-item">
												<!--begin::Wrapper-->
												<div class="d-flex align-items-center mb-9">
													<!--begin::Symbol-->
													<div class="symbol symbol-70px symbol-circle me-5">
														<span class="symbol-label bg-light-danger">
															<i class="ki-duotone ki-abstract-25 fs-3x text-danger">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</span>
													</div>
													<!--end::Symbol-->
													<!--begin::Info-->
													<div class="m-0">
														<!--begin::Subtitle-->
														<h4 class="fw-bold text-gray-800 mb-3">Pustakawan Izin</h4>
														<!--end::Subtitle-->
														<!--begin::Items-->
														<div class="d-flex d-grid gap-5">
															<!--begin::Items-->
                                                            <div class="d-flex d-grid gap-5">
                                                                <!--begin::Item-->
                                                                <div class="m-0">
                                                                    <!--begin::Number-->
                                                                    <span class="text-gray-700 fw-bolder d-block fs-1 lh-1 ls-n1 mb-1">
																	2
																	</span>
                                                                    <!--end::Number-->
                                                                </div>
                                                                <!--end::Item-->
                                                            </div>
                                                            <!--end::Items-->
														</div>
														<!--end::Items-->
													</div>
													<!--end::Info-->
												</div>
												<!--end::Wrapper-->
											</div>
											<!--end::Item-->
											@php

											@endphp
											<!--begin::Item-->
											<div class="carousel-item">
												<!--begin::Wrapper-->
												<div class="d-flex align-items-center mb-9">
													<!--begin::Symbol-->
													<div class="symbol symbol-70px symbol-circle me-5">
														<span class="symbol-label bg-light-primary">
															<i class="ki-duotone ki-abstract-36 fs-3x text-primary">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</span>
													</div>
													<!--end::Symbol-->

													<!--begin::Info-->
													<div class="m-0">
														<!--begin::Subtitle-->
														<h4 class="fw-bold text-gray-800 mb-3">Pustakawan Tidak Hadir</h4>
														<!--end::Subtitle-->
														<!--begin::Items-->
														<div class="d-flex d-grid gap-5">
															<!--begin::Items-->
                                                            <div class="d-flex d-grid gap-5">
                                                                <!--begin::Item-->
                                                                <div class="m-0">
                                                                    <!--begin::Number-->
                                                                    <span class="text-gray-700 fw-bolder d-block fs-1 lh-1 ls-n1 mb-1"> 2 </span>
                                                                    <!--end::Number-->
                                                                </div>
                                                                <!--end::Item-->
                                                            </div>
                                                            <!--end::Items-->
														</div>
														<!--end::Items-->
													</div>
													<!--end::Info-->
												</div>
												<!--end::Wrapper-->
											</div>
											<!--end::Item-->
										</div>
										<!--end::Carousel-->
									</div>
									<!--end::Body-->
								</div>
								<!--end::Slider Widget 2-->
							</div>
							<!--end::Col-->
						</div>
						<!--end::Row-->
                        <!--begin::Engage widget 4-->
                        <div class="card border-transparent" data-bs-theme="light" style="background-color: #1C325E;">
                            <!--begin::Body-->
                            <div class="card-body d-flex ps-xl-15">
                                <!--begin::Wrapper-->
                                <div class="m-0">
                                    <!--begin::Title-->
                                    <div class="position-relative fs-2x z-index-2 fw-bold text-white mb-7">
                                    <span class="me-2">Sistem absensi pustakawan ini mencakup</span>
                                    <br/>Penjadwalan, rekapituliasi dan payroll</div>
                                    <!--end::Title-->
                                    <!--begin::Action-->
                                    <div class="mb-3">
                                        <a href="admin/absen-ruang" class="btn btn-danger fw-semibold me-2">Ruang Absensi</a>
                                    </div>
                                    <!--begin::Action-->
                                </div>
                                <!--begin::Wrapper-->
                                <!--begin::Illustration-->
                                <img src="admin/assets/media/santri/menara.png" class="position-absolute bottom-0 end-0 h-150px" alt="" />
                                <!--end::Illustration-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Engage widget 4-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <!--end::KONTEN-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->

    @include('layout.footer')
    <!--end::Footer-->
</div>

@endsection
