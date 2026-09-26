@php
    $listId = $listId ?? 'admin-sort-list';
@endphp

<div class="admin-sort-panel">
    <div class="admin-sort-hint" role="status">
        <span class="admin-sort-hint-icon" aria-hidden="true">
            <i class="bi bi-arrows-move"></i>
        </span>
        <div class="admin-sort-hint-text">
            <strong>تغییر ترتیب نمایش</strong>
            <span>آیتم را بگیرید و جابه‌جا کنید. ترتیب جدید خودکار ذخیره می‌شود.</span>
        </div>
        <span class="admin-sort-count">{{ count($items) }} مورد</span>
    </div>

    @if(count($items))
        <ul class="admin-sort-list" id="{{ $listId }}" data-sort-url="{{ $sortUrl }}">
            @foreach($items as $key => $row)
                <li class="admin-sort-item" data-id="{{ $row->id }}">
                    <span class="admin-sort-handle" aria-hidden="true">
                        <i class="bi bi-grip-vertical"></i>
                    </span>
                    <span class="admin-sort-order">{{ $key + 1 }}</span>
                    <div class="admin-sort-body">
                        <span class="admin-sort-title">{{ $row->title ?? '' }}</span>
                        @if(!empty($childrenRoute) && count($row->children ?? []) > 0)
                            <a href="{{ route($childrenRoute, ['parent_id' => $row->id]) }}"
                               class="admin-sort-children-btn">
                                <i class="bi bi-diagram-3"></i>
                                زیرمجموعه‌ها
                            </a>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="admin-sort-empty">
            <i class="bi bi-inbox"></i>
            <p>موردی برای مرتب‌سازی وجود ندارد.</p>
        </div>
    @endif
</div>
