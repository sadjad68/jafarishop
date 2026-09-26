@extends('pages.panel.master')
@section('product_import', 'active')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/site/css/panel/tpl-user-panel.css') }}">
@endpush
@section('content')
<div class="header p-3">
    <p class="font-md m-0 d-flex align-items-center h3">
        <i class="bi bi-upload me-2 d-flex"></i>
        ورود گروهی محصولات (اکسل)
    </p>
</div>
<div class="content px-xl-3 py-2">
    <div class="card border rounded-3 shadow-sm">
        <div class="card-body p-4">
            <p class="text-muted mb-3">
                فایل اکسل باید شامل ستون‌های <strong>name</strong>, <strong>url</strong>, <strong>category</strong>, <strong>brand</strong>, <strong>price</strong>, <strong>discounted_price</strong>, <strong>stock</strong> باشد.
                مقدار دسته‌بندی و برند باید دقیقاً با یکی از مقادیر موجود در سایت مطابقت داشته باشد.
            </p>
            <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
                <a href="{{ route('product.import.sample') }}" class="btn btn-outline-primary">
                    <i class="bi bi-download me-1"></i>
                    دریافت فایل نمونه
                </a>
            </div>
            <form action="{{ route('product.import.store') }}" method="post" enctype="multipart/form-data" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">فایل اکسل (xlsx / xls)</label>
                    <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" accept=".xlsx,.xls" required>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">ارسال و پردازش</button>
            </form>

            @if(isset($result) && $result instanceof \App\Services\ProductImportResult)
                <hr class="my-4">
                <h5 class="mb-3">نتیجه ورود</h5>
                @if($result->hasGlobalErrors())
                    <div class="alert alert-danger">
                        <ul class="mb-0 list-unstyled">
                            @foreach($result->getGlobalErrors() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <ul class="list-unstyled mb-3">
                        <li><strong>تعداد کل سطرها (بدون هدر):</strong> {{ $result->getTotalRows() }}</li>
                        <li><strong>وارد شده (جدید):</strong> {{ $result->getImportedCount() }}</li>
                        <li><strong>بروزرسانی شده:</strong> {{ $result->getUpdatedCount() }}</li>
                        <li><strong>ناموفق:</strong> {{ $result->getFailedCount() }}</li>
                    </ul>
                    @if($result->getFailedCount() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام محصول</th>
                                        <th>خطاها</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($result->getFailedRows() as $rowNum => $row)
                                        <tr>
                                            <td>{{ $rowNum }}</td>
                                            <td>{{ $row['name'] }}</td>
                                            <td>
                                                <ul class="mb-0 list-unstyled text-danger small">
                                                    @foreach($row['errors'] as $e)
                                                        <li>{{ $e }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </div>
    <div class="mt-3">
        <p class="text-muted small mb-1">دسته‌بندی‌های موجود:</p>
        <p class="small">{{ implode(' ، ', array_values($categories)) ?: '—' }}</p>
        <p class="text-muted small mb-1">برندهای موجود:</p>
        <p class="small">{{ implode(' ، ', array_values($brands)) ?: '—' }}</p>
    </div>
</div>
@endsection
