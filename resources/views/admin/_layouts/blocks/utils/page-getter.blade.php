@php
    $page = 1;
        $previousUrl = url()->previous();
        $parsedUrl = parse_url($previousUrl);
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);

            // بررسی وجود پارامتر page در query string
            if (isset($queryParams['page'])) {
                $page = $queryParams['page'];
            }
        }
@endphp
<input type="hidden" name="fallback_page" value="{{$page}}">
