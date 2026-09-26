<div class="admin-upload setting-upload col-12 my-2">
    <div class="admin-field">
        <label class="admin-label">{{@$data['p_name']}}</label>
        <div class="admin-upload-media">
            <label class="admin-dropzone" for="imgInp">
                <input type="file" class="admin-dropzone-input" name="{{@$data['type']}}[{{@$data['key']}}][]" multiple
                       accept="image/*" id="imgInp" value="{{old('image')}}">
                <span class="admin-dropzone-body">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <strong>انتخاب تصاویر</strong>
                    <small>کلیک کنید یا فایل را اینجا رها کنید</small>
                </span>
            </label>
            @if(isset($data))
                <div id="data-image" class="gallery d-flex flex-wrap gap-2" style="display: flex">
                    @foreach($data->image_array as $image)
                        <div class="admin-thumb-wrap">
                            <div class="image-container position-relative">
                                <img class="rounded img-gallery-thumb" src="{{$image}}" alt=""
                                     onerror="this.classList.add('is-broken')">
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="gallery flex-wrap d-flex gap-2" id="gallery" style="display: none"></div>
        </div>
    </div>
</div>

<script>
    function removeFileFromFileList(index) {
        const dt = new DataTransfer()
        const input = document.getElementById('imgInp')
        const { files } = input
        for (let i = 0; i < files.length; i++) {
            const file = files[i]
            if (index !== i)
                dt.items.add(file) // here you exclude the file. thus removing it.
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
                                if (document.querySelectorAll('.image-container').length === 0) {
                                    $('#imgInp').val('');
                                }
                                @if(isset($data))
                                $('#data-image').css('display', 'flex');
                                @endif
                            });
                    }
                    reader.readAsDataURL(file);
                });
            }
        };
        $('#imgInp').on('change', function () {
            if (this.files.length > 0) {
                $('#gallery').empty();
            }
            imagesPreview(this, '#gallery');
            $('#gallery').css('display', 'flex');
            $('#data-image').css('display', 'none');
        });
    });
</script>
