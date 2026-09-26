@if (@$brand['description'] != null)
    <section class="seo-box plp-seo">
        <div class="container">
            <div class="box">
                <div class="boxdes">
                    <p class="plp-seo__eyebrow">درباره این برند</p>
                    <input type="checkbox" id="expanded">
                    <div id="text-box" class="p text-start content">
                        {!! @$brand['description'] !!}
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
