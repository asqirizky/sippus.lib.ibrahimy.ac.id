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
            <form id="kt_modal_update_details" class="form" method="POST" enctype="multipart/form-data" action="{{ asset('/admin/pengguna/ubahpassword('.$item->id.')') }}">
                @csrf
                <!--begin::Heading-->
                <div class="mb-5 text-center">
                    <!--begin::Title-->
                    <h1 class="mb-3">Ubah Password</h1>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->
                <!--begin::Input group-->
                <div class="text-center fv-row mb-2">
                    <!--begin::Label-->
                    <div class="card-body d-flex flex-center flex-column">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-125px symbol-circle mb-4">
                            <img src="{{ asset('/storage/foto/' . $item->foto) }}" alt="image" />
                        </div>
                        <!--end::Avatar-->
                        <!--begin::Name-->
                        <a href="#" class="fs-2 text-gray-800 text-hover-primary fw-bold mb-2">{{ $item->name }}</a>
                        <!--end::Name-->
                    </div>
                    <!--end::Hint-->
                </div>
                <!--end::Input group-->
                <!--end::Input group-->
                <div class="fv-row mb-7">
                    <!--begin::Label-->
                    <label class="required fw-bold fs-6 mb-2">Password Baru</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="password" id="password" name="password" class="form-control mb-3 mb-lg-0" placeholder="Password" required autocomplete="password" autofocus />
                    <!--end::Input-->
                </div>
                <div class="fv-row mb-15">
                    <!--begin::Label-->
                    <label class="required fw-bold fs-6 mb-2">Konfirmasi Password Baru</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input id="password-confirm" type="password" name="password_confirmation" class="form-control mb-3 mb-lg-0" placeholder="Konfirmasi Password" required autocomplete="password-confirm"/>
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

</script>

{{-- <script src="admin/assets/js/custom/utilities/modals/bidding.js"></script> --}}


{{-- @include('sweetalert::alert') --}}
