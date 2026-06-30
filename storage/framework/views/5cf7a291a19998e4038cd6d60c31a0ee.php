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
                        Data Izin Tenaga Khidmah</h1>
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
                        <li class="breadcrumb-item text-muted">Data Izin</li>
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
        <div id="kt_app_content" class="app-content flex-column-fluid ">
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
                            <form method="GET" class="d-flex align-items-center gap-3">
                                <div>
                                    <select name="bulan" id="bulan" class="form-select" data-control="select2" data-hide-search="true">
                                        <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($b); ?>" <?php echo e(request('bulan', now()->month) == $b ? 'selected' : ''); ?>>
                                                <?php echo e(\Carbon\Carbon::create()->month($b)->translatedFormat('F')); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <select name="tahun" id="tahun" class="form-select" data-control="select2" data-hide-search="true">
                                        <?php $__currentLoopData = range(now()->year - 5, now()->year + 1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($t); ?>" <?php echo e(request('tahun', now()->year) == $t ? 'selected' : ''); ?>>
                                                <?php echo e($t); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-danger">
                                    <i class="ki-outline ki-filter fs-5"></i> Filter
                                </button>
                                <?php if(auth()->user()->hasPermissionTo('khidmah izin-tambah')): ?>
                                <div class="flex-wrap gap-3 d-flex justify-content-between align-items-end" data-kt-user-table-toolbar="base">
                                    <button type="button" class="btn btn-m btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#kt_modal_update_details">
                                        <i class="ki-duotone ki-plus fs-4 me-2"></i> Tambah Izin
                                    </button>
                                </div>
                                <?php endif; ?>
                            </form>
                            <?php echo $__env->make('admin.TenagaKhidmah.IzinKhidmah.IzinTambahKhidmah', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                                    <th class="text-start rounded-start w-80px pe-2"></th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Tanggal Mulai</th>
                                    <th class="text-center">Tanggal Selesai</th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center rounded-end">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                <?php $__currentLoopData = $izinKhidmah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-center">
                                        <div class="px-4 py-3 badge fs-7 badge-light-success"><?php echo e($item->pustakawan->nama_pustakawan); ?></div>
                                    </td>
                                    <td class="text-center"><?php echo e(Carbon\Carbon::parse($item->tanggal_mulai)->isoFormat('dddd, D MMMM Y')); ?></td>
                                    <td class="text-center"><?php echo e(Carbon\Carbon::parse($item->tanggal_selesai)->isoFormat('dddd, D MMMM Y')); ?></td>
                                    <td class="text-center"></td>
                                    <td></td>
                                    <td class="text-center">
                                        <div class="px-4 py-3 badge fs-7 badge-light-warning"><?php echo e($item->keterangan); ?></div>
                                    </td>
                                    <td class="text-center pe-4">
                                    <a href="#" class="btn btn-sm btn-light-primary btn-active-primary btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Opsi
                                        <i class="ki-outline ki-down fs-5 ms-1"></i></a>
                                        <!--begin::Menu-->
                                        <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px" data-kt-menu="true">
                                        <?php if(auth()->user()->hasPermissionTo('khidmah izin-hapus')): ?>
                                            <!--begin::Menu item-->
                                            <div class="px-3 menu-item">
                                                <a href="<?php echo e(route('khidmah.izin.destroy', $item->id)); ?>" class="px-3 menu-link delete-button" data-kt-users-table-filter="delete_row" data-confirm-delete="true">Hapus</a>
                                            </div>
                                            <!--end::Menu item-->
                                        <?php endif; ?>
                                        </div>
                                        <!--end::Menu-->
                                    </td>
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
<script src="<?php echo e(asset('admin/assets/js/scripts.bundle.js')); ?>"></script>
<!--begin::Veadmin/ndors Javascript(used for this page only)-->
<script src="<?php echo e(asset('admin/assets/plugins/custom/datatables/datatables.bundle.js')); ?>"></script>
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

<?php echo $__env->make('layout.sidebarnavbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/sippus.lib.ibrahimy.ac.id/html/resources/views/admin/TenagaKhidmah/IzinKhidmah/IzinTenagaKhidmah.blade.php ENDPATH**/ ?>