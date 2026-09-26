<div class="product-image">
    <div class="app-figure d-none d-lg-block">
        <div class="pdp-studio">
        <div class="pdp-gallery pdp-studio__plate">
            {{-- دکمه اصلی تصویر --}}
            <button type="button" @click="setActiveItem(activeImageIndex)" class="btn-image-modal w-100 p-0 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#exampleModal" :aria-label="'بزرگنمایی ' + product.title">
                <img :src="main_image" :srcset="main_image + ' 2x'" :alt="product.title" :title="product.title" width="1000" height="1000" class="w-100 h-auto" />
            </button>
        </div>
        {{-- نمایش تصاویر کوچک — فیلم‌استریپ استودیو --}}
        <div class="btn-more pdp-studio__film">
            <div class="row row-cols-5 m-0 px-1 pdp-studio__thumbs">
                {{-- تا ۴ تصویر --}}
                <div class="col p-1" v-for="(row, key) in images.slice(0, 4)" :key="key">
                    <button type="button" @click.prevent="previewDesktopImage(key)" class="w-100 btn-image-detail" :class="{ 'is-active': activeImageIndex === key }" :aria-label="'نمایش تصویر ' + (key + 1)" :aria-pressed="activeImageIndex === key ? 'true' : 'false'">
                        <img :src="row.image_small" :alt="product.title" :title="product.title" width="100" height="100" class="p-0 w-100 h-auto" />
                    </button>
                </div>
                {{-- اگر بیشتر از ۵ تا تصویر بود --}}
                <div class="col p-1" v-if="images.length > 5">
                    <button class="btn-more-img" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        @{{ images.length - 4 }}+
                        <br>
                        مشاهده
                    </button>
                </div>
            </div>
        </div>
        </div>
        {{-- Modal --}}
        <div class="modal modal-pro-img fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content bg-modal carousel slide" id="proImgIndicatorsDesk">
                    <div class="modal-body p-0">
                        <button type="button" class="btn-close pdp-lightbox__close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                        </button>
                        <div class="pdp-lightbox__body col-xxl-5 col-xl-6 col-lg-7 p-4 p-lg-5 m-auto d-flex justify-content-center align-items-center h-100">
                            <div class="pdp-lightbox__stage position-relative w-100">
                                <div class="carousel-inner">
                                    <div class="carousel-item" :class="{ active: index === 0 }" v-for="(row, index) in images" :key="'main-' + index">
                                        <img :src="row.image_big || row.image_small" :alt="product.title" width="1000" height="1000" class="w-100 h-auto pdp-lightbox__image" />
                                    </div>
                                </div>
                                <button class="carousel-control-prev pdp-lightbox__nav" type="button" data-bs-target="#proImgIndicatorsDesk" data-bs-slide="prev">
                                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next pdp-lightbox__nav" type="button" data-bs-target="#proImgIndicatorsDesk" data-bs-slide="next">
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-0">
                        <div class="carousel-indicators pdp-lightbox__thumbs d-flex gap-2">
                            <button v-for="(row, index) in images" :key="'indicator-' + index" type="button" data-bs-target="#proImgIndicatorsDesk" :data-bs-slide-to="index" :class="{ active: index === 0 }" :aria-current="index === 0 ? 'true' : null" :aria-label="'Slide ' + (index + 1)" class="pdp-lightbox__thumb p-0 border-0 bg-transparent">
                                <img :src="row.image_small" :alt="product.title" width="80" height="80" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- نسخه موبایل --}}
    <div class="slider-mobile d-block d-lg-none">
        <div class="pdp-mobile-gallery">
            <div v-if="mobileCarouselReady" id="proImgIndicatorsMob" class="carousel slide pdp-mobile-gallery__carousel">
                <div class="pdp-mobile-gallery__stage">
                    <div class="carousel-inner">
                        <div class="carousel-item" v-for="(row, key) in images" :key="'mobile-' + key" :class="{ active: key === 0 }">
                            <div class="pdp-mobile-gallery__frame">
                                <button
                                    type="button"
                                    class="pdp-mobile-gallery__zoom"
                                    @click="openMobileImageSheet(key)"
                                    :aria-label="'بزرگنمایی ' + product.title">
                                    <img
                                        :src="row.image_big"
                                        width="700"
                                        height="700"
                                        :title="product.title"
                                        :alt="product.title"
                                        class="pdp-mobile-gallery__image"
                                        loading="lazy"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-indicators pdp-mobile-gallery__dots" v-if="images.length > 1">
                        <button
                            v-for="(row, index) in images"
                            :key="'indicator-' + index"
                            type="button"
                            data-bs-target="#proImgIndicatorsMob"
                            :data-bs-slide-to="index"
                            :class="{ active: index === 0 }"
                            :aria-current="index === 0 ? 'true' : null"
                            :aria-label="'Slide ' + (index + 1)"
                        ></button>
                    </div>
                    <button class="carousel-control-prev pdp-mobile-gallery__nav" v-if="images.length > 1" type="button" data-bs-target="#proImgIndicatorsMob" data-bs-slide="prev">
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next pdp-mobile-gallery__nav" v-if="images.length > 1" type="button" data-bs-target="#proImgIndicatorsMob" data-bs-slide="next">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-bottom pdp-mobile-lightbox h-auto rounded-top-4 rounded-bottom-0"
            tabindex="-1"
            id="pdpMobileImageSheet"
            aria-labelledby="pdpMobileImageSheetLabel">
            <div class="pdp-mobile-lightbox__handle" aria-hidden="true"></div>
            <div class="offcanvas-header border-0 py-2 px-3">
                <h2 class="offcanvas-title fs-6 text-truncate mb-0 pe-2" id="pdpMobileImageSheetLabel">
                    @{{ product.title }}
                </h2>
                <button type="button" class="btn-close flex-shrink-0" data-bs-dismiss="offcanvas" aria-label="بستن"></button>
            </div>
            <div class="offcanvas-body p-0 pb-3">
                <div id="proImgIndicatorsMobSheet" class="carousel slide pdp-mobile-lightbox__carousel">
                    <div class="pdp-mobile-lightbox__stage">
                        <div class="carousel-inner h-100">
                            <div
                                class="carousel-item h-100"
                                v-for="(row, index) in images"
                                :key="'mobile-sheet-' + index"
                                :class="{ active: index === 0 }">
                                <img
                                    :src="row.image_big || row.image_small"
                                    :alt="product.title"
                                    :title="product.title"
                                    width="1000"
                                    height="1000"
                                    class="pdp-mobile-lightbox__image"
                                />
                            </div>
                        </div>
                        <div class="carousel-indicators pdp-mobile-lightbox__dots" v-if="images.length > 1">
                            <button
                                v-for="(row, index) in images"
                                :key="'sheet-indicator-' + index"
                                type="button"
                                data-bs-target="#proImgIndicatorsMobSheet"
                                :data-bs-slide-to="index"
                                :class="{ active: index === 0 }"
                                :aria-current="index === 0 ? 'true' : null"
                                :aria-label="'Slide ' + (index + 1)"
                            ></button>
                        </div>
                        <button class="carousel-control-prev pdp-mobile-lightbox__nav" v-if="images.length > 1" type="button" data-bs-target="#proImgIndicatorsMobSheet" data-bs-slide="prev">
                            <i class="bi bi-chevron-right" aria-hidden="true"></i>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next pdp-mobile-lightbox__nav" v-if="images.length > 1" type="button" data-bs-target="#proImgIndicatorsMobSheet" data-bs-slide="next">
                            <i class="bi bi-chevron-left" aria-hidden="true"></i>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
