@php
    $reviewCount = count($comments);
    $starFill = max(0, min(5, (float) $rate));
@endphp
<div class="pdp-meta">
    <button type="button" class="pdp-meta__rating" onclick="activateTabAndScroll('contact-tab')">
        <span class="pdp-meta__stars" aria-hidden="true">
            @for ($i = 1; $i <= 5; $i++)
                @if ($starFill >= $i)
                    <i class="bi bi-star-fill"></i>
                @elseif ($starFill >= $i - 0.5)
                    <i class="bi bi-star-half"></i>
                @else
                    <i class="bi bi-star"></i>
                @endif
            @endfor
        </span>
        <span class="pdp-meta__reviews">
            @if ($reviewCount > 0)
                <span class="font-num">{{ $reviewCount }}</span>
                نظر
            @else
                ثبت نظر
            @endif
        </span>
    </button>
</div>
@push('scripts')
    <script>
        function activateTabAndScroll(tabId, offset = 300) {
            const tabTrigger = document.querySelector(`#${tabId}`);
            if (tabTrigger) {
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();
                setTimeout(() => {
                    const tabPaneId = tabTrigger.getAttribute('data-bs-target');
                    const tabPane = document.querySelector(tabPaneId);
                    if (tabPane) {
                        const topPos = tabPane.getBoundingClientRect().top + window.scrollY - offset;
                        window.scrollTo({
                            top: topPos,
                            behavior: 'smooth'
                        });
                    }
                }, 200);
            }
        }
    </script>
@endpush
