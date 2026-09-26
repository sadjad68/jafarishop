@if(count($team_members) > 0)
    <section class="team t1-section t1-section--ink">
        <div class="container">
            @include('pages.first-page._partials.theme1._section-head', [
                't1_eyebrow' => 'تیم ما',
                't1_title' => @$settings['first_page_team_title'],
                't1_desc' => @$settings['first_page_team_text'],
                't1_center' => true,
            ])
        </div>
        <div class="swiper swiper-team" data-reveal>
            <div class="swiper-wrapper justify-content-md-center py-2">
                @foreach($team_members as $row)
                    <div class="swiper-slide">
                        <article class="team-card">
                            <div class="team-media">
                                <img src="{{$row->getAvatar()}}" class="team-img" alt="{{$row['full_name']}}"
                                     title="{{$row['full_name']}}" loading="lazy">
                            </div>
                            <div class="information">
                                <p class="name mb-0">
                                    {{$row['full_name']}}
                                </p>
                                @if(count($row['services']) > 0)
                                <p class="expert mb-0">
                                    @foreach($row['services'] as $team_service)
                                        {!! $team_service['title'] !!}@if (!$loop->last)، @endif
                                    @endforeach
                                </p>
                                @endif
                                @if($row['mobile'])
                                <a href="{{"tel:".$row['mobile']}}" class="team-card__phone">
                                    <i class="bi bi-telephone" aria-hidden="true"></i>
                                    {{$row['mobile']}}
                                </a>
                                @endif
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
