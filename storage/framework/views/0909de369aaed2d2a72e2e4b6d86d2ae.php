<!--begin::Modal - Update user details-->
<div class="modal fade" id="kt_modal_update_details" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <div class="pb-0 border-0 modal-header justify-content-end">
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Modal body-->
            <div class="pt-0 modal-body scroll-y px-15 px-lg-15 pb-15">
                <!--begin:Form-->
                <form id="form_tambah_izin" class="form" method="POST" enctype="multipart/form-data" action="<?php echo e(route('khidmah.izin.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <!--begin::Heading-->
                    <div class="text-center mb-13">
                        <h1 class="mb-3">Tambah Izin Tenaga Khidmah</h1>
                        <div class="text-muted fw-semibold fs-5">Kehadiran Umana Perpustakaan Ibrahimy.</div>
                    </div>
                    <!--end::Heading-->

                    <!--begin::Input group-->
                    <div class="mb-6">
                        <label class="mb-2 required fw-semibold fs-6">Pilih Pustakawan</label>
                        <select class="form-select" name="pustakawan_id" data-control="select2" data-dropdown-parent="#kt_modal_update_details" data-placeholder="Pilih Pustakawan" required>
                            <option value=""></option>
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
                            <?php $__currentLoopData = ['Izin', 'Tugas Pesantren', 'Sakit', 'Libur']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="keterangan" value="<?php echo e($ket); ?>" required>
                                    <span class="fw ps-2 fs-6"><?php echo e($ket); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mb-6">
                        <label class="mb-2 fs-6 required fw-semibold">Mode Hari</label>
                        <div class="gap-4 mt-3 flex-wrap d-flex">
                            <?php $__currentLoopData = [
                                'satu_full'   => 'Satu Hari (Full)',
                                'satu_shift'  => 'Satu Hari (Shift)',
                                'banyak_full' => 'Beberapa Hari (Full)',
                                'banyak_shift'=> 'Beberapa Hari (Shift)'
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input mode-hari" type="radio" name="mode_hari" value="<?php echo e($key); ?>" required>
                                    <span class="fw ps-2 fs-6"><?php echo e($label); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mb-8 fv-row">
                        <label class="mb-2 required fw-semibold fs-6">Tanggal Mulai</label>
                        <input type="date" class="form-control form-control-lg" name="tanggal_mulai" required>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mb-8 fv-row" id="container_tanggal_selesai">
                        <label class="mb-2 required fw-semibold fs-6">Tanggal Selesai</label>
                        <input type="date" class="form-control form-control-lg" name="tanggal_selesai" required>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mb-8 fv-row" id="container_pilih_jadwal">
                        <label class="mb-2 required fw-semibold fs-6">Pilih Jadwal</label>
                        <div class="mt-3 d-flex align-items-center">
                            <?php $__currentLoopData = ['pagi' => 'Pagi', 'siang' => 'Siang', 'malam' => 'Malam']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="form-check form-check-custom form-check-inline me-3">
                                    <input type="checkbox" class="form-check-input shift-input" name="shifts[]" value="<?php echo e($v); ?>">
                                    <span class="fw ps-2 fs-6"><?php echo e($l); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <div class="text-center">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan</span>
                        </button>
                    </div>
                </form>
                <!--end:Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Update user details-->

<script>
    // Pastikan data ini di-pass dengan benar dari Controller
    const JADWAL_PEGAWAI = <?php echo json_encode($pustakawan_jadwal ?? [], 15, 512) ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form_tambah_izin');
    const selectPustakawan = document.querySelector('[name="pustakawan_id"]');
    const tglMulai   = document.querySelector('[name="tanggal_mulai"]');
    const tglSelesai = document.querySelector('[name="tanggal_selesai"]');
    const shiftInputs = document.querySelectorAll('.shift-input');

    const containerJadwal = document.getElementById('container_pilih_jadwal');
    const containerSelesai = document.getElementById('container_tanggal_selesai');

    // ===== JALUR SELECT2 METRONIC EVENT =====
    // Karena Metronic menggunakan Select2, event 'change' vanilla tidak selalu tertangkap.
    $(selectPustakawan).on('change', function () {
        evaluasiStateLayout();
    });

    // ===== RESET SHIFT STATUS =====
    function resetShift() {
        shiftInputs.forEach(s => {
            s.checked = false;
            s.disabled = true;
        });
    }

    // ===== LOGIKA COCOKAN HARI & AKTIFKAN SHIFT VAlID =====
    function enableShiftByTanggal() {
        resetShift();

        const pustakawan_id = selectPustakawan.value;
        const tanggal = tglMulai.value;
        const mode = document.querySelector('.mode-hari:checked')?.value;

        if (!pustakawan_id || !tanggal || (mode !== 'satu_shift' && mode !== 'banyak_shift')) return;

        // Dapatkan nama hari bahasa indonesia
        const hari = new Date(tanggal)
            .toLocaleDateString('id-ID', { weekday: 'long' })
            .toLowerCase();

        // Cari jadwal yang cocok
        const jadwal = JADWAL_PEGAWAI.find(j =>
            j.pustakawan_id == pustakawan_id &&
            j.hari.toLowerCase() === hari
        );

        if (!jadwal) return;

        // Aktifkan checkbox berdasarkan ketersediaan shift di database
        if (parseInt(jadwal.pagi) === 1) document.querySelector('.shift-input[value="pagi"]').disabled = false;
        if (parseInt(jadwal.siang) === 1) document.querySelector('.shift-input[value="siang"]').disabled = false;
        if (parseInt(jadwal.malam) === 1) document.querySelector('.shift-input[value="malam"]').disabled = false;
    }

    // ===== UTAMA: EVALUASI KONDISI INTERFACE =====
    function evaluasiStateLayout() {
        const mode = document.querySelector('.mode-hari:checked')?.value;

        if (!mode) {
            $(containerJadwal).hide();
            return;
        }

        // Kondisi 1: Satu Hari (Full)
        if (mode === 'satu_full') {
            tglSelesai.value = tglMulai.value;
            $(containerSelesai).hide();
            $(containerJadwal).hide();
            resetShift();
        }
        // Kondisi 2: Satu Hari (Shift)
        else if (mode === 'satu_shift') {
            tglSelesai.value = tglMulai.value;
            $(containerSelesai).hide();
            $(containerJadwal).show();
            enableShiftByTanggal();
        }
        // Kondisi 3: Beberapa Hari (Full)
        else if (mode === 'banyak_full') {
            $(containerSelesai).show();
            $(containerJadwal).hide();
            resetShift();
        }
        // Kondisi 4: Beberapa Hari (Shift)
        else if (mode === 'banyak_shift') {
            $(containerSelesai).show();
            $(containerJadwal).show();
            // Pada banyak hari shift, validasi shift berdasarkan Hari Mulai (Mewakili shift berkala)
            enableShiftByTanggal();
        }
    }

    // ===== REGISTER EVENT LISTENERS =====
    document.querySelectorAll('.mode-hari').forEach(radio => {
        radio.addEventListener('change', evaluasiStateLayout);
    });

    tglMulai.addEventListener('change', function() {
        evaluasiStateLayout();
    });

    // Validasi Sederhana Sebelum Submit Form
    form.addEventListener('submit', function (e) {
        const mode = document.querySelector('.mode-hari:checked')?.value;

        // Cek jika mode ber-shift tapi belum memilih shift satupun
        if (mode === 'satu_shift' || mode === 'banyak_shift') {
            const anyChecked = Array.from(shiftInputs).some(s => s.checked);
            if (!anyChecked) {
                e.preventDefault();
                alert('Silakan pilih minimal satu jadwal shift yang tersedia!');
                return false;
            }
        }

        // Cek Validitas Tanggal Akhir
        if ((mode === 'banyak_full' || mode === 'banyak_shift') && tglSelesai.value < tglMulai.value) {
            e.preventDefault();
            alert('Tanggal selesai tidak boleh mendahului tanggal mulai!');
            return false;
        }
    });

    // Inisialisasi awal saat modal dibuka
    evaluasiStateLayout();
});
</script>
<?php /**PATH /home/sever/ols-docker-env/sites/sippus.lib.ibrahimy.ac.id/html/resources/views/admin/TenagaKhidmah/IzinKhidmah/IzinTambahKhidmah.blade.php ENDPATH**/ ?>