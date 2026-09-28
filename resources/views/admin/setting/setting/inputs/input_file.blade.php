@push('styles')
    <link rel="stylesheet" href="{{asset('assets/admin/cropper/cropper.css')}}">
    <style>
        .label {
            cursor: pointer;
        }

        .progress {
            display: none;
            margin-bottom: 1rem;
        }

        .cropper-inline-alert {
            display: none;
        }

        .img-container {
            max-width: 300px;
            max-height: 300px;
            margin: auto;
            display: block;
            overflow: hidden;
        }

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
            background: rgba(250, 250, 250, 0.2) !important;
        }

        .cropper-canvas {
            margin: auto !important;
            transform: unset !important;
        }

        .img-container img {
            max-width: 100%;
        }
    </style>
@endpush
@php
    $unique_id = \Illuminate\Support\Str::random(10);
    $width = @$options['width'];
    $height = @$options['height'];
    $allowsGif = in_array(@$data['key'], ['logo', 'footer_logo'], true);
@endphp
<div class="admin-upload setting-upload col-md-6 col-12 my-2">
    <div class="admin-field">
        <label class="admin-label">
            {{ @$data['p_name'] }}
            @if($height != null && $width != null)
                <span class="setting-size-hint">(سایز {{$height}} * {{$width}})</span>
            @else
                <span class="setting-size-hint">(سایز ندارد)</span>
            @endif
        </label>
        <div class="admin-upload-media">
            <label class="admin-dropzone" for="image_input{{ $unique_id }}">
                @if(@$options['width'] && @$options['height'])
                    <input type="file" class="admin-dropzone-input"
                           accept="image/*" id="image_input{{ $unique_id }}" data-unique-id="{{ $unique_id }}">
                @else
                    <input type="file" class="admin-dropzone-input"
                           accept="image/*" name="{{ @$data['type'] }}[{{ @$data['key'] }}]"
                           id="image_input{{ $unique_id }}" data-unique-id="{{ $unique_id }}">
                @endif
                <span class="admin-dropzone-body">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <strong>انتخاب تصویر</strong>
                    <small>{{ $allowsGif ? 'PNG، JPG، WebP یا GIF متحرک' : 'کلیک کنید یا فایل را اینجا رها کنید' }}</small>
                </span>
            </label>
            @if(isset($data))
                <div id="data-image{{ $unique_id }}" class="admin-thumb-wrap" style="display: block">
                    <div class="image-container position-relative">
                        <a href="{{ route('admin.common.remove-image', ['model' => \App\Modules\Setting\Entities\Setting::class, 'name' => 'value', 'id' => @$data['id'],"clear_cache"=>true]) }}"
                           type="button"
                           class="btn btn-danger btn-sm delete-image rounded-circle p-0 position-absolute"
                           data-bs-toggle="tooltip"
                           data-bs-title="حذف تصویر">
                            <i class="bi bi-x d-flex"></i></a>
                        <img class="rounded img-gallery-thumb" src="{{ $data->image }}" alt=""
                             onerror="this.classList.add('is-broken')">
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if(@$options['width'] && @$options['height'])
        <div class="modal fade" id="modal{{ $unique_id }}" tabindex="-1" role="dialog" data-bs-backdrop='static'
             aria-labelledby="modalLabel{{ $unique_id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel{{ $unique_id }}">انتخاب قسمت مورد نظر</h5>
                        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="img-container">
                            <img id="image{{ $unique_id }}" src="" alt="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
                        <button type="button" class="btn btn-primary" id="crop{{ $unique_id }}">بریدن</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="gallery flex-wrap" id="image_preview{{ $unique_id }}" style="display: none"></div>
        <input type="file" id="cropped_image_file{{ $unique_id }}" name="{{ @$data['type'] }}[{{ @$data['key'] }}]"
               class="d-none">
    @endif
</div>


@if(@$options['width'] && @$options['height'])
@push('scripts')
    <script src="{{ asset('assets/admin/cropper/cropper.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.getElementById('image_input{{ $unique_id }}');
            var image = document.getElementById('image{{ $unique_id }}');
            var croppedImageInput = document.getElementById('cropped_image_file{{ $unique_id }}');
            var previousImage = document.getElementById('data-image{{ $unique_id }}');
            var $modal = $('#modal{{ $unique_id }}');
            var cropper;

            var allowsGif = {{ $allowsGif ? 'true' : 'false' }};

            input.addEventListener('change', function (e) {
                var files = e.target.files;
                if (files && files.length > 0) {
                    var file = files[0];
                    if (allowsGif && file.type === 'image/gif') {
                        if (previousImage) {
                            previousImage.style.display = 'none';
                        }
                        var dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        croppedImageInput.files = dataTransfer.files;
                        var gifUrl = URL.createObjectURL(file);
                        var preview = document.getElementById('image_preview{{ $unique_id }}');
                        preview.style.display = 'block';
                        preview.innerHTML = '<img src="' + gifUrl + '" class="img-thumbnail" alt=""/>';
                        return;
                    }
                    var url = URL.createObjectURL(file);
                    image.src = url;
                    if (previousImage) {
                        previousImage.style.display = 'none';
                    }
                    $modal.modal('show');
                }
            });

            $modal.on('shown.bs.modal', function () {
                cropper = new Cropper(image, {
                    aspectRatio: {{ $width }} / {{ $height }},
                    viewMode: 1,
                });
            }).on('hidden.bs.modal', function () {
                cropper.destroy();
                cropper = null;
            });

            document.getElementById('crop{{ $unique_id }}').addEventListener('click', function () {
                if (cropper) {
                    var canvas = cropper.getCroppedCanvas({
                        width: {{ $width }},
                        height: {{ $height }},
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high'
                    });

                    canvas.toBlob(function (blob) {
                        if (!blob) {
                            console.error('Canvas is empty');
                            return;
                        }

                        var file = new File([blob], 'croppedImage.png', {type: 'image/png'});

                        var dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        croppedImageInput.files = dataTransfer.files;

                        var url = URL.createObjectURL(blob);
                        document.getElementById('image_preview{{ $unique_id }}').style.display = 'block';
                        document.getElementById('image_preview{{ $unique_id }}').innerHTML = '<img src="' + url + '" class="img-thumbnail"/>';

                        $modal.modal('hide');
                    }, 'image/png');
                }
            });
        });
    </script>
@endpush
@endif
