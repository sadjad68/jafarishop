@php
    use App\Modules\General\Helper\ColorHelper;

    $colorTypes = \Config::get('settings.color_types');
    $selectedValue = $data['value'];
    $selectedLabel = array_search($selectedValue, $colorTypes, true);
    if ($selectedLabel === false) {
        $selectedLabel = array_key_first($colorTypes);
        $selectedValue = $colorTypes[$selectedLabel] ?? '';
    }
    $selectedColors = json_decode($selectedValue, true) ?: [];
@endphp

<div class="col-xxl-3 col-sm-6 col-12 px-md-2 px-0 my-2">
    <label>{{ $data['p_name'] }}</label>
    <div class="theme-color-picker" data-theme-color-picker>
        <input type="hidden" name="{{ $data['key'] }}" value="{{ $selectedValue }}">
        <button type="button" class="theme-color-picker__toggle" aria-haspopup="listbox" aria-expanded="false">
            <span class="theme-color-swatch" style="{{ ColorHelper::themeSwatchVars($selectedColors) }}">{{ $selectedLabel }}</span>
        </button>
        <div class="theme-color-picker__menu" hidden>
            <input type="search" class="theme-color-picker__search" placeholder="جستجو" autocomplete="off">
            <ul class="theme-color-picker__list" role="listbox">
                @foreach($colorTypes as $label => $colorType)
                    @php $colorData = json_decode($colorType, true) ?: []; @endphp
                    <li>
                        <button type="button"
                                class="theme-color-picker__option{{ $colorType === $selectedValue ? ' is-selected' : '' }}"
                                role="option"
                                data-value="{{ $colorType }}"
                                data-label="{{ $label }}"
                                data-search="{{ $label }}">
                            <span class="theme-color-swatch" style="{{ ColorHelper::themeSwatchVars($colorData) }}">{{ $label }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@push('styles')
    <style>
        .theme-color-picker {
            position: relative;
        }

        .theme-color-picker__toggle {
            display: block;
            width: 100%;
            padding: 4px;
            border-radius: 14px;
            border: 1px solid var(--admin-stroke, #dee2e6);
            background: var(--admin-input, #fff);
            text-align: inherit;
        }

        .theme-color-picker__toggle::after {
            display: none;
        }

        .theme-color-picker__menu {
            position: absolute;
            inset-inline: 0;
            top: calc(100% + 6px);
            z-index: 20;
            padding: 8px;
            border-radius: 14px;
            border: 1px solid var(--admin-stroke, #dee2e6);
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        }

        .theme-color-picker__search {
            width: 100%;
            height: 40px;
            margin-bottom: 8px;
            padding: 0 12px;
            border-radius: 10px;
            border: 1px solid var(--admin-stroke, #dee2e6);
        }

        .theme-color-picker__list {
            max-height: 260px;
            overflow: auto;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .theme-color-picker__option {
            display: block;
            width: 100%;
            margin: 0 0 4px;
            padding: 0;
            border: 0;
            background: transparent;
            text-align: inherit;
        }

        .theme-color-swatch {
            display: block;
            width: 100%;
            min-height: 38px;
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 600;
            line-height: 1.5;
            color: var(--theme-tx, #111) !important;
            background: linear-gradient(90deg, var(--theme-c3) 0%, var(--theme-c2) 50%, var(--theme-c1) 100%) !important;
            text-shadow: none !important;
        }

        .theme-color-picker__option.is-selected .theme-color-swatch,
        .theme-color-picker__option:hover .theme-color-swatch {
            outline: 2px solid color-mix(in srgb, var(--admin-accent, #3b82f6) 70%, white);
            outline-offset: 1px;
        }

        html[data-theme="dark"] .theme-color-picker__menu {
            background: #151b2e;
        }

        html[data-theme="dark"] .theme-color-picker__search {
            background: var(--admin-input, #151b2e);
            color: var(--admin-text, #e8eef8);
        }
    </style>
@endpush
@push('scripts')
    <script>
        (function () {
            function closePicker(picker) {
                const menu = picker.querySelector('.theme-color-picker__menu');
                const toggle = picker.querySelector('.theme-color-picker__toggle');
                if (!menu || !toggle) {
                    return;
                }
                menu.hidden = true;
                toggle.setAttribute('aria-expanded', 'false');
            }

            function openPicker(picker) {
                document.querySelectorAll('[data-theme-color-picker]').forEach(closePicker);
                const menu = picker.querySelector('.theme-color-picker__menu');
                const toggle = picker.querySelector('.theme-color-picker__toggle');
                const search = picker.querySelector('.theme-color-picker__search');
                menu.hidden = false;
                toggle.setAttribute('aria-expanded', 'true');
                if (search) {
                    search.focus();
                }
            }

            function bindPicker(picker) {
                if (picker.dataset.bound === '1') {
                    return;
                }
                picker.dataset.bound = '1';

                const hidden = picker.querySelector('input[type="hidden"]');
                const toggle = picker.querySelector('.theme-color-picker__toggle');
                const menu = picker.querySelector('.theme-color-picker__menu');
                const search = picker.querySelector('.theme-color-picker__search');

                toggle.addEventListener('click', function (event) {
                    event.preventDefault();
                    if (menu.hidden) {
                        openPicker(picker);
                    } else {
                        closePicker(picker);
                    }
                });

                picker.querySelectorAll('.theme-color-picker__option').forEach(function (option) {
                    option.addEventListener('click', function (event) {
                        event.preventDefault();
                        hidden.value = option.getAttribute('data-value');
                        toggle.innerHTML = option.innerHTML;
                        picker.querySelectorAll('.theme-color-picker__option').forEach(function (item) {
                            item.classList.toggle('is-selected', item === option);
                        });
                        closePicker(picker);
                    });
                });

                search.addEventListener('input', function () {
                    const term = search.value.trim();
                    picker.querySelectorAll('.theme-color-picker__option').forEach(function (option) {
                        const match = option.getAttribute('data-search').indexOf(term) !== -1;
                        option.parentElement.hidden = !match;
                    });
                });
            }

            function boot() {
                document.querySelectorAll('[data-theme-color-picker]').forEach(bindPicker);
            }

            document.addEventListener('click', function (event) {
                document.querySelectorAll('[data-theme-color-picker]').forEach(function (picker) {
                    if (!picker.contains(event.target)) {
                        closePicker(picker);
                    }
                });
            });

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', boot);
            } else {
                boot();
            }
        })();
    </script>
@endpush
