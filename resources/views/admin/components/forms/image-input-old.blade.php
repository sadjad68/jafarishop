<div class="admin-upload d-flex flex-wrap align-items-start gap-3">
    <div class="admin-field flex-grow-1">
        <label class="admin-label">
            {{$label}}
            @if($validations && in_array("requiredCms",$validations))
                <span class="text-danger">*</span>
            @endif
        </label>
        <label class="admin-dropzone" for="image_input{{$name.$unique_id}}">
            <input type="file" class="admin-dropzone-input" name="{{$name}}" accept="image/*" id="image_input{{$name.$unique_id}}" value="{{old($name)}}">
            <span class="admin-dropzone-body">
                <i class="bi bi-cloud-arrow-up"></i>
                <strong>انتخاب تصویر</strong>
                <small>کلیک کنید یا فایل را اینجا رها کنید</small>
            </span>
        </label>
        <div class="admin-preview-wrap mt-2">
            <img class="rounded admin-preview-image" id="avatar{{$unique_id}}" src="" alt="">
            <button type="button" class="btn btn-danger btn-sm delete-preview-image rounded-circle p-0 position-absolute" style="display: none;" id="delete_preview_image{{$unique_id}}">
                <i class="bi bi-x d-flex"></i>
            </button>
        </div>
    </div>
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
        <div class="gallery flex-wrap" id="image_preview{{$unique_id}}" style="display: none"></div>
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
                    dt.items.add(file) // here you exclude the file. thus removing it.
            }
            input.files = dt.files // Assign the updates list
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
                                $($.parseHTML('<img>')).attr('src', event.target.result).addClass('img-gallery-thumb'),
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
