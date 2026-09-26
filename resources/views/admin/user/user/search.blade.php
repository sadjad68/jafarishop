<div id="searchModal" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-custom border-custom shadow bg-white">
            <div class="modal-header px-3 py-2">
                <h4 class="m-0">
                    جستجو
                </h4>
                <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body p-2">
                <form method="GET" action="{{URL::current()}}" class="m-0">
                    @if(request()->has('type'))
                        <input name="type" value="{{request()->get('type')}}" type="hidden" />
                    @endif
                    <div class="row w-100 m-0">
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <x-cms-input
                                    name="full_name"
                                    label="نام و نام خانوادگی"
                                    type="text"
                                />
                            </div>
                        </div>
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <x-cms-input
                                    name="mobile"
                                    label="شماره موبایل"
                                    type="text"
                                />
                            </div>
                        </div>
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <x-cms-input
                                    name="email"
                                    label="ایمیل"
                                    type="email"
                                />
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-10 col-12 ms-auto p-2">
                            <button type="submit" class="btn btn-custom rounded-custom w-100">
                                <i class="bi bi-search"></i>
                                جستجو
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
