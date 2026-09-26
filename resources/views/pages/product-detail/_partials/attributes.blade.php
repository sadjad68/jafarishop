<div class="pdp-features">
    <ul class="pdp-features__list" id="feature-list">
        @foreach ($properties as $property)
            <li class="pdp-features__item">
                <i class="bi bi-check2 pdp-features__icon" aria-hidden="true"></i>
                <span class="pdp-features__text">{{ $property['value'] ?? $property->value ?? '' }}</span>
            </li>
        @endforeach
    </ul>
    <button type="button" id="featureButton"
        class="btn pdp-features__toggle d-flex gap-1 align-items-center show-more-button font-bold mt-2 border-0 p-0 d-none"
        onclick="toggleFeatures()" aria-expanded="false">
        <i class="bi bi-chevron-down d-flex" aria-hidden="true"></i>
        مشاهده بیشتر
    </button>
</div>
