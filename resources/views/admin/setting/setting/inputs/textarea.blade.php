<div class="col-md-12 col-6 p-2">
    <div class="form-group">
        <label class="text-start w-100 d-flex justify-content-start" for="">
            <span>{{@$data['p_name']}}</span>
        </label>
        <textarea  type="text" class="form-control bg-light rounded-custom" name="{{@$data['type']}}[{{@$data['key']}}]" placeholder="">{{@$data['value']}}</textarea>
    </div>
</div>
