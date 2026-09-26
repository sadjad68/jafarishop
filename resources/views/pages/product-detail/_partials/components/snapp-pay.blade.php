<template v-if="snappLoading || (snappData && snappData.snapp_show)">
    <div v-if="snappLoading" class="snapp-pay-box border border-primary-subtle rounded-3 p-2 my-3 mx-2 mx-md-0 d-flex align-items-center justify-content-center bg-white" style="border-width: 1.5px !important; min-height: 64px;">
        <span class="spinner-border spinner-border-sm text-primary me-2" role="status" aria-hidden="true"></span>
        <small class="text-muted">در حال دریافت اطلاعات اسنپ‌پی...</small>
    </div>
    <div v-else-if="snappData && snappData.snapp_show" class="snapp-pay-box border border-primary-subtle rounded-3 p-2 my-3 mx-2 mx-md-0 d-flex align-items-center bg-white" style="border-width: 1.5px !important;">
        <div class="snapp-pay-text d-flex flex-column align-items-start text-start flex-grow-1 pe-2">
            <span class="fw-bold mb-1 d-flex align-items-center" style="color: #002c52; font-size: 14px;">
                @{{ snappData.snapp_title_message }}
            </span>
            <span class="text-secondary" style="font-size: 13px;">@{{ snappData.snapp_description }}</span>
        </div>
        <div class="snapp-logo d-flex align-items-center justify-content-center rounded flex-shrink-0" style="background-color: #0081ff; width: 48px; height: 48px;">
            <span class="text-white fw-bold f-number-en" style="font-size: 11px; line-height: 1.2; text-align: left;" dir="ltr">Snapp!<br>Pay</span>
        </div>
    </div>
</template>
