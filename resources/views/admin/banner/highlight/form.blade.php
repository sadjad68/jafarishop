@php
$unique_id = Illuminate\Support\Str::random(10);
$tags = $tags ?? collect();
$placeValues = $devices['desktop']['place']['values'] ?? [];
$tagCropDefault = \App\Modules\Banner\Services\HighlightService::tagBannerSize();
$hasStoredImage = isset($data) && is_string($data->getRawOriginal('image')) && trim($data->getRawOriginal('image')) !== '';
@endphp
<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-input name="title" label="عنوان" :validations="['requiredCms']" type="text"
                    :valueData="@$data" />
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-input name="link" label="لینک" :validations="[]" type="text" :valueData="@$data" />
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <label for="target">
                نمایش بر اساس
            </label>
            <span class="text-danger">*</span>
            <select id="target" class="w-100 form-control admin-input" name="target" v-model="bindTarget" requiredCms>
                <option value="place">جایگاه</option>
                <option value="tag">تگ</option>
            </select>
        </div>
        <input type="hidden" name="type" value="desktop">

        <div class="col-xxl-3 col-sm-6 col-12 p-2" v-if="bindTarget === 'tag'">
            <label for="tag_id_select">
                تگ
            </label>
            <span class="text-danger">*</span>
            <select id="tag_id_select" class="w-100 form-control admin-input mt-1" name="tag_id" v-model="selectedTagId" requiredCms>
                <option value="">انتخاب تگ</option>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                @endforeach
            </select>
            <p class="small text-muted mt-1 mb-0">برای هر تگ یک بنر ثبت می‌شود و در دسکتاپ و موبایل صفحه اول نمایش داده می‌شود.</p>
        </div>

        <template v-if="bindTarget === 'place'">
            <div class="col-xxl-3 col-sm-6">
                <div class="form-group">
                    <div class="d-flex align-items-center justify-content-between">
                        <label for="" class="mb-0">مکان قرار گیری <span class="text-danger">*</span></label>
                        <button type="button"
                            class="btn p-0 bg-transparent border-0 fs-6 shadow-none d-flex align-items-center gap-1 text-info"
                            data-bs-toggle="modal" data-bs-target="#sizeModal" data-bs-title="راهنمای سایز"
                            title="راهنمای سایز">
                            <i class="bi bi-info-square d-flex" aria-hidden="true"></i>
                            راهنمای سایز
                        </button>
                    </div>
                    <select class="w-100 form-control admin-input mt-2" name="place" data-live-search="true"
                        placeholder="انتخاب کنید" requiredCms v-model="selectedPlacement" @change="updateImageSize($event)">
                        <option value="">انتخاب کنید</option>
                        @foreach($placeValues as $key2 => $type)
                        <option value="{{$type['key']}}" data-width="{{$type['width']}}" data-height="{{$type['height']}}"
                            {{ @$data['place']==$type['key'] ? 'selected' : '' }}>
                            {{$key2}}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </template>

        <div class="col-12" v-show="bindTarget === 'tag'">
            <button type="button"
                class="btn p-0 bg-transparent border-0 fs-6 shadow-none d-flex align-items-center gap-1 text-info"
                data-bs-toggle="modal" data-bs-target="#sizeModalTag" title="راهنمای سایز (بنر تگ)">
                <i class="bi bi-info-square d-flex" aria-hidden="true"></i>
                راهنمای سایز (بنر تگ)
            </button>
            <div class="modal fade" id="sizeModalTag" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <p class="modal-title fs-5">راهنمای سایز بنر تگ</p>
                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{asset('assets/admin/images/sizes/desktop-tag-banner.jpg')}}" class="d-block mx-auto img-fluid" alt="راهنمای سایز بنر تگ">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.banner.highlight.size-modal')

        <div class="col-xxl-3 col-sm-6 col-12 p-2"
            v-show="(bindTarget === 'place' && selectedPlacement != '') || (bindTarget === 'tag' && selectedTagId !== '')">
            <div class="p-1 w-100">
                <div class="form-group" id="after">
                    <label>
                        تصویر
                        (سایز : w@{{ width }} * h@{{ height }} )
                        @if(! $hasStoredImage)
                            <span class="text-danger">*</span>
                        @endif
                    </label>

                    <input id="image-banner" @if(! $hasStoredImage) requiredCms @endif type="file"
                        class="form-control bg-light rounded-custom admin-input" accept="image/*"
                        v-on:change="cropHandler({{ '$event' }}, false, 'after')">

                    <input type="file" id="after-input" name="image" class="d-none">
                    <div id="thumbs" class="row"></div>
                </div>
            </div>
            @if(isset($data))
            <div id="data-image{{$unique_id}}" style="display: block">
                <div class="image-container p-1 position-relative">
                    <img class="rounded shadow border img-gallery-thumb" src="{{$data->image}}" alt="{{ $data->title ?? '' }}">
                </div>
            </div>
            @endif
        </div>
        <input type="hidden" name="width" :value="width">
        <input type="hidden" name="height" :value="height">

        @include('admin.components.show-first-page',['section'=>'highlight'])
        <div class="w-100 pe-0">
            @include('admin._layouts.blocks.utils.page-getter')
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                ذخیره
            </button>
        </div>
    </div>
