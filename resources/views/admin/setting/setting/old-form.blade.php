
<div class="container-fluid">
    <div class="card-block row w-100 m-0 ">
        {{--        تب های تنظیمات--}}
        <div class="overflow-auto pb-2">
            <ul class="setting-tab nav nav-tabs list-inline" id="myTab" role="tablist">
                @foreach($setting_types as $key => $setting_type)
                    <li class="nav-item list-inline-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif" id="{{@$key}}-tab" data-bs-toggle="tab" data-bs-target="#{{@$key}}-tab-pane" type="button" role="tab" aria-controls="{{@$key}}-tab-pane" aria-selected="true">
                            {{@$setting_type}}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-content" id="myTabContent">
            {{-- Todo : راهنمای نمایش در قسمت های سایت روی هر فیلد اضافه شود--}}
            @foreach($setting_types as $key => $setting_type)
                <div class="tab-pane fade show @if($loop->first) active @endif" id="{{@$key}}-tab-pane" role="tabpanel" aria-labelledby="{{@$key}}-tab" tabindex="0">
                   <div class="row w-100 m-0">
                       @php
                           $settings = \App\Modules\Setting\Entities\Setting::where('type',$key)->get();
                       @endphp
                    @foreach($settings as $data)
                            @component('admin.components.setting.'.@$key, ['data' => @$data])
                            @endcomponent
                    @endforeach
                   </div>
                </div>
            @endforeach
        </div>
        {{--        تب های تنظیمات--}}
        <div class="pe-3 text-end">
            <button type="submit" class="btn btn-custom rounded-custom w-fit px-4 py-2">
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
