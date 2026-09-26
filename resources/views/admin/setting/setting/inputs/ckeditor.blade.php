{{-- Review : fix ckeditor -> right to left --}}
<div class="col-md-12 col-6 p-2">
    <div class="form-group">
        <label for="">{{@$data['p_name']}}</label>
        <textarea  type="text" class="form-control bg-light rounded-custom editor" name="{{@$data['type']}}[{{@$data['key']}}]"
        >{{@$data['value']}}</textarea>
    </div>
</div>
