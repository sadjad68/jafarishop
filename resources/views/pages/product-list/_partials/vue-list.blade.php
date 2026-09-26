<template v-if="products.length > 0" class="row w-100 m-0">
    <div v-for="product in products" class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6 p-sm-2 p-1">
    @include('layouts.common.product.vue-product-box')
    </div>
</template>
<template v-if="filterLoading == true">
    @include('pages._shared.plp-loading', ['variant' => 'skeleton'])
</template>
<template v-if="isFilter == true && products.length == 0 && filterLoading == false && loading == false && titleLoading == false">
    @include('pages._shared.plp-empty', ['message' => 'محصولی با این فیلتر پیدا نشد.'])
</template>
<template v-if="stopCall == false && loading == true && filterLoading == false">
    @include('pages._shared.plp-loading', ['variant' => 'more'])
</template>
