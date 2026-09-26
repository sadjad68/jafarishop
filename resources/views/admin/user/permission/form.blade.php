{{ csrf_field() }}
<div class="box-body">
    <div class="form-group">
        <div class="row">
            <div class="col-xxl-3 col-sm-6 col-12">
                <label>عنوان:</label>
                <input class="form-control bg-light rounded-custom" type="text" id="name" name="name"
                       value="@if(isset($data->name)) {{$data->name}} @endif" placeholder="عنوان  را وارد کنید . . .">
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <div class="w-100 align-items-center justify-content-between d-flex">
            <div class="d-flex align-items-center justify-content-center">
                <input type="checkbox" name="select_all" value="1" id="select_all" class="form-check-input m-2">
                <label for="select_all"><span class="text fs-6">انتخاب همه</span></label>
            </div>

            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-4 py-2 me-2">
                ذخیره
            </button>
        </div>
        <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-1 g-0"
             data-masonry='{"percentPosition": true , "originLeft": false }'>
            <?php
            if (isset($data->permission)) {
                $accessDB = unserialize($data->permission) ? unserialize($data->permission) : [];
            } else {
                $accessDB = [];
            }
            ?>
            @foreach(Config::get('site.permissions') as $key=>$value)
                @if($key !== "dashboard")
                    <div class="widget col p-2 rounded-custom" style="height: fit-content;">
                        <div class="card permission-card rounded-custom overflow-hidden p-0 border">
                            <div class="widget-header permission-widget-header bordered-bottom p-3">
                                <i class="widget-icon bi bi-shield-lock"></i>
                                <span class="widget-caption">{{{ $value['title'] }}}</span>
                            </div>
                            <!--Widget Header-->
                            <div class="widget-body px-3 py-1">
                                <div class="widget-main no-padding">
                                    <div class="tickets-container">
                                        @foreach($value['access'] as $keyAccess => $access)
                                            <div
                                                class="col-12 d-flex align-items-center justify-content-between my-1 px-2 py-1 rounded permission-access-row">

                                                @php $permission = 'admin.'.$key.'.'.$keyAccess; @endphp
                                                <input type="checkbox" class="form-check-input my-0 me-1 "
                                                       id="{{$permission}}" name="access[]" value="{{$permission}}"
                                                       @if(isset($data) && in_array($permission,$accessDB)) checked @endif>

                                                <label class="w-100" for="{{$permission}}"><span
                                                        class="text">{{{ $access }}}</span></label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="widget col p-2 " style="height: fit-content;">
                        <div class="card permission-card rounded-custom overflow-hidden p-0 border">
                            <div class="widget col-md-12">
                                <div class="widget-header permission-widget-header bordered-bottom p-3">
                                    <i class="widget-icon bi bi-shield-lock"></i>
                                    <span class="widget-caption">{{{ $value['title'] }}}</span>
                                </div>
                                <div class="d-flex align-items-center px-3 py-2 permission-access-row m-3 rounded">
                                    @php $permission = 'admin.dashboard'; @endphp
                                    <input type="checkbox" class="form-check-input me-2" name="access[]"
                                           value="{{$permission}}"
                                           checked style="pointer-events: none;">
                                    <span class="text">دسترسی پیش‌فرض همه کاربران</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @include('admin._layouts.blocks.utils.page-getter')
    <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-4 py-2 me-2">
        ذخیره
    </button>
</div>
