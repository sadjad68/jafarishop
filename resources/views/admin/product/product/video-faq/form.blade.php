<div class="">
    <input type="hidden" name="product_id" value="{{@$product->id}}">
    <div class="card-block row w-100 m-0">
        <div class="bg-light border p-3 rounded-5 mb-3 position-relative">
            <h4 class="p-0" style="font-weight: bold;">
                ویدیو ها
            </h4>
            <button type="button"
                    @click="addVideo"
                    class="btn btn-lg btn-outline-success rounded-custom btn-sm btn-add-form d-flex align-items-center"
            >
                <i class="bi bi-plus d-flex my-0"></i>
                افزودن
            </button>
            <hr>
            <div class="bg-light mt-1">
                <div class="row w-100 m-0" >
                    <div class="col-xl-12 col-sm-12 col-xs-12 p-1" v-for="(video, index) in videos" :key="index">
                        <label class="col-form-label" style="font-weight: bold;"> ویدیو شماره @{{ index+1 }}</label>
                        <button type="button"
                            @click="deleteVideo(index)"
                            class="btn btn-sm me-2 align-items-center"
                        >
                            <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                        </button>

                        <input type="hidden" :name="'videos[' + index + '][video_id]'" :value="video?.id">
                        <textarea
                            class="form-control rounded-custom"
                            :name="'videos[' + index + '][code]'"
                            placeholder="تگ ویدیو را وارد کنید.."
                            v-model="video.code"
                            requiredCms
                        ></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-light border p-3 rounded-5 mb-3 position-relative">
            <h4 class="p-0" style="font-weight: bold;">
                سوالات متداول
            </h4>
            <button type="button"
                    @click="addFaq"
                    class="btn btn-lg btn-outline-success rounded-custom btn-sm btn-add-form d-flex align-items-center"
            >
                <i class="bi bi-plus d-flex my-0"></i>
                افزودن
            </button>
            <hr>
            <div class="bg-light p-1">
                <div class="row w-100" v-for="(faq, index2) in faqs" :key="index2">
                    <input type="hidden" :name="'faqs[' + index2 + '][faq_id]'" :value="faq?.id">
                    <div class="col-xl-12 col-sm-12 col-xs-12 p-2">
                        <label class="col-form-label" style="font-weight: bold;"> سوال شماره @{{ index2+1 }}</label>
                        <button type="button"
                                @click="deleteFaq(index2)"
                                class="btn btn-sm me-2 align-items-center"
                        >
                            <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                        </button>
                    </div>
                    <div class="col-xl-6 col-sm-12 col-xs-12 p-2">
                        <textarea class="form-control rounded-custom" :name="'faqs[' + index2 + '][question]'" placeholder="متن سوال را وارد کنید..." v-model="faq.question" requiredCms></textarea>
                    </div>
                    <div class="col-xl-6 col-sm-12 col-xs-12 p-2">
                        <textarea class="form-control rounded-custom" :name="'faqs[' + index2 + '][answer]'" placeholder="متن پاسخ را وارد کنید..." v-model="faq.answer" requiredCms></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-100 pe-0 text-end">
            <button  class="btn btn-custom rounded-custom w-fit px-3 py-2" type="submit">
                ذخیره
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/admin/js/vue.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vue-select.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vue-select.css') }}">
    <script type="text/javascript">
        new Vue({
            el: "#cms-form-video-faq",
            data: {
                productId: {{ @$product->id }},
                videos: @json($product->videos),
                faqs: @json($product->faqs),
            },
            computed: {
                filteredValues() {
                    const query = this.searchQuery.toLowerCase();
                    return this.values.filter(value => value.title.toLowerCase().includes(query));
                }
            },
            methods: {
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
                async getVideos() {
                    this.loading = true;
                    try {
                        const response = await axios.get(`{{ route('admin.product-video-faq.list') }}?product_id=${this.productId}`);
                        if (response.data.videos.length > 0) {
                            this.videos = response.data.videos;
                        } else {
                            this.addVideo();
                        }
                        if (response.data.faqs.length > 0) {
                            this.faqs = response.data.faqs;
                        } else {
                            this.addFaq();
                        }
                    } catch (error) {
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                },
                addVideo() {
                    this.videos.push({ code: "" });
                },
                addFaq() {
                    this.faqs.push({question: "", video: ""});
                },
                async deleteVideo(videoIndex) {
                    if (this.videos[videoIndex]?.id) {
                        await this.deleteMain(this.videos[videoIndex].id);
                    }
                    this.videos = this.videos.filter((_, index) => {
                        return index !== videoIndex
                    });
                },
                async deleteFaq(faqIndex) {
                    if (this.faqs[faqIndex]?.id) {
                        await this.deleteMainFaq(this.faqs[faqIndex].id);
                    }
                    this.faqs = this.faqs.filter((_, index2) => {
                        return index2 !== faqIndex
                    });
                },

                async deleteMain(id) {
                    this.loading = true;
                    try {
                        await axios.get('{{ route('admin.product-video-faq.delete-video') }}/' +id);
                    } catch (error) {
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                },

                async deleteMainFaq(id) {
                    this.loading = true;
                    try {
                        await axios.get('{{ route('admin.product-video-faq.delete-faq') }}/' +id);
                        this.getVideos();
                    } catch (error) {
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                },
            },
            async mounted() {
                // await this.getVideos();
            }
        });

    </script>
    @include('admin._layouts.blocks.utils.confirmDelete')

@endpush
