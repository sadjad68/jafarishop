<form action="{{ route('post-comment') }}" method="POST">
    @csrf
    <input type="hidden" name="commentable_id" value="{{$sample['id']}}">
    <input type="hidden" name="commentable_type" value="{{ get_class($sample) }}">
    <input type="hidden" name="status" value="0">
    <div class="comment-form">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label small mb-1 font-th text-secondary">نام و نام خانوادگی</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="نام خود را بنویسید" name="name"
                   required oninvalid="warnRequired(' نام ونام خانوادگی')">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label small mb-1 font-th text-secondary" >شماره همراه</label>
            <input type="text" class="form-control" id="mobile" placeholder="شماره همراه خود را بنویسید" name="mobile"
                   required oninvalid="warnRequired(' شماره تماس')"
                   onchange="checkMobile(event)">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label small mb-1 font-th text-secondary" >نظر</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="content"
                      required oninvalid="warnRequired(' متن نظر')"></textarea>
        </div>
        <button type="submit" class="btn btn-form font-th position-relative">ثبت</button>
    </div>
</form>
