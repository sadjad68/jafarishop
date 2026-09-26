<div class="admin-order-items">
    <div class="admin-order-items__head">
        <div>
            <h3 class="admin-order-items__title">
                <i class="bi bi-box-seam"></i>
                جزئیات اقلام فاکتور
            </h3>
            <p class="admin-order-items__meta">{{ count($data->allItems) }} قلم کالا</p>
        </div>
        @if(count($data->items) > 0 && $data->order_status == 'paid')
            <a href="{{ route('admin.order.order-return', ['id' => $data->id]) }}"
               class="btn btn-danger btn-sm rounded-custom"
               onclick="return confirm('آیا مطمئن هستید؟')">
                <i class="bi bi-arrow-return-left"></i>
                مرجوع کردن کل فاکتور
            </a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table align-middle admin-order-items__table mb-0">
            <thead>
            <tr>
                <th class="text-start">#</th>
                <th class="text-start">عنوان محصول</th>
                <th>تصویر</th>
                <th>قیمت نهایی / تخفیف</th>
                <th>تعداد (فعلی / اولیه)</th>
                @if($data->order_status == 'paid')
                    <th>عملیات</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($data->allItems as $key => $row)
                @php
                    $imageUrl = @$row->product_variant_id
                        ? (\App\Modules\Product\Services\VariantService::getProductImagesSizeSeperated(@$row->product_variant->images)[0]['image_medium'] ?? @$row->product->getImage())
                        : @$row->product->getImage();
                @endphp
                <tr>
                    <td class="text-start">
                        <span class="admin-order-items__index font-num-r">{{ $key + 1 }}</span>
                    </td>
                    <td class="text-start">
                        <a href="{{ \App\Library\SiteUrl::product(@$row->product) }}" target="_blank" class="admin-order-items__product-link">
                            {{ @$row->product->title }}
                        </a>
                        <div class="admin-order-items__specs">
                            @foreach(@$row->product_variant->specifications ?? [] as $specification)
                                <span class="admin-order-items__spec">
                                    @if(@$specification->parent->is_color)
                                        <i class="admin-order-items__color" style="background: {{ @$specification->color_code ?? '#fff' }}"></i>
                                    @endif
                                    {{ @$specification->parent->title }}: {{ @$specification->title }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <img src="{{ $imageUrl }}" alt="تصویر محصول" class="admin-order-items__thumb">
                    </td>
                    <td>
                        <div class="fw-semibold text-primary font-num-r">
                            {{ number_format(intval($row->discounted_price) ?: intval($row->price)) }} تومان
                        </div>
                        @if($row->discounted_price != 0 && $row->discounted_price != $row->price)
                            <del class="text-muted small font-num-r">{{ number_format(intval($row->price)) }} تومان</del>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-label-info font-num-r">{{ @$row->quantity }}</span>
                        @if(@$row->old_quantity !== null && @$row->old_quantity != @$row->quantity)
                            <span class="d-block mt-1">
                                <del class="text-danger small font-num-r">{{ @$row->old_quantity }}</del>
                            </span>
                        @endif
                    </td>
                    @if($data->order_status == 'paid')
                        <td>
                            @if(@$row->quantity > 0)
                                <button class="btn btn-sm btn-outline-warning rounded-custom"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $row->id }}">
                                    <i class="bi bi-arrow-return-left"></i>
                                    مرجوع جزئی
                                </button>

                                <div class="collapse mt-2" id="collapse-{{ $row->id }}">
                                    <div class="admin-order-items__return-form">
                                        <form action="{{ route('admin.order.return', ['id' => $row->id]) }}" method="POST">
                                            @csrf
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <x-cms-input
                                                        name="quantity"
                                                        label="تعداد مرجوعی"
                                                        :validations="['numberCms','requiredCms', 'max:' . $row->quantity]"
                                                        type="number"
                                                        :valueData="$row"
                                                    />
                                                </div>
                                                <div class="col-12 text-center">
                                                    <button type="submit" class="btn btn-primary btn-sm w-100 rounded-custom"
                                                            onclick="return confirm('آیا مطمئن هستید؟')">
                                                        ثبت مرجوعی
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <span class="badge bg-label-secondary">مرجوع شده</span>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
