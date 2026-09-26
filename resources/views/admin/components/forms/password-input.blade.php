<div class="admin-field">
    <label class="admin-label" for="{{$name.'-'.$unique_id}}">
        {{$label}}
        @if($validations && in_array("requiredCms",$validations))
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="admin-password-wrap">
        <input
            id="{{$name.'-'.$unique_id}}"
            name="{{$name}}"
            class="form-control admin-input"
            type="password"
            @foreach($pure_validations as $row)
                {{$row}}
            @endforeach
            @foreach($valuable_validations as $key=>$row)
                {{$key}}="{{$row}}"
            @endforeach
            placeholder="{{$label . ' را وارد کنید'}}"
            autocomplete="new-password"
        />
        <button class="admin-password-toggle" type="button" id="button{{$unique_id}}" onclick="togglePassword{{$unique_id}}(event)" aria-label="نمایش رمز عبور">
            <i class="bi bi-eye-slash" aria-hidden="true"></i>
        </button>
    </div>
</div>

@push('scripts')
    <script>
        function togglePassword{{$unique_id}}(e){
            const password = document.getElementById('{{$name.'-'.$unique_id}}');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            const toggleButton = document.getElementById('button{{$unique_id}}');
            toggleButton.setAttribute('aria-label', type === 'password' ? 'نمایش رمز عبور' : 'مخفی کردن رمز عبور');
            toggleButton.innerHTML = type === 'password'
                ? '<i class="bi bi-eye-slash" aria-hidden="true"></i>'
                : '<i class="bi bi-eye" aria-hidden="true"></i>';
        }
    </script>
@endpush
