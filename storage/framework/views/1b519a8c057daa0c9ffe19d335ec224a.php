<?php $__env->startSection('admin-konten'); ?>

<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="py-3 app-toolbar py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="flex-wrap page-title d-flex flex-column justify-content-center me-3">
                    <h1 class="my-0 text-gray-900 page-heading d-flex fw-bold fs-3 flex-column justify-content-center">Rekap Absen Harian Viar</h1>
                    <ul class="pt-1 my-0 breadcrumb breadcrumb-separatorless fw-semibold fs-7">
                        <li class="breadcrumb-item text-muted">
                            <a href="/admin/home" class="text-muted text-hover-primary">Beranda</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bg-gray-500 bullet w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Rekap Viar</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <div class="mb-5 card mb-xl-10">
                    <div class="py-10 card-body">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="mb-4 text-gray-900 fw-bold fs-2">Rekap Absensi Viar A & B Perpustakaan Ibrahimy</div>
                            <div class="gap-2 mb-6">
                                
                                <form method="GET" action="<?php echo e(route('viar.cetak')); ?>" target="_blank" class="mb-4 d-flex align-items-center">
                                    <div class="gap-2 d-flex align-items-end">
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
                                        <button type="submit" class="btn btn-danger btn-md fw-bold">
                                            <i class="ki-outline ki-down-square me-1"></i>Cetak PDF
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="flex-wrap d-flex">
                            <div class="px-4 py-3 border border-gray-300 border-dashed rounded min-w-125px me-6">
                                <div class="text-gray-600 fw-semibold fs-6">Hadir</div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-simcard fs-3 text-success me-2"></i>
                                    <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="<?php echo e($hadir ?? 0); ?>"><?php echo e($hadir ?? 0); ?></div>
                                </div>
                            </div>
                            <div class="px-4 py-3 border border-gray-300 border-dashed rounded min-w-125px me-6">
                                <div class="text-gray-600 fw-semibold fs-6">Tanpa Keterangan</div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-simcard-2 fs-3 text-danger me-2"></i>
                                    <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="<?php echo e($tanpaKeterangan ?? 0); ?>"><?php echo e($tanpaKeterangan ?? 0); ?></div>
                                </div>
                            </div>
                            <div class="px-4 py-3 border border-gray-300 border-dashed rounded min-w-125px me-6">
                                <div class="text-gray-600 fw-semibold fs-6">Izin</div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-profile-user fs-3 text-primary me-2"></i>
                                    <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="<?php echo e($izinJumlah ?? 0); ?>"><?php echo e($izinJumlah ?? 0); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-flush">
                    <div class="gap-2 py-5 card-header align-items-center gap-md-5 "  style="background-image:url('admin/assets//media/pattern.png'); background-size: 300px; background-position: right; background-repeat: no-repeat; background-color: #7239EA;">
                        <div class="card-title">
                            <a href="<?php echo e(route('viar-mandiri')); ?>" class="btn btn-warning me-4">Mandiri</a>
                        </div>
                        <div class="gap-2 card-toolbar d-flex justify-content-end align-items-center">
                            <form action="" method="GET" class="gap-2 d-flex align-items-center">
                                <input class="form-control mw-150px" type="date" name="tanggal" value="<?php echo e($tanggal ?? date('Y-m-d')); ?>">
                                <button class="btn fw-bold btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>
                    <div class="py-4 card-body">
                        <table class="table align-middle table-striped table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead class="fw-bold fs-5" style="background-color: #7239EA;">
                                <tr class="text-white text-start fw-bold fs-7 text-uppercase gs-0">
                                    <th class="rounded-start ps-4 min-w-125px">Nama</th>
                                    <th class="text-center min-w-125px">Tanggal</th>
                                    <th class="text-center min-w-125px">Jam Masuk</th>
                                    <th class="text-center min-w-125px">Shift</th>
                                    <th class="text-center min-w-125px rounded-end">Keterangan</th>
                                </tr>
                            </thead>

                            <tbody class="text-gray-600 fw-semibold">
                            <?php $__currentLoopData = $absenViar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="px-4 py-3 badge fs-7 badge-light-success">
                                            <?php echo e($item->nik ?? '-'); ?> - <?php echo e($item->nama_pustakawan ?? 'Tidak Diketahui'); ?>

                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php echo e($item->tanggal ? Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMMM Y') : '-'); ?>

                                    </td>
                                    <td class="text-center"><?php echo e($item->jam_masuk ?? '-'); ?></td>
                                    <td class="text-center"><?php echo e($item->jadwal ?? '-'); ?></td>
                                    <td class="text-center">
                                        <div class="px-4 py-3 badge fs-7 badge-light-primary"><?php echo e($item->keterangan ?? '-'); ?></div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php echo $__env->make('layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>

<script>
    var hostUrl = "assets/";
</script>
<script src="admin/assets/js/scripts.bundle.js"></script>
<script src="admin/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="admin/assets/js/custom/apps/ecommerce/catalog/products.js"></script>
<script src="admin/assets/js/widgets.bundle.js"></script>
<script src="admin/assets/js/custom/widgets.js"></script>
<script src="admin/assets/js/custom/apps/chat/chat.js"></script>
<script src="admin/assets/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="admin/assets/js/custom/utilities/modals/create-app.js"></script>
<script src="admin/assets/js/custom/utilities/modals/users-search.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.sidebarnavbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sever/ols-docker-env/sites/sippus.lib.ibrahimy.ac.id/html/resources/views/admin/Viar/RekapViar/rekap_viar.blade.php ENDPATH**/ ?>