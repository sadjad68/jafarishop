<div class="col-xxl-3 col-sm-6 col-12 px-md-2 px-0 my-2">
<label>
{{$data['p_name']}}
</label>
    <select id="theme-select" class="w-100 boot-select selectpicker" name="{{$data['key']}}" data-live-search="true"
            >
        @foreach(\Config::get('themes') as $key3 => $theme)
            <option value="{{$key3}}" @if($data['value'] == $key3) selected @endif>
                {{$theme['name']}}
            </option>
        @endforeach

</select>
</div>
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/admin/css/233bootstrap-select.min.css')}}">
@endpush
@push('scripts')
    <script>
        const selectEl = document.getElementById('theme-select');
        let previousValue = selectEl.value;
        let oldText = selectEl.children



        selectEl.addEventListener('change', function () {

            Swal.fire({
                title: 'آیا مطمئن هستید که تم تغییر کند؟',
                text: 'لطفا قبل از تغییر تم، ویدیوهای آموزشی مربوط به این قسمت را مشاهده کنید',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'بله',
                cancelButtonText: 'خیر',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cms-form').submit();
                } else {
                    $("#theme-select").selectpicker('val', previousValue);
                    // $('#theme-select').selectpicker('refresh');

                }
            });
        });
    </script>
@endpush
