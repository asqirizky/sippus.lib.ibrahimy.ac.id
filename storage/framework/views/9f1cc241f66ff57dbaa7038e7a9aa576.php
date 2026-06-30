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
                        Barokah Pustakawan</h1>
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
                        <li class="breadcrumb-item text-muted">
                            <a href="admin/kehadiran-rekapan" class="text-muted text-hover-primary">Rekapan</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bg-gray-500 bullet w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Barokah Pustakawan</li>
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
                <div class="card card-flush" >
                    <!--begin::Card header-->
                    <div class="gap-2 py-5 card-header align-items-center gap-md-5 bg-success" style="background-image: url('/admin/assets/media/pattern.png'); background-size: 650px; background-position: right; background-repeat: no-repeat;">
                        <!--begin::Card title-->
                        <?php if(auth()->user()->hasPermissionTo('struktural barokah-generate')): ?>
                        <div class="card-title">
                            <!--end::Button filter-->
                            <a href="<?php echo e(route('struktural-generate', [
                                    'bulan' => request('bulan', now()->month),
                                    'tahun' => request('tahun', now()->year)
                                    ])); ?>" class="btn btn-warning me-4">Generate</a>
                            <!--end::Button filter-->
                        </div>
                        <?php endif; ?>
                        <!--end::Card title-->
                        <!--begin::Card title-->
                        <div class="gap-2 card-toolbar d-flex justify-content-end align-items-center">
                            <form action="<?php echo e(url()->current()); ?>" method="GET" class="gap-2 d-flex align-items-center">
                                <div>
                                    <select name="bulan" class="form-select mw-150px">
                                        <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($b); ?>"
                                                <?php echo e((int)request('bulan', now()->month) === $b ? 'selected' : ''); ?>>
                                                <?php echo e(\Carbon\Carbon::create()->month($b)->translatedFormat('F')); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <select name="tahun" class="form-select mw-100px">
                                        <?php $__currentLoopData = range(now()->year - 5, now()->year + 1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($t); ?>"
                                                <?php echo e((int)request('tahun', now()->year) === $t ? 'selected' : ''); ?>>
                                                <?php echo e($t); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn fw-bold btn-primary">
                                    Filter
                                </button>
                            </form>
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
                                    <th class="rounded-start ps-4"></th>
                                    <th class="text-start min-w-125px">Nama</th>
                                    <th class="text-center min-w-125px">Jabatan</th>
                                    <th class="text-center min-w-125px">Pengabdian</th>
                                    <th class="text-center min-w-125px">Kehadiran</th>
                                    <th class="text-center min-w-125px">Tunkel</th>
                                    <th class="text-center min-w-125px">Anak</th>
                                    <th class="text-center min-w-125px">Kehormatan</th>
                                    <th class="text-center min-w-125px">TBK</th>
                                    <th class="text-center min-w-70px rounded-end">SKS</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                            <?php $__empty_1 = true; $__currentLoopData = $barokah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td></td>
                                    <td class="text-start">
                                        <div class="px-4 py-3 badge fs-7 badge-light-success">
                                            <?php echo e($item->pustakawan->nama_pustakawan ?? '-'); ?>

                                        </div>
                                    </td>
                                    <td class="text-center"><?php echo e($item->t_jabatan ? number_format($item->t_jabatan->tunjangan_jabatan, 0, ',', '.') : '-'); ?></td>
                                    <td class="text-center"><?php echo e($item->t_pengabdian ? number_format($item->t_pengabdian->tunjangan_pengabdian, 0, ',', ',') : '-'); ?></td>
                                    <td class="text-center"><?php echo e($item->t_kehadiran ? number_format($item->t_kehadiran->tunjangan, 0, ',', ',') : '-'); ?></td>
                                    <td class="text-center"><?php echo e($item->t_tunkel ? number_format($item->t_tunkel->tunkel, 0, ',', ',') : '-'); ?></td>
                                    <td class="text-center"><?php echo e($item->t_anak ? number_format($item->t_anak->tunjangan_anak, 0, ',', ',') : '-'); ?></td>
                                    <td class="text-center"><?php echo e($item->t_kehormatan ? number_format($item->t_kehormatan->tunjangan_kehormatan, 0, ',', ',') : '-'); ?></td>
                                    <td class="text-center"><?php echo e($item->t_dosen ? number_format($item->t_dosen->t_rank_dosen, 0, ',', ',') : '-'); ?></td>
                                    <td class="text-center"><?php echo e($item->sks); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center">No available data in table</td>
                                </tr>
                            <?php endif; ?>
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
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="admin/assets/js/scripts.bundle.js"></script>
<script src="admin/assets/plugins/global/plugins.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--end::Custom Javascript-->
<!--end::Javascript-->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.sidebarnavbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/sippus.lib.ibrahimy.ac.id/html/resources/views/admin/Struktural/BarokahStruktural/barokah_pustakawan.blade.php ENDPATH**/ ?>