<div class="admin-field">
    <label class="admin-label" for="{{$name}}">
        {{$label}}
        @if($validations && in_array("requiredCms",$validations))
            <span class="text-danger">*</span>
        @endif
    </label>
    <textarea id="{{$name}}"
              @foreach($validations as $row)
                  {{$row}}
              @endforeach
              class="form-control admin-input ckeditor4"
              name="{{$name}}" placeholder="{{$label . ' را وارد کنید'}}"
    >@if(isset($valueData) && $valueData){{$valueData[$name]}}@else{{old($name)}}@endif</textarea>
</div>
