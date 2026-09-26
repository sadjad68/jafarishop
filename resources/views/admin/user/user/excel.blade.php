<div id="excelModal" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-custom border-custom shadow bg-white">
            <div class="modal-header px-3 py-2">
                <h4 class="m-0">
                    خروجی اکسل
                </h4>
                <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body p-2">
                <form method="GET" action="{{route('admin.user.export')}}" class="m-0">
                    <div class="row w-100 m-0">
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <x-cms-input
                                    name="min_orders"
                                    label="کمترین تعداد سفارش"
                                    type="text"
                                />
                            </div>
                        </div>
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <x-cms-input
                                    name="max_orders"
                                    label="بیشترین تعداد سفارش"
                                    type="text"
                                />
                            </div>
                        </div>
                        <div class="col-lg-4 p-2 mt-3">
                            <div class="form-group">
                                <x-cms-check-box
                                    name="has_address"
                                    label=" دارای آدرس"
                                    :valueData="@$data"
                                    :value="0"
                                />
                            </div>
                        </div>
                        <div class="col-md-6 p-1">
                            <input class="form-control form-control-sm mb-2 rounded-custom"
                                   type="text" id="datepicker_start" name="start"
                                   placeholder="تاریخ شروع" />
                        </div>
                        <div class="col-md-6 p-1">
                            <input class="form-control form-control-sm mb-2 rounded-custom"
                                   type="text" id="datepicker_date" name="end"
                                   placeholder="تاریخ پایان" />
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-4">خروجی</button>

                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{asset('assets/admin/js/bootstrap-datepicker.min.js?v0.01')}}"></script>
    <script src="{{asset('assets/admin/js/bootstrap-datepicker.fa.min.js?v0.01')}}"></script>
    <script>
        $(document).ready(function() {
            $("#datepicker_start").datepicker({
                changeMonth: true,
                changeYear: true
            });
            $("#datepicker_date").datepicker({
                changeMonth: true,
                changeYear: true
            });
        });
    </script>
@endpush
