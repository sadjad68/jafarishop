<div class="{{@$data['class'] != null ? @$data['class'] : 'col-sm-4 col-12'}} p-2">
    <div class="form-group">
        @if(is_array($options) && !empty($options['alert']))
            <div class="alert alert-{{ $options['alert_type'] ?? 'warning' }} py-1 px-2 mb-2 small d-flex align-items-center gap-1">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{!! $options['alert'] !!}</span>
            </div>
        @endif
        <label for="">{{@$data['p_name']}}</label>
        <input requiredCms type="text" class="form-control bg-light rounded-custom" name="{{@$data['type']}}[{{@$data['key']}}]" placeholder="" value="{{@$data['value']}}" >
    </div>
</div>
