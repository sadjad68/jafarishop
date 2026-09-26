@php
    $pkgBody = trim((string) ($package['description'] ?? ''));
@endphp
<article class="pkg-dossier__story">
    <p class="pkg-dossier__kicker">شرح پکیج</p>
    <p class="pkg-dossier__heading">{{ $package['title'] }}</p>
    @if($pkgBody !== '')
        <div class="pkg-dossier__prose">
            {!! $package['description'] !!}
        </div>
    @endif
</article>
