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

        .alert {
            display: none;
        }

        #cropperModal .img-container {
            max-width: 100%;
            max-height: min(70vh, 640px);
            margin: auto;
            display: block;
            overflow: hidden;
            background: #111;
        }

        #cropperModal .img-container img {
            display: block;
            max-width: 100%;
        }
    </style>
@endpush
@php
    $unique_id = Illuminate\Support\Str::random(10);
@endphp
<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        <div class="container-fluid">
            <div class="card-block row w-100 m-0">
                <div class="col-12 p-2">
                    <div
                        class="form-check border border-custom2 w-fit rounded-custom  rounded-custom p-0 d-flex align-items-center">
                        <input class="form-check-input my-2 ms-2"
                               style="width: 30px; height: 30px;"
                               id="has_page"
                               @if(@$data->has_page == 1) checked="checked" @endif
                               value="1"
                               name="has_page"
                               type="checkbox"
                               @click="changePage()"
                        >
                        <label class="form-check-label p-2" for="has_page">
                            دارای صفحه مجزا
                        </label>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 col-12 p-2">
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
                <div class="col-xxl-4 col-sm-6 col-12 p-2">
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
                <div class="col-xxl-4 col-sm-6 col-12 p-2" v-if="hasPage == 'true'">
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
                <div class="col-12 p-2" v-if="hasPage == 'true'">
                    <div class="form-group">
                        <x-cms-text-area
                            name="short_description"
                            label="توضیحات کوتاه "
                            type="text"
                            :valueData="@$data"
                        />
                    </div>
                    <div class="col-12 px-0 py-2">
                        <div class="form-group">
                            <x-cms-ck-editor
                                name="description"
                                label="توضیحات"
                                type="text"
                                :valueData="@$data"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card-block row w-100 m-0">
                <div class="col-12 p-2">
                    <div
                        class="form-check border border-custom2 w-fit rounded-custom  rounded-custom p-0 d-flex align-items-center">
                        <input class="form-check-input my-2 ms-2"
                               style="width: 30px; height: 30px;"
                               id="double_image"
                               @if(@$data->double_image == 1) checked="checked" @endif
                               value="1"
                               name="double_image"
                               type="checkbox"
                               @click="changeType()"
                        >
                        <label class="form-check-label p-2" for="double_image">
                            نمایش بصورت قبلی-بعدی
                        </label>
                    </div>
                </div>


                <div class="col-12 p-2 d-flex flex-sm-row align-items-sm-start" v-if="selectedType == 'multiple'">
                    <div class="p-1 w-100">
                        <div class="form-group" id="images">
                            <label>
                                تصاویر
                                (سایز : w1000 * h1000 )
                            </label>
                            <input multiple type="file" class="form-control bg-light rounded-custom" accept="image/*"
                                   @change="cropHandler($event, true, 'images')">
                            <input type="file" id="images-input" name="images[]" class="d-none" multiple>
                            <div id="thumbs" class="row"></div>
                        </div>
                    </div>
                    @if(isset($data))
                        <div id="list">
                            <ul class="p-0 m-0 d-flex align-items-center flex-wrap rounded-custom overflow-hidden">
                                @foreach($data->images as $img)
                                    <li class="list-unstyled  p-3 shadow-sm rounded-lg" id="arrayorder_{{$img['id']}}" style="background-color: #eae8fd">
                                        <div class="image-container p-1 position-relative">
                                            <a href="{{route('admin.common.delete-image', 'model='.\App\Modules\Service\Entities\WorkSampleImage::class . '&id=' . @$img['id'])}}"
                                               type="button" class="btn btn-danger btn-sm delete-image rounded-circle p-0 position-absolute"
                                               data-bs-toggle="tooltip" data-bs-title="حذف تصویر">
                                                <i class="bi bi-x d-flex"></i>
                                            </a>
                                            <img class="rounded shadow border img-gallery-thumb" src="{{$img->getImage()}}">
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="col-12 row w-100 m-0 p-2"
                     v-if="selectedType == 'before-after'">
                    <div class="col-xl-3 col-lg-4 col-sm-6 p-1 align-self-start">
                        <div class="p-1 w-100">
                            <div class="form-group" id="before">
                                <label>
                                    تصویر قبل
                                    (سایز : w1000 * h1000 )
                                </label>
                                <input  type="file" class="form-control bg-light rounded-custom"
                                       accept="image/*"
                                       @change="cropHandler($event, false, 'before')">
                                <input type="file" id="before-input" name="before" class="d-none">
                                <div id="thumbs" class="row"></div>
                            </div>
                        </div>
                        @if(isset($data))
                            <div id="data-image{{$unique_id}}" style="display: block">
                                <div class="image-container p-1 position-relative">
                                    <img class="rounded shadow border img-gallery-thumb" src="{{$data->getBeforeImage()}}">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 p-1 align-self-start">
                        <div class="p-1 w-100">
                            <div class="form-group" id="after">
                                <label>
                                    تصویر بعد
                                    (سایز : w1000 * h1000 )
                                </label>
                                <input  type="file" class="form-control bg-light rounded-custom"
                                       accept="image/*"
                                       @change="cropHandler($event, false, 'after')">
                                <input type="file" id="after-input" name="after" class="d-none">
                                <div id="thumbs" class="row"></div>
                            </div>
                        </div>
                        @if(isset($data))
                            <div id="data-image{{$unique_id}}" style="display: block">
                                <div class="image-container p-1 position-relative">
                                    <img class="rounded shadow border img-gallery-thumb" src="{{$data->getImage()}}">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Modal is hoisted to body by admin-theme.js; keep plain DOM (no Vue bindings). --}}
                <div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel"
                     aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cropperModalLabel">قسمت مورد نظر را انتخاب کنید</h5>
                                <button type="button" class="btn btn-close" id="cropperModalClose" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="img-container">
                                    <img id="cropperModalImage" src="" alt="">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" id="cropperModalCancel">انصراف</button>
                                <button type="button" class="btn btn-primary" id="cropperModalCrop">بریدن</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                @include('admin.components.show-first-page',['section'=>'sample'])
                </div>
            </div>
        </div>
        <div class="w-100 pe-0">
            @include('admin._layouts.blocks.utils.page-getter')
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                ذخیره
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{asset('assets/admin/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
    <script src="{{asset('assets/admin/cropper/cropper.js')}}"></script>
    <script type="text/javascript">
        new Vue({
            el: "#cms-form-sample",
            data: {
                selectedType: '{{@$data->double_image == 0 ? 'multiple' : 'before-after'}}',
                hasPage: '{{@$data->has_page == 1 ? 'true' : 'false'}}',
                uploading: false,
                uploadProgress: 0,
                width: 1000,
                height: 1000,
                alertMessage: '',

                cropImages: [],
                cropper: null,
                cropConfig: {},
                cropModalBound: false
            },
            methods: {
                errorsGenerator(inputs) {
                    const inputErrors = [];
                    inputs.forEach(element => {
                        const errors = [];
                        const isEmpty = element.value.trim() === '';

                        if (element.hasAttribute("requiredCms")) {
                            if (isEmpty) {
                                errors.push({message: "وارد کردن مقدار الزامیست", validation: "requiredCms"});
                            }
                        }

                        if (errors.length > 0) {
                            inputErrors.push({element: element, errors: errors});
                        }
                    });
                    return inputErrors;
                },
                resetErrorElements(inputs) {
                    inputs.forEach(element => {
                        element.style.border = '';
                        const previousError = element.nextSibling;
                        if (previousError && previousError.tagName === 'P') {
                            previousError.parentNode.removeChild(previousError);
                        }
                    })
                    return true;
                },
                clearError(element) {
                    element.style.border = '';
                    const previousError = element.nextSibling;
                    if (previousError && previousError.tagName === 'P') {
                        previousError.parentNode.removeChild(previousError);
                    }
                },
                addListenerInput(element) {
                    const vm = this;
                    element.addEventListener('input', function () {
                        vm.clearError(element);
                    });
                    element.addEventListener('change', function () {
                        vm.clearError(element);
                    });
                    return true;
                },
                validateForm(submitEvent) {
                    const inputs = submitEvent.target.querySelectorAll('input,select,textarea');
                    this.resetErrorElements(inputs);
                    const errors = this.errorsGenerator(inputs);
                    if (errors.length > 0) {
                        errors.forEach(error => {
                            const errorElement = document.createElement('p');
                            errorElement.textContent = error.errors[0].message;
                            errorElement.style.color = "red";
                            errorElement.style.display = 'none';
                            error.element.style.border = '1px solid red';
                            errorElement.style.display = 'block';
                            error.element.parentNode.insertBefore(errorElement, error.element.nextSibling);
                            this.addListenerInput(error.element);
                        })
                        Swal.fire({
                            icon: 'error',
                            text: "کاربر گرامی اطلاعات فرم را به درستی پر کنید",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });

                        return false;
                    } else {
                        submitEvent.target.submit();
                    }
                },
                loadCkEditor() {
                    if (this.hasPage === "true") {
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
                                                if (evt.data.$.ctrlKey && evt.data.$.shiftKey && evt.data.dataKey === 86) {
                                                    evt.data.preventDefault();
                                                    event.editor.execCommand('paste');
                                                }
                                            });
                                        }
                                    }
                                });
                            });
                        }, 100);
                    }
                },
                changePage() {
                    this.hasPage = (this.hasPage === "true") ? "false" : "true";
                    this.loadCkEditor();
                },
                changeType() {
                    this.selectedType = (this.selectedType === "multiple") ? "before-after" : "multiple";
                    document.querySelectorAll('#thumbs').forEach(function(element) {
                        $(element).empty();
                    });
                },
                getCropModal() {
                    return document.getElementById('cropperModal');
                },
                getCropImageEl() {
                    return document.getElementById('cropperModalImage');
                },
                bindCropModalControls() {
                    if (this.cropModalBound) {
                        return;
                    }
                    const modal = this.getCropModal();
                    if (!modal) {
                        return;
                    }
                    const vm = this;
                    document.getElementById('cropperModalClose')?.addEventListener('click', function () {
                        vm.cancelCrop();
                    });
                    document.getElementById('cropperModalCancel')?.addEventListener('click', function () {
                        vm.cancelCrop();
                    });
                    document.getElementById('cropperModalCrop')?.addEventListener('click', function () {
                        vm.cropImage();
                    });
                    modal.addEventListener('shown.bs.modal', function () {
                        vm.initCropperInstance();
                    });
                    modal.addEventListener('hidden.bs.modal', function () {
                        vm.destroyCropperInstance();
                    });
                    this.cropModalBound = true;
                },
                destroyCropperInstance() {
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                },
                initCropperInstance() {
                    const image = this.getCropImageEl();
                    if (!image || !image.getAttribute('src')) {
                        return;
                    }
                    this.destroyCropperInstance();
                    this.cropper = new Cropper(image, {
                        aspectRatio: this.width / this.height,
                        viewMode: 1,
                        autoCropArea: 1,
                        responsive: true,
                        background: false
                    });
                },
                openCropModal() {
                    const modal = this.getCropModal();
                    if (!modal) {
                        return;
                    }
                    if (window.bootstrap && bootstrap.Modal) {
                        bootstrap.Modal.getOrCreateInstance(modal).show();
                        return;
                    }
                    $(modal).modal('show');
                },
                closeCropModal() {
                    const modal = this.getCropModal();
                    if (!modal) {
                        return;
                    }
                    if (window.bootstrap && bootstrap.Modal) {
                        const instance = bootstrap.Modal.getInstance(modal);
                        if (instance) {
                            instance.hide();
                        }
                        return;
                    }
                    $(modal).modal('hide');
                },
                cropHandler(event, multiple, name) {
                    this.bindCropModalControls();
                    this.cropConfig = {multiple: multiple, name: name};
                    this.cropImages = [];
                    const files = event && event.target ? event.target.files : [];
                    if (!files || files.length === 0) {
                        return;
                    }
                    for (let i = 0; i < files.length; i++) {
                        this.cropImages.push(URL.createObjectURL(files[i]));
                    }
                    event.target.value = '';
                    this.showCropperModal();
                },
                showCropperModal() {
                    const image = this.getCropImageEl();
                    if (!image || this.cropImages.length === 0) {
                        return;
                    }
                    this.destroyCropperInstance();
                    image.src = this.cropImages[0];
                    this.openCropModal();
                },
                cancelCrop() {
                    if (this.cropImages.length > 1) {
                        URL.revokeObjectURL(this.cropImages.shift());
                        this.destroyCropperInstance();
                        const image = this.getCropImageEl();
                        if (image) {
                            image.src = this.cropImages[0];
                            this.$nextTick(() => {
                                this.initCropperInstance();
                            });
                        }
                        return;
                    }
                    if (this.cropImages.length === 1) {
                        URL.revokeObjectURL(this.cropImages.shift());
                    }
                    this.closeCropModal();
                    const image = this.getCropImageEl();
                    if (image) {
                        image.removeAttribute('src');
                    }
                },
                async cropImage() {
                    if (!this.cropper) {
                        return;
                    }
                    const parentElement = $("#" + this.cropConfig.name + " > #thumbs");
                    const image_name = Date.now();
                    const name = this.cropConfig.name;

                    const canvas = this.cropper.getCroppedCanvas({
                        width: 1000,
                        height: 1000,
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high'
                    });
                    if (!canvas) {
                        return;
                    }

                    const vm = this;
                    await new Promise(function (resolve) {
                        canvas.toBlob(function (blob) {
                            if (!blob) {
                                console.error('Canvas is empty');
                                resolve();
                                return;
                            }
                            var file = new File([blob], image_name + '.jpg', {type: 'image/png'});
                            const croppedImageInput = document.getElementById(vm.cropConfig.name + "-input");
                            var dataTransfer = new DataTransfer();
                            if (vm.cropConfig.multiple) {
                                for (let i = 0; i < croppedImageInput.files.length; i++) {
                                    dataTransfer.items.add(croppedImageInput.files[i]);
                                }
                                dataTransfer.items.add(file);
                                croppedImageInput.files = dataTransfer.files;
                            } else {
                                dataTransfer.items.add(file);
                                croppedImageInput.files = dataTransfer.files;
                            }
                            resolve();
                        }, 'image/png');
                    });

                    if (!this.cropConfig.multiple) {
                        parentElement.empty();
                    }

                    const div = $('<div>', {
                        class: 'admin-preview-wrap position-relative mt-2 col-md-1',
                        id: image_name
                    });
                    const img = $('<img>', {
                        class: 'rounded w-25',
                        style: "width: 100px !important;",
                        src: canvas.toDataURL('image/png'),
                    });

                    var button = $('<button>', {
                        type: 'button',
                        class: 'btn btn-danger btn-sm delete-preview-image rounded-circle p-0 position-absolute',
                        click: () => this.deleteImage(name, image_name)
                    }).append($('<i>', {class: 'bi bi-x d-flex'}));
                    div.append(img).append(button);
                    div.appendTo(parentElement);

                    if (this.cropImages.length > 1) {
                        URL.revokeObjectURL(this.cropImages.shift());
                        this.destroyCropperInstance();
                        const image = this.getCropImageEl();
                        if (image) {
                            image.src = this.cropImages[0];
                            this.$nextTick(() => {
                                this.initCropperInstance();
                            });
                        }
                        return;
                    }

                    if (this.cropImages.length === 1) {
                        URL.revokeObjectURL(this.cropImages.shift());
                    }
                    this.closeCropModal();
                    const image = this.getCropImageEl();
                    if (image) {
                        image.removeAttribute('src');
                    }
                },
                deleteImage(inputName, image_name) {
                    $("#" + image_name)[0].remove();
                    const croppedImageInput = document.getElementById(inputName + "-input");
                    const dataTransfer = new DataTransfer();
                    for (let i = 0; i < croppedImageInput.files.length; i++) {
                        const element_name = image_name + '.jpg';
                        if (croppedImageInput.files[i].name !== element_name) {
                            dataTransfer.items.add(croppedImageInput.files[i]);
                        }
                    }
                    croppedImageInput.files = dataTransfer.files;
                }
            },
            watch: {},
            mounted() {
                this.bindCropModalControls();
                this.loadCkEditor();
            }
        });
    </script>
@endpush
