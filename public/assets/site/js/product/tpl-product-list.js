var swiper = new Swiper(".swiper-categories", {
    spaceBetween: 30,
    slidesPerView: 4,
    grabCursor: true,
    autoplay: {
        delay: 1500,
        disableOnInteraction: false,
    },
    breakpoints: {
        0: {
            slidesPerView: 2.5,
            spaceBetween: 10,
        },
        576: {
            slidesPerView: 3.5,
            spaceBetween: 15,
        },
        768: {
            slidesPerView: 4.5,
            spaceBetween: 15,
        },
        992: {
            slidesPerView: 5,
            spaceBetween: 15,
        },
        1200: {
            slidesPerView: 6,
            spaceBetween: 15,
        },
        1400: {
            slidesPerView: 6,
            spaceBetween: 15,
        },
        1600: {
            slidesPerView: 7,
            spaceBetween: 15,
        },
    },
});

let BtnDeleteFilter = document.querySelectorAll('#deleteFilter');

BtnDeleteFilter.forEach((item) => {
    item.addEventListener('click', function (e) {
        const li = item.parentElement;
        li.remove();
    })
})

let colors = document.querySelectorAll('#Colors');
colors.forEach((item) => {
    item.lastElementChild.style.backgroundColor = "transparent";
    item.lastElementChild.innerHTML = "<i class='bi bi-plus d-flex fs-5'></i>";
})

document.addEventListener("DOMContentLoaded", function () {
    // انتخاب همه چک‌باکس‌های دارای کلاس form-check-input به صورت آرایه
    const checkboxes = Array.from(document.querySelectorAll(".colorInput"));

    // تابعی برای بررسی تیره بودن رنگ
    function isDark(color) {
        const rgb = color.match(/\d+/g);
        const brightness = (0.299 * rgb[0]) + (0.587 * rgb[1]) + (0.114 * rgb[2]);
        return brightness < 128; // معیار برای تشخیص رنگ تیره
    }

    checkboxes.forEach(checkbox => {
        const label = checkbox.nextElementSibling; // پیدا کردن لیبل بعد از چک‌باکس
        if (label && label.classList.contains("colorLabel")) {
            const bgColor = window.getComputedStyle(label).backgroundColor; // دریافت رنگ پس‌زمینه لیبل

            // اضافه کردن کلاس اگر رنگ تیره باشد
            if (isDark(bgColor)) {
                checkbox.classList.add("black-filter"); // کلاس دلخواه خود را اضافه کنید
            }
        }
    });
});
