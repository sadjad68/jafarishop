<section class="pdp-tabs tabs mt-3 mb-4">
    <div class="pdp-tabs__nav-wrap">
        <ul class="nav pdp-tabs__nav" id="myTab" role="tablist">
            @if($tabs['home-tab'])
                <li class="nav-item" role="presentation">
                    <button class="nav-link pdp-tabs__btn {{ $activeTab == 'home-tab' ? 'active' : '' }}" onclick="updateTextColorActive();" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="{{ $activeTab == 'home-tab' ? 'true' : 'false' }}">توضیحات</button>
                </li>
            @endif

            @if($tabs['profile-tab'])
                <li class="nav-item" role="presentation">
                    <button class="nav-link pdp-tabs__btn {{ $activeTab == 'profile-tab' ? 'active' : '' }}" onclick="updateTextColorActive();" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="{{ $activeTab == 'profile-tab' ? 'true' : 'false' }}">مشخصات</button>
                </li>
            @endif

            @if($tabs['video-tab'])
                <li class="nav-item" role="presentation">
                    <button class="nav-link pdp-tabs__btn {{ $activeTab == 'video-tab' ? 'active' : '' }}" onclick="updateTextColorActive();" id="video-tab" data-bs-toggle="tab" data-bs-target="#video-tab-pane" type="button" role="tab" aria-controls="video-tab-pane" aria-selected="{{ $activeTab == 'video-tab' ? 'true' : 'false' }}">ویدیو و تیزر</button>
                </li>
            @endif

            @if($tabs['faq-tab'])
                <li class="nav-item" role="presentation">
                    <button class="nav-link pdp-tabs__btn {{ $activeTab == 'faq-tab' ? 'active' : '' }}" onclick="updateTextColorActive();" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq-tab-pane" type="button" role="tab" aria-controls="faq-tab-pane" aria-selected="{{ $activeTab == 'faq-tab' ? 'true' : 'false' }}">سوالات متداول</button>
                </li>
            @endif

            <li class="nav-item" role="presentation">
                <button class="nav-link pdp-tabs__btn {{ $activeTab == 'contact-tab' ? 'active' : '' }}" onclick="updateTextColorActive();" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="{{ $activeTab == 'contact-tab' ? 'true' : 'false' }}">نظرات</button>
            </li>
        </ul>
    </div>

    <div class="tab-content pdp-tabs__panels" id="myTabContent">
        @if($tabs['home-tab'])
            <div class="tab-pane fade pdp-tabs__panel {{ $activeTab == 'home-tab' ? 'show active' : '' }}" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                @include('pages.product-detail._partials.tabs.description')
            </div>
        @endif

        @if($tabs['profile-tab'])
            <div class="tab-pane fade pdp-tabs__panel {{ $activeTab == 'profile-tab' ? 'show active' : '' }}" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                @include('pages.product-detail._partials.tabs.specifications')
            </div>
        @endif

        @if($tabs['video-tab'])
            <div class="tab-pane fade pdp-tabs__panel {{ $activeTab == 'video-tab' ? 'show active' : '' }}" id="video-tab-pane" role="tabpanel" aria-labelledby="video-tab" tabindex="0">
                @include('pages.product-detail._partials.tabs.videos')
            </div>
        @endif

        @if($tabs['faq-tab'])
            <div class="tab-pane fade pdp-tabs__panel {{ $activeTab == 'faq-tab' ? 'show active' : '' }}" id="faq-tab-pane" role="tabpanel" aria-labelledby="faq-tab" tabindex="0">
                @include('pages.product-detail._partials.tabs.faq')
            </div>
        @endif

        <div class="tab-pane fade pdp-tabs__panel {{ $activeTab == 'contact-tab' ? 'show active' : '' }}" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
            <div class="sk-comments sk-comments--embedded">
                @include('layouts.common.comment._partials.comment-base',['commentable_id'=>$product['id'],'commentable_type'=>get_class($product)])
            </div>
        </div>
    </div>
</section>
