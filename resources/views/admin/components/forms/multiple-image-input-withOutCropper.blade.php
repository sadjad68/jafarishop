    <div class="p-1 w-fit">
        <div class="form-group">
            <label>
                {{$label}}
                @if($validations && in_array("requiredCms",$validations)) <span class="text-danger">*</span> @endif
            </label>
            <input type="file" class="form-control bg-light rounded-custom" multiple name="{{$name}}[]"
            accept="image/*" id="image_input{{$unique_id}}" value="{{old($name)}}">
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



@push('scripts')
    <script src="{{asset('assets/admin/js/jquery-ui.js')}}"></script>
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
                        var imageContainer = $('<div class="image-container p-1 position-relative"></div>').append(
                            $($.parseHTML('<img>')).attr('src', event.target.result).addClass('img-gallery-thumb'),
                            deleteButton
                        );
                        imageContainer.appendTo(placeToInsertImagePreview);
                        deleteButton.on('click', function () {
                            removeFileFromFileList(0);
                            {{--console.log($('#image_input{{$unique_id}}'));--}}

                            $(this).closest('.image-container').remove();
                           if(document.querySelectorAll('.image-container').length === 0){
                               $('#image_input{{$unique_id}}').val('');
                           }
                        });
                        @if(isset($imageSrc))
                        $('#data-image').css('display', 'block');
                        @endif
                    }
                    reader.readAsDataURL(file);
                });
            }
        };
        $('#image_input{{$unique_id}}').on('change', function () {
            if (this.files.length > 0) {
                $('#gallery').empty();
            }
            imagesPreview(this, '#image_preview{{$unique_id}}');
            $('#image_preview{{$unique_id}}').css('display', 'flex');
            $('#data-image').css('display', 'none');
        });
    });
</script>
<meta name="csrf-token" content="{!! csrf_token() !!}"/>
<script type="text/javascript">
    $(document).ready(function () {
        function slideout() {
            setTimeout(function () {
                $("#response").slideUp("slow", function () {
                });

            }, 2000);
        }

        $("#response").hide();
        $(function () {
            $("#list ul").sortable({
                opacity: 0.8, cursor: 'move', update: function () {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    var deleteUrl = $('#delete-url').val();
                    var order = $(this).sortable("serialize") + '&update=update'+ '&' + deleteUrl + '&_token=' + CSRF_TOKEN;
                    $.post("{{route('admin.common.sort-image')}} ", order, function (theResponse) {
                        $("#response").html(theResponse);
                        $("#response").slideDown('slow');
                        Swal.fire({
                            icon: 'success',
                            text: "ترتیب با موفقیت تغییر کرد",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                    });

                }
            });
        });

    });
</script>

@endpush
