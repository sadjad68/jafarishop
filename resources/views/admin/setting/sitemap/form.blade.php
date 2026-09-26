<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        @foreach($sitemaps as $key=>$sitemap)
            @php
            $selected = \App\Modules\Setting\Entities\Sitemap::where('key',$sitemap['key'])->first();
            @endphp
            <div class="row m-0">
                <div class="col-xxl-6 col-sm-6 col-12 p-2">
                    <div class="form-group">
                        <label>{{$sitemap['p_name']}}</label>
                        <select data-live-search="true" class="w-100 boot-select selectpicker" name="{{$sitemap['key']}}[priority]{{$sitemap['priority']}}">
                           @foreach($option_priority as $key_priority => $priority )
                                <option value="{{$key_priority}}" @if(isset($selected) && $selected['priority'] == $priority) selected @endif>{{$priority}}</option>
                           @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xxl-6 col-sm-6 col-12 p-2">
                    <div class="form-group">
                        <label>{{$sitemap['p_name']}}</label>
                        <select data-live-search="true" class="w-100 boot-select selectpicker" name="{{$sitemap['key']}}[change_frequency]{{$sitemap['change_frequency']}}">
                            @foreach($option_change_frequency as $key_frequency => $frequency )
                                <option value="{{$key_frequency}}" @if(isset($selected) && $selected['change_frequency'] == $frequency) selected @endif>{{$frequency}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="w-100 pe-0">
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                ذخیره
            </button>
        </div>
    </div>
</div>
