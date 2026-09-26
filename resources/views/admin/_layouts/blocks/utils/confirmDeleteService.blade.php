<script type="text/javascript">
    function confirmDeleteService(url,allUrl) {
        const swalWithButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-danger mx-2',
                cancelButton: 'btn btn-secondary',
            },
            buttonsStyling: false,
        });

        swalWithButtons.fire({
            icon: 'warning',
            text: "آیا از حذف آیتم مطمئن هستید؟",
            showCancelButton: true,
            confirmButtonText: 'حذف کامل به همراه زیر دسته ها',
            cancelButtonText: 'لغو',
            showCloseButton: true,
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                swalWithButtons.fire({
                    icon: 'question',
                    text: 'تغییر زیر دسته ها به دسته اصلی یا حذف به همراه تغییر زیر دسته ها؟',
                    showCancelButton: true,
                    confirmButtonText: 'حذف به همراه تغییر زیر دسته ها به دسته اصلی',
                    cancelButtonText: 'حذف کامل',
                }).then((innerResult) => {
                    if (innerResult.isConfirmed) {
                        // انجام عملیات حذف با تغییر زیر دسته ها به دسته اصلی
                        location.href = url;
                    } else {
                        location.href = allUrl;
                        // انجام عملیات حذف کامل
                        // ...
                    }
                });
            }
        });
    }
</script>

