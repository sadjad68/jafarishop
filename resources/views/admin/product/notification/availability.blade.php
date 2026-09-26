@extends('admin._layouts.master')
@section('title', 'درخواست‌های موجودی')
@section('content')
    <div class="body d-flex py-3" id="cms-form">
        <div class="container-fluid">
            <div class="page-header">
                <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                    <h3 class="fw-bolder mb-0">درخواست‌های موجودی کالا</h3>

                    <div
                        class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100 mt-3">
                        <!-- فرم جستجو -->
                        <form action="{{ route('admin.availability_notification.index') }}" method="GET"
                              class="d-flex mb-2 mb-md-0">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                       placeholder="جستجو بر اساس نام محصول..."
                                       value="{{ request('search') }}"
                                       style="min-width: 250px;">
                                <button class="btn btn-custom-b" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('admin.availability_notification.index') }}"
                                       class="btn btn-outline-secondary" title="حذف فیلتر">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </form>

                        <div class="d-flex align-items-center">
                            <button type="button" onclick="submitBulkForm()"
                                    class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                                <i class="bi bi-send-check d-flex h5 my-0 me-2"></i>
                                ارسال پیامک به انتخاب شده‌ها
                            </button>
                        </div>

                        <div class="d-flex align-items-center">
                            <a href="{{ route('admin.availability_notification.export', request()->all()) }}"
                               class="btn ms-2 my-2 btn-outline-success rounded-custom d-flex align-items-center w-fit">
                                <i class="bi bi-file-earmark-excel d-flex h5 my-0 me-2"></i>
                                خروجی اکسل
                            </a>
                        </div>

                        <ul class="list-inline align-items-center m-0">
                            <li class="list-inline-item mx-0">
                                <span class="badge bg-label-info p-2 rounded-custom">
                                    تعداد کل درخواست‌ها: {{ $items->total() }}
                                </span>
                            </li>
                        </ul>

                        <div class="d-flex align-items-center">
                            @component('admin.components.video-button')
                                @slot('type', 'product_notification')
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="card-block row">
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="form-control">
                            <form action="{{ route('admin.availability_notification.bulk-send') }}" method="POST"
                                  id="bulk-form">
                                @csrf
                                <div class="table-responsive d-flex align-items-center">
                                    <table id="myDataTable" class="table align-middle border-custom mb-0">
                                        <thead class="text-center text-light">
                                        <tr>
                                            <th class="fw-bolder">
                                                <input type="checkbox" class="form-check-input" id="select-all">
                                            </th>
                                            <th class="fw-bolder">کاربر</th>
                                            <th class="fw-bolder">شماره تماس</th>
                                            <th class="fw-bolder">محصول / متغیر</th>
                                            <th class="fw-bolder">وضعیت ارسال</th>
                                            <th class="fw-bolder">تاریخ درخواست</th>
                                            <th class="fw-bolder">عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody class="text-center text-light">
                                        @forelse($items as $key => $item)
                                            <tr>
                                                <th>
                                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                                           class="form-check-input item-checkbox">
                                                </th>
                                                <td>
                                                    <div
                                                        class="fw-bold">{{ $item->user->full_name ?? $item->user->name ?? 'کاربر ناشناس' }}</div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-label-primary fs-6">{{ $item->user->mobile ?? '-' }}</span>
                                                </td>
                                                <td style="white-space:normal">
                                                    @if($item->product)
                                                        <a href="{{ \App\Library\SiteUrl::product($item->product) }}"
                                                           target="_blank" class="text-dark fw-bold d-block mb-1">
                                                            {{ $item->product->title }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted italic">محصول حذف شده</span>
                                                    @endif
                                                    @if($item->variant)
                                                        <div class="d-flex justify-content-center">
                                                            <span
                                                                class="badge bg-label-secondary border text-dark px-2 py-1"
                                                                style="font-size: 0.75rem; border-style: dashed !important;">
                                                                <i class="bi bi-layers-half me-1"></i>
                                                                متغیر:
                                                                <span
                                                                    class="fw-bolder">{{ $item->variant->variant_title }}</span>
                                                            </span>
                                                        </div>
                                                    @else
                                                        @if(!empty($item->product_variant_id))
                                                            <small class="text-warning" style="font-size: 10px;">(متغیر
                                                                یافت نشد: #{{ $item->product_variant_id }})</small>
                                                        @else
                                                            <small class="text-muted" style="font-size: 10px;">(محصول
                                                                ساده / بدون متغیر)</small>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->is_sent)
                                                        <span class="badge bg-label-success" data-bs-toggle="tooltip"
                                                              title="{{ $item->sent_at }}">
                                                                <i class="bi bi-check2-all me-1"></i> ارسال شده
                                                            </span>
                                                        <div class="small text-muted"
                                                             style="font-size: 10px">{{ $item->sent_at }}</div>
                                                    @else
                                                        <span class="badge bg-label-warning">
                                                                <i class="bi bi-clock me-1"></i> در انتظار
                                                            </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="small">{{ $item->getJalaliCreateAtWithHour() }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a class="d-flex me-2 align-items-center"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-title="حذف"
                                                           onclick="confirmDelete('{{ route('admin.availability_notification.delete', ['id' => $item->id]) }}')"
                                                           href="javascript:void(0)">
                                                            <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <span class="text-muted">موردی یافت نشد.</span>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </form>

                            @component('admin.components.pagination.default')
                                @slot('paginator', $items)
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if(session('warning'))
        <script>Swal.fire("{{ session('warning') }}")</script>
    @endif
    @include('admin._layouts.blocks.utils.confirmDelete')
    <script>
        document.getElementById('select-all').onclick = function () {
            let checkboxes = document.querySelectorAll('.item-checkbox');
            for (let checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }

        function submitBulkForm() {
            let selected = document.querySelectorAll('.item-checkbox:checked').length;

            if (selected === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'توجه',
                    text: 'لطفاً حداقل یک مورد را انتخاب کنید.',
                    confirmButtonText: 'متوجه شدم',
                    confirmButtonColor: '#3085d6',
                    direction: 'rtl',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: true,
                    timer: 3000
                });
                return;
            }

            Swal.fire({
                title: 'آیا اطمینان دارید؟',
                html: 'آیا از ارسال پیامک به <b>' + selected + '</b> مورد انتخاب شده اطمینان دارید؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'بله، ارسال شود',
                cancelButtonText: 'انصراف',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                direction: 'rtl',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('bulk-form').submit();
                }
            });
        }
    </script>
@endpush
