@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{asset('assets/admin/cropper/cropper.css')}}">
    <style>
        .label { cursor: pointer; }
        .progress { display: none; margin-bottom: 1rem; }
        .cropper-inline-alert { display: none; }
        .img-container { max-width: 300px;max-height: 300px;margin: auto;display: block; overflow: hidden; }
        .modal .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - (1.75rem * 2));
        }
        .modal .modal-dialog-centered::before {
            content: '';
            display: block;
            height: calc(100vh - (1.75rem * 2));
            height: -webkit-min-content;
            height: -moz-min-content;
            height: min-content;
        }
        .cropper-view-box img {
            opacity: 0 !important;
        }
        .cropper-crop-box {
            background: rgba(250,250,250,0.2) !important;
        }
        .cropper-canvas{
            margin: auto !important;
            transform: unset !important;
        }
    </style>
@endpush

<div class="admin-upload d-flex flex-column gap-2">
    <div class="admin-field">
        <label class="admin-label">
            {{$label}}
            @if($validations && in_array("requiredCms",$validations))
                <span class="text-danger">*</span>
            @endif
        </label>
        <div class="admin-upload-media">
            <label class="admin-dropzone" for="image_input{{$name.$unique_id}}">
                <input type="file" class="admin-dropzone-input" name="{{$name}}" accept="image/*" id="image_input{{$name.$unique_id}}" value="{{old($name)}}">
                <span class="admin-dropzone-body">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <strong>انتخاب تصویر</strong>
                    <small>کلیک کنید یا فایل را اینجا رها کنید</small>
                </span>
            </label>
            @if($imageSrc)
                <div id="data-image{{$unique_id}}" class="admin-thumb-wrap" style="display: block">
                    <div class="image-container position-relative">
                        @if($deletable)
                            <a href="{{route('admin.common.remove-image',$deleteUrl.'&name='.$name)}}" type="button"
                               class="btn btn-danger btn-sm delete-image rounded-circle p-0 position-absolute"
                               data-bs-toggle="tooltip" data-bs-title="حذف تصویر">
                                <i class="bi bi-x d-flex"></i>
                            </a>
                        @endif
                        <img class="rounded img-gallery-thumb" src="{{$imageSrc}}">
                    </div>
                </div>
            @endif
        </div>
        <div class="admin-preview-wrap mt-2">
            <img class="rounded admin-preview-image" id="avatar{{$unique_id}}" src="" alt="">
            <button type="button" class="btn btn-danger btn-sm delete-preview-image rounded-circle p-0 position-absolute" style="display: none;" id="delete_preview_image{{$unique_id}}">
                <i class="bi bi-x d-flex"></i>
            </button>
        </div>
    </div>
    <div class="progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>
    <div class="cropper-inline-alert" id="cropper-alert{{$unique_id}}" role="alert"></div>
    <div class="modal fade" id="modal{{$unique_id}}"
         tabindex="-1"
         role="dialog"
         data-bs-backdrop='static'
         aria-labelledby="modalLabel{{$unique_id}}"
         aria-hidden="true"
    >
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel{{$unique_id}}">قسمت مورد نظر را انتخاب کنید</h5>
                    <button type="button" class=" btn btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="image{{$unique_id}}" src="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-primary" id="crop{{$unique_id}}">بریدن</button>
                </div>
            </div>
        </div>
    </div>
    <div class="gallery flex-wrap" id="image_preview{{$unique_id}}" style="display: none"></div>
{{--    <input type="hidden" name="my_image" id="my_image{{$unique_id}}">--}}
    <input type="file" name="{{$name}}" id="cropped_image_file{{$unique_id}}" class="d-none" value="{{old($name)}}">
</div>

