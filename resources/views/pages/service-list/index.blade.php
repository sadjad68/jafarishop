@extends('layouts.main.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/site/css/services/tpl-service-list.css?v0.29')}}">
@endpush
@if(isset($service))
    @section('robots', @$service->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
    @section('title_seo',@$service->seoTitle ? $service->seoTitle : $service->title)
    @section('description_seo',@$service->seoDescription)
    @section('image_seo',@$service->image)
@else
    @section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
    @section('title_seo',@$seo_data['title_seo'] ? @$seo_data['title_seo'] : "خدمات")
    @section('description_seo',@$seo_data['description_seo'] ? @$seo_data['description_seo'] : "لیست خدمات")
@endif
@section('logo')
    <img src="{{$settings['logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}"
         class="logo-menu">
@endsection
@section('content')
    @if(isset($service))
        @include('pages.service-list._partials.header-inner-with-children')
    @else
        @include('pages.service-list._partials.header-inner')
    @endif
    @if(isset($service) && $service->description_position == "top")
        @if(isset($service['description']))
            @include('pages.service-list._partials.description')
        @endif
    @endif


    <section class="sk-page pt-3">
        <div class="container">
            @include('pages.service-list._partials.list')
        </div>
    </section>
    @if(isset($service))
        @include('pages.service-list._partials.faq')
    @endif
    @if(!isset($service))

            @include('pages.service-list._partials.list-description')
    @endif
    @if(isset($service) && $service->description_position == "bottom")
        @if(isset($service['description']))
            @include('pages.service-list._partials.description')
        @endif
    @endif
    @if(isset($service))
        @include('layouts.common.comment.comments',['commentable_id'=>$service['id'],'commentable_type'=>get_class($service)])
    @endif
@stop
@push('scripts')
    <script>
        let tableList = document.querySelectorAll('.seo-box table, .sk-prose table');
        tableList.forEach((item) => {
            item.className = "table table-bordered bg-transparent mx-auto table-striped";
            item.outerHTML = `<div class="table-responsive">${item.outerHTML}</div>`
        })
    </script>
    @if (isset($service) && count($faqs) > 0)
        <script type="application/ld+json">
            {
                "@@context": "https://schema.org",
                "@@type": "FAQPage",
                "mainEntity": [
            @foreach($faqs as $key => $faq)
                {
                           "@@type": "Question",
                           "name": "{!! strip_tags($faq['question'])!!}",
			"acceptedAnswer": {
				"@@type": "Answer",
				"text": "{!! strip_tags($faq['answer'])!!}"
			}
		}
                @if(!$loop->last)
                    ,
                @endif
            @endforeach
            ]
        }
        </script>
    @endif
@endpush
