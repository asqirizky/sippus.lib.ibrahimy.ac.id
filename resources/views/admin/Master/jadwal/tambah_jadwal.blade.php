<!--begin::Modal - Update user details-->
<div class="modal fade" id="kt_modal_update_details" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-800px">
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
            <form id="kt_modal_update_details" class="form" method="POST" enctype="multipart/form-data" action="/admin/master-jadwal">
                @csrf
                <!--begin::Heading-->
                <div class="mb-13 text-center">
                    <!--begin::Title-->
                    <h1 class="mb-3">Tambah Jadwal</h1>
                    <div class="text-muted fw-semibold fs-5">Absensi Pustakawan.</div>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->
                <!--begin::Input group-->
                <div class="fv-row mb-8">
                    <!--begin::Label-->
                    <label class="required fw-semibold fs-6 mb-2">Shift</label>
                    <!--end::Label-->
                    <!--begin::Image placeholder-->
                    <input type="text" name="jadwal" class="form-control mb-3 mb-lg-0" placeholder="Jadwal" required autocomplete="shift">
                    <!--end::Image input-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="fv-row mb-8">
                    <!--begin::Label-->
                    <label class="required fw-semibold fs-6 mb-2">Jam Masuk</label>
                    <!--end::Label-->
                    <!--begin::Image placeholder-->
                    <input class="form-control flatpickr-input" placeholder="Jam Masuk" name="jamMasuk" id="kt_datepicker_8" type="text" readonly="readonly">
                    <!--end::Image input-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="fv-row mb-8">
                    <!--begin::Label-->
                    <label class="required fw-semibold fs-6 mb-2">Jam Pulang</label>
                    <!--end::Label-->
                    <!--begin::Image placeholder-->
                    <input class="form-control flatpickr-input" placeholder="Jam Pulang" name="jamPulang" id="kt_datepicker_9" type="text" readonly="readonly">
                    <!--end::Image input-->
                </div>
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

<script src="admin/assets/plugins/global/plugins.bundle.js"></script>

<script src="admin/assets/js/custom/utilities/modals/bidding.js"></script>

<script>
    $("#kt_datepicker_8").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
});
</script>

<script>
    $("#kt_datepicker_9").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
});
</script>


{{-- @include('sweetalert::alert') --}}
