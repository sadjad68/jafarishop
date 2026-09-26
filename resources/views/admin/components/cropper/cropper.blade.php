@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


  <link rel="stylesheet" href="{{asset('assets/admin/cropper/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/admin/cropper/cropper.css')}}">
  <style>
    .label {
      cursor: pointer;
    }

    .progress {
      display: none;
      margin-bottom: 1rem;
    }

    .alert {
      display: none;
    }

    .img-container img {
      max-width: 100%;
    }
  </style>
@endpush

<div class="container">
    <h1>Upload cropped image to server</h1>
    <label class="label" data-toggle="tooltip" title="Change your avatar">
        <img class="rounded" id="avatar" src="" alt="">
        <input type="file" class="sr-only" id="input" name="image" accept="image/*">
    </label>
    <div class="progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>
    <div class="alert" role="alert"></div>
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Crop the image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="image" src="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop">Crop</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <input type="hidden" name="my_image" id="my_image">
    <input type="file" name="cropped_image_file" id="cropped_image_file" class="d-none">

@push('scripts')
  <script src="  {{asset('assets/admin/cropper/jquery.min.js')}}"></script>
  <script src="  {{asset('assets/admin/cropper/bootstrap.bundle.min.js')}}"></script>
  <script src="  {{asset('assets/admin/cropper/cropper.js')}}"></script>

<script>
    window.addEventListener('DOMContentLoaded', function () {
        var avatar = document.getElementById('avatar');
        var image = document.getElementById('image');
        var input = document.getElementById('input');
        var $progress = $('.progress');
        var $progressBar = $('.progress-bar');
        var $alert = $('.alert');
        var $modal = $('#modal');
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

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: 2 / 1,
                viewMode: 3,
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });

        document.getElementById('crop').addEventListener('click', function () {
            var initialAvatarURL;
            var canvas;
            $modal.modal('hide');

            if (cropper) {
                canvas = cropper.getCroppedCanvas({

                });
                initialAvatarURL = avatar.src;
                avatar.src = canvas.toDataURL();
                $progress.show();
                $alert.removeClass('alert-success alert-warning');
                canvas.toBlob(function (blob) {
                    var formData = new FormData();
                    formData.append('avatar', blob, 'avatar.jpg');
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function() {
                        var base64data = reader.result;
                        document.getElementById('my_image').value = base64data;

                        // Create a new File object to simulate file input
                        var fileInputElement = document.getElementById('cropped_image_file');
                        var dataTransfer = new DataTransfer();
                        dataTransfer.items.add(new File([blob], 'cropped_image.jpg'));
                        fileInputElement.files = dataTransfer.files;

                        $progress.hide();
                    };
                });
            }
        });
    });
</script>
@endpush
