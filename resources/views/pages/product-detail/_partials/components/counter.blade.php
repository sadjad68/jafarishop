<div class="pdp-qty number d-inline-flex align-items-center">
    <div class="value-button pdp-qty__dec d-flex align-items-center justify-content-center dynamic-color"
        @click="decreaseQuantity()" role="button" tabindex="0" aria-label="کاهش تعداد">
        <i class="bi bi-dash d-flex"></i>
    </div>
    <input type="text" class="font-num-r pdp-qty-input" readonly :value="quantity" aria-label="تعداد" />
    <div class="value-button pdp-qty__inc d-flex align-items-center justify-content-center dynamic-color"
        @click="increaseQuantity()" role="button" tabindex="0" aria-label="افزایش تعداد">
        <i class="bi bi-plus d-flex"></i>
    </div>
</div>
