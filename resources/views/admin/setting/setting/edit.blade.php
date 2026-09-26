@extends('admin._layouts.master')
@section('title')
    ویرایش تنظیمات
@stop
@section('content')
    @php
        $theme_provider = app(\App\Modules\General\Helper\ThemeProvider::class);
    @endphp

    <div class="body d-flex py-3">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="border-0 mb-4">
                        <div
                            class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                            <h3 class="fw-bolder mb-0">
                                ویرایش تنظیمات
                            </h3>
                            <div class="d-flex align-items-center">
                                @component("admin.components.video-button")
                                    @slot("type","sms")
                                    @slot("text","تنظیمات پیامک")
                                @endcomponent
                                @component("admin.components.video-button")
                                    @slot("type","sliders")
                                    @slot("text","لوگو")
                                @endcomponent
                                @component("admin.components.video-button")
                                    @slot("type","setting_text_part1")
                                    @slot("text","تنظیمات (بخش اول)")
                                @endcomponent
                                @component("admin.components.video-button")
                                    @slot("type","setting_text_part2")
                                    @slot("text","تنظیمات (بخش دوم)")
                                @endcomponent
                                @component("admin.components.video-button")
                                    @slot("type","menu_setting")
                                    @slot("text","تنظیمات منو")
                                @endcomponent
                                @component("admin.components.video-button")
                                    @slot("type","shopika_theme")
                                    @slot("text","تم مارکتو")
                                @endcomponent
                            </div>
                            <div class="d-flex align-items-center justify-content-between w-100"
                                 style="flex-direction: row-reverse;">

                                <ul class="list-inline align-items-center m-0">
                                    <li class="list-inline-item mx-0">
                                        <a href="{{'/purge-cache'}}" target="_blank"
                                           class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center">
                                            <i class="bi bi-trash2 d-flex h5 my-0 me-2"></i>
                                            پاک کردن کش سایت
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card border p-2 setting-card">
                <form onsubmit="getNavId(event)" action="{{route('admin.setting.edit')}}" method="POST" class="setting-form"
                      enctype="multipart/form-data" id="cms-form"
                      @submit.prevent="validateForm">
                    @csrf
                    @include('admin.setting.setting.form')
                </form>
            </div>
        </div>
    </div>
    @include('admin.setting.setting.inputs.menu-modal')
    @include('admin.setting.setting.inputs.logo-modal')
@stop
@push('scripts')
    <script src="{{asset('assets/admin/ckeditor/ckeditor.js')}}"></script>
    <script>
        setTimeout(() => {

            var cks = document.getElementsByClassName('editor');

            Array.from(cks).forEach((el) => {
                console.log(el);
                var editor1 = CKEDITOR.replace(el, {
                    language: 'fa',
                    contentsLangDirection: 'rtl',
                    content: 'fa',
                    removeButtons: 'Font,FontSize,PasteFromWord',
                    filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
                    filebrowserUploadMethod: 'form',
                    on: {
                        instanceReady: function (event) {
                            event.editor._.forcePasteDialog = true;
                            event.editor.on('key', function (evt) {
                                if (evt.data.$.ctrlKey && evt.data.$.shiftKey && evt.data.dataKey === 86) { // 86 کد کلید V است
                                    evt.data.preventDefault();
                                    event.editor.execCommand('paste');
                                }
                            });
                        }
                    }
                });
                // اگر الزاماً لازم است، می توانید اطلاعات خاصی به ویرایشگر اضافه کنید.
            });

        }, 100)
    </script>
@endpush
