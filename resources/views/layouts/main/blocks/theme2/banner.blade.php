@mobile

@if(@$settings['mobile_header_banner'])
    <section class="banner-top d-lg-none d-block">
        @include('layouts.main.blocks.theme2.components.header-banner',
['image'=>@$settings['mobile_header_banner'],'link'=>@$settings['mobile_header_banner_link'],'alt'=>@$settings['siteName_fa']])
    </section>
@endif
@else
    @if(@$settings['desktop_header_banner'])
        <section class="banner-top d-lg-block d-none">
            @include('layouts.main.blocks.theme2.components.header-banner',
['image'=>@$settings['desktop_header_banner'],'link'=>@$settings['desktop_header_banner_link'],'alt'=>@$settings['siteName_fa']])
        </section>
    @endif
    @endmobile

