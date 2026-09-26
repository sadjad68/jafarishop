<div class="admin-field">
    <label class="admin-label" for="{{$name}}">
        {{$label}}
        @if($validations && in_array("requiredCms",$validations)) <span class="text-danger">*</span> @endif
    </label>
    <input
        id="{{$name}}"
        @foreach($validations as $row)
            {{$row}}
        @endforeach
        @if(isset($properties))
            @foreach($properties as $property)
                {{$property}}
            @endforeach
        @endif
        type="{{$type}}"
        class="form-control admin-input {{$class}}"
        name="{{$name}}"
        placeholder="{{$label . ' را وارد کنید'}}"
        value="@if(isset($valueData) && $valueData){{$valueData[$name]}}@else{{old($name)}}@endif"
    >
</div>
