<div class="container-fluid setting-shell px-0">
    <div class="setting-toolbar">
        <button type="button"
                class="btn p-0 bg-transparent border-0 shadow-none d-flex align-items-center gap-2 setting-help-link"
                data-bs-toggle="modal"
                data-bs-target="#logoModal"
                data-bs-title="راهنمای سایز لوگو" title="راهنمای سایز لوگو">
            <i class="bi bi-info-square d-flex"></i>
            راهنمای سایز لوگو
        </button>
    </div>
    <nav class="setting-nav">
        <ul class="setting-tab nav nav-tabs list-inline" id="nav-tab" role="tablist">
            @php
                $siteName = \App\Library\SiteHelper::getInformation()['site_name'] ?? '';
            @endphp

            @foreach($partials as $key => $partial)
                @php $partialName = $partial['name'] ?? ''; @endphp
                @if (in_array($theme_provider->getValue(),$partial['theme_type'] ))

                    @if($partialName != 'hidden' && ($siteName == 'khodadadgallery' || $partialName != 'تنظیمات پرداخت بیعانه'))
                        <li class="nav-item list-inline-item">
                            <button class="nav-link @if($key == $active_tab_key) active @endif"
                                    id="nav-{{ $key }}-tab"
                                    data-bs-toggle="tab" data-bs-target="#nav-{{ $key }}"
                                    type="button" role="tab" aria-controls="nav-{{ $key }}"
                                    aria-selected="true">{{ $partialName }}</button>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </nav>
    <div class="tab-content setting-tab-content" id="nav-tabContent">
        @foreach($partials as $key => $partial)
            @php $partialName = $partial['name'] ?? ''; @endphp
            @if (in_array($theme_provider->getValue(),$partial['theme_type'] ))
                @if($partialName != 'hidden' && ($siteName == 'khodadadgallery' || $partialName != 'تنظیمات پرداخت بیعانه'))
                    <div class="tab-pane fade @if($key == $active_tab_key) show active @endif"
                         id="nav-{{ $key }}"
                         role="tabpanel" aria-labelledby="nav-{{ $key }}-tab" tabindex="0">
                        <div class="row w-100 m-0">
                            @if(count($partial['partials']) > 0)
                                @foreach($partial['partials'] as $child)
                                    @if (in_array($theme_provider->getValue(),$child['theme_type'] ))
                                        <section class="setting-section w-100">
                                            <h2 class="setting-section-title">
                                                {{@$child['name']}}
                                            </h2>
                                            <div class="row w-100 m-0 setting-section-fields">
                                                @foreach($child['fields'] as $setting)
                                                    @php
                                                        $options = $setting['options'];
                                                            $setting = \App\Modules\Setting\Entities\Setting::where('key', $setting['key'])
                                                ->where(function ($query) use ($theme_provider) {
                                                    $query->whereNull('theme_type')
                                                          ->orWhere('theme_type', $theme_provider->getValue());
                                                })
                                                ->first();
                                                    @endphp
                                                    @if(@$setting)
                                                        @component('admin.setting.setting.inputs.'.@$setting['type'], ['data' => @$setting,'options'=>$options])
                                                        @endcomponent
                                                    @endif
                                                @endforeach
                                            </div>
                                        </section>
                                    @endif
                                @endforeach
                                @if($partial['name'] == "تنظیمات عمومی پیامک" || $partial['name'] == "تنظیمات پیامک")
                                    @include('admin.setting.setting.sms-pattern-box')
                                @endif
                            @else
                                <section class="setting-section w-100">
                                    <div class="row w-100 m-0 setting-section-fields">
                                        @foreach($partial['fields'] as $setting)
                                            @php
                                                $options = $setting['options'];
                                         $setting = \App\Modules\Setting\Entities\Setting::where('key', $setting['key'])
                                            ->where(function ($query) use ($theme_provider) {
                                                $query->whereNull('theme_type')
                                                      ->orWhere('theme_type', $theme_provider->getValue());
                                            })
                                            ->first();
                                            @endphp
                                            @if(@$setting)
                                                @component('admin.setting.setting.inputs.'.@$setting['type'], ['data' => @$setting,'options'=>$options])
                                                @endcomponent
                                            @endif
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    </div>
    <div class="setting-save">
        <button type="submit" class="btn btn-custom rounded-custom w-fit px-4 py-2">
            ذخیره
        </button>
    </div>
</div>
@push('scripts')
    <script>
        function getNavId(event) {
            event.preventDefault();
            let tab = document.querySelector('.setting-tab .nav-link.active');
            if (!tab) {
                return;
            }
            let activeTabId = tab.getAttribute('data-bs-target').substring(1);

            const inputHidden = document.createElement("input");
            inputHidden.setAttribute('name', 'active_tab');
            inputHidden.setAttribute('type', 'hidden');
            inputHidden.setAttribute('value', activeTabId);

            let form = document.getElementById('cms-form');
            form.appendChild(inputHidden);

            form.submit();
        }
    </script>

@endpush
