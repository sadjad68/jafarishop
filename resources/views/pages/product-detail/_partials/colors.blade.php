<div class="pdp-variant pdp-variant--color">
    <div class="pdp-variant__header">
        <span class="pdp-variant__label">
            @{{ mainSpec.main_title.replace(/:+$/, '') }}
            <span class="pdp-variant__chosen" v-if="selectedChildTitle(mainSpec)">@{{ selectedChildTitle(mainSpec) }}</span>
        </span>
    </div>
    <ul class="pdp-variant__list pdp-variant__list--colors" role="listbox" :aria-label="mainSpec.main_title">
        <li v-for="child in mainSpec.children" :key="child.id" role="presentation">
            <button
                type="button"
                role="option"
                :aria-label="child.title"
                :aria-selected="isSelectedSpec(mainSpec.main_id, child.id)"
                :class="{
                    'pdp-variant__swatch': true,
                    'pdp-variant__swatch--active': isSelectedSpec(mainSpec.main_id, child.id),
                    'pdp-variant__swatch--light': ['#fff', '#ffffff', '#feffff', 'rgb(255, 255, 255)'].includes(child.color_code?.trim().toLowerCase()),
                    'pdp-variant__swatch--disabled': loadingSpec
                }"
                :style="{ background: child.color_code }"
                :disabled="loadingSpec"
                @click.prevent.stop="selectSpecs({ ...child, main_id: mainSpec.main_id })"
            >
                <i
                    v-if="isSelectedSpec(mainSpec.main_id, child.id)"
                    class="bi bi-check-lg pdp-variant__swatch-check"
                    aria-hidden="true"
                ></i>
            </button>
        </li>
    </ul>
</div>
