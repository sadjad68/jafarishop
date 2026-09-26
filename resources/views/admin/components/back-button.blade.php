<a type="button" onclick="goBack()" class="btn btn-custom rounded-custom w-fit px-3 py-2 mt-2">بازگشت</a>

<script>
    function goBack() {
        if (document.referrer && document.referrer !== window.location.href) {
            window.location.href = document.referrer;
        } else {
            history.back();
        }
    }
</script>
