@if($link)
    <a href="{{ @$link }}">
        <img src="{{ @$image }}" width="1920" height="75" class="w-100 h-auto"
             alt="{{@$alt}}" title="{{@$alt}}" />
    </a>
@else
    <img src="{{ @$image }}" width="1920" height="75" class="w-100 h-auto"
         alt="{{@$alt}}" title="{{@$alt}}" />
@endif
