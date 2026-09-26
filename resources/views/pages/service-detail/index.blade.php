@extends('layouts.main.master')
@section('robots', @$service->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$service->seoTitle ? $service->seoTitle : $service->title)
@section('description_seo',@$service->seoDescription)
@section('image_seo',@$service->image)
@section('logo')
<img src="{{$settings['logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
    @include('pages.service-detail._partials.header-detail')
    @include('pages.service-detail._partials.samples-inner')
    @include('pages.service-detail._partials.price-table')
    @include('pages.service-detail._partials.seo-box')
    @include('pages.service-detail._partials.faq')
    @include('pages.service-detail._partials.related-service')
    @include('pages.service-detail._partials.related-blogs')
    @include('layouts.common.comment.comments',['commentable_id'=>$service['id'],'commentable_type'=>get_class($service)])
@stop
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/site/css/services/tpl-service-detail.css?v0.33')}}">
    <link rel="stylesheet" href="{{asset('assets/site/css/blogs/tpl-blog-list.css?v0.33')}}">
@endpush
@push('scripts')
    <script src="{{asset('assets/site/js/services/tpl-service-detail.js?v0.02')}}"></script>
        <script>
            let tableList = document.querySelectorAll('.seo-box table, .sk-prose table');
            tableList.forEach((item) => {
                item.className = "table table-bordered bg-transparent mx-auto table-striped";
                item.outerHTML = `<div class="table-responsive">${item.outerHTML}</div>`
            })
        </script>
@endpush
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
          "name": "خدمات",
          "item": "{{ route('service.list') }}"
        },
       @if(isset($service->parent_id))
            {
              "@@type": "ListItem",
              "position": 3,
              "name": "{{$service->parent->title}}",
          "item": "{{ route('service.detail', ['url' => $service->parent->url]) }}"
        },
             @endif
        {
       "@@type": "ListItem",
@if(isset($service->parent_id))
            "position": 4,
@else
            "position": 3,
@endif
        "name": "{{$service['title']}}",
          "item": "{{ route('service.detail', ['url' => $service->url]) }}"
        }
              ],
      "name": "menu"
    }
    </script>
    @if(count($faqs) > 0)
        <script type="application/ld+json">
            {!! json_encode([
                "@@context" => "https://schema.org",
                "@@type" => "FAQPage",
                "mainEntity" => $faqs->map(function($faq) {
                    return [
                        "@@type" => "Question",
                        "name" => strip_tags($faq['question']),
                        "acceptedAnswer" => [
                            "@@type" => "Answer",
                            "text" => strip_tags($faq['answer']),
                        ]
                    ];
                })->toArray()
            ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
        </script>
    @endif
@endpush
