@php
    $arrayKey = @$data['key'];
    $arrayHintId = $arrayKey . '-hint';
@endphp
<div class="col-12 p-2">
    <div class="form-group setting-array">
        <label class="text-start w-100 d-flex justify-content-start" for="{{ $arrayKey }}">
            <span>{{ @$data['p_name'] }}</span>
        </label>
        <input type="text"
               class="form-control setting-array-input"
               name="{{ @$data['type'] }}[{{ $arrayKey }}]"
               id="{{ $arrayKey }}"
               value="{{ @$data['value'] }}"
               placeholder="مورد جدید را وارد کنید و Enter بزنید"
               autocomplete="off"
               aria-describedby="{{ $arrayHintId }}">
        <p class="setting-array-hint" id="{{ $arrayHintId }}">
            مورد قبلی را حذف نکنید؛ مورد جدید را در کادر وارد کنید و Enter بزنید.
        </p>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('assets/admin/js/selectize.js?v0.17') }}"></script>
    <script>
        $(document).ready(function() {
            var $arrayField = $('#{{ $arrayKey }}');
            $arrayField.selectize({
                plugins: ['remove_button'],
                delimiter: '~~##',
                persist: false,
                maxItems: null,
                mode: 'multi',
                createOnBlur: true,
                placeholder: 'مورد جدید را وارد کنید و Enter بزنید',
                valueField: 'tag',
                labelField: 'tag',
                searchField: 'tag',
                create: function(input) {
                    return {
                        tag: input
                    };
                }
            });
            var selectize = $arrayField[0] && $arrayField[0].selectize;
            if (selectize) {
                var placeholderText = 'مورد جدید را وارد کنید و Enter بزنید';
                var keepPlaceholder = function() {
                    selectize.$control_input
                        .attr('placeholder', placeholderText)
                        .attr('aria-describedby', '{{ $arrayHintId }}');
                };
                selectize.on('item_add', keepPlaceholder);
                selectize.on('item_remove', keepPlaceholder);
                keepPlaceholder();
            }
        });
    </script>
@endpush
