<script type="text/javascript">
    function confirmDelete(url) {
        Swal.fire({
            icon: 'warning',
            title: 'حذف آیتم',
            text: "آیا از حذف این مورد مطمئن هستید؟",
            showCancelButton: true,
            confirmButtonText: 'تایید و حذف',
            cancelButtonText: 'لغو',
            reverseButtons: true,
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed) {
                location.href = url;
            }
        });
    }
</script>
