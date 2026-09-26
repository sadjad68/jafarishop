@extends('layouts.main.master')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/site/css/gallery/tpl-gallery-category.css?v0.13') }}">
@endpush
@section('robots', @$gallery_category->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo', @$gallery_category->seoTitle ? $gallery_category->seoTitle : $gallery_category->title)
@section('description_seo', @$gallery_category->seoDescription)
@section('image_seo', @$gallery_category->item_image)
@section('logo')
    <img src="{{ $settings['logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}" title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('content')
@include('pages.gallery-list._partials.header-inner')

<section class="sk-page gallery-page" aria-labelledby="gallery-album-title">
    <div class="container">
        <a href="{{ route('gallery.category') }}" class="gallery-page__back">
            <i class="bi bi-arrow-right" aria-hidden="true"></i>
            همه آلبوم‌ها
        </a>

        <div class="gallery-mosaic gallery-mosaic--shots">
            @forelse($galleries as $gallery)
                <button
                    type="button"
                    class="gallery-tile gallery-tile--shot"
                    data-bs-toggle="modal"
                    data-bs-target="#galleryModal{{ $gallery['id'] }}"
                    @if(empty($gallery['title'])) aria-label="نمایش تصویر" @endif
                >
                    <span class="gallery-tile__media">
                        <img
                            src="{{ $gallery['item_image'] }}"
                            alt="{{ $gallery['title'] }}"
                            title="{{ $gallery['title'] }}"
                            width="720"
                            height="900"
                            loading="lazy"
                        >
                    </span>
                    <span class="gallery-tile__go gallery-tile__go--icon" aria-hidden="true">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </span>
                    @if(!empty($gallery['title']))
                        <span class="gallery-tile__caption">{{ $gallery['title'] }}</span>
                    @endif
                </button>
            @empty
                <p class="sk-empty">هنوز تصویری در این آلبوم وجود ندارد.</p>
            @endforelse
        </div>

        @include('pages.gallery-list._partials.modal-gallery')
    </div>
</section>
@stop
@push('schema')
    <script type="application/ld+json">
        {
          "@@context": "https://schema.org/",
          "@@type": "BreadcrumbList",
          "itemListElement": [
            {
              "@@type": "ListItem",
              "position": 1,
              "name": "{{$settings['siteName_fa']}}",
              "item": "{{route('index')}}"
        },
        {
          "@@type": "ListItem",
          "position": 2,
          "name": "گالری",
          "item": "{{route('gallery.category')}}"
        },
           {
          "@@type": "ListItem",
          "position": 3,
          "name": "{{$gallery_category['title']}}",
          "item": "{{ route('gallery.list', ['url' => $gallery_category['url']]) }}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush
