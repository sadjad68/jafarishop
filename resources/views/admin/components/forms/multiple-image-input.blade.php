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
<div class="p-1 w-fit">
    <div class="form-group">
        <label>
            {{$label}}
            @if($validations && in_array("requiredCms",$validations)) <span class="text-danger">*</span> @endif
        </label>
        <input type="file" class="form-control bg-light rounded-custom" multiple
               accept="image/*" id="image_input{{$unique_id}}" data-unique-id="{{$unique_id}}" value="{{old($name)}}">
    </div>
</div>

<div id="list">
    <ul class="p-0 m-0 d-flex align-items-center flex-wrap rounded-custom overflow-hidden">
        @if($imageSrc)
            <input type="hidden" id="delete-url" value="{{ @$deleteUrl }}">
            <input type="hidden" id="thumbnail-url" value="{{ @$thumbnailUrl }}">
            @foreach($imageSrc as $img)
                <li class="list-unstyled  p-3 shadow-sm rounded-lg" id="arrayorder_{{$img['id']}}" style="background-color: #eae8fd">
                    <div class="image-container p-1 position-relative">
                        @if(@$deletable == true)
                        <a href="{{route('admin.common.delete-image', $deleteUrl . '&id=' . @$img['id'])}}"
                           type="button" class="btn btn-danger btn-sm delete-image rounded-circle p-0 position-absolute"
                           data-bs-toggle="tooltip" data-bs-title="حذف تصویر">
                            <i class="bi bi-x d-flex"></i>
                        </a>
                        @endif
                            @if(@$withThumbnail == true && $img['thumbnail'] == 0)
                            <a href="{{route('admin.common.set-thumb', $thumbnailUrl . '&id=' . @$img['id'])}}"
                               type="button" class="btn btn-success btn-sm delete-image rounded-circle p-0 position-absolute"
                                   data-bs-toggle="tooltip" data-bs-title="انتخاب به عنوان تصویر شاخص">
                                <i class="bi bi-check d-flex"></i>
                            </a>
                            @endif
                        <img class="rounded shadow border img-gallery-thumb" src="{{$img->getImage()}}">
                    </div>
                </li>
            @endforeach
        @endif
        <div class="gallery flex-wrap mw-100" id="image_preview{{$unique_id}}" style="display: none"></div>
    </ul>
</div>

<div class="progress">
    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
</div>
<div class="cropper-inline-alert" id="cropper-alert{{$unique_id}}" role="alert"></div>
<div class="modal fade"
     data-bs-backdrop='static'
     id="modal{{$unique_id}}"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modalLabel{{$unique_id}}"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel{{$unique_id}}">قسمت مورد نظر را انتخاب کنید</h5>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">
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
{{--<input type="hidden" name="my_images[]" id="my_images{{$unique_id}}">--}}
<input type="file" name="{{$name}}[]" id="cropped_image_files{{$unique_id}}" class="d-none" value="{{old($name)}}" multiple>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            var uniqueId = document.querySelector('input[data-unique-id]').getAttribute('data-unique-id');
            var $progress = $('.progress');
            var $progressBar = $('.progress-bar');
            var $alert = $('#cropper-alert{{$unique_id}}');
            var $modal = $('#modal' + uniqueId);
            var cropper;
            var files;
            var currentIndex = 0;
            var croppedFiles = new DataTransfer();
            var cancelled = false; // Flag to check if the user cancelled

            function cropNextImage() {
                if (currentIndex >= files.length) {
                    $modal.modal('hide');
                    $progress.hide();
                    updateCroppedInputFiles();
                    return;
                }

                var file = files[currentIndex];
                var reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('image' + uniqueId).src = e.target.result;
                    $modal.modal('show');
                };

                reader.readAsDataURL(file);
            }

            function updateCroppedInputFiles() {
                // var fileInputElement = document.getElementById('cropped_image_files' + uniqueId);
                // fileInputElement.files = croppedFiles.files;
            }

            document.getElementById('image_input' + uniqueId).addEventListener('change', function (e) {
                files = e.target.files;
                currentIndex = 0;
                cropNextImage();
            });

            $modal.on('shown.bs.modal', function () {
                var image = document.getElementById('image' + uniqueId);
                cropper = new Cropper(image, {
                    aspectRatio: {{$width}} / {{$height}},
                    viewMode: 1,
                });
                cancelled = false; // Reset cancelled flag
            }).on('hidden.bs.modal', function () {
                cropper.destroy();
                cropper = null;
                if (cancelled || currentIndex >= files.length) {
                    return; // Do nothing if the user cancelled or all files are cropped
                }
                cropNextImage(); // Open the modal for the next image
            });

            document.getElementById('crop' + uniqueId).addEventListener('click', function () {
                if (cropper) {
                    var canvas = cropper.getCroppedCanvas({
                        width: {{$width}},
                        height: {{$height}},
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high'
                    });
                    canvas.toBlob(function (blob) {
                        var reader = new FileReader();
                        reader.readAsDataURL(blob);
                        reader.onloadend = function () {
                            var base64data = reader.result;
                            var hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'my_images[]';
                            hiddenInput.value = base64data;
                            document.body.appendChild(hiddenInput);

                            croppedFiles.items.add(new File([blob], 'cropped_image' + currentIndex + '.jpg'));

                            var imgPreview = document.createElement('img');
                            imgPreview.src = base64data;
                            imgPreview.classList.add('rounded', 'shadow', 'border', 'img-gallery-thumb', 'p-1', 'mw-100');
                            document.getElementById('image_preview' + uniqueId).appendChild(imgPreview);
                            document.getElementById('image_preview' + uniqueId).style.display = 'flex';

                            var fileInputElement = document.getElementById('cropped_image_files' + uniqueId);
                            fileInputElement.files = croppedFiles.files;

                            currentIndex++;
                            $modal.modal('hide');
                        };
                    });
                }
            });

            // Handle the cancel button click
            document.querySelector('.btn-secondary').addEventListener('click', function () {
                cancelled = true;
                $modal.modal('hide');
                // Clear the input file
                document.getElementById('image_input' + uniqueId).value = '';
            });
        });
    </script>
    <script src="{{ asset('assets/admin/cropper/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/admin/cropper/cropper.js') }}"></script>
@endpush
