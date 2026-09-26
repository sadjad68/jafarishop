@if(isset($search_form) && $search_form && isset($result['data']) && method_exists($result['data'], 'hasPages'))
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4 pt-3 border-top">
    {{-- نمایش بازه نتایج --}}
    <p class="font-th small text-secondary mb-0">
        نمایش {{ $result['data']->firstItem() }} تا {{ $result['data']->lastItem() }} از {{ number_format($result['data']->total()) }} نتیجه
    </p>

    {{-- پیجینیشن --}}
    @if($result['data']->hasPages())
        <div class="search-pagination">
            {{ $result['data']->appends(array_merge(request()->except($tab_key . '_page'), ['tab' => $tab_key]))->links() }}
        </div>
    @endif
</div>
@endif
