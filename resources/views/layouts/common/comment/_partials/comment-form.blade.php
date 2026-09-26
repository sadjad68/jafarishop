@php
    $formPrefix = isset($formPrefix) ? $formPrefix : 'comment-main';
    $showRating = isset($showRating) ? $showRating : true;
    $replyId = isset($replyId) ? $replyId : null;
    $submitLabel = isset($submitLabel) ? $submitLabel : ($replyId ? 'ثبت پاسخ' : 'ثبت نظر');
    $authUser = auth()->user();
    $prefillName = old('name', $authUser ? $authUser->full_name : '');
    $prefillMobile = old('mobile', $authUser ? $authUser->mobile : '');
@endphp
<form action="{{ route('post-comment') }}" method="POST" class="sk-comment-form">
    @csrf
    <input type="hidden" name="commentable_id" value="{{ $commentable_id }}">
    <input type="hidden" name="commentable_type" value="{{ $commentable_type }}">
    <input type="hidden" name="status" value="0">
    @if($replyId)
        <input type="hidden" name="reply_id" value="{{ $replyId }}">
    @endif
    <div class="comment-form">
        <div class="mb-3">
            <label for="{{ $formPrefix }}-name" class="form-label small mb-1 font-th dynamic-color">نام و نام خانوادگی</label>
            <input type="text"
                   class="form-control dynamic-color"
                   id="{{ $formPrefix }}-name"
                   name="name"
                   value="{{ $prefillName }}"
                   placeholder="نام خود را بنویسید"
                   autocomplete="name"
                   required
                   oninvalid="warnRequired(' نام ونام خانوادگی')">
        </div>
        <div class="mb-3">
            <label for="{{ $formPrefix }}-mobile" class="form-label small mb-1 font-th dynamic-color">شماره همراه</label>
            <input type="text"
                   class="form-control dynamic-color"
                   id="{{ $formPrefix }}-mobile"
                   name="mobile"
                   value="{{ $prefillMobile }}"
                   placeholder="شماره همراه خود را بنویسید"
                   inputmode="numeric"
                   autocomplete="tel"
                   dir="ltr"
                   required
                   oninvalid="warnRequired(' شماره تماس')"
                   onchange="checkMobile(event)">
        </div>
        <div class="mb-3">
            <label for="{{ $formPrefix }}-content" class="form-label small mb-1 font-th dynamic-color">نظر</label>
            <textarea class="form-control dynamic-color"
                      id="{{ $formPrefix }}-content"
                      name="content"
                      rows="4"
                      required
                      oninvalid="warnRequired(' متن نظر')"
                      placeholder="نظر خود را بنویسید"></textarea>
        </div>
        @if($showRating)
            <fieldset class="mb-3 sk-comment-form__rating">
                <legend class="form-label small mb-1 font-th dynamic-color">امتیاز خود را وارد کنید</legend>
                <div class="rating">
                    <input type="radio" id="{{ $formPrefix }}-star5" name="rate" value="5" checked>
                    <label class="star" for="{{ $formPrefix }}-star5" aria-label="۵ ستاره">
                        <span class="bi bi-star-fill d-flex" aria-hidden="true"></span>
                    </label>
                    <input type="radio" id="{{ $formPrefix }}-star4" name="rate" value="4">
                    <label class="star" for="{{ $formPrefix }}-star4" aria-label="۴ ستاره">
                        <span class="bi bi-star-fill d-flex" aria-hidden="true"></span>
                    </label>
                    <input type="radio" id="{{ $formPrefix }}-star3" name="rate" value="3">
                    <label class="star" for="{{ $formPrefix }}-star3" aria-label="۳ ستاره">
                        <span class="bi bi-star-fill d-flex" aria-hidden="true"></span>
                    </label>
                    <input type="radio" id="{{ $formPrefix }}-star2" name="rate" value="2">
                    <label class="star" for="{{ $formPrefix }}-star2" aria-label="۲ ستاره">
                        <span class="bi bi-star-fill d-flex" aria-hidden="true"></span>
                    </label>
                    <input type="radio" id="{{ $formPrefix }}-star1" name="rate" value="1">
                    <label class="star" for="{{ $formPrefix }}-star1" aria-label="۱ ستاره">
                        <span class="bi bi-star-fill d-flex" aria-hidden="true"></span>
                    </label>
                </div>
            </fieldset>
        @endif
        <p class="sk-comment-form__note">نظر شما پس از بررسی منتشر می‌شود.</p>
        <button type="submit" class="btn btn-form font-th position-relative">{{ $submitLabel }}</button>
    </div>
</form>