</div>
@push('styles')
<link rel="stylesheet" href="{{asset('assets/admin/css/233bootstrap-select.min.css')}}">
<style>
    .select-vue .dropdown-menu {
        text-align: right;
        width: 100%;
        z-index: 1200;
    }
</style>
@endpush
@push('scripts')
<div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">قسمت مورد نظر را انتخاب کنید</h5>
                <button type="button" class="btn-close" id="highlight-crop-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="highlight-cropper-image" src="" alt="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="highlight-crop-cancel">انصراف</button>
                <button type="button" class="btn btn-primary" id="highlight-crop-apply">بریدن</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/admin/js/vue.js') }}"></script>
<script src="{{ asset('assets/admin/js/vue-select.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/admin/css/vue-select.css') }}">
<script src="{{asset('assets/admin/cropper/cropper.js')}}"></script>
<script type="text/javascript">
    new Vue({
        el: "#cms-form-highlight",
        data: {
            bindTarget: @json(old('target', isset($data) ? ($data->target ?? 'place') : 'place')),
            selectedPlacement: @json(old('place', isset($data) ? ($data->place ?? '') : '')),
            selectedTagId: @json(old('tag_id', isset($data) && $data->tag_id ? (string) $data->tag_id : '')),
            uploading: false,
            uploadProgress: 0,
            width: {{ (int) ($tagCropDefault['width'] ?? 1400) }},
            height: {{ (int) ($tagCropDefault['height'] ?? 308) }},
            alertMessage: '',
            placeValues: @json($placeValues),
            tagCropDefault: @json($tagCropDefault),
            cropImages: [],
            cropConfig: {},
        },
        created() {
            this.highlightCropper = null;
        },
        watch: {
            selectedPlacement(val) {
                this.checkImageValidation();
            },
            bindTarget(val, oldVal) {
                if (val === 'tag' && oldVal === 'place') {
                    this.selectedPlacement = '';
                    this.applyTagCropSizes();
                } else if (val === 'place' && oldVal === 'tag') {
                    this.selectedTagId = '';
                }
                this.checkImageValidation();
            },
            selectedTagId() {
                this.checkImageValidation();
            }
        },
        methods: {
            applyTagCropSizes() {
                const spec = this.tagCropDefault;
                if (spec) {
                    this.width = parseInt(spec.width, 10);
                    this.height = parseInt(spec.height, 10);
                }
            },
            checkImageValidation() {
                const element = document.getElementById("image-banner");
                if (!element) {
                    return;
                }
                @if (!empty($hasStoredImage))
                    element.removeAttribute('requiredCms');
                    return;
                @endif
                if (!element.hasAttribute('requiredCms')) {
                    element.setAttribute('requiredCms', 'true');
                }
            },
            updateImageSize(event) {
                const selectedOption = event.target.options[event.target.selectedIndex];
                this.width = parseInt(selectedOption.getAttribute('data-width') || 0, 10);
                this.height = parseInt(selectedOption.getAttribute('data-height') || 0, 10);
            },
            errorsGenerator(inputs) {
                const inputErrors = [];
                inputs.forEach(element => {
                    const errors = [];
                    const isEmpty = element.value.trim() === '';
                    if (element.hasAttribute("requiredCms") && isEmpty) {
                        errors.push({ message: "وارد کردن مقدار الزامیست", validation: "requiredCms" });
                    }
                    if (errors.length > 0) {
                        inputErrors.push({ element: element, errors: errors });
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
                });
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
                element.addEventListener('input', () => this.clearError(element));
                element.addEventListener('change', () => this.clearError(element));
                return true;
            },
            validateForm(submitEvent) {
                const inputs = submitEvent.target.querySelectorAll('input, select, textarea');
                this.resetErrorElements(inputs);
                const errors = this.errorsGenerator(inputs);
                if (errors.length > 0) {
                    errors.forEach(error => {
                        const errorElement = document.createElement('p');
                        errorElement.textContent = error.errors[0].message;
                        errorElement.style.color = "red";
                        error.element.style.border = '1px solid red';
                        error.element.parentNode.insertBefore(errorElement, error.element.nextSibling);
                        this.addListenerInput(error.element);
                    });
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
            cropHandler(evt, multiple, name) {
                this.cropConfig = { multiple, name };
                this.cropImages.forEach(function (url) {
                    URL.revokeObjectURL(url);
                });
                this.cropImages = [];
                const files = (evt && evt.target && evt.target.files) ? evt.target.files : [];
                if (files.length > 0) {
                    for (var i = 0; i < files.length; i++) {
                        this.cropImages.push(URL.createObjectURL(files[i]));
                    }
                    if (evt.target) {
                        evt.target.value = '';
                    }
                    this.showCropperModal();
                }
            },
            getHighlightModal() {
                const el = document.getElementById('cropperModal');
                if (window.bootstrap && bootstrap.Modal) {
                    return bootstrap.Modal.getOrCreateInstance(el, { backdrop: 'static', keyboard: false });
                }
                return {
                    show: function () { $(el).modal('show'); },
                    hide: function () { $(el).modal('hide'); }
                };
            },
            bindHighlightCropperButtons() {
                const modalEl = document.getElementById('cropperModal');
                if (modalEl && modalEl.parentElement !== document.body) {
                    document.body.appendChild(modalEl);
                }
                const applyBtn = document.getElementById('highlight-crop-apply');
                const cancelBtn = document.getElementById('highlight-crop-cancel');
                const closeBtn = document.getElementById('highlight-crop-close');
                const onCancel = (evt) => {
                    if (evt) {
                        evt.preventDefault();
                        evt.stopPropagation();
                    }
                    this.cancelCrop();
                };
                const onApply = (evt) => {
                    if (evt) {
                        evt.preventDefault();
                        evt.stopPropagation();
                    }
                    this.cropImage();
                };
                if (applyBtn) {
                    applyBtn.addEventListener('click', onApply);
                }
                if (cancelBtn) {
                    cancelBtn.addEventListener('click', onCancel);
                }
                if (closeBtn) {
                    closeBtn.addEventListener('click', onCancel);
                }
            },
            destroyHighlightCropper() {
                if (this.highlightCropper) {
                    this.highlightCropper.destroy();
                    this.highlightCropper = null;
                }
            },
            hideHighlightModal() {
                try {
                    this.destroyHighlightCropper();
                } catch (err) {
                    this.highlightCropper = null;
                }
                const modal = this.getHighlightModal();
                if (modal && typeof modal.hide === 'function') {
                    modal.hide();
                }
            },
            showCropperModal() {
                const image = document.getElementById('highlight-cropper-image');
                const modalEl = document.getElementById('cropperModal');
                if (!image || !modalEl || !this.cropImages.length) {
                    return;
                }
                this.destroyHighlightCropper();
                image.src = this.cropImages[0];

                const initCropper = () => {
                    this.destroyHighlightCropper();
                    const w = parseInt(this.width, 10) || 1;
                    const h = parseInt(this.height, 10) || 1;
                    this.highlightCropper = new Cropper(image, {
                        aspectRatio: w / h,
                        viewMode: 1,
                        autoCropArea: 1
                    });
                };

                const onShown = () => {
                    if (image.complete && image.naturalWidth > 0) {
                        initCropper();
                        return;
                    }
                    image.addEventListener('load', initCropper, { once: true });
                };

                if (modalEl.classList.contains('show')) {
                    onShown();
                    return;
                }
                modalEl.addEventListener('shown.bs.modal', onShown, { once: true });
                this.getHighlightModal().show();
            },
            cancelCrop() {
                this.hideHighlightModal();
                if (this.cropImages.length > 1) {
                    URL.revokeObjectURL(this.cropImages.shift());
                    this.$nextTick(() => this.showCropperModal());
                } else {
                    this.cropImages.forEach(function (url) {
                        URL.revokeObjectURL(url);
                    });
                    this.cropImages = [];
                    const image = document.getElementById('highlight-cropper-image');
                    if (image) {
                        image.src = '';
                    }
                }
            },
            async cropImage() {
                if (!this.highlightCropper) {
                    return;
                }
                const parentElement = $("#" + this.cropConfig.name + " > #thumbs");
                const image_name = Date.now();
                const name = this.cropConfig.name;

                const canvas = this.highlightCropper.getCroppedCanvas({
                    width: this.width,
                    height: this.height,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                });
                if (!canvas) {
                    return;
                }
                const vm = this;
                await canvas.toBlob(function (blob) {
                    if (!blob) {
                        console.error('Canvas is empty');
                        return;
                    }
                    var file = new File([blob], image_name + '.jpg', { type: 'image/png' });
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
                }, 'image/png');

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

                this.hideHighlightModal();
                if (this.cropImages.length > 1) {
                    URL.revokeObjectURL(this.cropImages.shift());
                    this.$nextTick(() => this.showCropperModal());
                } else {
                    this.cropImages.forEach(function (url) {
                        URL.revokeObjectURL(url);
                    });
                    this.cropImages = [];
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
        mounted() {
            @if (isset($data))
                if (this.bindTarget === 'place' && this.selectedPlacement) {
                    const foundBanner = Object.entries(this.placeValues).find(([title, value]) => value.key === this.selectedPlacement);
                    if (foundBanner) {
                        this.height = foundBanner[1].height;
                        this.width = foundBanner[1].width;
                    }
                } else if (this.bindTarget === 'tag') {
                    this.applyTagCropSizes();
                }
            @else
                if (this.bindTarget === 'tag') {
                    this.applyTagCropSizes();
                }
            @endif
            this.checkImageValidation();
            this.bindHighlightCropperButtons();
        }
    });
</script>

@endpush
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

    #cropperModal {
        z-index: 2000 !important;
    }

    #cropperModal .modal-dialog {
        pointer-events: auto;
        max-height: calc(100vh - 2rem);
    }

    #cropperModal .modal-content {
        overflow: hidden;
        max-height: calc(100vh - 2rem);
    }

    #cropperModal .modal-header,
    #cropperModal .modal-footer {
        position: relative;
        z-index: 6;
        pointer-events: auto;
        flex-shrink: 0;
    }

    #cropperModal .img-container {
        max-width: 100%;
        height: min(55vh, 480px);
        margin: auto;
        display: block;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    #cropperModal .img-container img {
        max-width: 100%;
        display: block;
    }
</style>
@endpush
