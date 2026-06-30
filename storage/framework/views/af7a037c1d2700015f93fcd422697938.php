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
            <form class="form" method="POST" enctype="multipart/form-data" action="<?php echo e(route('master-libur.update', $item->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <!--begin::Heading-->
                <div class="text-center mb-13">
                    <!--begin::Title-->
                    <h1 class="mb-3">Edit Hari Libur</h1>
                    <div class="text-muted fw-semibold fs-5">Kehadiran Pustakawan.</div>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->
                <!--begin::Input group-->
                <div class="mb-8 fv-row text-start">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Tanggal</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="date" class="form-control form-control-lg" name="tanggal" value="<?php echo e($item->tanggal); ?>" placeholder="Libur" required>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Ruang</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <select class="form-select" name="ruang_id" data-control="select2" data-hide-search="true" required>
                        <?php $__currentLoopData = $ruangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ruang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ruang->id); ?>" <?php echo e($item->ruang_id == $ruang->id ? 'selected' : ''); ?>>
                                <?php echo e($ruang->ruang_pustakawans); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 fv-row text-start">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Acara</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="text" class="form-control form-control-lg" name="libur" value="<?php echo e($item->libur); ?>" placeholder="" required>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 fv-row text-start">
                    <label class="mb-2 required fw-semibold fs-6">Shift</label>
                    <div class="mt-3 d-flex align-items-center">
                        <?php $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-2 form-check me-3">
                                <input type="checkbox"
                                    class="form-check-input"
                                    name="jadwals[]"
                                    value="<?php echo e($shift->id); ?>"
                                    <?php echo e($item->jadwals->contains('id', $shift->id) ? 'checked' : ''); ?>>
                                <label class="form-check-label"><?php echo e($shift->jadwal); ?></label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="form-text">Pilih satu atau lebih shift yang libur pada tanggal tersebut.</div>
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



<?php /**PATH /home/sever/ols-docker-env/sites/sippus.lib.ibrahimy.ac.id/html/resources/views/admin/Master/libur/libur_edit.blade.php ENDPATH**/ ?>