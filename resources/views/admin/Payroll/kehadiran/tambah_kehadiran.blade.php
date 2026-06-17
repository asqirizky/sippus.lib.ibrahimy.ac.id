<!--begin::Modal - Tambah Tunjangan Kehadiran-->
<div class="modal fade" id="kt_modal_update_details" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content">
            <div class="pb-0 border-0 modal-header justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <div class="modal-body scroll-y px-15 px-lg-15 pb-15">
                <form class="form" method="POST" enctype="multipart/form-data" action="admin/payroll-kehadiran">
                    @csrf
                    <div class="text-center mb-13">
                        <h1 class="mb-3">Tambah Tunjangan Kehadiran</h1>
                        <div class="text-muted fw-semibold fs-5">Kehadiran Umana' Perpustakaan Ibrahimy.</div>
                    </div>
                    <!-- begin:provinsi -->
                    <div class="mb-8 fv-row">
                        <label class="mb-2 required fw-semibold fs-6">Domisili</label>
                        <select id="province" name="province" class="mb-2 form-select" data-hide-search="true">
                            <option value="" selected disabled>Pilih Provinsi</option>
                        </select>
                    </div>
                    <!-- end:provinsi-->
                    <!-- begin:kabupaten -->
                    <div class="mb-8 fv-row">
                        <select id="regency" name="regency" class="mb-2 form-select" disabled>
                            <option value="" selected disabled>Pilih Kabupaten/Kota</option>
                        </select>
                    </div>
                    <!-- end:kabupaten-->
                    <!-- begin:kabupaten -->
                    <div class="mb-8 fv-row">
                        <select id="district" name="district" class="mb-2 form-select" disabled>
                            <option value="" selected disabled>Pilih Kecamatan</option>
                        </select>
                    </div>
                    <!-- end:kabupaten-->
                    <!-- begin:kecamatan -->
                    <div class="mb-8 fv-row">
                        <select id="village" name="village" class="mb-2 form-select" disabled>
                            <option value="" selected disabled>Pilih Desa/Kelurahan</option>
                        </select>
                    </div>
                    <!-- end:kecamatan-->
                    <!--begin::Input Hidden-->
                        <input type="hidden" name="tempatTinggal" id="tempatTinggal">
						<!--end::Input Hidden-->
                    <!-- begin:Tunjangan -->
                    <div class="mb-8 fv-row">
                        <label class="mb-2 required fw-semibold fs-6">Tunjangan</label>
                        <input type="text" class="form-control form-control-lg" name="tunjangan" placeholder="Tunjangan Kehadiran" required>
                    </div>
                    <!-- end:Tunjangan -->
                    <!-- begin:Tunjangan -->
                    <div class="mb-8 fv-row">
                        <label class="mb-2 required fw-semibold fs-6">Tahun APBM</label>
                        <div>
                            <select name="APBM" id="tahun" class="form-select" data-control="select2" data-hide-search="true">
                                @foreach(range(now()->year - 5, now()->year + 1) as $t)
                                    <option value="{{ $t }}" {{ request('tahun', now()->year) == $t ? 'selected' : '' }}>
                                        {{ $t }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- end:Tunjangan -->
                    <!-- Buttons -->
                    <div class="text-center">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        {{--  <button class="btn btn-primary" >  --}}
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan</span>
                            <span class="indicator-progress">Please wait...
                            <span class="align-middle spinner-border spinner-border-sm ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="admin/assets/plugins/global/plugins.bundle.js"></script>
<script src="admin/assets/js/custom/utilities/modals/bidding.js"></script>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", async function () {
    const province = document.getElementById('province');
    const regency = document.getElementById('regency');
    const district = document.getElementById('district');
    const village = document.getElementById('village');
    const tempatTinggalInput = document.getElementById('tempatTinggal');
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = true;

    try {
        // Ambil data provinsi
        const provinces = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json').then(res => res.json());
        provinces.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            province.appendChild(option);
        });

        province.addEventListener('change', async function () {
            regency.innerHTML = '<option selected disabled>Pilih Kabupaten/Kota</option>';
            regency.disabled = true;
            district.innerHTML = '<option selected disabled>Pilih Kecamatan</option>';
            district.disabled = true;
            village.innerHTML = '<option selected disabled>Pilih Desa/Kelurahan</option>';
            village.disabled = true;

            const regencies = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.value}.json`).then(res => res.json());
            regencies.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                regency.appendChild(option);
            });
            regency.disabled = false;
        });

        regency.addEventListener('change', async function () {
            district.innerHTML = '<option selected disabled>Pilih Kecamatan</option>';
            district.disabled = true;
            village.innerHTML = '<option selected disabled>Pilih Desa/Kelurahan</option>';
            village.disabled = true;

            const districts = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${this.value}.json`).then(res => res.json());
            districts.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                district.appendChild(option);
            });
            district.disabled = false;
        });

        district.addEventListener('change', async function () {
            village.innerHTML = '<option selected disabled>Pilih Desa/Kelurahan</option>';
            village.disabled = true;

            const villages = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${this.value}.json`).then(res => res.json());
            villages.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                village.appendChild(option);
            });
            village.disabled = false;
        });

        village.addEventListener('change', function () {
            tempatTinggalInput.value = `${village.options[village.selectedIndex].text}, ${district.options[district.selectedIndex].text}, ${regency.options[regency.selectedIndex].text}, ${province.options[province.selectedIndex].text}`;
            submitBtn.disabled = false;
        });

    } catch (error) {
        Swal.fire({
            title: 'Gagal Memuat Data',
            text: 'Tidak bisa memuat daftar wilayah. Silakan cek koneksi internet Anda.',
            icon: 'error'
        });
    }
});
</script>
