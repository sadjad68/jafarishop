<a class="d-flex me-2 align-items-center"
   id="changePassword{{$id}}" data-bs-target="#changePasswordModal{{$id}}" data-bs-toggle="modal"
>
    <i class="d-flex bi bi-key color-custom2 fs-5"></i>
</a>
@push('modals')
    <div id="changePasswordModal{{$id}}" class="modal fade" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content rounded-custom border-custom shadow bg-white">
                <div class="modal-header px-3 py-2">
                    <h4 class="m-0 d-flex align-items-center">
                        تغییر رمز عبور
                        <i class="d-flex bi bi-key color-custom2 fs-5 ms-2"></i>
                    </h4>
                    <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body p-2">
                    <form @submit.prevent="validateForm" method="POST"
                          action="{{route('admin.user.change-password',['id'=>$id])}}" class="m-0">
                        @method('PATCH')
                        @csrf
                        <div class="row w-100 m-0">
                            <div class="col-lg-6 p-2">
                                <div class="form-group">
                                    <x-cms-password-input name="password" label="رمز عبور"
                                                            :validations="['requiredCms','minCms'=>6]"/>
                                </div>
                            </div>
                            <div class="col-lg-6 p-2">
                                <div class="form-group">
                                    <x-cms-password-input name="re_password" label="تکرار رمز عبور"
                                                            :validations="['requiredCms','minCms'=>6]"/>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-10 col-12 ms-auto p-2">
                                <button type="submit" class="btn btn-custom rounded-custom w-100">
                                    <i class="bi bi-save"></i>
                                    ذخیره
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush
