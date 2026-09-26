@php $unqiue_str = \Illuminate\Support\Str::random(10); @endphp

<style>
    .img-gallery-thumb{
        width: 100px;
        height: 100px;
    }
    .gallery{
        max-width: 330px;
        width: max-content;
    }
</style>
<div class="d-flex">
    <div class="p-1 w-100">
        <div class="form-group">
            <label for="">تصویر</label>
            <input type="file" class="form-control bg-light rounded-custom" @if(isset($multiple)) name="image[]" multiple @else name="image" @endif
            accept="image/*" id="imgInp{{$unqiue_str}}" value="{{old('image')}}">
        </div>
    </div>

    <div class="">
        @if(isset($data))
            <div id="data-image" style="display: block">
                <img class="rounded shadow border img-gallery-thumb" src="@if(isset($data)){{$data->item_image}}@else{{old('image')}}@endif">
            </div>
        @endif

        <div class="gallery flex-wrap" id="gallery{{$unqiue_str}}" style="display: none"></div>
    </div>
</div>

<script>
    $(function () {
        var imagesPreview = function (input, placeToInsertImagePreview) {
            if (input.files) {
                var files = Array.from(input.files);

                files.map(function (file) {
                    var reader = new FileReader();

                    reader.onload = function (event) {

                        var deleteButton = $('<button type="button" class="btn btn-danger btn-sm delete-image rounded-circle p-0 position-absolute"><i class="bi bi-x d-flex"></i></button>');
                        var imageContainer = $('<div class="image-container p-1 position-relative"></div>').append(
                            $($.parseHTML('<img>')).attr('src', event.target.result).addClass('img-gallery-thumb'),
                            deleteButton
                        );

                        imageContainer.appendTo(placeToInsertImagePreview);

                        deleteButton.on('click', function () {
                            $(this).closest('.image-container').remove();
                            $('#imgInp').val('');
                        });
                    }

                    reader.readAsDataURL(file);
                });
            }
        };

        $('#imgInp{{$unqiue_str}}').on('change', function () {
            // Clear existing images only if there are new images selected
            if (this.files.length > 0) {
                $('#gallery').empty();
            }

            imagesPreview(this, '#gallery{{$unqiue_str}}');
            $('#gallery{{$unqiue_str}}').css('display', 'flex');
            $('#data-image').css('display', 'none');
        });
    });

</script>
