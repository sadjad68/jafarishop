
            <div class="container-fluid">
                <div class="card-block row w-100 m-0">
                    <div class="col-xxl-3 col-sm-6 col-12 p-2">
                        <div class="form-group">
                            <label for="">عنوان</label>
                            <input requiredCms type="text" class="form-control bg-light rounded-custom" name="title" placeholder="" value="@if(isset($data)){{$data->title}}@else{{old('title')}}@endif" >
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6 col-12 p-2">
                        @component('admin.components.admin.url-validate', ['data' => @$data])
                        @endcomponent
                    </div>
                    <div class="col-xxl-3 col-sm-6 col-12 p-2">
                        @include('admin._layouts.blocks.utils.image')
                    </div>
                    <div class="col-md-12 col-12 p-2">
                        <div class="form-group">
                            <label for="">توضیحات</label>
                            <textarea  type="text" class="form-control bg-light rounded-custom ckeditor" name="description"
                                >@if(isset($data)){{$data->description}}@else{{old('description')}}@endif</textarea>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="form-check border border-custom2 w-fit rounded-custom  rounded-custom p-0 d-flex align-items-center">
                            <input class="form-check-input my-2 ms-2"
                                   style="width: 30px; height: 30px;"
                                   @if(isset($data) && $data->active == 1) checked="checked" @endif value="1" name="active" type="checkbox" id="flexSwitchCheckChecked1">
                            <label class="form-check-label p-2" for="flexSwitchCheckChecked1">دسته دوره فعال است</label>
                        </div>
                    </div>
                    <div class="pe-2 text-end">
                        <button type="submit"  id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-4 py-2">
                            ذخیره
                        </button>
                    </div>
                </div>
            </div>
