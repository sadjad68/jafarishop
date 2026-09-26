@extends('admin._layouts.master')
@section('title','جزئیات درخواست')
@section('content')
    <div class="body d-flex py-3">
        <div class="container-fluid">
            <div class="page-header">
                <div class="card-header py-3 no-bg bg-transparent border-0 px-0">
                    <h3 class="fw-bolder">جزئیات درخواست: {{ $data->full_name }}</h3>
                    <a href="{{ route('admin.service-request.index') }}" class="btn btn-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-right"></i> بازگشت به لیست
                    </a>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card border-custom h-100">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">اطلاعات تماس</h5>
                            <div class="mb-3">
                                <label class="text-muted d-block">نام و نام خانوادگی:</label>
                                <span class="fw-bold">{{ $data->full_name }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted d-block">شماره همراه:</label>
                                <span class="fw-bold text-primary">{{ $data->phone }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted d-block">تاریخ ارسال:</label>
                                <span>{{ $data->getCreatedAtDate()}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card border-custom mb-3">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">متن درخواست/توضیحات</h5>
                            <p class="lh-lg">{{ $data->description ?? 'بدون توضیحات' }}</p>
                        </div>
                    </div>

                    <div class="card border-custom">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">فایل‌های ضمیمه ({{ count($data->images ?? []) }})</h5>
                            <div class="d-flex flex-wrap gap-3">
                                @forelse($data->full_image_paths as $path)
                                    <a href="{{ $path }}" target="_blank">
                                        <img src="{{ $path }}" class="img-thumbnail rounded shadow-sm"
                                             style="width: 120px; height: 120px; object-fit: cover;">
                                    </a>
                                @empty
                                    <p class="text-muted">تصویری ضمیمه نشده است.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
