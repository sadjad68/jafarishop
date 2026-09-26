@foreach($comment['replies'] as $reply)
    <div class="reply-comment pdp-comment pdp-comment--reply sk-comment sk-comment--reply position-relative">
        <div class="pdp-comment__header header sk-comment__header d-flex align-items-center justify-content-between">
            <div class="pdp-comment__author d-flex align-items-center">
                <span class="pdp-comment__avatar pdp-comment__avatar--admin sk-comment__avatar">
                    <img src="{{ asset('assets/site/images/avatar.png') }}" width="36" height="36" loading="lazy" alt="" title="">
                </span>
                <div>
                    <p class="pdp-comment__name sk-comment__name m-0 font-bold">{{ $reply['name'] }}</p>
                    <span class="pdp-comment__badge sk-comment__badge">پاسخ</span>
                </div>
            </div>
            @if(!empty($reply->date))
                <time class="sk-comment__date" datetime="{{ optional($reply->created_at)->toAtomString() }}">{{ $reply->date }}</time>
            @endif
        </div>
        <div class="pdp-comment__body body sk-comment__body mt-2">
            <p class="pdp-comment__text sk-comment__text font-re m-0">{{ $reply['content'] }}</p>
        </div>
    </div>
@endforeach
