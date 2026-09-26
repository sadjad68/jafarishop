@if(count($tags) > 0)
<div class="tags">
    <ul class="p-0 m-0 d-flex align-items-center flex-wrap">
        <p class="font-small font-th m-0">
            برچسب ها :
        </p>
        @foreach($tags as $tag)
        <li class="list-unstyled m-1">
            <a href="{{ route('tag.detail', ['url' => $tag['url']]) }}" class="tag-item">
                {{$tag['title']}}
            </a>
        </li>
        @endforeach

    </ul>
</div>
@endif
