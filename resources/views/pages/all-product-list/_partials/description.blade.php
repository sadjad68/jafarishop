@if($settings['all_product_description'] != null)
<section class="seo-box">
    <div class="container">
        <div class="box">
            <div class="boxdes">
                <input type="checkbox" id="expanded">
                <div id="text-box" class="p text-start">

                    {!! $settings['all_product_description'] !!}
                </div>
                @if ($theme_provider->getValue() == 'theme2')
                @else
                    <label for="expanded" id="more-button" role="button" class="btn button btn-one m-auto px-4 py-2">
                        بیشتر
                    </label>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
