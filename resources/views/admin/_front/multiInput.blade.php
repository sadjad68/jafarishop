@extends('admin._layouts.master')
@section('title','داشبورد')
@section('content')
<div id="app">
    <div>
        <h2>Select an image</h2>
        <input multiple type="file" @change="onFileChange">
    </div>
    <div v-if="images">
        <div v-for="(image, index) in images">
            <img :src="image" />
            <button @click="removeImage(index)">Remove image</button>
        </div>
    </div>
</div>
@push('styles')
<style>
    #app {
        text-align: center;
    }

    img {
        width: 30%;
        margin: auto;
        display: block;
        margin-bottom: 10px;
    }

    button {}
</style>
@endpush
@push('scripts')
<script src="{{asset('assets/admin/js/vue.js')}}"></script>
<script>
    new Vue({
        el: '#app',
        data: {
            images: []
        },

        methods: {
            onFileChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length) return;
                this.createImage(files);
            },
            createImage(files) {
                var vm = this;
                for (var index = 0; index < files.length; index++) {
                    var reader = new FileReader();
                    reader.onload = function (event) {
                        vm.images.push(event.target.result);
                    };
                    reader.readAsDataURL(files[index]);
                }
            },
            removeImage(index) {
                this.images.splice(index, 1);
            }
        }
    });
</script>

@endpush
@endsection
