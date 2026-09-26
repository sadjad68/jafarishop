@if(@$data->link)
    <a href="{{ @$data->link }}" class="d-block">
        <img src="{{ @$data->image }}"
             class="w-100 h-auto" width="444" height="316" alt="{{ @$data->title }}"
             title="{{ @$data->title }}" loading="lazy" />
    </a>
@else
    <img src="{{ @$data->image }}"
         class="w-100 h-auto" width="444" height="316" alt="{{ @$data->title }}"
         title="{{ @$data->title }}" loading="lazy" />
@endif
