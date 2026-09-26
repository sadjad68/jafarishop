<div id="detailModal{{$row->id}}" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
        <div class="modal-content rounded-custom border-custom shadow bg-white">
            <div class="modal-header px-3 py-2">
                <h4 class="m-0">
                    جزییات
                </h4>
                <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body p-2">
                    <div class="row w-100 m-0">
                        <div class="col-md-4 p-2">
                            <p class="small mb-0">
                                استان
                            </p>
                            <div class="bg-light border p-2" style="border-radius: .75rem">
                                <p class="m-0">{{@$row->state->name}}</p>
                            </div>
                        </div>
                        <div class="col-md-4 p-2">
                            <p class="small mb-0">
                                شهر
                            </p>
                            <div class="bg-light border p-2" style="border-radius: .75rem">
                                <p class="m-0">{{@$row->city->name}}</p>
                            </div>
                        </div>
                        <div class="col-md-4 p-2">
                            <p class="small mb-0">
                                کد پستی
                            </p>
                            <div class="bg-light border p-2" style="border-radius: .75rem">
                                <p class="m-0">{{@$row->postal_code}}</p>
                            </div>
                        </div>
                        <div class="col-md-6 p-2">
                            <p class="small mb-0">
                                نام گیرنده
                            </p>
                            <div class="bg-light border p-2" style="border-radius: .75rem">
                                <p class="m-0">{{@$row->receiptor_full_name}}</p>
                            </div>
                        </div>
                        <div class="col-md-6 p-2">
                            <p class="small mb-0">
                                شماره گیرنده
                            </p>
                            <div class="bg-light border p-2" style="border-radius: .75rem">
                                <p class="m-0">{{@$row->receiptor_mobile}}</p>
                            </div>
                        </div>
                        <div class="col-md-12 p-2">
                            <p class="small mb-0">
                                آدرس
                            </p>
                            <div class="bg-light border p-2" style="border-radius: .75rem;height: 8rem">
                                <p class="m-0">
                                    {!! @$row->address !!}
                                </p>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
