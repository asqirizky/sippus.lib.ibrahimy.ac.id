<?php $__env->startSection('admin-konten'); ?>


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
                        Data Tunjangan Rank Dosen</h1>
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
                        <li class="breadcrumb-item text-muted">Tunjangan</li>
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
                                <div class="gap-2 d-flex justify-content-end">
                                    <!--begin::button-->
                                    <button type="button" class="btn btn-m btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#kt_modal_update_details">
                                        <i class="ki-duotone ki-plus fs-4 me-2"></i> Tambah Tunjangan
                                    </button>
                                    <!--end::button-->
                                </div>
                            </div>
                            <?php echo $__env->make('admin.Payroll.rank_dosen.tambah_rank', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <!--end::Card toolbar-->
                        </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="py-4 card-body">
                        <!--begin::Table-->
                        <table class="table align-middle table-striped fs-6 gy-5" id="kt_ecommerce_products_table">
                            <thead class="fw-bold fs-5 bg-success">
                                <tr class="text-white text-start fw-bold fs-7 text-uppercase gs-0">
                                    <th class="rounded-start w-10px pe-2"></th>
                                    <th class="text-center min-w-125px">Tahun Ke</th>
                                    <th class="text-center min-w-125px">Pendidikan Terakhir</th>
                                    <th class="text-center min-w-125px">Tunjangan</th>
                                    <th></th>
                                    <th class="text-center min-w-125px">APBM</th>
                                    <th class="text-center min-w-100px"></th>
                                    <th class="text-center rounded-end min-w-100px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                <?php $__currentLoopData = $rank; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td></td>
                                    <td class="text-center">
                                        <div class="px-4 py-3 badge fs-7 badge-light-success"><?php echo e($item->tahun); ?></div>
                                    </td>
                                    <td class="text-center">
                                        <?php if($item->pendidikan_terakhir == "S1"): ?>
                                            <div class="px-4 py-3 badge fs-7 badge-light-info"><?php echo e($item->pendidikan_terakhir); ?></div>
                                        <?php elseif($item->pendidikan_terakhir == "S2"): ?>
                                            <div class="px-4 py-3 badge fs-7 badge-light-warning"><?php echo e($item->pendidikan_terakhir); ?></div>
                                        <?php elseif($item->pendidikan_terakhir == "S3"): ?>
                                            <div class="px-4 py-3 badge fs-7 badge-light-danger"><?php echo e($item->pendidikan_terakhir); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">Rp. <?php echo e(number_format($item->t_rank_dosen ?? 0, 0, ',', ',')); ?></td>
                                    <td class="text-center"></td>
                                    <td class="text-center">
                                        <?php if($item->APBM == "2025"): ?>
                                            <div class="px-4 py-3 badge fs-7 badge-light-warning"><?php echo e($item->APBM); ?></div>
                                        <?php elseif($item->APBM == "2026"): ?>
                                            <div class="px-4 py-3 badge fs-7 badge-light-danger"><?php echo e($item->APBM); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"></td>
                                    <td class="text-center pe-4">
                                        <a href="#" class="btn btn-sm btn-light-primary btn-active-primary btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Opsi
                                            <i class="ki-outline ki-down fs-5 ms-1"></i></a>
                                            <!--begin::Menu-->
                                            <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px" data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                <div class="px-3 menu-item">
                                                    <a class="px-3 menu-link" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target<?php echo e($item->id); ?>">Edit</a>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <div class="px-3 menu-item">
                                                    <a href="<?php echo e(route('payrol-rankDosen.hapus', $item->id)); ?>" class="px-3 menu-link delete-button">Hapus</a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu-->
                                        </td>
                                        <?php echo $__env->make('admin.Payroll.rank_dosen.edit_rank', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    <?php echo $__env->make('layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
<script src="admin/assets/plugins/custom/datatables/datatables.bundle.js"></script>
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.sidebarnavbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sever/ols-docker-env/sites/sippus.lib.ibrahimy.ac.id/html/resources/views/admin/Payroll/rank_dosen/rank_dosen.blade.php ENDPATH**/ ?>