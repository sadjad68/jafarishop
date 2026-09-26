@extends('admin._layouts.master')

@section('title')
ویرایش
@endsection
@section('content')
<div class="main-panel">
    <div class="main-content">
        <div class="content-wrapper">
            <div class="container-fluid"><!-- DOM - jQuery events table -->
			<h3 class="bg-white py-2 px-4 rounded-lg">
                سطح دسترسی
			</h3>
			<div class="card rounded-lg border-0 p-3">
                <form id="cms-form" method="post" action="{{url('/admin/permission/edit/'.@$data->id)}}"
                    enctype="multipart/form-data">
                    @include('admin.user.permission.form')
                </form>
			</div>
		</div>
	</div>
</div>
</div>
@stop
@push('scripts')
    <script src="{{asset('assets/admin/js/masonry.pkgd.js')}}" async></script>

    <script>

	$(document).ready(function () {
		$("#select_all").on('click', function () {
			$.each($("input"), function (index, value) {
				if (value.type == 'checkbox') {
					value.checked = $("#select_all")[0].checked;
				}
			});
		});
	});

	(function($,W,D)
	{
		var JQUERY4U = {};

		JQUERY4U.UTIL =
		{
			setupFormValidation: function()
			{
				//form validation rules
				$("#cms-form").validate({
					rules: {
						name: "required",
						agree: "required"
					},
					messages: {
						name: "این فیلد الزامی است."
					},
					submitHandler: function(form) {
						form.submit();
					}
				});
			}
		}

		//when the dom has loaded setup form validation rules
		$(D).ready(function($) {
			JQUERY4U.UTIL.setupFormValidation();
		});

	})(jQuery, window, document);
</script>
@endpush
