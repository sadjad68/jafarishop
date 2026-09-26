@foreach($comment['replies'] as $reply)
    <div class="reply-comment col-11 me-auto position-relative mb-4">
        <div class="header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="{{asset('assets/site/images/avatar.png')}}" class="me-2" width="30" height="30" loading="lazy" alt="avatar" title="avatar">
                <p class="m-0 font-bold">{{$reply['name']}}</p>
            </div>

        </div>
        <div class="body mt-3">
            <p class="font-re m-0">
                {{$reply['content']}}
            </p>
        </div>
    </div>
@endforeach
