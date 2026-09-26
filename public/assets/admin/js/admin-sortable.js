(function ($) {
    'use strict';

    window.AdminSortable = {
        init: function (selector, options) {
            options = options || {};
            var $list = $(selector);
            if (!$list.length) {
                return;
            }

            if (typeof $.fn.sortable !== 'function') {
                console.error('AdminSortable: jQuery UI sortable is not loaded.');
                return;
            }

            if ($list.data('ui-sortable')) {
                $list.sortable('destroy');
            }

            var url = options.url || $list.data('sort-url');
            var csrf = options.csrf || $('meta[name="csrf-token"]').attr('content');

            if (csrf) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    }
                });
            }

            $list.sortable({
                items: '> li.admin-sort-item',
                cancel: 'a, input, button, select, textarea',
                cursor: 'grabbing',
                placeholder: 'admin-sort-placeholder',
                forcePlaceholderSize: true,
                tolerance: 'pointer',
                axis: 'y',
                start: function (event, ui) {
                    ui.item.addClass('is-dragging');
                    ui.placeholder.height(ui.item.outerHeight());
                },
                stop: function (event, ui) {
                    ui.item.removeClass('is-dragging');
                },
                update: function () {
                    var sortedIDs = $list.sortable('toArray', { attribute: 'data-id' });

                    $list.children('.admin-sort-item').each(function (index) {
                        $(this).find('.admin-sort-order').text(index + 1);
                    });

                    $.ajax({
                        url: url,
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ order: sortedIDs }),
                        success: function () {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    text: 'ترتیب با موفقیت ذخیره شد',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        },
                        error: function () {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'error',
                                    text: 'خطا در ذخیره ترتیب. دوباره تلاش کنید.',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 4000
                                });
                            }
                        }
                    });
                }
            });
        }
    };
})(jQuery);
