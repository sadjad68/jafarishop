@props([
    'data' => [],
    'row',
])
@php
    $seeMoreItems = collect($data ?? []);
@endphp
@foreach ($seeMoreItems->take(3) as $singleData)
    <span class="badge bg-label-primary m-1" style="font-size: 11px">{{ $singleData->title }}</span>
@endforeach

@if ($seeMoreItems->count() > 3)
    <div id="categories-{{ $row->id }}" style="display: none;">
        @foreach ($seeMoreItems->skip(3) as $singleData)
            <span class="badge bg-label-primary" style="font-size: 11px">{{ $singleData->title }}</span>
        @endforeach
    </div>
    <button type="button" id="see-{{ $row->id }}" class="btn btn-sm btn-link text-primary p-0" onclick="showMoreCategories({{ $row->id }})">
        مشاهده بیشتر
    </button>
@endif

@once
    @push('scripts')
        <script>
            function showMoreCategories(rowId) {
                const hiddenCategories = document.getElementById(`categories-${rowId}`);
                const seeButton = document.getElementById(`see-${rowId}`);
                if (hiddenCategories.style.display === 'none') {
                    hiddenCategories.style.display = 'block';
                    seeButton.textContent = 'مشاهده کمتر';
                } else {
                    hiddenCategories.style.display = 'none';
                    seeButton.textContent = 'مشاهده بیشتر';
                }
            }
        </script>
    @endpush
@endonce
