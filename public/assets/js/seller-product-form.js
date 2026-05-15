
(function($) {
    "use strict";
    $(document).ready(function() {
        if (!$.fn || !$.fn.hummingbird) {
            console.warn(
                'hummingbird-treeview plugin is not available. Check script load order: jQuery must load before hummingbird-treeview.js, and avoid including jQuery twice.'
            );
            return;
        }
        const formData = $('#product-form-data').data();
        // Input formatting
        var inputNames = ['unit', 'weight', 'min_qty', 'unit_weight', 'low_stock_quantity',
            'unit_price', 'current_stock', 'product_length', 'product_breadth', 'product_height',
            'discount'
        ];
        $.each(inputNames, function(index, name) {
            $('input[name="' + name + '"]').on('input', function() {
                $(this).val(function(index, value) {
                    return value.replace(/[^0-9.]/g, '');
                });
            });
        });

        // Treeview initialization
        $("#treeview").hummingbird();
        // Restore categories
        var selected_ids = $('input[name="old_categories_string"]').val() || '';
        if (selected_ids != '') {
            const myArray = selected_ids.split(",");
            myArray.forEach(element => {
                $('#treeview input:checkbox#' + element).prop('checked', true);
                $('#treeview input:checkbox#' + element).parents("ul").css("display", "block");
                $('#treeview input:checkbox#' + element).parents("li").children('.las')
                    .removeClass("la-plus").addClass('la-minus');
            });
        }
        var main_id = $('input[name="main_category_id"]').val() || 0;

        function syncMainCategoryId(preferredId = null) {
            let checked = $('input[name="category_ids[]"]:checked').map(function() {
                return $(this).val();
            }).get();

            let current = $('#main_category_id').val();

            let next = null;
            if (preferredId != null && checked.includes(String(preferredId))) {
                next = String(preferredId);
            } else if (current && checked.includes(String(current))) {
                next = String(current);
            } else if (checked.length > 0) {
                next = String(checked[0]);
            }

            if (next) {
                $('#main_category_id').val(next);
                $('input[name="main_category_id"]').val(next);
            } else {
                $('#main_category_id').val('');
                $('input[name="main_category_id"]').val('');
            }
        }

        // On load: keep existing main category if still selected, else pick first checked
        syncMainCategoryId(main_id);
        update_sku();
        let initialCategoryIds = [];

$('input[name="category_ids[]"]:checked').each(function () {

    initialCategoryIds.push($(this).val());

});



        // UPDATE ATTRIBUTES
        function updateAttributes() {
            var categoryIds = [];
            $('input[name="category_ids[]"]:checked').each(function() {
                categoryIds.push($(this).val());
            });
            if (categoryIds.length > 0) {
                $.ajax({
                    type: 'POST',
                    url: formData.getAttributesRoute,
                    data: {
                        category_ids: categoryIds,
                        _token: (typeof AIZ !== 'undefined' && AIZ.data && AIZ.data.csrf) ? AIZ.data.csrf : (formData.csrf || $('meta[name="csrf-token"]').attr('content'))
                    },
                    success: function(response) {
                        const currentSelected = $('#choice_attributes').val() || [];
                        const oldSelected = formData.choiceAttributesOld || [];
                        $('#choice_attributes').empty();
                        $.each(response, function(index, attribute) {
                            let isSelected = currentSelected.includes(attribute.id
                                .toString()) || oldSelected.includes(attribute
                                .id.toString());
                            let selectedAttr = isSelected ? 'selected' : '';
                            $('#choice_attributes').append(
                                `<option value="${attribute.id}" ${selectedAttr}>${attribute.name}</option>`
                            );
                        });
                        if ($.fn && $.fn.selectpicker) {
                            $('#choice_attributes').selectpicker('refresh');
                        } else if (window.AIZ && AIZ.plugins && AIZ.plugins
                            .bootstrapSelect) {
                            AIZ.plugins.bootstrapSelect('refresh');
                        }
                    }
                });
            }
        }

        $('input[name="category_ids[]"]').on('change', function() {
            updateAttributes();
            syncMainCategoryId($(this).val());
            let categoryIds = [];
            $('input[name="category_ids[]"]:checked').each(function() {
                categoryIds.push($(this).val());
            });
        });

        // Initialize attributes on load
        updateAttributes();

        // Attributes change
        $('#choice_attributes').on('change', function() {
            $.each($("#choice_attributes option:selected"), function(j, attribute) {
                let flag = false;
                $('input[name="choice_no[]"]').each(function(i, choice_no) {
                    if ($(attribute).val() == $(choice_no).val()) {
                        flag = true;
                    }
                });
                if (!flag) {
                    add_more_customer_choice_option($(attribute).val(), $(attribute)
                        .text());
                }
            });
            // Remove unselected
            $('input[name="choice_no[]"]').each(function() {
                let val = $(this).val();
                let isSelected = false;
                $("#choice_attributes option:selected").each(function() {
                    if ($(this).val() == val) isSelected = true;
                });
                if (!isSelected) {
                    $(this).closest('.form-group.row').remove();
                }
            });
            update_sku();
        });

        // Colors active toggle
        $('input[name="colors_active"]').on('change', function() {
            $('#colors').prop('disabled', !$(this).is(':checked'));
            AIZ.plugins.bootstrapSelect('refresh');
            update_sku();
        });

        $(document).on("change", ".attribute_choice, #colors", function() {
            update_sku();
        });

        // Date Range Picker
        if ($('.aiz-date-range').length) {
            $('.aiz-date-range').daterangepicker({
                autoUpdateInput: false,
                minDate: new Date(),
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    format: 'DD-MM-YYYY HH:mm'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY HH:mm') + ' to ' + picker
                    .endDate.format('DD-MM-YYYY HH:mm'));
            }).on('cancel.daterangepicker', function() {
                $(this).val('');
            });
        }

        // Restore discount UI state after validation errors
        if (typeof window.toggleDiscount === 'function') {
            window.toggleDiscount();
        }
        // Load Addons
        // let oldAddons = formData.oldAddons;
        // let existingAddons = formData.existingAddons;
        // let loadData = oldAddons.length > 0 ? oldAddons : existingAddons;
        // if (loadData && loadData.length > 0) {
        //     loadData.forEach(addon => {
        //         addAddon(addon, false); // false = append for initial load
        //     });
        // }
        // Category Search Implementation
        $(document).on('keyup', '#category-search', function() {
            var value = $(this).val().toLowerCase();
            $("#treeview li").filter(function() {
                var text = $(this).text().toLowerCase();
                var match = text.indexOf(value) > -1;
                $(this).toggle(match);
                if (match && value.length > 0) {
                    $(this).parents('li').show();
                    $(this).parents('ul').show();
                }
            });
        });

        // Sticky Header Scroll Logic
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.sticky-action-container');
            if (header) {
                if (window.pageYOffset > 50) {
                    header.classList.add('stuck');
                } else {
                    header.classList.remove('stuck');
                }
            }
        });

        // ADDON COLLAPSE
        $(document).on('click', '.addon-header-clickable', function(e) {
            if ($(e.target).is('input, button, i')) return;
            let block = $(this).closest('.addon-block');
            let body = block.find('.addon-body');
            body.slideToggle(200);
        });

        // SELECT ALL OPTIONS
        $(document).on('click', '.select-all-options', function() {
            let block = $(this).closest('.addon-block');
            let options = block.find('.option-toggle');
            let allChecked =
                options.length === options.filter(':checked').length;
            options.prop('checked', !allChecked);
            $(this).text(allChecked ? 'Select All' : 'Deselect All');
        });

        // OPTION TOGGLE
        $(document).on('change', '.option-toggle', function() {
            let row = $(this).closest('.addon-option-row');
            let checked = $(this).is(':checked');
            row.toggleClass('opacity-50', !checked);
            row.find('.option-input').prop('disabled', !checked);
        });

    });

    // Global functions
    window.add_more_customer_choice_option = function(i, name) {
        const formData = $('#product-form-data').data();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': (typeof AIZ !== 'undefined' && AIZ.data && AIZ.data.csrf) ? AIZ.data.csrf : (formData.csrf || $('meta[name="csrf-token"]').attr('content'))
            },
            type: "POST",
            url: formData.addMoreChoiceRoute,
            data: {
                attribute_id: i
            },
            success: function(data) {
                var obj = JSON.parse(data);
                name = name.trim();
                $('#customer_choice_options').append('\
                <div class="form-group row">\
                    <div class="col-md-3">\
                        <input type="hidden" name="choice_no[]" value="' + i + '">\
                        <input type="text" class="form-control" name="choice[]" value="' + name +
                    '" placeholder="Choice Title" readonly>\
                    </div>\
                    <div class="col-md-8">\
                        <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_' +
                    i +
                    '[]" multiple data-container="body">\                                ' +
                    obj + '\
                        </select>\
                    </div>\
                </div>');
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });
    };
    window.update_sku = function() {
        const formData = $('#product-form-data').data();
        $.ajax({
            type: "POST",
            url: formData.skuCombinationRoute,
            data: $('#choice_form').serialize(),
            success: function(data) {
                $('#sku_combination').html(data);
                AIZ.uploader.previewGenerate();
                AIZ.plugins.fooTable();
                if ($('#sku_combination').find('.variant').length > 0) {
                    $('#show-hide-div').hide();
                } else {
                    $('#show-hide-div').show();
                }
            }
        });
    };
    window.delete_row = function(em) {
        $(em).closest('.form-group').remove();
        update_sku();
    };
    window.delete_variant = function(em) {
        $(em).closest('.variant').remove();
    };
    window.toggleDiscount = function() {
        let boxes = $('.discount-box');
        let discountInput = $('#discountInput');
        let dateRange = $('#date_range');
        let enabled = $('#discountToggleBtn').is(':checked');
        if (enabled) {
            dateRange.prop('disabled', false);
            boxes.show();
        } else {
            dateRange.prop('disabled', true);
            boxes.hide();
        }
    };

})(jQuery);


function loadCheckoutServices(categoryIds = []) {

    let route = $('#product-form-data').data('checkout-services-route');

    $.ajax({
        url: route,
        type: "GET",
        data: {
            category_ids: categoryIds
        },
        success: function(response) {

            $('#checkout-services-wrapper').html(response);

        },
        error: function(xhr) {

            console.log(xhr.responseText);

            $('#checkout-services-wrapper').html(`
                <div class="col-12">
                    <div class="alert alert-danger mb-0">
                        Unable to load services.
                    </div>
                </div>
            `);
        }
    });
}

function getSelectedCategoryIds() {

    let selected = [];

    $('#treeview input[type="checkbox"]:checked').each(function () {

        let val = $(this).val();

        if (val) {
            selected.push(val);
        }
    });

    return selected;
}

$(document).ready(function () {

    // CREATE + EDIT PAGE AUTO LOAD
    let initialCategories = getSelectedCategoryIds();

    if (initialCategories.length > 0) {

        loadCheckoutServices(initialCategories);
    }

    // CATEGORY CHANGE
    $(document).on('change', '#treeview input[type="checkbox"]', function () {

        let categoryIds = getSelectedCategoryIds();

        loadCheckoutServices(categoryIds);
    });

});
