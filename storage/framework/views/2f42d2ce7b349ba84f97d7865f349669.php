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
            <form class="form" method="POST" enctype="multipart/form-data" action="<?php echo e(route('struktural-izin.store')); ?>">
                <?php echo csrf_field(); ?>
                <!--begin::Heading-->
                <div class="text-center mb-13">
                    <!--begin::Title-->
                    <h1 class="mb-3">Tambah Izin Struktural</h1>
                    <div class="text-muted fw-semibold fs-5">Kehadiran Umana Perpustakaan Ibrahimy.</div>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->
                <!--begin::Input group-->
                <div class="mb-6">
                    <label class="mb-2 required fw-semibold fs-6">Pilih Pustakawan</label>
                    <select class="form-select" name="pustakawan_id" data-control="select2" data-hide-search="true" data-placeholder="Pilih Pustakawan" required>
                        <option value="" disabled selected>Pilih Pustakawan</option>
                        <?php $__currentLoopData = $pustakawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->nama_pustakawan); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-6">
                    <label class="mb-2 fs-6 required fw-semibold">Keterangan</label>
                    <div class="d-flex flex-wrap gap-4 mt-3">
                        <label class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="keterangan" value="Izin">
                            <span class="fw ps-2 fs-6">Izin</span>
                        </label>
                        <label class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="keterangan" value="Tugas Pesantren">
                            <span class="fw ps-2 fs-6">Tugas Pesantren</span>
                        </label>
                        <label class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="keterangan" value="Sakit">
                            <span class="fw ps-2 fs-6">Sakit</span>
                        </label>
                        <label class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="keterangan" value="Libur">
                            <span class="fw ps-2 fs-6">Libur</span>
                        </label>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-6">
                    <label class="mb-2 fs-6 required fw-semibold">Mode Hari</label>
                    <div class="gap-4 mt-3 flex-warp d-flex">
                        <?php $__currentLoopData = [
                            'satu_full'   => 'Satu Hari (Full)',
                            'satu_shift'  => 'Satu Hari (Shift)',
                            'banyak_full' => 'Beberapa Hari (Full)',
                            'banyak_shift'=> 'Beberapa Hari (Shift)'
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input need-pegawai mode-hari" type="radio" name="mode_hari" value="<?php echo e($key); ?>">
                                <span class="fw ps-2 fs-6"><?php echo e($label); ?></span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Tanggal Mulai</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                        <input type="date" class="form-control form-control-lg" name="tanggal_mulai" placeholder="Eselon" required>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Tanggal Selesai</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                        <input type="date" class="form-control form-control-lg" name="tanggal_selesai" placeholder="Eselon" required>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <div class="mb-8 fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Pilih Jadwal</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="mt-3 d-flex align-items-center">
                        <label class="form-check form-check-custom form-check-inline me-3">
                            <input type="checkbox"
                                class="form-check-input need-pegawai shift-input"
                                name="shifts[]"
                                value="pagi">
                            <span class="fw ps-2 fs-6">Pagi</span>
                        </label>
                        <label class="form-check form-check-custom form-check-inline me-3">
                            <input type="checkbox"
                                class="form-check-input need-pegawai shift-input"
                                name="shifts[]"
                                value="siang">
                            <span class="fw ps-2 fs-6">Siang</span>
                        </label>
                        <label class="form-check form-check-custom form-check-inline me-3">
                            <input type="checkbox"
                                class="form-check-input need-pegawai shift-input"
                                name="shifts[]"
                                value="malam">
                            <span class="fw ps-2 fs-6">Malam</span>
                        </label>
                    </div>
                    <!--end::Col-->
                </div>
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

<script>
    const JADWAL_PEGAWAI = <?php echo json_encode($pustakawan_jadwal, 15, 512) ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const selectPustakawan = document.querySelector('[name="pustakawan_id"]');
    const tglMulai   = document.querySelector('[name="tanggal_mulai"]');
    const tglSelesai = document.querySelector('[name="tanggal_selesai"]');
    const modeRadios = document.querySelectorAll('.mode-hari');
    const shiftInputs = document.querySelectorAll('.shift-input');

    // ===== RESET SHIFT =====
    function resetShift() {
        shiftInputs.forEach(s => {
            s.checked = false;
            s.disabled = true;
        });
    }

    // ===== ENABLE SHIFT SESUAI JADWAL =====
    function enableShiftByTanggal() {

        resetShift();

        const pustakawan_id = selectPustakawan.value;
        const tanggal = tglMulai.value;

        if (!pustakawan_id || !tanggal) return;

        const hari = new Date(tanggal)
            .toLocaleDateString('id-ID', { weekday: 'long' })
            .toLowerCase();

        const jadwal = JADWAL_PEGAWAI.find(j =>
            j.pustakawan_id == pustakawan_id &&
            j.hari.toLowerCase() === hari
        );

        if (!jadwal) return;

        if (jadwal.pagi == 1) {
            document.querySelector('[value="pagi"]').disabled = false;
        }

        if (jadwal.siang == 1) {
            document.querySelector('[value="siang"]').disabled = false;
        }

        if (jadwal.malam == 1) {
            document.querySelector('[value="malam"]').disabled = false;
        }
    }

    // ===== SYNC TANGGAL =====
    function syncTanggal() {
        const mode = document.querySelector('.mode-hari:checked')?.value;

        if (mode === 'satu_full' || mode === 'satu_shift') {
            tglSelesai.value = tglMulai.value;
        }
    }

    // ===== HANDLE MODE =====
    function handleModeHari(mode) {

        tglSelesai.readOnly = false;

        if (mode === 'satu_full') {
            resetShift();
            tglSelesai.readOnly = true;
            syncTanggal();
        }

        else if (mode === 'satu_shift') {
            tglSelesai.readOnly = true;
            syncTanggal();
            enableShiftByTanggal();
        }

        else if (mode === 'banyak_full') {
            resetShift();
        }

        else if (mode === 'banyak_shift') {
            enableShiftByTanggal();
        }
    }

    // ===== EVENT =====
    modeRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            handleModeHari(this.value);
        });
    });

    tglMulai.addEventListener('change', function () {
        syncTanggal();

        const mode = document.querySelector('.mode-hari:checked')?.value;
        if (mode === 'satu_shift' || mode === 'banyak_shift') {
            enableShiftByTanggal();
        }
    });

    selectPustakawan.addEventListener('change', function () {
        const mode = document.querySelector('.mode-hari:checked')?.value;
        if (mode === 'satu_shift' || mode === 'banyak_shift') {
            enableShiftByTanggal();
        }
    });

    resetShift();
});
</script>


<?php /**PATH /var/www/html/resources/views/admin/Struktural/IzinStruktural/izin_tambah.blade.php ENDPATH**/ ?>