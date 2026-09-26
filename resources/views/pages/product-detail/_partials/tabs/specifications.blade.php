<div class="pdp-specs">
    <h3 class="pdp-tabs__heading">مشخصات {{ @$product['title'] }}</h3>
    <div class="pdp-specs__table-wrap">
        <table class="table pdp-specs__table m-0">
            <tbody>
                @foreach($specifications as $specification)
                    <tr>
                        <th scope="row">{{ @$specification[0]->parent->title }}</th>
                        <td>
                            @foreach($specification as $row)
                                {{ $row['title'] }}@if(!$loop->last)، @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                @foreach($specification_values as $specification_value)
                    <tr>
                        <th scope="row">{{ $specification_value[0]['specification'] }}</th>
                        <td>
                            @foreach($specification_value as $row2)
                                <p class="mb-0">{{ $row2['value'] }}</p>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                <tr class="pdp-specs__tags-row tags-tr">
                    <th colspan="2">@include('pages.product-detail._partials.tags')</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
