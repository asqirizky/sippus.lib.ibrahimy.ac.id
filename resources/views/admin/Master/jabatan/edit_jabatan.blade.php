<!--begin::Modal - Update user details-->
<div class="modal fade" id="kt_modal_new_target{{ $item->id }}" tabindex="-1" aria-hidden="true">
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
            <form id="kt_modal_update_details" class="form" method="POST" enctype="multipart/form-data" action="{{ route('master-jabatan.update',$item->id) }}">
                @csrf
                @method('PUT')
                <!--begin::Heading-->
                <div class="text-center mb-13">
                    <!--begin::Title-->
                    <h1 class="mb-3">Tambah Jabatan</h1>
                    <div class="text-muted fw-semibold fs-5">Kehadiran Umana Perpustakaan Ibrahimy.</div>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->
                <!--begin::Input group-->
                <div class="mb-8 fv-row text-start">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Eselon</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="text" class="form-control form-control-lg" name="eselon" placeholder="Jabatan" value="{{ $item->eselon }}" required>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 fv-row text-start">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Jabatan</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <input type="text" class="form-control form-control-lg" name="nama_jabatan" placeholder="Jabatan" value="{{ $item->nama_jabatan }}" required>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8 fv-row text-start">
                    <!--begin::Label-->
                    <label class="mb-2 required fw-semibold fs-6">Status</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="gap-5 d-flex align-items-center">
                        <!--begin::Switch-->
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input" name="status" type="checkbox" value="{{ $item->status }}"  {{ $item->status == '1' ? 'checked' : '' }} >
                            <span id="statusLabel" class="form-check-label fw-semibold text-muted">
                                {{ $item->status == '1' ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                        <!--end::Switch-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
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
            <!--end:Form-->
        </div>
        <!--end::Modal body-->

            <!--end::Form-->
        </div>
    </div>
</div>
<!--end::Modal - Update user details-->

{{-- <script src="admin/assets/plugins/global/plugins.bundle.js"></script>
<script src="admin/assets/js/custom/utilities/modals/bidding.js"></script> --}}

{{-- @include('sweetalert::alert') --}}
