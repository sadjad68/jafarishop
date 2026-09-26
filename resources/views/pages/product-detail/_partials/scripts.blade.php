<script>
    document.addEventListener("DOMContentLoaded", function () {
        const scrollBtn = document.getElementById("scrollBtn");
        if (!scrollBtn) return;

        scrollBtn.addEventListener("click", function () {
            const target = document.getElementById("targetSection");
            if (!target) return;

            const offset = -1000;
            const y = target.getBoundingClientRect().top + window.pageYOffset + offset;

            window.scrollTo({
                top: y,
                behavior: "smooth"
            });
        });
    });
</script>
<script src="{{ asset('assets/site/js/tpl-sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/site/js/tpl-popper.min.js') }}"></script>
<script src="{{ asset('assets/site/js/product/tpl-magiczoomplus.js') }}"></script>
<script src="{{ asset('assets/site/js/product/tpl-product-detail.js?v0.16') }}"></script>
<script>
    let tableList = document.querySelectorAll('.content table');
    tableList.forEach((item) => {
        item.className = "table table-bordered bg-transparent mx-auto table-striped";
        item.outerHTML = `<div class="table-responsive">${item.outerHTML}</div>`
    })
</script>

