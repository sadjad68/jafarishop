<div class="col-xxl-3 col-sm-6 col-12 px-md-2 px-0 my-2">
    <label>
        {{$data['p_name']}}
    </label>
    <select class="w-100 boot-select selectpicker" name="{{@$data['type']}}[{{@$data['key']}}]" data-live-search="true">
        @foreach($data['options'] as $key_option => $option)
            <option value="{{$key_option}}" @if($data['value'] == $key_option) selected @endif>
                {{$option}}
            </option>
        @endforeach
    </select>
</div>
