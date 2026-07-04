<!--begin::Modal - Update user details-->
<div class="modal fade" id="kt_modal_ubahpassword{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <!--begin::Modal content-->
        <div class="modal-content">
        <div class="modal-header pb-0 border-0 justify-content-end">
            <!--begin::Close-->
            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                <i class="ki-outline ki-cross fs-1">
                </i>
            </div>
            <!--end::Close-->
        </div>
        <!--begin::Modal header-->
        <!--begin::Modal body-->
        <div class="modal-body scroll-y px-15 px-lg-15 pt-0 pb-15">
            <!--begin:Form-->
            <form id="kt_modal_update_details" class="form" method="POST" enctype="multipart/form-data" action="{{ url('/admin/pengguna/ubahpassword/'.$item->id) }}">
                @csrf
                <!--begin::Heading-->
                <div class="mb-5 text-center">
                    <!--begin::Title-->
                    <h1 class="mb-3">Ubah Password</h1>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->
                <!--begin::Input group-->
                <div class="text-center fv-row mb-7">
                    <!--begin::Label-->
                    <label class="d-block fw-semibold-solid fs-6 mb-5">{{ $item->pustakawan->nama_pustakawan }}</label>
                    <!--end::Label-->
                    <!--begin::Image placeholder-->
                    <style>.image-input-placeholder { background-image: url('admin/assets/media/svg/files/blank-image.svg'); } [data-bs-theme="dark"] .image-input-placeholder { background-image: url('admin/assets/media/svg/files/blank-image-dark.svg'); }</style>
                    <!--end::Image placeholder-->
                    <!--begin::Image input-->
                    <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                        <!--begin::Preview existing avatar-->
                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ asset('/admin/assets/media/' . ($item->pustakawan->foto ?? 'default.png')) }});"></div>
                        <!--end::Preview existi-->
                    </div>
                    <!--end::Image input-->
                </div>
                <!--end::Input group-->
                <!--end::Input group-->
                <!--begin::Main wrapper-->
                <div class="fv-row" data-kt-password-meter="true">
                    <!--begin::Wrapper-->
                    <div class="mb-1">
                        <!--begin::Label-->
                        <label class="form-label fw-semibold fs-6 mb-2">
                            Password Baru
                        </label>
                        <!--end::Label-->
                        <!--begin::Input wrapper-->
                        <div class="position-relative mb-3">
                            <input class="form-control form-control-lg password-input"
                                type="password" placeholder="Password Baru" name="password" id="password-{{ $item->id }}" autocomplete="off" />
                            <!--begin::Visibility toggle-->
                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                data-kt-password-meter-control="visibility">
                                    <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                            <!--end::Visibility toggle-->
                        </div>
                        <!--end::Input wrapper-->
                        <!--begin::Highlight meter-->
                        <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                            <div class="meter-bar flex-grow-1 bg-secondary bg-active-danger rounded h-5px me-2"></div>
                            <div class="meter-bar flex-grow-1 bg-secondary bg-active-warning rounded h-5px me-2"></div>
                            <div class="meter-bar flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="meter-bar flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                        </div>
                        <!--end::Highlight meter-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Hint-->
                    <div class="text-muted">
                        Gunakan 8 karakter atau lebih dengan campuran huruf, angka atau simbol.
                    </div>
                    <div class="password-message text-danger fs-7 mt-2" style="display: none;">Password minimal 8 karakter</div>
                    <!--end::Hint-->
                </div>
                <!--end::Main wrapper-->
                <div class="fv-row mb-15">
                    <!--begin::Label-->
                    <label class="required fw-bold fs-6 mb-2">Konfirmasi Password Baru</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input id="password-confirm-{{ $item->id }}" type="password" name="password_confirmation" class="form-control mb-3 mb-lg-0 password-confirm-input" placeholder="Konfirmasi Password" required autocomplete="password-confirm"/>
                    <div class="password-confirm-message text-danger fs-7 mt-2" style="display: none;">Password tidak cocok</div>
                    <!--end::Input-->
                </div>
                <!--begin::Actions-->
                <!--end::Input group-->
                <div class="text-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                    {{--  <button class="btn btn-primary" >  --}}
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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

{{-- <script src="admin/assets/plugins/global/plugins.bundle.js"></script> --}}


<script>
    $("#kt_datepicker_2").flatpickr({
        dateFormat: "j F Y",
    });

    document.querySelectorAll('.password-input').forEach(function(input) {
        const modal = input.closest('.modal');
        const meterBars = modal.querySelectorAll('.meter-bar');
        const message = modal.querySelector('.password-message');
        const confirmInput = modal.querySelector('.password-confirm-input');
        const confirmMessage = modal.querySelector('.password-confirm-message');

        function checkPasswordMatch() {
            if (confirmInput.value.length === 0) {
                confirmMessage.style.display = 'none';
                return;
            }
            if (input.value !== confirmInput.value) {
                confirmMessage.style.display = 'block';
            } else {
                confirmMessage.style.display = 'none';
            }
        }

        input.addEventListener('input', function () {
            const password = this.value;
            const length = password.length;

            meterBars.forEach(bar => {
                bar.classList.remove('bg-danger', 'bg-warning', 'bg-success');
                bar.classList.add('bg-secondary');
            });

            if (length === 0) {
                message.style.display = 'none';
                checkPasswordMatch();
                return;
            }

            if (length < 8) {
                meterBars.forEach(bar => {
                    bar.classList.remove('bg-secondary');
                    bar.classList.add('bg-danger');
                });
                message.style.display = 'block';
                checkPasswordMatch();
                return;
            }

            message.style.display = 'none';

            const hasLower = /[a-z]/.test(password);
            const hasUpper = /[A-Z]/.test(password);
            const hasDigit = /\d/.test(password);
            const hasSymbol = /[^a-zA-Z0-9]/.test(password);
            const types = [hasLower, hasUpper, hasDigit, hasSymbol].filter(Boolean).length;

            if (types >= 3) {
                meterBars.forEach(bar => {
                    bar.classList.remove('bg-secondary');
                    bar.classList.add('bg-success');
                });
            } else {
                meterBars.forEach(bar => {
                    bar.classList.remove('bg-secondary');
                    bar.classList.add('bg-warning');
                });
            }

            checkPasswordMatch();
        });

        confirmInput.addEventListener('input', checkPasswordMatch);
    });
</script>

{{-- <script src="admin/assets/js/custom/utilities/modals/bidding.js"></script> --}}


{{-- @include('sweetalert::alert') --}}
