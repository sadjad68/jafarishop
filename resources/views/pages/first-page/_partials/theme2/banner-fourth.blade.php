@php
    use App\Modules\Banner\Services\HighlightService;
    $slots = HighlightService::firstPageAuxStripSlots();
@endphp
@include('pages.first-page._partials.components.first-page-highlight-slotted-row', [
    'device' => 'desktop',
    'highlightData' => $highlightData ?? [],
    'slots' => $slots,
    'sectionClass' => 'banners',
    'innerContainerClass' => 'container',
    'rowClass' => 'row w-100 m-0',
    'linkClass' => 'd-block text-decoration-none',
])
