<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title fs-5" id="exampleModalLabel">راهنمای صفحات</p>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Nav Tabs -->
                <ul class="nav nav-tabs" id="tabContent" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="static-links-tab" data-bs-toggle="tab" data-bs-target="#static-links" type="button" role="tab" aria-controls="static-links" aria-selected="true">
                            لینک‌های صفحات ثابت
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="dynamic-links-tab" data-bs-toggle="tab" data-bs-target="#dynamic-links" type="button" role="tab" aria-controls="dynamic-links" aria-selected="false">
                            لینک‌های صفحات متغییر
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="tabContent">
                    <!-- Static Links Tab -->
                    <div class="tab-pane fade show active" id="static-links" role="tabpanel" aria-labelledby="static-links-tab">
                        <div class="row w-100 m-0">
                            <div role="alert" class="d-block m-0 p-0">
                                <ul class="p-0 m-0">
                                    @foreach(\Config::get('settings.static_links') as $key_static_link => $static_link)
                                        <li class="d-flex align-items-center justify-content-between alert alert-success p-1">
                                            <span class="col-4 d-block">{{$static_link['title']}}</span>
                                            <span class="col-4 d-block" style="direction: ltr">{{$key_static_link}}</span>
                                            <button type="button"
                                                    class="btn btn-dark  small btn-sm  shadow-none d-flex align-items-center gap-1 d-block"
                                                    onclick="copyToClipboardLegacy('{{$key_static_link}}')">
                                                <i class="bi bi-clipboard-check d-flex"></i>
                                                کپی لینک
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Links Tab -->
                    <div class="tab-pane fade" id="dynamic-links" role="tabpanel" aria-labelledby="dynamic-links-tab">
                        <div class="row w-100 m-0">
                            <div role="alert" class="d-block m-0 p-0">
                                <ul class="p-0 m-0">
                                    @foreach(\Config::get('settings.dynamic_links') as $key_dynamic_link => $dynamic_link)
                                        <li class="d-flex align-items-center justify-content-between alert alert-success p-1">
                                            <span class="col-4 d-block">{{$dynamic_link['title']}}</span>
                                            <span class="col-4 d-block" style="direction: ltr">{{$key_dynamic_link}}</span>
                                            <a type="button" href="{{route($dynamic_link['url'])}}" target="_blank" class="btn btn-dark  small btn-sm  shadow-none d-flex align-items-center gap-1 d-block">مشاهده لیست در ادمین</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function copyToClipboardLegacy(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'متن با موفقیت کپی شد!',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }).catch((err) => {
                    console.error('خطا در کپی کردن متن:', err);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'کپی متن انجام نشد.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                });
            } else {
                // پشتیبانی نشدن Clipboard API
                const tempInput = document.createElement('textarea');
                tempInput.style.position = 'absolute';
                tempInput.style.left = '-9999px';
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                tempInput.setSelectionRange(0, tempInput.value.length);
                try {
                    const success = document.execCommand('copy');
                    if (success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'متن با موفقیت کپی شد!',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'کپی متن انجام نشد.',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    }
                } catch (err) {
                    console.error('خطا در کپی کردن متن:', err);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'کپی متن انجام نشد.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }
                document.body.removeChild(tempInput);
            }
        }
    </script>


@endpush
