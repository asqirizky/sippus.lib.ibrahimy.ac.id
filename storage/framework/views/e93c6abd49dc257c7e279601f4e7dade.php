<!--begin::Modal - Update user details-->
<div class="modal fade" id="kt_modal_update_details" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Modal body-->
            <div class="modal-body scroll-y px-15 px-lg-15 pt-0 pb-15">
                <!--begin:Form-->
                <form class="form" method="POST" action="<?php echo e(route('khidmah.barokah.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">Tambah Barokah Khidmah</h1>
                        <div class="text-muted fw-semibold fs-5">Sistem Informasi Presensi Pustakawan</div>
                    </div>
                    <!--end::Heading-->
                    <!--begin::Input group: Kategori Enum-->
                    <div class="fv-row mb-6">
                        <label class="required fw-semibold fs-6 mb-2">Kategori Peruntukan</label>
                        <select name="jenis" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Pilih Kategori" required>
                            <option value=""></option>
                            <option value="khidmah">Tenaga Khidmah (Perpustakaan)</option>
                        </select>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group: Nominal Barokah-->
                    <div class="fv-row mb-8">
                        <label class="required fw-semibold fs-6 mb-2">Nominal Barokah (Rp)</label>
                        <div class="input-group mb-5">
                            <span class="input-group-text">Rp</span>
                            <input name="barokah" type="text" class="form-control" placeholder="Contoh: 50000" oninput="this.value = this.value.replace(/\D/g, '');" required />
                        </div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan Data</span>
                            <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end:Form-->
            </div>
            <!--end::Modal body-->
        </div>
    </div>
</div>
<!--end::Modal - Update user details-->
<?php /**PATH /var/www/vhosts/sippus.lib.ibrahimy.ac.id/html/resources/views/admin/TenagaKhidmah/BarokahKhidmah/TambahBarokahKhidmah.blade.php ENDPATH**/ ?>