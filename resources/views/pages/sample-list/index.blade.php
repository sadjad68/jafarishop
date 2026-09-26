@extends('layouts.main.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/site/css/samples/tpl-sample-list.css?v0.29')}}">
    <style>
        [v-cloak] { display: none; }
    </style>
@endpush
@section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$seo_data['title_seo'] ? @$seo_data['title_seo'] : "نمونه کار")
@section('description_seo',@$seo_data['description_seo'] ? @$seo_data['description_seo'] : "لیست نمونه کار")
@section('logo')
<img src="{{$settings['logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
    @include('pages.sample-list._partials.header-inner')
    <section class="sk-page" id="samples" v-cloak :aria-busy="loading ? 'true' : 'false'">
        <div class="container">
            @include('pages.sample-list._partials.tabs')
            <div v-scroll="scroll">
                <div class="sk-sample-grid" v-if="!isFilter">
                    @forelse($samples as $sample)
                        @if ($sample['url'] != null)
                            <a href="{{ route('portfolio.detail', ['url' => $sample['url']]) }}" class="sk-sample-card">
                                <span class="sk-sample-card__media">
                                    <img src="{{$sample->getImage()}}" alt="{{$sample['title']}}"
                                         title="{{$sample['title']}}" loading="lazy" width="480" height="360">
                                </span>
                                <span class="sk-sample-card__name">
                                    {{$sample['title']}}
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                </span>
                            </a>
                        @else
                            <div class="sk-sample-card">
                                <span class="sk-sample-card__media">
                                    <img src="{{$sample->getImage()}}" alt="{{$sample['title']}}"
                                         title="{{$sample['title']}}" loading="lazy" width="480" height="360">
                                </span>
                                <span class="sk-sample-card__name">{{$sample['title']}}</span>
                            </div>
                        @endif
                    @empty
                        <p class="sk-empty" style="grid-column: 1 / -1;">هنوز نمونه کاری برای نمایش وجود ندارد.</p>
                    @endforelse
                </div>
                <div class="sk-sample-grid" v-if="samples.length > 0">
                    <template v-for="sample in samples">
                        <a v-if="sample.url != null" :href="sample.url" class="sk-sample-card">
                            <span class="sk-sample-card__media">
                                <img :src="sample.image" :alt="sample.title" :title="sample.title" loading="lazy" width="480" height="360">
                            </span>
                            <span class="sk-sample-card__name">
                                @{{ sample.title }}
                                <i class="bi bi-chevron-left" aria-hidden="true"></i>
                            </span>
                        </a>
                        <div v-else class="sk-sample-card">
                            <span class="sk-sample-card__media">
                                <img :src="sample.image" :alt="sample.title" :title="sample.title" loading="lazy" width="480" height="360">
                            </span>
                            <span class="sk-sample-card__name">@{{ sample.title }}</span>
                        </div>
                    </template>
                </div>
                <p class="sk-empty" v-if="isFilter && !loading && samples.length === 0">
                    نمونه کاری برای این خدمت پیدا نشد.
                </p>
                @include('pages.sample-list._partials.loading')
                <div id="scrollMePlease"></div>
            </div>
        </div>
    </section>
    @include('pages.sample-list._partials.description')
@stop
@push('vue')
    @include('pages.sample-list._partials.vue')
@endpush
@push('scripts')
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
        {"@@context":"https://schema.org",
        "@@type":"ItemList",
        "itemListElement":[
        @foreach($samples as $sample)
        {"@@type":"CreativeWork",
        "image":"{{$sample->getImage()}}",
        "name":"{{$sample['title']}}",
        "headline":"{{@$sample->seoTitle ? $sample->seoTitle : $sample->title}}",
        "description":"{{$sample->seoDescription}}",
        @if($sample['url'] != null)
        "url":"{{ route('portfolio.detail', ['url' => $sample['url']]) }}"
        @endif
        }@if(!$loop->last),@endif
        @endforeach
        ],
        "numberOfItems":{{count($samples)}},
        "url":"{{{route('portfolio.list')}}}",
        "name":"{{@$seo_data['title_seo'] ? @$seo_data['title_seo'] : "نمونه کار"}}"}
    </script>
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
        }
              ],
      "name": "menu"
    }
    </script>
    @endpush
@endpush
