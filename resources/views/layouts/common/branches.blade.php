@php
    $t1_phone = $t1_phone ?? trim((string) (@$settings['main_phone_number'] ?? ''));
    $t1_phone_label = $t1_phone_label ?? 'تماس با ما';
    $t1_has_branches = $t1_has_branches ?? (isset($branches) && count($branches) > 0);
@endphp
<aside class="about-us__visit" data-reveal aria-label="شعبه و تماس">
    <p class="about-us__visit-kicker">{{ $t1_has_branches ? 'شعبه و تماس' : 'تماس' }}</p>

    @if($t1_has_branches)
        <div class="about-us__branch" v-cloak>
            <label class="about-us__label" for="t1-about-branch">انتخاب شعبه</label>
            <div class="about-us__select-wrap">
                <select id="t1-about-branch" class="about-us__select" v-model="mainBranch">
                    <option v-for="branch in branches" :key="branch.id" :value="branch">@{{ branch.title }}</option>
                </select>
                <i class="bi bi-chevron-down" aria-hidden="true"></i>
            </div>
            <p class="about-us__address" v-if="mainBranch && mainBranch.address">@{{ mainBranch.address }}</p>
            <a v-if="mainBranch && mainBranch.map"
               class="about-us__map"
               :href="mainBranch.map"
               target="_blank"
               rel="nofollow noopener noreferrer">
                مسیریابی روی نقشه
                <i class="bi bi-geo-alt" aria-hidden="true"></i>
            </a>
        </div>
    @endif

    @if($t1_phone !== '')
        <a href="tel:{{ $t1_phone }}" class="about-us__phone">
            <span class="about-us__phone-copy">
                <span class="about-us__phone-label">{{ $t1_phone_label }}</span>
                <span class="about-us__phone-num" dir="ltr">@toPersianNumber($t1_phone)</span>
            </span>
            <span class="about-us__phone-icon" aria-hidden="true">
                <i class="bi bi-telephone"></i>
            </span>
        </a>
    @endif
</aside>
