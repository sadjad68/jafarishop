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
                    <x-cms-multiple-image-input
                        name="image"
                        label="تصویر "
                        :imageSrc="isset($data) ? $data->images->map(function($item){ return $item;}) : []"
                        :validations="['requiredCms']"
                        :deletable="true"
                        :withThumbnail="false"
                        folder="work-sample"
                        :deleteUrl="'model='.\App\Modules\Service\Entities\WorkSampleImage::class"
                        width="1000"
                        height="1000"
                        cropper="1"
                    />
                </div>
                <div class="col-12 row w-100 m-0 p-2"
                     v-if="selectedType == 'before-after'">
                    <div class="col-xl-3 col-lg-4 col-sm-6 p-1 align-self-start">
                        <div class="d-flex">
                            <div class="p-1 w-100">
                                <div class="form-group">
                                    <label>
                                        تصویر قبلی
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control bg-light rounded-custom" accept="image/*" @change="onFileChange('Before')">
                                    <div class="position-relative mt-2" v-if="croppedImageUrlBefore">
                                        <img class="rounded w-25" :src="croppedImageUrlBefore" alt="Cropped Image Preview">
                                        <button type="button" class="btn btn-danger btn-sm delete-preview-image rounded-circle p-0 position-absolute" style="top: 5px; right: 5px;" @click="deleteImage('Before')">
                                            <i class="bi bi-x d-flex"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if(isset($data))
                                <div  id="data-image{{$unique_id}}" style="display: block">
                                    <div class="image-container p-1 position-relative">
                                        <a href="{{route('admin.common.remove-image','model='.\App\Modules\Service\Entities\WorkSampleImage::class.'&name=before_image')}}" class="btn btn-danger btn-sm delete-image rounded-circle p-0 position-absolute" data-bs-toggle="tooltip" data-bs-title="حذف تصویر" style="top: 0;right: 0">
                                            <i class="bi bi-x d-flex"></i>
                                        </a>
                                        <img class="rounded shadow border img-gallery-thumb" src="{{@$data->getBeforeImage()}}">
                                    </div>
                                </div>
                            @endif
                            <div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">قسمت مورد نظر را انتخاب کنید</h5>
                                            <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="img-container">
                                                <img ref="image" :src="modalImageSrcBefore" alt="Image to Crop">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                                            <button type="button" class="btn btn-primary" @click="cropImage('Before')">بریدن</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="progress" v-if="uploading">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" :style="{width: uploadProgress + '%'}">@{{ uploadProgress }}%</div>
                            </div>
                            <div class="alert" role="alert" v-if="alertMessage">@{{ alertMessage }}</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 p-1 align-self-start">
                        <div class="d-flex">
                            <div class="p-1 w-100">
                                <div class="form-group">
                                    <label>
                                        تصویر بعدی
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control bg-light rounded-custom" accept="image/*" @change="onFileChange('After')">
                                    <div class="position-relative mt-2" v-if="croppedImageUrlAfter">
                                        <img class="rounded w-25" :src="croppedImageUrlAfter" alt="Cropped Image Preview">
                                        <button type="button" class="btn btn-danger btn-sm delete-preview-image rounded-circle p-0 position-absolute" style="top: 5px; right: 5px;" @click="deleteImage('After')">
                                            <i class="bi bi-x d-flex"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            @if(isset($data))
                                <div  id="data-image{{$unique_id}}" style="display: block">
                                    <div class="image-container p-1 position-relative">
                                        <a href="{{route('admin.common.remove-image','model='.\App\Modules\Service\Entities\WorkSampleImage::class.'&name=after_image')}}" class="btn btn-danger btn-sm delete-image rounded-circle p-0 position-absolute" data-bs-toggle="tooltip" data-bs-title="حذف تصویر" style="top: 0;right: 0">
                                            <i class="bi bi-x d-flex"></i>
                                        </a>
                                        <img class="rounded shadow border img-gallery-thumb" src="{{@$data->getImage()}}">
                                    </div>
                                </div>
                            @endif
                            <div class="modal fade" id="cropperModalAfter" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">قسمت مورد نظر را انتخاب کنید</h5>
                                            <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="img-container">
                                                <img ref="imageAfter" :src="modalImageSrcAfter" alt="Image to Crop">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                                            <button type="button" class="btn btn-primary" @click="cropImage('After')">بریدن</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="progress" v-if="uploading">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" :style="{width: uploadProgress + '%'}">@{{ uploadProgress }}%</div>
                            </div>
                            <div class="alert" role="alert" v-if="alertMessage">@{{ alertMessage }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 p-2 d-flex align-items-end">
                    <div class="form-group">
                        <x-cms-check-box
                            name="show_in_first_page"
                            label="نمایش در صفحه اول"
                            :valueData="@$data"
                        />
                    </div>
                </div>




            </div>
        </div>

        <input type="file" id="croppedImageBefore" name="before_image" class="d-none">
        <input type="file" id="croppedImageAfter" name="after_image" class="d-none">
        <div class="w-100 pe-0">
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                ذخیره
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{asset('assets/admin/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
    <script src="{{asset('assets/admin/cropper/jquery.min.js')}}"></script>
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


                modalImageSrc: '',
                croppedImageUrl: '',
                croppedImageUrlBefore: '',
                croppedImageUrlAfter: '',
                showModal: false,
                showModalAfter: false,
                showModalBefore: false,
                cropper: null,
                modalImageSrcBefore: '',
                modalImageSrcAfter: '',
                croppedImageBefore: '',
                croppedImageAfter: '',
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
                    console.log(element);
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
                        console.log(errors);
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
                changeType() {
                    console.log(this.selectedType)
                    this.selectedType = (this.selectedType === "multiple") ? "before-after" : "multiple";
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
                    }
                },
                changePage() {
                    this.hasPage = (this.hasPage === "true") ? "false" : "true";
                    this.loadCkEditor();
                },




                //Crop Before & After
                onFileChange(type) {
                    const files = event.target.files;
                    if (files.length > 0) {
                        const file = files[0];
                        const propertyName = `modalImageSrc${type}`;
                        this[propertyName] = URL.createObjectURL(file);
                        const modalName = `showModal${type}`;
                        this[modalName] = true;
                    }
                },
                cropImage(type) {
                    if (this.cropper) {
                        const propertyName = `croppedImageUrl${type}`;
                        const inputName = `croppedImage${type}`;
                        const canvas = this.cropper.getCroppedCanvas({
                            width: this.width,
                            height: this.height
                        });

                        canvas.toBlob(function (blob) {
                            if (!blob) {
                                console.error('Canvas is empty');
                                return;
                            }
                            // Create a new File from the blob
                            var file = new File([blob], 'croppedImage.jpg', { type: 'image/jpeg' });
                            const croppedImageInput = document.getElementById(inputName);
                            var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            croppedImageInput.files = dataTransfer.files;
                        }, 'image/jpeg');


                        this[propertyName] = canvas.toDataURL('image/jpeg');


                        // Here you can handle the file upload to the server
                        // $('#cropperModal').modal('hide');

                        const modalName = `showModal${type}`;
                        this[modalName] = false;
                    }
                },
                deleteImage(type) {
                    const propertyName = `croppedImageUrl${type}`;
                    this[propertyName] = '';
                }
            },
            watch: {
                showModalAfter(newVal) {


                    if (newVal) {
                        this.$nextTick(() => {

                            $('#cropperModalAfter').modal('show');
                            const imageElement = this.$refs.imageAfter;
                            this.cropper = new Cropper(imageElement, {
                                aspectRatio: this.width / this.height,
                                viewMode: 1
                            });
                        });
                        this.croppedImageUrlAfter = this.$refs.imageAfter;
                    } else {
                        if (this.cropper) {
                            this.cropper.destroy();
                            this.cropper = null;
                        }
                    }
                    console.log(this.$refs.imageAfter);
                    $('#cropperModalAfter').modal('hide');
                },
                showModalBefore(newVal) {
                    if (newVal) {
                        this.$nextTick(() => {
                            console.log('watch')
                            console.log('this.cropper2')
                            console.log(this.cropper)
                            $('#cropperModal').modal('show');
                            const imageElement = this.$refs.image;
                            this.cropper = new Cropper(imageElement, {
                                aspectRatio: this.width / this.height,
                                viewMode: 1
                            });
                        });
                        this.croppedImageUrlBefore = this.$refs.image;
                    } else {
                        console.log('this.cropper3')
                        console.log(this.cropper)
                        if (this.cropper) {
                            this.cropper.destroy();
                            this.cropper = null;
                        }
                    }
                    console.log(this.$refs.image);
                    $('#cropperModal').modal('hide');
                }
            },
            mounted() {
                this.loadCkEditor();
            }
        });
    </script>
@endpush
