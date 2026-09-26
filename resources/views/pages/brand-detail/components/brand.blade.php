<ul class="p-0 m-0">
    @foreach($brands as $row)
    <li class="list-unstyled">
        <a href="{{ route('brand.detail', ['url' => @$row['url']]) }}" class="color-title d-flex align-items-center small">
            <i class="bi bi-chevron-left d-flex me-1"></i>
            {{@$row['title']}}
        </a>
    </li>
    @endforeach

</ul>