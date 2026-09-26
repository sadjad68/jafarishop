<div class="main-comment position-relative mb-2">
    <div class="header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <img src="{{asset('assets/site/images/avatar.png')}}" class="me-2" width="30" height="30" loading="lazy" alt="avatar" title="avatar">
            <p class="m-0 font-bold">{{$comment['name']}}</p>
        </div>
        <!-- reply button -->
        <button type="button" class="btn border-0 bg-transparent shadow-none p-0" data-bs-toggle="modal" data-bs-target="#exampleModal{{$comment['id']}}">
            <i class="bi bi-reply d-flex fs-5"></i>
        </button>

    </div>
    <div class="body mt-3">
        <p class="font-re m-0">
            {{$comment['content']}}
        </p>
    </div>
</div>
