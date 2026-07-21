<!--begin::Modal - Update user details-->
<div class="modal fade" id="kt_modal_edit{{ $pustakawan->id }}" tabindex="-1" aria-hidden="true">
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
            <form method="POST" enctype="multipart/form-data" action="{{ route('master-pustakawan.update', $pustakawan->id) }}">
                @csrf
                @method('PUT')
                <!--begin::Heading-->
                <div class="text-center mb-13">
                    <!--begin::Title-->
                    <h1 class="mb-3">Edit Biodata Pustakawan</h1>
                    <div class="text-muted fw-semibold fs-5">Absensi Pustakawan.</div>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->
                <div class="fv-row mb-14">
                    <!--begin::Label-->
                    <label class="mb-5 d-block required fw-semibold fs-6">Foto</label>
                    <!--end::Label-->
                    <!--begin::Image placeholder-->
                    <style>
                        .image-input-placeholder {
                            background-image: url('{{ asset('admin/assets/media/svg/files/blank-image.svg') }}');
                        }

                        [data-bs-theme="dark"] .image-input-placeholder {
                            background-image: url('{{ asset('admin/assets/media/svg/files/blank-image-dark.svg') }}');
                        }
                    </style>
                    <!--end::Image placeholder-->
                    <!--begin::Image input-->
                    <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                        <!--begin::Preview existing avatar-->
                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $pustakawan->foto ? asset('admin/assets/media/' . $pustakawan->foto) : asset('admin/assets/media/svg/files/blank-image.svg') }}');"></div>
                        <!--end::Preview existing avatar-->
                        <!--begin::Label-->
                        <label class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Upload Foto">
                            <i class="ki-outline ki-pencil fs-7">
                            </i>
                            <!--begin::Inputs-->
                            <input type="file" name="foto" id="foto" value="blank.png" accept=".png, .jpg, .jpeg"/>
                            <input type="hidden" name="avatar_remove" />
                            <!--end::Inputs-->
                        </label>
                        <!--end::Label-->
                        <!--begin::Cancel-->
                        <span class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel Foto">
                            <i class="ki-outline ki-cross fs-2">
                            </i>
                        </span>
                        <!--end::Cancel-->
                        <!--begin::Remove-->
                        <span class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus foto">
                            <i class="ki-outline ki-cross fs-2">
                            </i>
                        </span>
                        <!--end::Remove-->
                    </div>
                    <!--end::Image input-->
                    <div class="form-text">File memiliki format: png, jpg, jpeg. Maksimal ukuran 1MB(1024kb)</div>
                    @error('kwitansi')
                        <div class="form-text text-danger">File terlalu besar</div>
                    @enderror
                </div>
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 fw-semibold fs-6">NIK</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="text" id="nikDisplay{{ $pustakawan->id }}" class="form-control form-control-solid form-control-lg" value="{{ $pustakawan->nik }}" required readonly>
                    <input type="hidden" id="nikHidden{{ $pustakawan->id }}" name="nik" value="{{ $pustakawan->nik }}">
                    <div id="nikPreview{{ $pustakawan->id }}" class="form-text text-info d-none">
                        NIK akan berubah jika jabatan diganti
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 fw-semibold fs-6">Nama Pustakawan</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="text" name="nama_pustakawan" class="form-control form-control-lg" value="{{ $pustakawan->nama_pustakawan }}">
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Tempat Lahir</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="text" name="tempat_lahir" class="form-control form-control-lg" value="{{ $pustakawan->tempat_lahir }}" required>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Tanggal Lahir</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="date" name="tgl_lahir" class="form-control form-control-lg" value="{{ $pustakawan->tgl_lahir }}" required>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Terhitung Mulai Tanggal</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="date" name="tmt" class="form-control form-control-lg struktural" value="{{ $pustakawan->tmt }}" required>
                    <!--end::Col-->
                    <div class="form-text">Khusus struktural</div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 fw-semibold fs-6">Terhitung Mulai Tanggal Mengajar</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="date" name="tmt_mengajar" class="form-control form-control-lg struktural" value="{{ $pustakawan->tmt_mengajar }}">
                    <!--end::Col-->
                    <div class="form-text">Khusus struktural</div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 fw-semibold fs-6">Tanggal Mulai Mengabdi</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="date" name="tgl_khidmah" class="form-control form-control-lg khidmah" value="{{ $pustakawan->tgl_khidmah }}">
                    <!--end::Col-->
                    <div class="form-text">Khusus tenaga khidmah</div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Domisili</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="text" class="form-control form-control-solid form-control-lg" value="{{ ucwords(strtolower($pustakawan->domisili)) }}" readonly>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Pendidikan Terakhir</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="mb-8 fv-row">
                        <select class="form-select struktural" name="pend_terakhir" value="" data-control="select2" data-hide-search="true" data-placeholder="Pilih Keterangan" required >
                            <option value="SD" {{ $pustakawan->pend_terakhir == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP/STLP" {{ $pustakawan->pend_terakhir == 'SMP/STLP' ? 'selected' : '' }}>SMP/STLP</option>
                            <option value="SMA/STLA" {{ $pustakawan->pend_terakhir == 'SMA/STLA' ? 'selected' : '' }}>SMA/STLA</option>
                            <option value="S1" {{ $pustakawan->pend_terakhir == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ $pustakawan->pend_terakhir == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ $pustakawan->pend_terakhir == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Status Perkawinan</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="mb-8 fv-row">
                        <select class="form-select" name="status_perkawinan" value="" data-control="select2" data-hide-search="true" data-placeholder="Pilih Keterangan" required >
                            <option value="Belum Menikah" {{ $pustakawan->status_perkawinan == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                            <option value="Menikah" {{ $pustakawan->status_perkawinan == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                        </select>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Jenis Kelamin</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="mb-8 fv-row">
                        <select class="form-select" name="jk" value="" data-control="select2" data-hide-search="true" data-placeholder="Pilih Keterangan" required >
                            <option value="Laki-laki" {{ $pustakawan->jk == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $pustakawan->jk == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Ruang</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="mb-8 fv-row">
                        <select class="form-select" name="ruang_id" data-control="select2" data-hide-search="true" data-placeholder="Pilih Keterangan" required >
                            @foreach ($ruangs as $item)
                                <option value="{{ $item->id }}" {{old('ruang_id', $pustakawan->ruang_id) == $item->id ? 'selected' : ''}}>{{ $item->ruang_pustakawans }} </option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Jabatan</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="mb-8 fv-row">
                        <select class="form-select" id="jabatanSelect{{ $pustakawan->id }}" name="jabatan_id" value="" data-control="select2" data-hide-search="true" data-placeholder="Pilih Jabatan" required >
                            <option value="" disabled selected>Pilih Jabatan</option>
                            @foreach ($jabatan as $jab)
                                <option value="{{ $jab->id }}"
                                    data-eselon="{{ $jab->eselon }}"
                                    {{ old('jabatan_id', $pustakawan->jabatan_id ?? '') == $jab->id ? 'selected' : '' }}>
                                    {{ $jab->nama_jabatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <div class="mb-8 text-start fv-row">
                    <label class="mb-2 fw-semibold fs-6">Nomor HP</label>

                    <input type="text"
                        class="form-control"
                        name="no_wa"
                        id="no_wa"
                        placeholder="08511161997"
                        value="{{ old('no_wa', $pustakawan->no_wa) }}"
                        inputmode="numeric"
                        pattern="[0-9]*">

                    <small class="text-muted">
                        Contoh: 08511161997
                    </small>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 fw-semibold fs-6">Asrama</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="text" name="asrama" class="form-control form-control-lg khidmah" value="{{ $pustakawan->asrama }}">
                    <!--end::Col-->
                    <div class="form-text">Khusus tenaga khidmah</div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Sekolah Pagi</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="mb-8 fv-row">
                        <select class="form-select khidmah" name="sekolah_pagi" data-control="select2" data-hide-search="true" required >
                            <option disabled selected>Pilih Sekolah Pagi</option>
                            <option value="Madrasah Ibtidaiyah" {{ $pustakawan->sekolah_pagi == 'Madrasah Ibtidaiyah' ? 'selected' : '' }}>Madrasah Ibtida'iyah</option>
                            <option value="Madrasah Tsanawiyah" {{ $pustakawan->sekolah_pagi == 'Madrasah Tsanawiyah' ? 'selected' : '' }}>Madrasah Tsanawiyah</option>
                            <option value="Madrasah Aliyah" {{ $pustakawan->sekolah_pagi == 'Madrasah Aliyah' ? 'selected' : '' }}>Madrasah Aliyah</option>
                            <option value="Madrasah Tahiliyah" {{ $pustakawan->sekolah_pagi == 'Madrasah Tahiliyah' ? 'selected' : '' }}>Madrasah Tahiliyah</option>
                            <option value="Madrasah Idadiyah" {{ $pustakawan->sekolah_pagi == 'Madrasah Idadiyah' ? 'selected' : '' }}>Madrasah Idadiyah</option>
                            <option value="Non Pendidikan" {{ $pustakawan->sekolah_pagi == 'Non Pendidikan' ? 'selected' : '' }}>Non Pendidikan</option>
                        </select>
                        <div class="form-text">khusus tenaga khidmah</div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 text-start fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Sekolah Sore</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="mb-8 fv-row">
                        <select class="form-select khidmah" name="sekolah_sore" data-control="select2" data-hide-search="true" required >
                            <option disabled selected>Pilih Sekolah Sore</option>
                            <option value="Fakultas Tarbiyah" {{ $pustakawan->sekolah_sore == 'Fakultas Tarbiyah' ? 'selected' : '' }}>Fakultas Tarbiyah</option>
                            <option value="Fakultas Dakwah dan Ushuluddin" {{ $pustakawan->sekolah_sore == 'SMA/SMK/STLA' ? 'selected' : '' }}>Fakultas Dakwah dan Ushuluddin</option>
                            <option value="Fakultas Sains dan Teknologi" {{ $pustakawan->sekolah_sore == 'Fakultas Sains dan Teknologi' ? 'selected' : '' }}>Fakultas Sains dan Teknologi</option>
                            <option value="Fakultas Ilmu Kesehatan" {{ $pustakawan->sekolah_sore == 'Fakultas Ilmu Kesehatan' ? 'selected' : '' }}>Fakultas Ilmu Kesehatan</option>
                            <option value="Fakultas Ilmu Sosial dan Humaniora" {{ $pustakawan->sekolah_sore == 'Fakultas Ilmu Sosial dan Humaniora' ? 'selected' : '' }}>Fakultas Ilmu Sosial dan Humaniora</option>
                            <option value="Pasca Sarjana" {{ $pustakawan->sekolah_sore == 'Pasca Sarjana' ? 'selected' : '' }}>Pasca Sarjana</option>
                            <option value="Non Pendidikan" {{ $pustakawan->sekolah_sore == 'Non Pendidikan' ? 'selected' : '' }}>Non Pendidikan</option>
                        </select>
                        <div class="form-text">khusus tenaga khidmah</div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 fv-row">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Status</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="gap-5 d-flex align-items-center">
                        <!--begin::Switch-->
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input" name="status" type="checkbox" value="{{ $pustakawan->status }}"  {{ $pustakawan->status == '1' ? 'checked' : '' }} >
                            <span id="statusLabel" class="form-check-label fw-semibold text-muted">
                                {{ $pustakawan->status == '1' ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    <!--end::Switch-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <div class="text-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitEditBtn{{ $pustakawan->id }}">
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

<!--<script src="admin/assets/plugins/global/plugins.bundle.js"></script>-->
<!--<script src="admin/assets/js/custom/utilities/modals/bidding.js"></script>-->

{{-- @include('sweetalert::alert') --}}
<script>
function formatNomorHP(number) {
    // contoh: 81234567890 -> 812-3456-7890
    return number
        .replace(/\D/g, '')
        .replace(/^0/, '')
        .replace(/(\d{3})(\d{4})(\d{0,4})/, function(_, a, b, c) {
            return c ? `${a}-${b}-${c}` : `${a}-${b}`;
        });
}

const input = document.getElementById('no_wa');
const preview = document.getElementById('preview_no_wa');

input.addEventListener('input', function () {
    let val = input.value.replace(/\D/g, '');

    if (val.startsWith('0')) {
        val = val.substring(1);
    }

    input.value = val;

    if (val.length >= 3) {
        preview.innerText = '+62 ' + formatNomorHP(val);
    } else {
        preview.innerText = '';
    }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('#kt_modal_edit{{ $pustakawan->id }} form');
    const submitBtn = document.getElementById('submitEditBtn{{ $pustakawan->id }}');

    if (form && submitBtn) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Simpan Perubahan?',
                text: 'Data biodata pustakawan akan diperbarui.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    submitBtn.classList.add('btn-disabled');
                    submitBtn.disabled = true;
                    submitBtn.querySelector('.indicator-label').classList.add('d-none');
                    submitBtn.querySelector('.indicator-progress').classList.remove('d-none');
                    form.submit();
                }
            });
        });
    }
});
</script>

<script>
    function updateStatusLabel() {
        const checkbox = document.getElementById('statusCheckbox');
        const label = document.getElementById('statusLabel');
        label.textContent = checkbox.checked ? 'Aktif' : 'Tidak Aktif';
    }

document.addEventListener("DOMContentLoaded", function () {
    const jabatanSelect = document.getElementById("jabatanSelect{{ $pustakawan->id }}");
    const nikDisplay = document.getElementById("nikDisplay{{ $pustakawan->id }}");
    const nikPreview = document.getElementById("nikPreview{{ $pustakawan->id }}");
    const currentNik = '{{ $pustakawan->nik }}';
    const currentJabatanId = '{{ $pustakawan->jabatan_id }}';

    function setFieldState(elements, disabled) {
        elements.forEach(el => {
            el.disabled = disabled;

            if (disabled) {
                el.classList.add('form-control-solid');
            } else {
                el.classList.remove('form-control-solid');
            }
        });
    }

    function updateNikPreview() {
        const selectedOption = jabatanSelect.options[jabatanSelect.selectedIndex];
        const newJabatanId = jabatanSelect.value;
        const newEselon = selectedOption.getAttribute('data-eselon');

        if (newJabatanId && newEselon !== null && newJabatanId != currentJabatanId) {
            const tahun = currentNik.substring(0, 4);
            const urut = currentNik.substring(currentNik.length - 3);
            const newNik = tahun + newEselon + urut;

            nikDisplay.value = newNik;
            nikPreview.classList.remove('d-none');
            nikPreview.textContent = 'NIK akan berubah: ' + currentNik + ' → ' + newNik;
        } else {
            nikDisplay.value = currentNik;
            nikPreview.classList.add('d-none');
        }
    }

    function toggleField() {
        const selectedText = jabatanSelect.options[jabatanSelect.selectedIndex].text.toLowerCase();
        const isKhidmah = selectedText.includes('khidmah');

        const struktural = document.querySelectorAll('.struktural');
        const khidmah = document.querySelectorAll('.khidmah');

        if (isKhidmah) {
            setFieldState(struktural, true);   // disable struktural
            setFieldState(khidmah, false);     // enable khidmah
        } else {
            setFieldState(struktural, false);  // enable struktural
            setFieldState(khidmah, true);      // disable khidmah
        }
    }

    toggleField();
    updateNikPreview();
    jabatanSelect.addEventListener('change', function() {
        toggleField();
        updateNikPreview();
    });
});
</script>
