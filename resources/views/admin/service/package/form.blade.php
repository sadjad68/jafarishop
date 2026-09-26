<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-input
                    name="title"
                    label="عنوان"
                    :validations="['requiredCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-input
                    name="url"
                    label="آدرس url"
                    :validations="['requiredCms','urlCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <x-cms-image-input
                name="image"
                label="تصویر (سایز : w540 * h205 )"
                :imageSrc="(isset($data) && $data->image) ? $data->getImage() : null"
                :validations="['requiredCms']"
                width="400"
                height="180"
                 cropper="1"
            />
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <x-cms-multi-select
                name="services"
                label="خدمات"
                :options="$services"
                optionValue="id"
                optionLabel="title"
                :searchable="true"
                :validations="['requiredCms']"
                :selectedOptions="isset($data) ? $data->services->map(function($item){return $item->id;})->toArray() : (isset($service) ? array($service->id) : []) "

            />
        </div>
{{--        Todo : format prices live--}}
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-input
                    name="price"
                    label="(تومان)قیمت"
                    :validations="['numberCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-input
                    name="discounted_price"
                    label="(تومان)قیمت بعد از تخفیف"
                    :validations="['numberCms']"
                    type="text"
                    :valueData="@$data"
                    oninput="check()"
                />
            </div>
        </div>
        <div class="col-12 p-2">
            <div class="form-group">
                <x-cms-ck-editor
                    name="description"
                    label="توضیحات"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
        @include('admin.components.show-first-page',['section'=>'package'])
        </div>
        <div class="col-12 ms-auto p-2">
            @include('admin._layouts.blocks.utils.page-getter')
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-5">
                ذخیره
            </button>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{asset('assets/admin/ckeditor/ckeditor.js')}}"></script>
    <script>
        setTimeout(function () {
            var cks = document.getElementsByClassName('ckeditor4');
            Array.from(cks).forEach((el) => {
                CKEDITOR.replace("description", {
                    language: 'fa',
                    content: 'fa',
                    removeButtons: 'Font,FontSize,PasteFromWord',
                    filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
                    filebrowserUploadMethod: 'form',
                    on: {
                        instanceReady: function(event) {
                            event.editor._.forcePasteDialog = true;
                            event.editor.on('key', function(evt) {
                                if (evt.data.$.ctrlKey && evt.data.$.shiftKey && evt.data.dataKey === 86) { // 86 کد کلید V است
                                    evt.data.preventDefault();
                                    event.editor.execCommand('paste');
                                }
                            });
                        }
                    }
                });
            });
        }, 100);
    </script>

    <script type="text/javascript">
        function check() {
            var price = document.getElementById("price").value;
            var discounted_price = document.getElementById("discounted_price").value;
            if (parseInt(discounted_price) >= parseInt(price)) {
                Swal.fire({
                    icon: 'error',
                    text: "مبلغ تخفیف نمیتواند بیشتر از قیمت باشد ",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
                document.getElementById("discounted_price").value = "";
                return false;
            }
        }
    </script>
@endpush
