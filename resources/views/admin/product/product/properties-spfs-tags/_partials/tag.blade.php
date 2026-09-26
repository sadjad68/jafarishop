<div class="border p-1" id="tag-elements">
    <label for="type">
        تگ ها
    </label>
    <v-select multiple v-model="selectedTags" :options="tags"
              :reduce="tag => tag.id" key="id" label="title" name="tags"
              placeholder="انتخاب کنید">
    </v-select>
    <input type="hidden" name="tags[]" v-for="item in selectedTags" :value="item">
</div>

@push('scripts')
    <script type="text/javascript">
        new Vue({
            el: "#tag-elements",
            data: {
                tags: @json($tags),
                selectedTags: @json($product->tags()->pluck('tag_id')->toArray())
            }
        });
    </script>
@endpush
