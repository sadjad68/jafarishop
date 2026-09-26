@extends('layouts.main.master')
@section('robots', @$sample->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$sample->seoTitle ? $sample->seoTitle : $sample->title)
@section('description_seo',$sample->seoDescription)
@section('image_seo',$sample->getImage())
@section('logo')
<img src="{{$settings['logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
    @include('pages.sample-detail._partials.header-inner')
    @include('pages.sample-detail._partials.related-services')
    @include('pages.sample-detail._partials.description')
    @include('pages.sample-detail._partials.related-blog')
    @include('layouts.common.comment.comments',['commentable_id'=>$sample['id'],'commentable_type'=>get_class($sample)])
@stop
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/site/css/samples/tpl-sample-detail.css?v0.18')}}">
    <link rel="stylesheet" href="{{asset('assets/site/css/blogs/tpl-blog-list.css?v0.33')}}">
@endpush
@push('scripts')
    <script src="{{asset('assets/site/js/samples/tpl-sample-detail.js?v0.01')}}"></script>
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
          "name": "نمونه کارها",
          "item": "{{{route('portfolio.list')}}}"
        },
           {
          "@@type": "ListItem",
          "position": 3,
          "name": "{{$sample['title']}}",
          "item": "{{ route('portfolio.detail', ['url' => $sample['url']]) }}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush
