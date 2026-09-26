
            <div class="container-fluid">
                <div class="card-block row w-100 m-0">
                    <div class="col-xxl-3 col-sm-6 col-12 p-2">
                        <div class="form-group">
                            <label for="">عنوان</label>
                            <input requiredCms type="text" class="form-control bg-light rounded-custom" name="title" placeholder="" value="@if(isset($data)){{$data->title}}@else{{old('title')}}@endif" >
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6 col-12 p-2 d-flex">
                        <div class="col-md-6 col-6 px-2">
                            <div class="form-group">
                                <label for="">ساعت</label>
                                <input requiredCms type="text" class="form-control bg-light rounded-custom" name="hours" placeholder="" value="@if(isset($data)){{$data->hours}}@else{{old('hours')}}@endif" >
                            </div>
                        </div>
                        <div class="col-md-6 col-6 px-2">
                            <div class="form-group">
                                <label for="">دقیقه</label>
                                <input requiredCms type="text" class="form-control bg-light rounded-custom" name="minutes" placeholder="" value="@if(isset($data)){{$data->minutes}}@else{{old('minutes')}}@endif" >
                            </div>
                        </div>
                    </div>
                    @if (isset($course))
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                    @else
                    <div class="col-xxl-3 col-sm-6 col-12 p-2 ">
                        <div class="form-group">
                            <div class="row w-100 m-0">
                                <div class="col-sm-6 p-1">
                                    <label for="order_status_id">انتخاب دوره </label>
                                    <input class="form-control bg-light rounded-custom" id="someinput" placeholder="جستجو">
                                </div>
                                <div class="col-sm-6 p-1">
                                    <label for="order_status_id">انتخاب دوره </label>
                                    <select id="optlist" class="form-select bg-light rounded-custom" name="course_id"
                                    >
                                        <option @if(isset($data) and $data->course_id == null)  selected @endif value="">
                                            دسته اصلی
                                        </option>
                                        @foreach ($courses as $row)
                                            <option @if(isset($data) and $data->course_id == $row['id']) selected @endif  value="{{$row['id']}}">
                                                {{$row['title']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif


                    <div class="col-xxl-3 col-sm-6 col-12 p-2">
                        @include('admin._layouts.blocks.utils.image')
                    </div>



                    <div class="col-xxl-3 col-sm-6 col-12 p-2">
                        <div class="form-group">
                            <label for=""> فایل ها </label>
                            <input class="form-control bg-light rounded-custom" type="file"  name="files[]" multiple>
                        </div>
                        @if(isset($data->sessionFiles))
                            @foreach ( @$data->sessionFiles as $file )
                                <div class="">
                                @if (str_contains($file->file, 'png') || str_contains($file->file, 'jpeg') ||
                                                        str_contains($file->file, 'jpg'))
                                    <img src="{{asset('assets/uploads/session/'.$file->file)}}" style="height: 150px; width: 40%">
                                @else
                                    <a href="{{asset('assets/uploads/session/'.$file->file)}}"  style="width: 40%"
                                       class="text-decoration-none" download>
                                        <p class="m-0 rounded bg-color10 color9 p-2 fs-12 align-center">
                                            {{@$file->file}}
                                            <i class="fs-5 color1 bi bi-paperclip"></i>
                                        </p>
                                    </a>
                                @endif

                                    <a type="button" class="btn btn-danger" href="{{url('admin/session-file/delete/'.$file['id'])}}">حذف</a>

                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-12 p-2">
                        <div class="form-group">
                            <label for="">توضیحات</label>
                            <textarea  type="text" class="form-control bg-light rounded-custom" name="description"
                                >@if(isset($data)){{$data->description}}@else{{old('description')}}@endif</textarea>
                        </div>
                    </div>
                    <div class="col-12 p-2 mt-2">
                        <ul class="list-inline p-0 m-0 list-unstyled">
                            <li class="list-inline-item">
                                <div class="form-check border border-custom2 w-fit rounded-custom  rounded-custom p-0 d-flex align-items-center">
                                    <input class="form-check-input my-2 ms-2"
                                           style="width: 30px; height: 30px;"
                                           @if(isset($data) && $data->free == 1) checked="checked" @endif value="1" name="free" type="checkbox" id="flexSwitchCheckChecked">
                                    <label class="form-check-label p-2" for="flexSwitchCheckChecked"> جلسه رایگان است</label>
                                </div>
                            </li>
                            <li class="list-inline-item">
                                <div class="form-check border border-custom2 w-fit rounded-custom  rounded-custom p-0 d-flex align-items-center">
                                    <input class="form-check-input my-2 ms-2"
                                           style="width: 30px; height: 30px;"
                                           @if(isset($data) && $data->active == 1) checked="checked" @endif value="1" name="active" type="checkbox" id="flexSwitchCheckChecked1">
                                    <label class="form-check-label p-2" for="flexSwitchCheckChecked1">جلسه فعال است</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="pe-2 text-end mt-3">
                        <button type="submit"  id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-4 py-2">
                            ذخیره
                        </button>
                    </div>

                </div>
            </div>

            <script type="text/javascript">
                $(function () {
                    var opts = $('#optlist option').map(function () {
                        return [[this.value, $(this).text()]];
                    });

                    $('#someinput').keyup(function () {
                        var rxp = new RegExp($('#someinput').val(), 'i');
                        var optlist = $('#optlist').empty();
                        opts.each(function () {
                            if (rxp.test(this[1])) {
                                optlist.append($('<option/>').attr('value', this[0]).text(this[1]));
                            }
                        });
                    });
                });
            </script>

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
