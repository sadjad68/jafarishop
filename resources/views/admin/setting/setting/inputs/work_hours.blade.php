@php
    $workHours = json_decode($data['value'] ?? '[]', true);
    if (!is_array($workHours)) {
        $workHours = [];
    }
@endphp
<div class="col-12 p-2">
    <div class="work-hours-editor">
        <p class="admin-label">{{ @$data['p_name'] }}</p>
        <ul class="work-hours-list">
            @foreach (Config::get('settings.days') as $key => $value)
                @php
                    $dayHours = is_array($workHours[$value] ?? null) ? $workHours[$value] : [];
                    $from = $dayHours['from'] ?? null;
                    $to = $dayHours['to'] ?? null;
                    $isClosed = ($from === null || $from === '') && ($to === null || $to === '');
                @endphp
                <li class="work-hours-row{{ $isClosed ? ' is-closed' : '' }}" data-day-key="{{ $key }}">
                    <span class="work-hours-day">{{ $value }}</span>
                    <label class="admin-switch" for="offday{{ $key }}">
                        <input class="admin-switch-input"
                               id="offday{{ $key }}"
                               value="1"
                               type="checkbox"
                               role="switch"
                               @if ($isClosed) checked @endif
                               oninput="checkOff('{{ $key }}')">
                        <span class="admin-switch-track" aria-hidden="true"></span>
                        <span class="admin-switch-text">تعطیل</span>
                    </label>
                    <div class="work-hours-range">
                        <label for="fromInput{{ $key }}">از</label>
                        <input requiredCms
                               type="text"
                               class="form-control admin-input"
                               name="{{ @$data['key'] }}[{{ $value }}][from]"
                               value="{{ $from }}"
                               id="fromInput{{ $key }}"
                               @if ($isClosed) readonly @endif
                               autocomplete="off"
                               onchange="checkHour(event)">
                        <label for="toInput{{ $key }}">تا</label>
                        <input requiredCms
                               type="text"
                               class="form-control admin-input"
                               name="{{ @$data['key'] }}[{{ $value }}][to]"
                               value="{{ $to }}"
                               id="toInput{{ $key }}"
                               @if ($isClosed) readonly @endif
                               autocomplete="off"
                               onchange="checkHour(event)">
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

@push('scripts')
    <script>
        function checkOff(key) {
            var fromInput = document.getElementById('fromInput' + key);
            var toInput = document.getElementById('toInput' + key);
            var checkbox = document.getElementById('offday' + key);
            var row = checkbox ? checkbox.closest('.work-hours-row') : null;

            if (!fromInput || !toInput || !checkbox) {
                return;
            }

            if (checkbox.checked) {
                fromInput.readOnly = true;
                fromInput.value = '';
                toInput.readOnly = true;
                toInput.value = '';
                if (row) {
                    row.classList.add('is-closed');
                }
            } else {
                fromInput.readOnly = false;
                toInput.readOnly = false;
                if (row) {
                    row.classList.remove('is-closed');
                }
            }
        }

        function checkHour(e) {
            const hour = e.target.value;
            if (hour < 0 || hour > 24) {
                Swal.fire({
                    icon: 'error',
                    text: "ساعت وارد شده نا معتبر است",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
                e.target.value = '';
                return false;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            @foreach (Config::get('settings.days') as $key => $value)
                checkOff({{ $key }});
            @endforeach
        });
    </script>
@endpush
