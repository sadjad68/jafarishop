<label class="admin-switch" for="{{$name}}">
    <input class="admin-switch-input"
           id="{{$name}}"
           @if(isset($valueData))
               @if($valueData[$name] == 1)
                   checked="checked"
               @endif
           @else
               @if($value == 1)
                   checked="checked"
               @endif
           @endif
           value="1"
           name="{{$name}}"
           type="checkbox"
           role="switch">
    <span class="admin-switch-track" aria-hidden="true"></span>
    <span class="admin-switch-text">
        {{$label}}
        @if($validations && in_array("requiredCms",$validations)) <span class="text-danger">*</span> @endif
    </span>
</label>
