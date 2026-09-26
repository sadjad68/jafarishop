@extends('layouts.main.master')
@section('robots', @$blog->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$blog->seoTitle ? $blog->seoTitle : $blog->title)
@section('description_seo',@$blog->seoDescription)
@section('image_seo',@$blog->getItemImage())
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('type','article')
@section('content')
<div class="blog-article-page">
    @include('pages.blog-detail._partials.header-inner')

    <div class="blog-article">
        <div class="container">
            <div class="blog-article__layout">
                <aside class="blog-article__aside">
                    <div class="blog-article__aside-sticky">
                        @include('pages.blog-detail._partials.related-service')
                        @include('pages.blog-detail._partials.related-blog')
                    </div>
                </aside>
                <div class="blog-article__main">
                    @include('pages.blog-detail._partials.description')
                </div>
            </div>
        </div>
    </div>

    @include('layouts.common.comment.comments',['commentable_id'=>$blog['id'],'commentable_type'=>get_class($blog)])
</div>
@stop
@push('styles')
<link rel="stylesheet" href="{{asset('assets/site/css/blogs/tpl-blog-detail.css?v0.79')}}">
<link rel="stylesheet" href="{{asset('assets/site/css/blogs/tpl-blog-list.css?v0.33')}}">

@endpush
@push('scripts')
    <script>
        let tableList = document.querySelectorAll('.blog-article__body table');
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
            "@@type": "Article",
            "author": [{
                "@@type": "Organization",
                "name": "{{@$settings['siteName_fa']}}",
                "url": "{{url('/')}}"
            }],

            "headline": "{{@$blog->seoTitle ? $blog->seoTitle : $blog->title}}",
            "image": {
                "@@type": "ImageObject",
                "url": "{{@$blog->getItemImage()}}"
            },
            "datePublished": "{{$blog->publish_date}}",
            "publisher": {
                "@@type": "Organization",
                "name": "{{@$default_seo['title_seo']}}",
                "logo": {
                    "@@type": "ImageObject",
                    "url": "{{@$settings['logo']}}"
                },
                "address": "{{url('/')}}"
            },

            "description": "{{@$blog->seoDescription}}"
        }
    </script>
    @php
        $breadcrumbs = [
            [
                "@@type"   => "ListItem",
                "position"=> 1,
                "name"    => $settings['siteName_fa'],
                "item"    => route('index'),
            ],
            [
                "@@type"   => "ListItem",
                "position"=> 2,
                "name"    => "مطالب",
                "item"    => route('blog.category-list'),
            ],
        ];

        $pos = 3;

        if (@$blog->category) {
            if (@$blog->category->parent) {
                $breadcrumbs[] = [
                    "@@type"   => "ListItem",
                    "position"=> $pos++,
                    "name"    => @$blog->category->parent->title,
                    "item"    => route('blog.list', ['url' => @$blog->category->parent->url]),
                ];
            }

            $breadcrumbs[] = [
                "@@type"   => "ListItem",
                "position"=> $pos++,
                "name"    => @$blog->category->title,
                "item"    => route('blog.list', ['url' => @$blog->category->url]),
            ];
        }

        $breadcrumbs[] = [
            "@@type"   => "ListItem",
            "position"=> $pos,
            "name"    => @$blog->title,
            "item"    => \App\Library\SiteUrl::blog($blog),
        ];
    @endphp

    <script type="application/ld+json">
        {!! json_encode([
                "@@context"        => "https://schema.org",
                "@@type"           => "BreadcrumbList",
                "itemListElement" => $breadcrumbs,
                "name"            => "menu",
            ],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        ) !!}
    </script>

@endpush
