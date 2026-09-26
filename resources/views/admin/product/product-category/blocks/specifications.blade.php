
    <div id="specification-management" class="border p-3">
        <div class="d-flex gap-3 align-items-center">
            <div class="mb-3 w-100">
                <label for="specificationSelect" class="d-block">
                    مشخصه
                </label>
                <select id="specificationSelect" class="boot-select text-start" style="width: 100%;"
                    data-placeholder="انتخاب مشخصه">
                    <option value="">مشخصه را انتخاب کنید</option>
                    @foreach ($specifications as $spec)
                        <option value="{{ $spec['id'] }}">{{ $spec['title'] }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" id="addSpecificationBtn" class="btn btn-primary mt-2">
                افزودن
            </button>
        </div>
        <hr class="my-2">
        <div id="specificationList" class="row w-100 m-0 mt-3 p-0"></div>
    </div>


    @push('scripts')
    <script>
        class SpecificationManagement {
            static selectedId = null;
            static selectedText = null;
            static selectedSpecs = new Set();

            static init(preselected = []) {
                const $select = $('#specificationSelect');
                const $list = $('#specificationList');

                // Mount Select2
                $select.select2({
                    dir: "rtl",
                    placeholder: "انتخاب مشخصه",
                    allowClear: true,
                    closeOnSelect: true,
                    language: { noResults: () => "موردی یافت نشد" }
                });

                // انتخاب مشخصه
                $select.on('select2:select', (e) => {
                    const data = e.params.data;
                    this.selectedId = data.id;
                    this.selectedText = data.text;
                });

                // افزودن مشخصه
                $('#addSpecificationBtn').on('click', async () => {
                    if (!this.selectedId) return alert("لطفاً یک مشخصه انتخاب کنید.");
                    if (this.selectedSpecs.has(this.selectedId)) return alert("این مشخصه قبلاً اضافه شده است.");

                    this.selectedSpecs.add(this.selectedId);
                    await this.addSpecificationItem(this.selectedId, this.selectedText, $list);
                    $select.val(null).trigger('change');
                    this.selectedId = null;
                });

                // رندر داده‌های از پیش انتخاب‌شده
                if (Array.isArray(preselected) && preselected.length) {
                    preselected.forEach(item => {
                        this.selectedSpecs.add(item.id);
                        this.addSpecificationItem(item.id, item.title, $list, item.value_ids);
                    });
                }
            }

            static async addSpecificationItem(id, title, $list, preselectedValueIds = []) {
    const wrapperId = `spec-item-${id}`;

    // اول یه اسکلت خالی بساز ولی select رو hidden کن تا لود تموم شه
    const $item = $(`
        <div class="col-12 col-sm-6 col-lg-4 mb-2 spec-item" id="${wrapperId}">
            <label class="mb-1 d-block">${title} :</label>
            <div class="d-flex align-items-center gap-2 mt-1">
                <select class="spec-value w-100 form-select d-none" data-spec-id="${id}" multiple></select>
                <div class="spinner-border spinner-border-sm text-primary ms-2 loading-spinner" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-spec">حذف</button>
            </div>
        </div>
    `);

    $list.append($item);
    const $valueSelect = $item.find('.spec-value');
    const $spinner = $item.find('.loading-spinner');

    // دریافت مقادیر از سرور
    const values = await this.fetchSpecificationValues(id);
    $valueSelect.empty();

    // پر کردن گزینه‌ها
    values.forEach(v => $valueSelect.append(`<option value="${v.id}">${v.title}</option>`));

    // فعال‌سازی Select2 بعد از پر شدن
    $valueSelect.select2({
        dir: "rtl",
        placeholder: "انتخاب مقدار",
        language: { noResults: () => "موردی یافت نشد" }
    });

    // حالا spinner رو حذف و select رو نشون بده
    $spinner.remove();
    $valueSelect.removeClass('d-none');

    // پیش‌انتخاب‌ها
    if (Array.isArray(preselectedValueIds) && preselectedValueIds.length) {
        $valueSelect.val(preselectedValueIds).trigger('change');
        preselectedValueIds.forEach(valueId => {
            $item.append(`<input type="hidden" name="filter_specifications[${id}][]" value="${valueId}">`);
        });
    }

    // تغییر انتخاب‌ها
    $valueSelect.on('change', function() {
        $item.find('input[type="hidden"]').remove();
        const selected = $(this).val() || [];
        selected.forEach(valueId => {
            $item.append(`<input type="hidden" name="filter_specifications[${id}][]" value="${valueId}">`);
        });
    });

    // حذف مشخصه
    $item.find('.remove-spec').on('click', () => {
        $(`#${wrapperId}`).remove();
        this.selectedSpecs.delete(id);
    });
}


            static async fetchSpecificationValues(id) {
                try {
                    const res = await fetch(`{{ url('admin/product-category/get-specification/${id}/') }}`);
                    if (!res.ok) throw new Error("خطا در دریافت مقدارها");
                    return await res.json();
                } catch (e) {
                    console.error(e);
                    return [];
                }
            }
        }

        // --- مقداردهی اولیه از سرور
        $(document).ready(() => {
            let preselected = [];

            @php
                // فرض کن هر parent چندتا child داره، اینجا گروهبندی‌شون می‌کنیم
                $grouped = [];
                foreach (@$data->specificationConditions ?? [] as $item) {
                    if ($item->parent) {
                        $grouped[$item->parent_id]['id'] = $item->parent_id;
                        $grouped[$item->parent_id]['title'] = $item->parent->title ?? '';
                        $grouped[$item->parent_id]['value_ids'][] = $item->id;
                    }
                }
            @endphp

            preselected = [
                @foreach ($grouped as $g)
                    {
                        id: {{ $g['id'] }},
                        title: '{{ $g['title'] }}',
                        value_ids: {!! json_encode($g['value_ids'] ?? []) !!}
                    },
                @endforeach
            ];

            SpecificationManagement.init(preselected);
        });
    </script>

    @endpush

