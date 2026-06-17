<!--begin::Modal - New Target-->
<div class="modal fade" id="kt_modal_new_target{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header pb-0 border-0 justify-content-end">
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <!--begin:Form-->
                <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('master-jadwal.update',$item->id) }}">
                    @csrf
                    @method('PUT')
                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <!--begin::Title-->
                        <h1 class="mb-3">Edit Jadwal</h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <div class="text-muted fw-semibold fs-5">Absensi Pustakawan</div>
                        <!--end::Description-->
                    </div>
                    <!--end::Heading-->
                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-8 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2 required">Jadwal</label>
                        <!--end::Label-->
                        <input type="text" class="form-control" value="{{ $item->jadwal}}" placeholder="Shift" name="jadwal" />
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-8">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Jam Masuk</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Image placeholder-->
                        <input class="form-control flatpickr-input" name="jamMasuk" value="{{ $item->jamMasuk }}" placeholder="Jam Masuk" id="kt_datepicker_10" type="text" >
                        <!--end::Image input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-8">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Jam Pulang</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Image placeholder-->
                        <input class="form-control flatpickr-input" name="jamPulang" value="{{ $item->jamPulang }}" placeholder="Jam Pulang" id="kt_datepicker_11" type="text" >
                        <!--end::Image input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="reset" id="kt_modal_new_target_cancel" class="btn btn-light me-3">Cancel</button>
                        <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
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
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - New Target-->

{{-- JS --}}
<script>
    $("#kt_datepicker_10").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
});
</script>

<script>
    $("#kt_datepicker_11").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
});
</script>
