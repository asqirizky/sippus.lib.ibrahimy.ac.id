<!--begin::Modal - Update user details-->
<div class="modal fade" id="kt_modal_new_target" tabindex="-1" aria-hidden="true">
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
               <form method="POST" enctype="multipart/form-data" action="{{ route('pustakawan.upBerkas', $pustakawan->id) }}">
                    @csrf
                    <div class="fv-row mb-14">
                        <label class="mb-5 d-block required fw-semibold fs-6">Ijazah/Sertifikat</label>
                        <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                            <div class="image-input-wrapper w-125px h-125px"></div>
                            <label class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                data-kt-image-input-action="change" title="Upload Foto">
                                <i class="ki-outline ki-pencil fs-7"></i>
                                <input type="file" name="berkas" accept=".png, .jpg, .jpeg" required />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                            <span class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                data-kt-image-input-action="remove" title="Hapus foto">
                                <i class="ki-outline ki-cross fs-2"></i>
                            </span>
                        </div>
                        <div class="form-text">File format: png, jpg, jpeg. Maksimal 1MB</div>
                    </div>

                    <div class="mb-8 text-start fv-row">
                        <label class="mb-2 fw-semibold fs-6">Keterangan</label>
                        <input type="text" class="form-control form-control-lg" name="keterangan" placeholder="Keterangan" required>
                    </div>

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
    </div>
</div>
<!--end::Modal - Update user details-->

<!--<script src="admin/assets/plugins/global/plugins.bundle.js"></script>-->
<!--<script src="admin/assets/js/custom/utilities/modals/bidding.js"></script>-->

{{-- @include('sweetalert::alert') --}}

