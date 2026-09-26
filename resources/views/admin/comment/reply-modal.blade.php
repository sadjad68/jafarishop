@push('modals')
<div class="modal fade" id="exampleModal{{$row['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title fs-5" id="exampleModalLabel">پاسخ به {{@$row['name']}}</p>
                <button type="button" class="btn-close shadow-none me-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body p-2">
                <form  @submit.prevent="validateForm" method="POST" action="{{route('admin.comment.reply')}}" class="m-0">
                    @csrf
                    <input type="hidden" name="reply_id" value="{{@$row['id']}}">
                    <input type="hidden" name="commentable_id" value="{{@$row['commentable_id']}}">
                    <input type="hidden" name="commentable_type" value="{{@$row['commentable_type']}}">
                    <input type="hidden" name="name" value="ادمین سایت">
                    <input type="hidden" name="mobile" value="{{@Auth::user()->mobile}}">
                    <input type="hidden" name="user_id" value="{{@Auth::user()->id}}">
                    <input type="hidden" name="status" value="1">
                    <div class="row w-100 m-0">
                        <div class="col-12 p-2">
                            <div class="form-group">
                                <div class="form-group">
                                    <x-cms-text-area
                                        name="content"
                                        label="متن نظر"
                                        :validations="['requiredCms']"
                                        type="text"
                                        :valueData="@$data"
                                    />
                                </div>
                            </div>
                            <div class="w-100 pe-0">
                                <button type="submit"
                                        class="btn btn-custom rounded-custom w-fit px-3 py-2 float-end">
                                    ذخیره
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
@endpush
