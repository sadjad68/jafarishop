@extends('admin._layouts.master')
@section('title', 'ورود گروهی محصولات')
@section('content')
    <div class="body d-flex py-3" id="cms-form">
        <div class="px-md-5 px-1 py-md-3 py-2">
            <div class="container">

                <div class="admin-hero">
                    <div class="admin-hero-text">
                        <span class="admin-hero-eyebrow">
                            <i class="bi bi-box-seam"></i>
                            مدیریت محصولات
                        </span>
                        <h2>ورود گروهی محصولات</h2>
                        <p>
                            فایل اکسل خود را آپلود کنید تا محصولات به‌صورت دسته‌جمعی وارد یا به‌روزرسانی شوند.
                            در صورت تطابق آدرس (url)، محصول موجود به‌روز می‌شود.
                        </p>
                        <a href="{{ route('admin.product.index') }}"
                           class="btn btn-custom-b rounded-custom d-inline-flex align-items-center mt-3">
                            <i class="bi bi-arrow-right d-flex me-2"></i>
                            بازگشت به لیست محصولات
                        </a>
                    </div>
                    <div class="admin-hero-deco" aria-hidden="true">
                        <i class="bi bi-file-earmark-spreadsheet"></i>
                    </div>
                </div>

                @if (session('import_result'))
                    @php $result = session('import_result'); @endphp

                    <div class="admin-section-title">
                        <span class="admin-section-ico"><i class="bi bi-clipboard-data"></i></span>
                        <span class="admin-section-label">خلاصه آخرین ورود</span>
                        <hr>
                    </div>

                    <div class="admin-import-stats">
                        <div class="admin-import-stat">
                            <span>کل ردیف‌ها</span>
                            <strong>{{ number_format($result->totalRows) }}</strong>
                        </div>
                        <div class="admin-import-stat is-success">
                            <span>وارد شده (جدید)</span>
                            <strong>{{ number_format($result->imported) }}</strong>
                        </div>
                        <div class="admin-import-stat is-info">
                            <span>به‌روزرسانی شده</span>
                            <strong>{{ number_format($result->updated) }}</strong>
                        </div>
                        <div class="admin-import-stat is-danger">
                            <span>ناموفق</span>
                            <strong>{{ number_format($result->failed) }}</strong>
                        </div>
                    </div>

                    @if (count($result->getRowErrors()) > 0)
                        <div class="admin-chart-card admin-import-errors">
                            <h3 class="admin-import-card-title">
                                <span class="admin-section-ico"><i class="bi bi-exclamation-triangle"></i></span>
                                خطاهای هر ردیف
                            </h3>
                            <div class="table-responsive admin-import-table-wrap">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 80px;">ردیف</th>
                                            <th>پیام خطا</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($result->getRowErrors() as $err)
                                            <tr>
                                                <td>
                                                    <span class="admin-import-row-badge">{{ $err['row'] }}</span>
                                                </td>
                                                <td>
                                                    <ul class="admin-import-error-list mb-0">
                                                        @foreach ($err['errors'] as $msg)
                                                            <li>{{ $msg }}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="row g-4 admin-import-layout @if(session('import_result')) admin-import-layout--after-result @endif">
                    <div class="col-lg-7">
                        <div class="admin-chart-card">
                            <h3 class="admin-import-card-title">
                                <span class="admin-section-ico"><i class="bi bi-cloud-upload"></i></span>
                                آپلود فایل
                            </h3>

                            <div class="admin-import-guide">
                                <div class="admin-import-guide-item">
                                    <i class="bi bi-check2-circle"></i>
                                    <span>فرمت‌های مجاز: xlsx، xls، csv</span>
                                </div>
                                <div class="admin-import-guide-item">
                                    <i class="bi bi-hdd"></i>
                                    <span>حداکثر حجم: ۱۰ مگابایت</span>
                                </div>
                                <div class="admin-import-guide-item">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span>به‌روزرسانی خودکار با url یکسان</span>
                                </div>
                            </div>

                            <p class="admin-import-note">
                                ستون‌های مورد نیاز: نام محصول، آدرس (url)، دسته‌بندی، برند، قیمت، قیمت تخفیف‌خورده، موجودی.
                                دسته‌بندی و برند باید دقیقاً مطابق نام موجود در سایت باشند.
                            </p>

                            <div class="admin-import-sample-row">
                                <a href="{{ route('admin.product.import.sample') }}"
                                   class="btn btn-custom-b rounded-custom d-inline-flex align-items-center"
                                   target="_blank">
                                    <i class="bi bi-download me-2"></i>
                                    دریافت فایل نمونه
                                </a>
                            </div>

                            <form action="{{ route('admin.product.import.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <label class="admin-upload-zone @error('file') is-invalid @enderror" id="importUploadZone">
                                    <input type="file"
                                           name="file"
                                           accept=".xlsx,.xls,.csv"
                                           required
                                           id="importFileInput">
                                    <span class="admin-upload-zone-icon" aria-hidden="true">
                                        <i class="bi bi-file-earmark-arrow-up"></i>
                                    </span>
                                    <span class="admin-upload-zone-title">فایل اکسل را انتخاب کنید</span>
                                    <span class="admin-upload-zone-hint">یا فایل را اینجا بکشید و رها کنید</span>
                                    <span class="admin-upload-zone-name" id="importFileName" hidden></span>
                                </label>
                                @error('file')
                                    <div class="admin-upload-error">{{ $message }}</div>
                                @enderror

                                <button type="submit" class="btn btn-custom rounded-custom w-100 admin-import-submit">
                                    <i class="bi bi-cloud-upload me-2"></i>
                                    شروع ورود
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="admin-chart-card admin-import-columns">
                            <h3 class="admin-import-card-title admin-import-card-title--flush">
                                <span class="admin-section-ico"><i class="bi bi-table"></i></span>
                                ستون‌های فایل نمونه
                            </h3>
                            <div class="table-responsive admin-import-table-wrap">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>ستون</th>
                                            <th>توضیح</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($headers as $en => $fa)
                                            <tr>
                                                <td>
                                                    <code class="admin-import-code">{{ $en }}</code>
                                                </td>
                                                <td>{{ $fa }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        (function () {
            var input = document.getElementById('importFileInput');
            var zone = document.getElementById('importUploadZone');
            var nameEl = document.getElementById('importFileName');
            var dragDepth = 0;
            if (!input || !zone || !nameEl) {
                return;
            }

            function showFileName() {
                var file = input.files && input.files[0];
                if (!file) {
                    nameEl.hidden = true;
                    nameEl.textContent = '';
                    zone.classList.remove('has-file');
                    return;
                }
                nameEl.textContent = file.name;
                nameEl.hidden = false;
                zone.classList.add('has-file');
            }

            input.addEventListener('change', showFileName);

            zone.addEventListener('dragenter', function (event) {
                event.preventDefault();
                dragDepth += 1;
                zone.classList.add('is-dragover');
            });

            zone.addEventListener('dragover', function (event) {
                event.preventDefault();
            });

            zone.addEventListener('dragleave', function (event) {
                event.preventDefault();
                dragDepth = Math.max(0, dragDepth - 1);
                if (dragDepth === 0) {
                    zone.classList.remove('is-dragover');
                }
            });

            zone.addEventListener('drop', function (event) {
                event.preventDefault();
                dragDepth = 0;
                zone.classList.remove('is-dragover');
                var files = event.dataTransfer && event.dataTransfer.files;
                if (!files || !files.length) {
                    return;
                }
                input.files = files;
                showFileName();
            });
        })();
    </script>
@endpush
