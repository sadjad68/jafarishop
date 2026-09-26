@extends('admin._layouts.master')

@section('title') افزودن دوره @stop
@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="border-0 mb-4">
                    <div
                        class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                        <h3 class="fw-bolder mb-0">
                            افزودن دوره
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card border p-2">
            <form id="cms-form" action="{{url('admin/course/create')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.course.course.form')
            </form>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script>
    let requiredInputs = document.querySelectorAll("[requiredCms]");
    let submitButton = document.getElementById('submitFormCms');

    submitButton.addEventListener('click', validateForm);

    function validateForm(e) {
        e.preventDefault();
        let isValid = true;

        requiredInputs.forEach(x => {
            const closestLabel = x.previousElementSibling;
            if (closestLabel) {
                const labelName = closestLabel.innerText || closestLabel.textContent;
                x.labelName = labelName.trim();
            }
            const isEmpty = x.value.trim() === '';
            if (isEmpty) {
                isValid = false;
                Swal.fire({
                    icon: 'error',
                    text: `${x.labelName} نمی‌تواند خالی باشد!`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
                console.log(x)
                x.style.border = '2px solid red';
            } else {
                x.style.border = '';
            }
        });

        if (isValid) {
            // If all inputs are valid, submit the form
            document.getElementById("cms-form").submit();
        }
    }
</script>

@endpush
