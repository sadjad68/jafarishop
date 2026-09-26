<div class="col-xxl-6 col-sm-6 p-2 d-flex align-items-end justify-content-start me-auto">
    <div class="mx-1" style="width: 100%;">
        <label class="form-label">انتخاب متغییر اصلی</label>
        <v-select
            multiple
            name="main_variant_specification_id"
            :options="sortedSpecifications"
            label="title"
            v-model="mainVariantSpecificationId"
            :reduce="option => option.id"
            :clearable="false"
            :searchable="true"
            :value="mainVariantSpecificationId"
            @input="handleMainVariantChange"
        ></v-select>
    </div>
    <input type="hidden" name="main_variant_specification_id[]" v-for="item in mainVariantSpecificationId" :value="item">

</div>
