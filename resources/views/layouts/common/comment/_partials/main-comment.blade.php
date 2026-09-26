<div class="main-comment pdp-comment sk-comment position-relative">
    <div class="pdp-comment__header header sk-comment__header d-flex align-items-start justify-content-between gap-2">
        <div class="pdp-comment__meta box-name-star">
            <div class="pdp-comment__author d-flex align-items-center">
                <span class="pdp-comment__avatar sk-comment__avatar">
                    <img src="{{ asset('assets/site/images/avatar.png') }}" width="40" height="40" loading="lazy" alt="" title="">
                </span>
                <div>
                    <p class="pdp-comment__name sk-comment__name m-0 font-bold">{{ $comment['name'] }}</p>
                    <time class="sk-comment__date" datetime="{{ optional($comment->created_at)->toAtomString() }}">{{ $comment->date }}</time>
                </div>
            </div>
            <div class="pdp-comment__rating sk-comment__rating d-flex align-items-center gap-2 mt-2">
                @php
                    $commentRate = (int) ($comment->rate ?? 0);
                    $commentRate = max(0, min(5, $commentRate));
                    $commentRateFa = \App\Modules\General\Helper\NumberHelper::latin2PersianDigit((string) $commentRate);
                @endphp
                <div class="sk-comment__stars" role="img" aria-label="امتیاز {{ $commentRateFa }} از ۵">
                    @for($star = 1; $star <= 5; $star++)
                        <i class="bi {{ $star <= $commentRate ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>
                    @endfor
                </div>
                <p class="pdp-comment__score m-0 number-star">
                    ({{ $commentRateFa }} از ۵)
                </p>
            </div>
        </div>

        <button type="button"
                class="pdp-comment__reply-btn sk-comment__reply btn border-0 shadow-none"
                data-bs-toggle="modal"
                data-bs-target="#comment-reply-{{ $comment['id'] }}"
                aria-label="پاسخ به نظر {{ $comment['name'] }}">
            <i class="bi bi-reply d-flex" aria-hidden="true"></i>
            <span>پاسخ</span>
        </button>
    </div>
    <div class="pdp-comment__body body sk-comment__body mt-3">
        <p class="pdp-comment__text sk-comment__text font-re m-0">{{ $comment['content'] }}</p>
    </div>
</div>
