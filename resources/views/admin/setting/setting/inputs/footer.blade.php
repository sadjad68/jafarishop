<div id="footer-elements">
        <button type="button"
                class="btn me-2 p-0 bg-transparent border-0 mt-4 mb-3 fs-6 shadow-none d-flex align-items-center gap-2 text-info "
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                data-bs-title="راهنمای صفحات" title="راهنمای صفحات">
            <i class="bi bi-info-square d-flex"></i>
            راهنمای صفحات
        </button>

    <div class="col-sm-12 col-12 p-2">
        <button @click="addItem" type="button" class="btn btn-custom-b rounded-custom w-100">
            افزودن گزینه دسترسی سریع
        </button>
    </div>
    <p class="setting-note">
        توجه داشته باشید لینک هارو بدون آدرس سایت داخل فیلد URL قرار بدید. برای مثال : /blogs
    </p>
    <input name="footer_data" type="hidden" :value="JSON.stringify(items)" />
    <div class="row w-100 setting-repeat-item" v-for="(item,index) in items">
        <div class="col-sm-4 col-12 p-2">
            <div class="form-group">
                <label for="">عنوان لینک</label>
                <input v-model="item.title" type="text" class="form-control bg-light rounded-custom"
                       placeholder="">
            </div>
        </div>
        <div class="col-sm-4 col-12 p-2">
            <div class="form-group">
                <label for="">URL صفحه</label>
                <input v-model="item.url" requiredcms="" type="text" class="form-control bg-light rounded-custom"
                       placeholder="" value="">
            </div>
        </div>

        <div class="col-sm-1 col-12 p-2">
            <button @click="deleteItem(index)" type="button" class="btn btn-custom-b rounded-custom w-fit"
                    style="margin: 1.3rem;">
                حذف
            </button>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
    <script type="text/javascript">
        new Vue({
            el: "#footer-elements",
            data: {
                items: @json(json_decode(@$data->value))
            },
            methods: {
                addItem() {
                    this.items.push({title: "", url: ""});
                },
                deleteItem(index){
                    this.items.splice(index, 1);
                }
            }
        });
    </script>
@endpush
