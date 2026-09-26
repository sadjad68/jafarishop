<div class="side-panel mobile d-lg-none d-block p-0 ">
    <div class="accordion" id="accordionExample">
        <div class="accordion-item bg-transparent border-0">
            <p class="accordion-header">
                <button class="accordion-button shadow-none px-3 bg-transparent border-0 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    @include('pages.panel.blocks.info-profile')
                </button>
            </p>
            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body p-2">
                    @include('pages.panel.blocks.menu-items')
                </div>
            </div>
        </div>
    </div>
</div>