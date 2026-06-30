<!--begin::Modal - Update user details-->
<div class="modal fade" id="kt_modal_update_details" tabindex="-1" aria-hidden="true">
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
            <form class="form" method="POST" enctype="multipart/form-data" action="admin/payroll-kehormatan">
                <?php echo csrf_field(); ?>
                <!--begin::Heading-->
                <div class="text-center mb-13">
                    <!--begin::Title-->
                    <h1 class="mb-3">Tambah Tunjangan Kehormatan</h1>
                    <div class="text-muted fw-semibold fs-5">Kehadiran Pustakawan.</div>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->
                <!--begin::Input group-->
                <div class="mb-8 fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Tunjangan</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="text" class="form-control form-control-lg" name="tunjangan_kehormatan" placeholder="Tunjangan Kehormatan" required autofocus>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!-- begin:Tunjangan -->
                <div class="mb-8 fv-row">
                    <label class="mb-2 required fw-semibold fs-6">Tahun APBM</label>
                    <div>
                        <select name="APBM" id="tahun" class="form-select" data-control="select2" data-hide-search="true">
                            <?php $__currentLoopData = range(now()->year - 5, now()->year + 1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($t); ?>" <?php echo e(request('tahun', now()->year) == $t ? 'selected' : ''); ?>>
                                    <?php echo e($t); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <!-- end:Tunjangan -->
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

<script src="admin/assets/plugins/global/plugins.bundle.js"></script>
<script src="admin/assets/js/custom/utilities/modals/bidding.js"></script>


<?php /**PATH /home/sever/ols-docker-env/sites/sippus.lib.ibrahimy.ac.id/html/resources/views/admin/Payroll/kehormatan/tambah_kehormatan.blade.php ENDPATH**/ ?>