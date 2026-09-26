<script>
// mobile
(function ($) {
    $("#price-range-submit-mobile").hide();
    $("#min_price_mobile,#max_price_mobile").on("change", function () {
        $("#price-range-submit-mobile").show();
        var min_price_range = parseInt($("#min_price_mobile").val());
        var max_price_range = parseInt($("#max_price_mobile").val());
        if (min_price_range > max_price_range) {
            $("#max_price_mobile").val(min_price_range);
        }
        $("#slider-range_mobile").slider({
            values: [min_price_range, max_price_range],
        });
    });
    $("#min_price_mobile,#max_price_mobile").on("paste keyup", function () {
        $("#price-range-submit_mobile").show();
        var min_price_range = parseInt($("#min_price_mobile").val());
        var max_price_range = parseInt($("#max_price_mobile").val());
        if (min_price_range == max_price_range) {
            max_price_range = min_price_range + 100;
            $("#min_price_mobile").val(min_price_range);
            $("#max_price_mobile").val(max_price_range);
        }
        $("#slider-range-mobile").slider({
            values: [min_price_range, max_price_range],
        });
    });
    $(function () {
        $("#slider-range-mobile").slider({
            range: true,
            orientation: "horizontal",
            min: {{$min_price}},
            max: {{$max_price}},
            values: [{{$min_filter_price}}, {{$max_filter_price}}],
            step: 100,
            slide: function (event, ui) {
                if (ui.values[0] == ui.values[1]) {
                    return false;
                }
                $("#min_price_mobile").val(ui.values[0]);
                $("#max_price_mobile").val(ui.values[1]);
            },
        });
        $("#min_price_mobile").val(
            $("#slider-range-mobile").slider("values", 0)
        );
        $("#max_price_mobile").val(
            $("#slider-range-mobile").slider("values", 1)
        );
    });
    $("#slider-range-mobile,#price-range-submit-mobile").click(function () {
        var min_price_mobile = $("#min_price_mobile").val();
        var max_price_mobile = $("#max_price_mobile").val();
        $("#searchResults").text(
            "Here List of products will be shown which are cost between " +
                min_price_mobile +
                " " +
                "and" +
                " " +
                max_price_mobile +
                "."
        );
    });
})(jQuery);

</script>
