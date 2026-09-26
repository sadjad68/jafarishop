<div class="modal fade sk-comments-modal"
     id="comment-reply-{{ $comment['id'] }}"
     tabindex="-1"
     aria-labelledby="comment-reply-title-{{ $comment['id'] }}"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered sk-comments-modal__dialog">
        <div class="modal-content bg-transparent border-0 sk-comments-modal__content">
            <div class="sk-comments-modal__panel">
                <span class="sk-comments-modal__handle" aria-hidden="true"></span>
                <button type="button"
                        class="sk-comments-modal__close btn bg-transparent border-0 shadow-none"
                        data-bs-dismiss="modal"
                        aria-label="بستن">
                    <i class="bi bi-x-lg text-light" aria-hidden="true"></i>
                </button>
                <header class="sk-comments-modal__head">
                    <span class="sk-comments-modal__icon" aria-hidden="true">
                        <i class="bi bi-reply"></i>
                    </span>
                    <div>
                        <h2 id="comment-reply-title-{{ $comment['id'] }}" class="sk-comments-modal__title">پاسخ به نظر</h2>
                        <p class="sk-comments-modal__hint">{{ $comment['name'] }}</p>
                    </div>
                </header>
                <div class="modal-body p-0">
                    @include('layouts.common.comment._partials.comment-form', [
                        'formPrefix' => 'comment-reply-'.$comment['id'],
                        'replyId' => $comment['id'],
                        'showRating' => false,
                        'submitLabel' => 'ثبت پاسخ',
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
