
<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <label for="">فرستنده</label>
                <input requiredCms type="text" class="form-control bg-light rounded-custom" name="name" placeholder="" value="{{ @$data->name }}" >
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <label for="">موبایل</label>
                <input requiredCms type="text" class="form-control bg-light rounded-custom" name="mobile" placeholder="" value="{{ @$data->mobile }}"  disabled>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <label for=""> ارسال شده در بخش {{ @$data->model_name }} </label>
                <input requiredCms type="text" class="form-control bg-light rounded-custom" name="commentable" placeholder="" value="{{ @$data->commentable->title }}"  disabled>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <label for="rate"> ستاره  </label>
                <input requiredCms type="text" class="form-control bg-light rounded-custom" name="rate" placeholder="" value="{{ @$data->rate }}" >
            </div>
        </div>
        <div class="col-12 p-2">
            <div class="form-group">
                <label for="">متن نظر</label>
                <textarea  type="text" class="form-control bg-light rounded-custom" name="content"
                    >@if(isset($data)){{$data->content}}@else{{old('content')}}@endif</textarea>
            </div>
        </div>
        <div class="col-md-6 mt-2">
            <div class="form-check border border-custom2 w-fit rounded-custom  rounded-custom p-0 d-flex align-items-center">
                <input class="form-check-input my-2 ms-2"
                       style="width: 30px; height: 30px;"
                       @if(isset($data) && $data->status == 1) checked="checked" @endif value="1" name="status" type="checkbox" role="switch" id="flexSwitchCheckChecked">
                <label class="form-check-label p-2" for="flexSwitchCheckChecked">نمایش نظر در صفحه </label>
            </div>
        </div>
        <div class="p-2 text-end">
            @include('admin._layouts.blocks.utils.page-getter')
            <button type="submit"  id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-4 py-2">
                ذخیره
            </button>
        </div>
    </div>
</div>
@push('scripts')

<script type="text/javascript">
function persianKeyUp(e) {
const pattern = /^[a-z+0-9]+$/gmi;
if(!pattern.test(e.target.value[(e.target.value.length)-1])){
    swal('', 'لطفا کیبورد خود را انگلیسی  کنید' , 'info');
    e.target.value = "";
    e.target.style.border = '1px solid red';
                        event.target.value = "";
                return false;
            }
}
</script>
@endpush
