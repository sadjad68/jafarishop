<div class="form-group">
    <label for="">URL</label>
    <input requiredCms type="text" class="form-control bg-light rounded-custom" name="url" id="url"
           onkeyup="englishKeyUp(event)"
           placeholder="" value="@if(isset($data)){{$data->url}}@else{{old('url')}}@endif">
</div>
@push('scripts')
<script>
    function string_to_slug(str) {
        str = str.replace(/^\s/g, ""); // trim
        str = str.toLowerCase();
        str = str
            .replace(/\s+/g, "-") // collapse whitespace and replace by -
            .replace(/-+/g, "-"); // collapse dashes
        return str;
    }

    // const patternString = "[\'!٬٫٪×،^£$%&*()}{@#~?><>,|=:_ .+]+";
    // const patternString = "[\'\\[\\]!٬٫٪×،^£$%&*()}{@#~?><>,|=:_ .+؟]+";
    const patternString = "[\'\\[\\]!٬٫٪×،^£$%&*()}{@#~?><>,|=:_.+؟]+";
    const pattern = new RegExp(patternString, 'u');

    function englishKeyUp(e) {
        const input = document.getElementById('url');
        const input_val = input.value;

        if (pattern.test(e.key)) {
            var msg = "لطفا از کاراکترهای اضافی استفاده نکنید";
            Swal.fire({
                icon: 'error',
                text: msg,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });
            input.value = "";
            return false;
        }

        const ck = string_to_slug(input_val)
        input.value = ck;
    }
</script>
@endpush
