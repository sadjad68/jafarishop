@extends('admin._layouts.master')
@section('title','داشبورد')
@section('content')
<br><br>
<div id="app">
    <v-select multiple :loading="options.length <= 0" v-model="selected" :options="options"
        :reduce="options => options.id" key="id" label="name" @input="onInput" placeholder="انتخاب کنید">
        <template #spinner="{ loading }">
            <div v-if="loading" class="vs__spinner" />
        </template>
    </v-select>
</div>
<br>
<br>
<select class="w-100 boot-select selectpicker" data-live-search="true" multiple placeholder="انتخاب کنید">
    <option>
        یک
    </option>
    <option>
        دو
    </option>
    <option>
        سه
    </option>
</select>
@push('styles')
<link rel="stylesheet" href="{{asset('assets/admin/css/233bootstrap-select.min.css')}}">
@endpush
@push('scripts')
<script src="{{asset('assets/admin/js/vue.js')}}"></script>
<script src="{{asset('assets/admin/js/833bootstrap-select.min.js')}}"></script>
<script src="{{asset('assets/admin/js/vue-select.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/admin/css/vue-select.css')}}">
<script>
    Vue.component("v-select", VueSelect.VueSelect);

    new Vue({
        el: "#app",
        data: {
            selected: null,
            options: [
                { name: "ریکت", id: 1 },
                { name: "ویو", id: 2 },
                { name: "لاراول", id: 3 },
                { name: "بوت استرپ", id: 4 }
            ]
        },
        mounted: {
            // APIからoptionの値を取得しthis.optionsに入れる
        },
        methods: {
            onInput() {
                // 確定時に発火
                console.log(this.selected);
            }
        }
    });

</script>
@endpush
@endsection
