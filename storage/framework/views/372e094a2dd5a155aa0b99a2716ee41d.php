<!--begin::Modal - Update user details-->
<div class="modal fade" id="kt_modal_new_target<?php echo e($item->id); ?>" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <!--begin::Modal content-->
        <div class="modal-content">
        <div class="pb-0 border-0 modal-header justify-content-end">
            <!--begin::Close-->
            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                <i class="ki-outline ki-cross fs-1">
                </i>
            </div>
            <!--end::Close-->
        </div>
        <!--begin::Modal header-->
        <!--begin::Modal body-->
        <div class="pt-0 modal-body scroll-y px-15 px-lg-15 pb-15">
            <!--begin:Form-->
            <form class="form" method="POST" enctype="multipart/form-data" action="<?php echo e(route('payroll-jabatan.update', $item->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <!--begin::Heading-->
                <div class="text-center mb-13">
                    <!--begin::Title-->
                    <h1 class="mb-3">Edit Tunjangan Kehadiran</h1>
                    <div class="text-muted fw-semibold fs-5">Kehadiran Umana Perpustakaan Ibrahimy.</div>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->
                <!--begin::Input group-->
                <div class="mb-8 fv-row text-start">
                    <label class="mb-2 required fw-semibold fs-6">Jabatan</label>
                    <div class="mb-8 fv-row">
                        <select class="mb-2 form-select" name="jabatan_id" data-control="select2" data-hide-search="true">
                            <?php $__currentLoopData = $jabatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($j->id); ?>"
                                    <?php if(old('jabatan_id', $item->jabatan_id ?? '') == $j->id): echo 'selected'; endif; ?>>
                                    <?php echo e($j->nama_jabatan); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <!--end::Input group-->
                <!-- begin:Tunjangan -->
                <div class="mb-8 fv-row text-start">
                    <label class="mb-2 required fw-semibold fs-6">Tunjangan</label>
                    <input type="text" class="form-control form-control-lg" name="tunjangan_jabatan" value="<?php echo e(number_format($item->tunjangan_jabatan ?? 0, 0, ',', '.')); ?>" placeholder="Tunjangan Kehadiran" required>
                </div>
                <!-- end:Tunjangan -->
                <!--begin::Input group-->
                <div class="mb-8 fv-row text-start">
                    <label class="mb-2 required fw-semibold fs-6">Tahun APBM</label>
                    <select name="APBM" id="tahun" class="form-select form-control-lg" data-control="select2" data-hide-search="true" required>
                        <option value="" disabled>Pilih Tahun</option>
                        <?php $__currentLoopData = range(now()->year - 5, now()->year + 1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($t); ?>"
                                <?php echo e(old('tahun', $item->APBM ?? '') == $t ? 'selected' : ''); ?>>
                                <?php echo e($t); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <!--end::Input group-->
                <div class="text-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                    
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Please wait...
                        <span class="align-middle spinner-border spinner-border-sm ms-2"></span></span>
                    </button>
                </div>
            </form>
            <!--end:Form-->
        </div>
        <!--end::Modal body-->

            <!--end::Form-->
        </div>
    </div>
</div>
<!--end::Modal - Update user details-->


<?php /**PATH /home/sever/ols-docker-env/sites/sippus.lib.ibrahimy.ac.id/html/resources/views/admin/Payroll/jabatan/edit_jabatan.blade.php ENDPATH**/ ?>