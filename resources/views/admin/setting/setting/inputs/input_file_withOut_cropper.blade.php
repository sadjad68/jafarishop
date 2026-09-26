@php $unique_id = \Illuminate\Support\Str::random(10);
@endphp
<div class="admin-upload setting-upload col-md-6 col-12 my-2">
    <div class="admin-field">
        <label class="admin-label">{{@$data['p_name']}}</label>
        <div class="admin-upload-media">
            <label class="admin-dropzone" for="image_input{{$unique_id}}">
                <input type="file" class="admin-dropzone-input" name="{{@$data['type']}}[{{@$data['key']}}]"
                       accept="image/*" id="image_input{{$unique_id}}">
                <span class="admin-dropzone-body">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <strong>انتخاب تصویر</strong>
                    <small>کلیک کنید یا فایل را اینجا رها کنید</small>
                </span>
            </label>
            @if(isset($data))
                <div id="data-image{{$unique_id}}" class="admin-thumb-wrap" style="display: block">
                    <div class="image-container position-relative">
                        <a href="{{route('admin.common.remove-image',['model'=>\App\Modules\Setting\Entities\Setting::class,'name'=>'value','id'=>@$data['id']])}}"
                           type="button"
                           class="btn btn-danger btn-sm delete-image rounded-circle p-0 position-absolute"
                           data-bs-toggle="tooltip"
                           data-bs-title="حذف تصویر">
                            <i class="bi bi-x d-flex"></i>
                        </a>
                        <img class="rounded img-gallery-thumb" src="{{$data->image}}" alt=""
                             onerror="this.classList.add('is-broken')">
                    </div>
                </div>
            @endif
            <div class="gallery flex-wrap" id="image_preview{{$unique_id}}" style="display: none"></div>
        </div>
    </div>
</div>

@push('scripts')
    <script>

        function removeFileFromFileList(index) {
            const dt = new DataTransfer()
            const input = document.getElementById('image_input{{$unique_id}}')
            const { files } = input
            for (let i = 0; i < files.length; i++) {
                const file = files[i]
                if (index !== i)
                    dt.items.add(file)
            }
            input.files = dt.files
        }
        $(function () {

            var imagesPreview = function (input, placeToInsertImagePreview) {


                if (input.files) {
                    var files = Array.from(input.files);
                    files.map(function (file) {
                        var reader = new FileReader();
                        reader.onload = function (event) {
                            var deleteButton = $('<button type="button" class="btn btn-danger btn-sm delete-image rounded-circle p-0 position-absolute"><i class="bi bi-x d-flex"></i></button>');
                            var imageContainer = $('<div class="admin-thumb-wrap"><div class="image-container position-relative"></div></div>');
                            imageContainer.find('.image-container').append(
                                $($.parseHTML('<img>')).attr('src', event.target.result).attr('alt', '').addClass('img-gallery-thumb'),
                                deleteButton
                            );
                            imageContainer.appendTo(placeToInsertImagePreview);
                            deleteButton.on('click', function () {
                                removeFileFromFileList(0);

                                $(this).closest('.admin-thumb-wrap').remove();
                                if(document.querySelectorAll('.image-container').length === 0){
                                    $('#image_input{{$unique_id}}').val('');
                                }
                                @if(isset($data))
                                $('#data-image{{$unique_id}}').css('display', 'block');
                                @endif
                            });
                        }
                        reader.readAsDataURL(file);
                    });
                }
            };
            $('#image_input{{$unique_id}}').on('change', function () {
                if (this.files.length > 0) {
                    $('#image_preview{{$unique_id}}').empty();
                }
                imagesPreview(this, '#image_preview{{$unique_id}}');
                $('#image_preview{{$unique_id}}').css('display', 'flex');
                $('#data-image{{$unique_id}}').css('display', 'none');
            });
        });
    </script>
@endpush
