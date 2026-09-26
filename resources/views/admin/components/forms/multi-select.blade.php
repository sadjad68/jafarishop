<div class="admin-field">
    <label class="admin-label" for="{{$name}}">
        {{$label}}
        @if($validations && in_array("requiredCms",$validations)) <span class="text-danger">*</span> @endif
    </label>
    <select
        id="{{$name}}"
        class="w-100 boot-select selectpicker admin-select"
        @if($searchable) data-live-search="true" @endif
        multiple
        @foreach($validations as $row)
            {{$row}}
        @endforeach
        placeholder="{{$label . ' را انتخاب کنید'}}"
        name="{{$name.'[]'}}"
        data-none-selected-text="{{$label . ' را انتخاب کنید'}}"
    >
        @foreach($options as $key=>$option)
            @if($optionValue && $optionLabel)
                <option value="{{$option[$optionValue]}}"
                        @if(in_array($option[$optionValue],$selectedOptions) || (old($name) && in_array($option[$optionValue],old($name)))) selected @endif
                >
                    {{$option[$optionLabel]}}
                </option>
            @else
                <option value="{{$key}}" @if(in_array($key,$selectedOptions) || (old($name) && in_array($key,old($name)))) selected @endif>
                    {{$option}}
                </option>
            @endif
        @endforeach
    </select>
</div>
