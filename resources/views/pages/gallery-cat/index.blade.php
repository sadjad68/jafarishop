@extends('layouts.main.master')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/site/css/gallery/tpl-gallery-category.css?v0.13') }}">
@endpush
@section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo', @$seo_data['title_seo'] ? @$seo_data['title_seo'] : 'گالری')
@section('description_seo', @$seo_data['description_seo'] ? @$seo_data['description_seo'] : 'گالری تصاویر')
@section('logo')
<img src="{{ $settings['logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}" title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('content')
@include('pages.gallery-cat._partials.header-inner')
<section class="sk-page gallery-page" aria-labelledby="gallery-page-title">
    <div class="container">
        <div class="gallery-mosaic gallery-mosaic--albums">
            @forelse($gallery_categories as $gallery_category)
                <a
                    href="{{ route('gallery.list', ['url' => $gallery_category['url']]) }}"
                    class="gallery-tile gallery-tile--album"
                >
                    <span class="gallery-tile__media">
                        <img
                            src="{{ $gallery_category['item_image'] }}"
                            alt="{{ $gallery_category['title'] }}"
                            title="{{ $gallery_category['title'] }}"
                            width="720"
                            height="900"
                            loading="lazy"
                        >
                    </span>
                    <span class="gallery-tile__count">
                        <i class="bi bi-images" aria-hidden="true"></i>
                        {{ (int) ($gallery_category->galleries_count ?? 0) }} تصویر
                    </span>
                    <span class="gallery-tile__go" aria-hidden="true">
                        مشاهده آلبوم
                        <i class="bi bi-arrow-up-left"></i>
                    </span>
                    <span class="gallery-tile__caption">{{ $gallery_category['title'] }}</span>
                </a>
            @empty
                <p class="sk-empty">هنوز آلبومی برای نمایش وجود ندارد.</p>
            @endforelse
        </div>
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
        }
              ],
      "name": "menu"
    }
    </script>
@endpush