@push('scripts')
    <script>

        window.addEventListener('DOMContentLoaded', function () {
            let fileType;

            setTimeout(function () {
                var avatar = document.getElementById('avatar{{$unique_id}}');
                var image = document.getElementById('image{{$unique_id}}');
                var input = document.getElementById('image_input{{$name.$unique_id}}');
                var deleteButton = document.getElementById('delete_preview_image{{$unique_id}}');
                var previous_image = document.getElementById('data-image{{$unique_id}}');
                var $progress = $('.progress');
                var $progressBar = $('.progress-bar');
                var $alert = $('#cropper-alert{{$unique_id}}');
                var $modal = $('#modal{{$unique_id}}');
                var cropper;

                $('[data-toggle="tooltip"]').tooltip();

                input.addEventListener('change', function (e) {
                    var files = e.target.files;
                    var done = function (url) {
                        input.value = '';
                        image.src = url;
                        $alert.hide();
                        $modal.modal('show');
                    };
                    var reader;
                    var file;
                    var url;

                    if (files && files.length > 0) {
                        file = files[0];
                        fileType = file.type;
                        if (file.type !== 'image/gif') {
                            // اگر فایل غیر از GIF بود، کراپر فعال شود
                            if (URL) {
                                done(URL.createObjectURL(file));
                            } else if (FileReader) {
                                reader = new FileReader();
                                reader.onload = function (e) {
                                    done(reader.result);
                                };
                                reader.readAsDataURL(file);
                            }
                        } else {
                            // اگر فایل GIF بود
                            if(previous_image){
                                previous_image.style.display = 'none';
                            }


                            // تبدیل فایل به Blob و اضافه کردن به input
                            var fileInputElement = document.getElementById('cropped_image_file{{$unique_id}}');
                            var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file); // فایل به input اضافه شود
                            fileInputElement.files = dataTransfer.files;

                            // deleteButton.style.display = 'block';

                        }
                    }
                });

                $modal.on('shown.bs.modal', function () {
                    cropper = new Cropper(image, {
                        aspectRatio: {{$width}} / {{$height}},
                        viewMode: 1,
                        ready: function () {
                            var containerData = cropper.getContainerData();
                            var cropBoxData = cropper.getCropBoxData();
                            var canvasData = cropper.getCanvasData();
                            canvasData.left = 0;
                            console.log(canvasData);
                            console.log(cropBoxData);
                        }
                    });
                }).on('hidden.bs.modal', function () {
                    cropper.destroy();
                    cropper = null;
                });

                document.getElementById('crop{{$unique_id}}').addEventListener('click', function () {
                    var initialAvatarURL;
                    var canvas;

                    $modal.modal('hide');

                    if (cropper) {
                        canvas = cropper.getCroppedCanvas({
                            width: {{$width}},
                            height: {{$height}},
                            imageSmoothingEnabled: true,
                            imageSmoothingQuality: 'high'
                        });
                        console.log(canvas)
                        initialAvatarURL = avatar.src;
                        console.log('initialAvatarURL');
                        console.log(initialAvatarURL);
                        avatar.src = canvas.toDataURL();
                        $progress.show();
                        $alert.removeClass('alert-success alert-warning');

                        canvas.toBlob(function (blob) {
                            var formData = new FormData();
                            formData.append('avatar{{$unique_id}}', blob, 'avatar.jpg');
                            var reader = new FileReader();
                            reader.readAsDataURL(blob);
                            reader.onloadend = function () {
                                var base64data = reader.result;

                                var fileInputElement = document.getElementById('cropped_image_file{{$unique_id}}');
                                var dataTransfer = new DataTransfer();
                                let ext = "jpg";
                                if(fileType == "image/png"){
                                    ext = "png";
                                }

                                dataTransfer.items.add(new File([blob], 'cropped_image.'+ext));
                                fileInputElement.files = dataTransfer.files;

                                $progress.hide();
                                deleteButton.style.display = 'block';
                                previous_image.style.display = 'none';
                            };
                        });
                    }
                });

                // فانکشن حذف تصویر
                deleteButton.addEventListener('click', function () {
                    avatar.src = '';
                    document.getElementById('my_image{{$unique_id}}').value = '';
                    document.getElementById('cropped_image_file{{$unique_id}}').value = '';
                    deleteButton.style.display = 'none';
                });
            }, 1000);
        });
    </script>

    <script src="{{asset('assets/admin/cropper/jquery.min.js')}}"></script>
    <script src="{{asset('assets/admin/cropper/cropper.js')}}"></script>

@endpush
