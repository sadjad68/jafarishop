<div class="container-fluid">
    <div class="card-block row w-100 m-0 ">
        {{--        تب ها --}}
        <div class="overflow-auto pb-2">
            <ul class="setting-tab nav nav-tabs list-inline" id="myTab" role="tablist">
                @foreach($theme_types as $key => $theme_type)
                    <li class="nav-item list-inline-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif" id="{{@$key}}-tab" data-bs-toggle="tab"
                                data-bs-target="#{{@$key}}-tab-pane" type="button" role="tab"
                                aria-controls="{{@$key}}-tab-pane" aria-selected="true">
                            {{@$theme_type}}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-content" id="myTabContent">
            @foreach($theme_types as $key => $theme_type)
                <div class="tab-pane fade show @if($loop->first) active @endif" id="{{@$key}}-tab-pane" role="tabpanel"
                     aria-labelledby="{{@$key}}-tab" tabindex="0">
                    <div class="row w-100 m-0">
                        @php
                            $themes = \App\Modules\Setting\Entities\Theme::where('type',$key)->get();
                        @endphp
                        <div class="w-100 row m-0">
                        @foreach($themes as $data)
                            @component('admin.components.theme.'.@$key, ['data' => @$data])
                            @endcomponent
                        @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{--        تب های--}}
        <div class="pe-4 text-end">
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
