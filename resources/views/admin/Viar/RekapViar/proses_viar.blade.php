<div class="modal fade" id="kt_modal_update_details" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content">
            <div class="pb-0 border-0 modal-header justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="pt-0 modal-body scroll-y px-15 px-lg-15 pb-15">

                {{-- PERBAIKAN: Mengarah ke route store khusus Kru Viar --}}
                <form method="POST" action="{{ route('viar-mandiri.store') }}">
                @csrf

                {{-- PILIH KRU VIAR --}}
                <div class="mb-6">
                    <label class="mb-2 required fw-semibold fs-6">Pilih Kru Viar</label>
                    <select id="selectPustakawan" class="form-select" name="nik" data-control="select2" data-hide-search="true" data-placeholder="Pilih Kru Viar" required>
                        <option value="">Pilih Kru Viar</option>

                        {{-- PERBAIKAN: Menyesuaikan dengan variabel baru $kruViar --}}
                        @foreach ($kruViar as $item)
                            <option value="{{ $item->nik }}">{{ $item->nama_pustakawan }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- MODE HARI --}}
                <div class="mb-6">
                    <label class="mb-2 required fw-semibold fs-6">Mode Hari</label>
                    <div class="gap-4 d-flex flex-wrap">
                        @foreach ([
                            'satu_full'    => 'Satu Hari (Full)',
                            'satu_shift'   => 'Satu Hari (Shift)',
                            'banyak_full'  => 'Beberapa Hari (Full)',
                            'banyak_shift' => 'Beberapa Hari (Shift)',
                        ] as $key => $label)
                            <label class="form-check form-check-custom form-check-solid">
                                <input type="radio" class="form-check-input mode-hari" name="mode_hari" value="{{ $key }}" required>
                                <span class="ps-2 fw-semibold">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- TANGGAL MULAI --}}
                <div class="mb-6">
                    <label class="mb-2 required fw-semibold fs-6">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control form-control-lg" value="{{ old('tanggal_mulai') }}" required>
                </div>

                {{-- TANGGAL SELESAI --}}
                <div class="mb-6">
                    <label class="mb-2 fw-semibold fs-6">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control form-control-lg" value="{{ old('tanggal_selesai') }}">
                </div>

                {{-- SHIFT --}}
                <div class="mb-8" id="shift-wrapper">
                    <label class="mb-2 fw-semibold fs-6">Pilih Shift</label>
                    <div class="mt-3 d-flex flex-wrap">
                        @foreach (['Pagi', 'Siang', 'Malam'] as $shift)
                            <label class="form-check form-check-custom form-check-inline me-5">
                                <input type="checkbox" class="form-check-input shift-input" name="shifts[]" value="{{ $shift }}">
                                <span class="ps-2 fw-semibold">{{ $shift }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Simpan Absen</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="admin/assets/plugins/global/plugins.bundle.js"></script>
<script src="admin/assets/js/custom/utilities/modals/bidding.js"></script>

<script>
// PERBAIKAN: Sekarang mendengarkan data jadwal shift kerja Kru Viar ($viar_jadwal)
const JADWAL_PEGAWAI = @json($viar_jadwal ?? []);

document.addEventListener('DOMContentLoaded', function () {
    const selectPustakawan = document.getElementById('selectPustakawan');
    const modeHari = document.querySelectorAll('.mode-hari');
    const shiftInputs = document.querySelectorAll('.shift-input');
    const tglMulai = document.getElementById('tanggal_mulai');
    const tglSelesai = document.getElementById('tanggal_selesai');

    function resetShift() {
        shiftInputs.forEach(input => {
            input.checked = false;
            input.disabled = true;
        });
    }

    function getHariIndonesia(tanggal) {
        const hari = new Date(tanggal)
            .toLocaleDateString('id-ID', { weekday: 'long' });
        return hari.toLowerCase();
    }

    function enableShiftByTanggal() {
        resetShift();
        const nik = selectPustakawan.value;
        const tanggal = tglMulai.value;
        if (!nik || !tanggal) return;

        const hari = getHariIndonesia(tanggal);

        const jadwal = JADWAL_PEGAWAI.find(item => {
            return (
                item.nik == nik &&
                item.hari.toLowerCase() == hari
            );
        });

        if (!jadwal) return;

        shiftInputs.forEach(input => {
            const shift = input.value.toLowerCase();
            if (jadwal[shift] == 1) {
                input.disabled = false;
            }
        });
    }

    function syncTanggal() {
        const mode = document.querySelector('.mode-hari:checked')?.value;
        if (mode === 'satu_full' || mode === 'satu_shift') {
            tglSelesai.value = tglMulai.value;
            tglSelesai.setAttribute('readonly', true);
        } else {
            tglSelesai.removeAttribute('readonly');
        }
    }

    function handleModeHari(mode) {
        resetShift();

        if (mode === 'satu_full') {
            tglSelesai.disabled = true;
            syncTanggal();
        }
        else if (mode === 'satu_shift') {
            tglSelesai.disabled = true;
            syncTanggal();
            enableShiftByTanggal();
        }
        else if (mode === 'banyak_full') {
            tglSelesai.disabled = false;
        }
        else if (mode === 'banyak_shift') {
            tglSelesai.disabled = false;
            enableShiftByTanggal();
        }
    }

    shiftInputs.forEach(input => {
        input.addEventListener('change', function () {
            const mode = document.querySelector('.mode-hari:checked')?.value;
            if (mode === 'satu_shift' && this.checked) {
                shiftInputs.forEach(other => {
                    if (other !== this) other.checked = false;
                });
            }
        });
    });

    modeHari.forEach(radio => {
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
});
</script>
