<div class="pdp-variants">
    <div class="pdp-variants__group" v-for="mainSpec in filteredMainSpecs" :key="mainSpec.main_id">
        <div v-if="mainSpec.main_is_color == '1'">
            @include('pages.product-detail._partials.colors')
        </div>
        <div v-else>
            @include('pages.product-detail._partials.text')
        </div>
    </div>
</div>
