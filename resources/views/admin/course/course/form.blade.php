
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
                        <div class="form-group">
                            <label for="">قیمت (به تومان) </label>
                            <input requiredCms type="text" class="form-control bg-light rounded-custom" name="price" placeholder="" onkeyup="persianKeyUp(event)" onchange="persianKeyUp(event)" value="@if(isset($data)){{$data->price}}@else{{old('price')}}@endif" >
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6 col-12 p-2">
                        <div class="form-group">
                            <label for=""> قیمت با تخفیف  (به تومان)</label>
                            <input type="text" class="form-control bg-light rounded-custom" name="discounted_price" onkeyup="persianKeyUp(event)" onchange="persianKeyUp(event)" placeholder="" value="@if(isset($data)){{$data->discounted_price}}@else{{old('discounted_price')}}@endif" >
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6 col-12 p-2 ">
                        <div class="form-group">
                            <div class="row w-100 m-0">
                                <div class="col-md-6 p-1">
                                    <label for="order_status_id">جستجو دسته بندی</label>
                                    <input class="form-control bg-light rounded-custom" id="someinput" placeholder="جستجو">
                                </div>
                                <div class="col-md-6 p-1">
                                    <label for="order_status_id">انتخاب دسته بندی</label>
                                    <select id="optlist" class="form-select bg-light rounded-custom" name="course_category_id"
                                    >
                                        <option @if(isset($data) and $data->course_category_id == null)  selected @endif value="">
                                            دسته اصلی
                                        </option>
                                        @foreach ($category as $row)
                                            <option @if(isset($data) and $data->course_category_id == $row['id']) selected @endif  value="{{$row['id']}}">
                                                {{$row['title']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6 col-12 p-2 ">
                        <div class="form-group">
                            <div class="row w-100 m-0">
                                <div class="col-md-6 p-1">
                                    <label for="order_status_id">جستجو پرسنل</label>
                                    <input class="form-control bg-light rounded-custom" id="someinput2" placeholder="جستجو">
                                </div>
                                <div class="col-md-6 p-1">
                                    <label for="order_status_id">انتخاب پرسنل</label>
                                    <select id="optlist2" class="form-select bg-light rounded-custom" name="teacher_id"
                                    >
                                        <option @if(isset($data) and $data->teacher_id == null)  selected @endif value="">
                                            دسته اصلی
                                        </option>
                                        @foreach ($users as $row)
                                            <option @if(isset($data) and $data->teacher_id == $row['id']) selected @endif  value="{{$row['id']}}">
                                                {{$row['full_name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6 col-12 p-2">
                        <div class="form-group">
                            <label for=""> h1 </label>
                            <input type="text" class="form-control bg-light rounded-custom" name="h1" onkeyup="persianKeyUp(event)" onchange="persianKeyUp(event)" placeholder="" value="@if(isset($data)){{$data->h1}}@else{{old('h1')}}@endif" >
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
                    <div class="col-xxl-3 col-sm-6 col-12 p-2">
                        @include('admin._layouts.blocks.utils.image')
                    </div>
                    <div class="col-xxl-3 col-sm-6 col-12 p-2">
                        <div class="form-group">
                            <label for="order_status_id">نوع دوره</label>
                            <select id="optlist" class="form-select bg-light rounded-custom" name="type"
                                    value="">
                                    <option @if(isset($data) and $data->type == 1) selected @endif value="1">
                                        حضوری
                                    </option>
                                    <option @if(isset($data) and $data->type == 0) selected @endif value="0">
                                        آنلاین
                                    </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 p-2">
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
                            <label class="form-check-label p-2" for="flexSwitchCheckChecked1">دوره فعال است</label>
                        </div>
                    </div>
                    <div class="col-12 ms-auto p-2">
                        <button type="submit"  id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-5">
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
                    var opts = $('#optlist2 option').map(function () {
                        return [[this.value, $(this).text()]];
                    });

                    $('#someinput2').keyup(function () {
                        var rxp = new RegExp($('#someinput2').val(), 'i');
                        var optlist = $('#optlist2').empty();
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
