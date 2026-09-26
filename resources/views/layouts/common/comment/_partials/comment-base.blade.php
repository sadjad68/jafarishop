<div class="pdp-comments sk-comments__grid row w-100 m-0 g-3">
    <div class="sk-comments__form-col col-xl-3 col-lg-4 col-md-5 p-1 pe-lg-2">
        <div class="pdp-comments__form-wrap sk-comments__form-wrap">
            @include('layouts.common.comment._partials.comment-form')
        </div>
    </div>
    <div class="sk-comments__list-col col-xl-9 col-lg-8 col-md-7 p-1 ps-lg-2">
        <div class="pdp-comments__list sk-comments__list">
            @forelse($comments as $comment)
                <article class="pdp-comments__thread sk-comments__thread">
                    @include('layouts.common.comment._partials.main-comment')
                    @include('layouts.common.comment._partials.reply-comment')
                    @include('layouts.common.comment._partials.reply-modal')
                </article>
            @empty
                <div class="pdp-comments__empty sk-comments__empty text-center">
                    <span class="sk-comments__empty-icon d-none" aria-hidden="true">
                        <i class="bi bi-chat-dots"></i>
                    </span>
                    <div class="col-xxl-3 col-xl-4 col-lg-5 col-md-6 col-sm-7 col-6 p-0 m-auto sk-comments__empty-img">
                        <img src="{{ asset('assets/site/images/empty-states/message_empty.png') }}" class="w-100" alt="" loading="lazy">
                    </div>
                    <p class="pdp-comments__empty-title sk-comments__empty-title font-md mb-0 mt-3">هنوز هیچ نظری ثبت نشده است</p>
                    <p class="pdp-comments__empty-sub sk-comments__empty-sub font-th small mb-0">اولین نفری باشید که نظر خود را می‌نویسد.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('assets/site/js/tpl-sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/site/js/tpl-form-validate.js') }}"></script>
@endpush
