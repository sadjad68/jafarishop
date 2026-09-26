@php
    $authUser = auth()->user();
    $prefillName = old('name', $authUser ? $authUser->full_name : '');
    $prefillMobile = old('mobile', $authUser ? $authUser->mobile : '');
@endphp
<div class="sk-contact__panel">
    <h2 class="sk-contact__title">ارسال پیام</h2>
    <form action="{{ route('us.post-contact') }}" method="POST">
        @csrf
        <div class="sk-contact__field">
            <label for="contact-name">نام و نام خانوادگی</label>
            <input type="text"
                   id="contact-name"
                   name="name"
                   value="{{ $prefillName }}"
                   required
                   autocomplete="name"
                   oninvalid="warnRequired('نام و نام خانوادگی')"
                   class="form-control">
        </div>
        <div class="sk-contact__field">
            <label for="mobile">شماره تماس</label>
            <input type="tel"
                   id="mobile"
                   name="mobile"
                   value="{{ $prefillMobile }}"
                   required
                   inputmode="tel"
                   autocomplete="tel"
                   oninvalid="warnRequired('شماره تماس')"
                   onchange="checkMobile(event)"
                   class="form-control"
                   dir="ltr">
        </div>
        <div class="sk-contact__field">
            <label for="contact-title">عنوان پیام</label>
            <input type="text"
                   id="contact-title"
                   name="title"
                   value="{{ old('title') }}"
                   required
                   oninvalid="warnRequired('عنوان پیام')"
                   class="form-control">
        </div>
        <div class="sk-contact__field">
            <label for="contact-message">متن پیام</label>
            <textarea id="contact-message"
                      name="message"
                      required
                      oninvalid="warnRequired('متن پیام')"
                      class="form-control"
                      rows="4">{{ old('message') }}</textarea>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="sk-contact__submit">ارسال پیام</button>
        </div>
    </form>
</div>
@include('layouts.common.sweetalert')
@push('scripts')
    <script src="{{asset('assets/site/js/tpl-form-validate.js')}}"></script>
@endpush
