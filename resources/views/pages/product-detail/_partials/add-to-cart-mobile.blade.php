{{-- offcanvas انتخاب واریانت (موبایل) --}}
<div class="offcanvas offcanvas-bottom h-auto rounded-top-4 rounded-bottom-0" tabindex="-1" id="offcanvasVariant"
    aria-labelledby="offcanvasVariantLabel">
    <div class="offcanvas-header border-bottom">
        <span class="offcanvas-title text-secondary" id="offcanvasVariantLabel">
            گزینه محصول را انتخاب کنید
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="بستن"></button>
    </div>
    <div class="offcanvas-body px-3 py-3">
        <div class="pdp-info__variants--toolbar pb-2" v-if="Object.keys(selectedSpecs).length !== 0">
            <button type="button" @click="resetAllSelections" class="pdp-info__reset">
                <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                پاک کردن انتخاب‌ها
            </button>
        </div>
        @include('pages.product-detail._partials.components.variant-selector')
        <div class="pt-3" v-if="selectedVariant || !hasVariants">
            @include('pages.product-detail._partials.components.price')
        </div>
    </div>
    <div class="offcanvas-footer px-3 pt-2 pb-3 border-top" v-if="isAvailable">
        <button type="button" @click="addToBasket()" class="btn pdp-add-btn w-100 d-flex align-items-center justify-content-center gap-2"
            data-bs-dismiss="offcanvas" aria-label="بستن">
            <i class="bi bi-bag-plus d-flex"></i>
            افزودن به سبد
        </button>
    </div>
</div>
