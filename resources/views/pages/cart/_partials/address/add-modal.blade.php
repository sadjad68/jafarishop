<div class="modal fade cart-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content cart-modal__content">
            <div class="modal-header cart-modal__header">
                <h2 class="modal-title" id="exampleModalLabel">افزودن آدرس جدید</h2>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body cart-modal__body">
                @include('pages.cart._partials.address.form')
            </div>
        </div>
    </div>
</div>
