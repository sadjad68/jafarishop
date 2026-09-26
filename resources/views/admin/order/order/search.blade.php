<div id="searchModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-custom border-custom shadow">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="bi bi-search"></i>
                    جستجوی پیشرفته سفارش‌ها
                </h4>
                <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="بستن">
                    <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{ URL::current() }}" class="admin-order-filter">
                    <input type="hidden" name="filter">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <x-cms-input name="id" label="شماره سفارش" type="text" />
                        </div>
                        <div class="col-lg-4">
                            <x-cms-input name="full_name" label="نام و نام خانوادگی کاربر" type="text" />
                        </div>
                        <div class="col-lg-4">
                            <x-cms-input name="mobile" label="شماره تماس کاربر" type="text" />
                        </div>
                        <div class="col-lg-4">
                            <label class="admin-label">روش ارسال</label>
                            <select name="shipping_method_id" class="form-select rounded-custom">
                                <option value="">همه روش‌ها</option>
                                @foreach($shipping_methods as $shipping_method)
                                    <option value="{{ $shipping_method->id }}">{{ $shipping_method->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="admin-label">وضعیت سفارش</label>
                            <select name="shipping_status_id" class="form-select rounded-custom">
                                <option value="">همه وضعیت‌ها</option>
                                @foreach($shipping_statuses as $shipping_status)
                                    <option value="{{ $shipping_status->id }}">{{ $shipping_status->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="admin-label">وضعیت پرداخت</label>
                            <select name="order_status" class="form-select rounded-custom">
                                <option value="">همه</option>
                                <option value="paying">در حال پرداخت</option>
                                <option value="paid">پرداخت شده</option>
                                <option value="deposit_paid">بیعانه پرداخت شده</option>
                                <option value="wait_for_verification">در انتظار تایید پرداخت</option>
                                <option value="unpaid">پرداخت نشده</option>
                                <option value="cancelled">لغو شده</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <x-cms-input name="transactionId" label="کد پیگیری اسنپ‌پی" type="text" />
                        </div>
                        <div class="col-lg-4">
                            <label class="admin-label">از تاریخ</label>
                            <input class="form-control rounded-custom" type="text" id="datepicker1" name="from_date" placeholder="از تاریخ">
                        </div>
                        <div class="col-lg-4">
                            <label class="admin-label">تا تاریخ</label>
                            <input class="form-control rounded-custom" type="text" id="datepicker2" name="to_date" placeholder="تا تاریخ">
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-custom rounded-custom px-4">
                                <i class="bi bi-search"></i>
                                اعمال فیلتر
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/admin/js/bootstrap-datepicker.min.js?v0.01') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-datepicker.fa.min.js?v0.01') }}"></script>
    <script>
        $(document).ready(function () {
            $('#datepicker1, #datepicker2').datepicker({
                changeMonth: true,
                changeYear: true
            });
        });
    </script>
@endpush
