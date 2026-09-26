<div class="pdp-variant">
    <div class="pdp-variant__header">
        <span class="pdp-variant__label">
            @{{ mainSpec.main_title.replace(/:+$/, '') }}
            <span class="pdp-variant__chosen" v-if="selectedChildTitle(mainSpec)">@{{ selectedChildTitle(mainSpec) }}</span>
        </span>
    </div>
    <ul class="pdp-variant__list" role="listbox" :aria-label="mainSpec.main_title">
        <li v-for="child in mainSpec.children" :key="child.id" role="presentation">
            <button
                type="button"
                role="option"
                :aria-selected="isSelectedSpec(mainSpec.main_id, child.id)"
                :class="{
                    'pdp-variant__chip': true,
                    'pdp-variant__chip--active': isSelectedSpec(mainSpec.main_id, child.id),
                    'pdp-variant__chip--disabled': loadingSpec
                }"
                :disabled="loadingSpec"
                @click.prevent.stop="selectSpecs({ ...child, main_id: mainSpec.main_id })"
            >
                <span
                    class="pdp-variant__chip-indicator"
                    aria-hidden="true"
                ></span>
                <span class="pdp-variant__chip-text">@{{ child.title }}</span>
            </button>
        </li>
    </ul>
</div>
