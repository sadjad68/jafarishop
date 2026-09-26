<div class="col-xxl-3 col-sm-6 col-12 px-md-2 px-0 my-2">
<label>
{{$data['p_name']}}
</label>
    <select class="w-100 boot-select selectpicker" name="{{$data['key']}}" data-live-search="true"  placeholder="انتخاب کنید">
        @foreach(\Config::get('settings.menu_types') as $key3 => $menu_type)
            <option value="{{$key3}}" @if($data['value'] == $key3) selected @endif>
                {{$menu_type}}
            </option>
        @endforeach

</select>
</div>
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/admin/css/233bootstrap-select.min.css')}}">
@endpush
@push('scripts')
    <script src="{{asset('assets/admin/js/833bootstrap-select.min.js')}}"></script>
@endpush
