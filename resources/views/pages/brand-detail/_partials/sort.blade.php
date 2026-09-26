<div class="row w-100 m-0 mb-3 plp-toolbar__row">
    <div class="col-lg-4 p-0">
        <div class="search position-relative">
            <input type="search" v-model="productTitle" class="form-control @if ($theme_provider->getValue() == 'theme2') form-control-lg @endif" placeholder="جستجوی نام محصول">
            <button @click="getProductAxios()" type="button" class="btn btn-search bg-transparent p-2 shadow-none border-0 position-absolute top-0 bottom-0 end-0">
                <i class="bi bi-search d-flex"></i>
            </button>
            <div class="spinner-border" v-if="titleLoading == true" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <div class="col-md-8 p-0 d-lg-block d-none">
        @include('pages.brand-detail.components.sort')
    </div>
</div>
