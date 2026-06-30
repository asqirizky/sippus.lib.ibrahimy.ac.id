<?php $__env->startSection('admin-konten'); ?>

<style>
.card-hover-dark {
    transition: all 0.3s ease;
    cursor: pointer;
}

.card-hover-dark:hover {
    filter: brightness(85%);
    transform: translateY(2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
</style>

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
					<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Ruang Pustakawan</h1>
					<!--end::Title-->
					<!--begin::Breadcrumb-->
					<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">
							<a href="admin/home" class="text-muted text-hover-primary">Home</a>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="breadcrumb-item">
							<span class="bullet bg-gray-500 w-5px h-2px"></span>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">Ruang Absen</li>
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
				<!--begin::Row-->
				<div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
					<!--begin::Col-->
					<div class="col-xl-3">
						<a href="<?php echo e(url('absen-struktural')); ?>" class="card-link">
							<div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100 card-hover-dark"
								style="background-color: #35cd6d; background-image:url('<?php echo e(asset('admin/assets/media/pattern.png')); ?>')">
								<!--begin::Header-->
								<div class="card-header pt-5 mb-3">
									<!--begin::Icon-->
									<div class="d-flex flex-center rounded-circle h-80px w-80px" style="border: 1px dashed rgba(255, 255, 255, 0.4);background-color: #35cd6d">
										<i class="ki-duotone ki-shield-tick text-white fs-2qx lh-0">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
											<span class="path5"></span>
											<span class="path6"></span>
											<span class="path7"></span>
											<span class="path8"></span>
										</i>
									</div>
									<!--end::Icon-->
								</div>
								<!--end::Header-->
								<div class="card-body d-flex align-items-end mb-3">
									<div class="fw-bold fs-6 text-white">
										<span class="d-block">Ruang untuk</span>
										<span>Pustakawan Struktural</span>
									</div>
								</div>
								<div class="card-footer card-footer-dark">
									<div class="fw-bold text-white py-2">
										<span class="fs-5 d-block">Klik untuk masuk</span>
										<span class="opacity-50">Absensi Struktural</span>
									</div>
								</div>
							</div>
						</a>
					</div>
					<!--end::Col-->
                    <!--begin absen dummy::Col-->
					<div class="col-xl-3">
						<a href="<?php echo e(url('absen-khidmah-room')); ?>" class="card-link">
							<div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100 card-hover-dark"
								style="background-color:#F1416C; background-image:url('<?php echo e(asset('admin/assets/media/pattern.png')); ?>')">
								<!--begin::Header-->
								<div class="card-header pt-5 mb-3">
									<!--begin::Icon-->
									<div class="d-flex flex-center rounded-circle h-80px w-80px" style="border: 1px dashed rgba(255, 255, 255, 0.4);background-color:#F1416C">
										<i class="ki-duotone ki-shield-tick text-white fs-2qx lh-0">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
											<span class="path5"></span>
											<span class="path6"></span>
											<span class="path7"></span>
											<span class="path8"></span>
										</i>
									</div>
									<!--end::Icon-->
								</div>
								<!--end::Header-->
								<div class="card-body d-flex align-items-end mb-3">
									<div class="fw-bold fs-6 text-white">
										<span class="d-block">Ruang untuk</span>
										<span>Pustakawan Tenaga Khidmah</span>
									</div>
								</div>
								<div class="card-footer card-footer-dark">
									<div class="fw-bold text-white py-2">
										<span class="fs-5 d-block">Klik untuk masuk</span>
										<span class="opacity-50">Absensi Tenaga Khidmah</span>
									</div>
								</div>
							</div>
						</a>
					</div>
					<!--end::Col-->
                    
                    
                    <!--begin::Col-->
					<div class="col-xl-3">
						<a href="<?php echo e(url('absen-viar-room')); ?>" class="card-link">
							<div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100 card-hover-dark"
								style="background-color: #7239EA; background-image:url('<?php echo e(asset('admin/assets/media/pattern.png')); ?>')">
								<!--begin::Header-->
								<div class="card-header pt-5 mb-3">
									<!--begin::Icon-->
									<div class="d-flex flex-center rounded-circle h-80px w-80px" style="border: 1px dashed rgba(255, 255, 255, 0.4);background-color: #7239EA">
										<i class="ki-duotone ki-shield-tick text-white fs-2qx lh-0">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
											<span class="path5"></span>
											<span class="path6"></span>
											<span class="path7"></span>
											<span class="path8"></span>
										</i>
									</div>
									<!--end::Icon-->
								</div>
								<!--end::Header-->
								<div class="card-body d-flex align-items-end mb-3">
									<div class="fw-bold fs-6 text-white">
										<span class="d-block">Ruang untuk</span>
										<span>Absen Viar A & B</span>
									</div>
								</div>
								<div class="card-footer card-footer-dark">
									<div class="fw-bold text-white py-2">
										<span class="fs-5 d-block">Klik untuk masuk</span>
										<span class="opacity-50">Absensi Viar</span>
									</div>
								</div>
							</div>
						</a>
					</div>
					<!--end::Col-->
                    
				</div>
				<!--end::Row-->
			</div>
			<!--end::Content container-->
		</div>
		<!--end::Content-->
	</div>
	<!--end::Content wrapper-->
	<!--begin::Footer-->
	<?php echo $__env->make('layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	<!--end::Footer-->
</div>
<!--end:::Main-->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.sidebarnavbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/Absen/absen_room.blade.php ENDPATH**/ ?>