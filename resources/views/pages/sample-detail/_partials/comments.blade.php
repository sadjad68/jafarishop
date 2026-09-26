<section class="comments">
    <div class="container">
        <div
            class="title-section position-relative mb-sm-5 mb-4 text-center col-xxl-5 col-xl-6 col-lg-7 col-md-12 m-auto p-0">
            <p class="fw-bolder h2 mb-4 title">نظرات کاربران</p>
        </div>
        <div class="row w-100 m-0">
            <div class="col-xl-3 col-lg-4 col-md-5 p-1 pe-lg-2">
                @include('pages.sample-detail._partials.comment.comment-form')
            </div>
            <div class="col-xl-9 col-lg-8 col-md-7 p-1 ps-lg-2">
                @forelse($comments as $comment)
                    @include('pages.sample-detail._partials.comment.main-comment')
                    @include('pages.sample-detail._partials.comment.reply-comment')

                    <!-- reply form modal -->
                    @include('pages.sample-detail._partials.comment.reply-modal')
                @empty
                    {{--            //Todo ui: empty for comment--}}
                @endforelse
            </div>
        </div>
    </div>
</section>
@push('scripts')
    <script src="{{asset('assets/site/js/tpl-sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('assets/site/js/tpl-form-validate.js')}}"></script>
@endpush
