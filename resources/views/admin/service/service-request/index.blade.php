@extends('admin._layouts.master')
@section('title','درخواست‌های خدمات')
@section('content')
    <div class="body d-flex py-3" id="cms-form">
        <div class="container-fluid">
            <div class="page-header">
                <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                    <h3 class="fw-bolder">درخواست‌های خدمات</h3>
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <p class="text-muted">لیست پیام‌ها و درخواست‌های ارسال شده از سمت کاربران</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card-block row">
                <div class="col-sm-12">
                    <form class="form-control">
                        <div class="table-responsive d-flex align-items-center">
                            <table class="table align-middle border-custom mb-0">
                                <thead class="text-center text-light">
                                <tr>
                                    <th class="fw-bolder">#</th>
                                    <th class="fw-bolder">نام و نام خانوادگی</th>
                                    <th class="fw-bolder">شماره تماس</th>
                                    <th class="fw-bolder">خدمت</th>
                                    <th class="fw-bolder">تاریخ ثبت</th>
                                    <th class="fw-bolder">وضعیت</th>
                                    <th class="fw-bolder">عملیات</th>
                                </tr>
                                </thead>
                                <tbody class="text-center text-light">
                                @foreach($requests as $key => $row)
                                    <tr style="{{ !$row->is_read ? 'background: rgba(0, 123, 255, 0.05);' : '' }}">
                                        <th>{{ $requests->firstItem() + $key }}</th>
                                        <th>{{ $row->full_name }}</th>
                                        <th>{{ $row->phone }}</th>
                                        <th>
                                            @if($row->service)
                                                <span class="badge bg-info">{{ $row->service->title }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </th>
                                        <th>{{ $row->getCreatedAtDate() }}</th>
                                        <th>
                                            @if($row->is_read)
                                                <span class="badge bg-label-success">خوانده شده</span>
                                            @else
                                                <span class="badge bg-label-danger">جدید</span>
                                            @endif
                                        </th>
                                        <th>
                                            <div class="btn-group" role="group">
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="مشاهده جزئیات"
                                                   href="{{ route('admin.service-request.show', $row->id) }}">
                                                    <i class="d-flex bi bi-eye color-custom2 fs-5"></i>
                                                </a>

                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="حذف"
                                                   onclick="confirmDelete('{{ route('admin.service-request.delete', $row->id) }}')"
                                                   href="javascript:void(0)">
                                                    <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                                </a>
                                            </div>
                                        </th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(count($requests))
                            <div class="mt-3">
                                {{ $requests->links('admin.components.pagination.default') }}
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function confirmDelete(url) {
            Swal.fire({
                title: 'آیا مطمئن هستید؟',
                text: "این درخواست برای همیشه حذف خواهد شد!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'بله، حذف کن',
                cancelButtonText: 'لغو'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ایجاد یک فرم مخفی برای ارسال متد DELETE
                    let form = document.createElement('form');
                    form.action = url;
                    form.method = 'POST';
                    form.innerHTML = `
                    @csrf
                    @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
