<div class="{{@$data['class'] != null ? @$data['class'] : 'col-sm-4 col-12'}} p-2">
    <label class="admin-switch" for="setting-{{ @$data['key'] }}">
        <input class="admin-switch-input"
               value="1"
               id="setting-{{ @$data['key'] }}"
               name="{{@$data['type']}}[{{@$data['key']}}]"
               type="checkbox"
               role="switch" @if(@$data['value'] == 1) checked @endif>
        <span class="admin-switch-track" aria-hidden="true"></span>
        <span class="admin-switch-text">{{@$data['p_name']}}</span>
    </label>
</div>
