
(function ($) {
    "use strict";
    $(document).ready(function () {
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
        $.each(inputNames, function (index, name) {
            $('input[name="' + name + '"]').on('input', function () {
                $(this).val(function (index, value) {
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
            let checked = $('input[name="category_ids[]"]:checked').map(function () {
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
            $('input[name="category_ids[]"]:checked').each(function () {
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
                    success: function (response) {
                        const currentSelected = ($('#choice_attributes').val() || []).map(String);
                        const oldSelected = (formData.choiceAttributesOld || []).map(String);
                        $('#choice_attributes').empty();
                        $.each(response, function (index, attribute) {
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

        $('input[name="category_ids[]"]').on('change', function () {
            updateAttributes();
            syncMainCategoryId($(this).val());
            let categoryIds = [];
            $('input[name="category_ids[]"]:checked').each(function () {
                categoryIds.push($(this).val());
            });
        });

        // Initialize attributes on load
        updateAttributes();

        // Attributes change
        $('#choice_attributes').on('change', function () {
            $.each($("#choice_attributes option:selected"), function (j, attribute) {
                let flag = false;
                $('input[name="choice_no[]"]').each(function (i, choice_no) {
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
            $('input[name="choice_no[]"]').each(function () {
                let val = $(this).val();
                let isSelected = false;
                $("#choice_attributes option:selected").each(function () {
                    if ($(this).val() == val) isSelected = true;
                });
                if (!isSelected) {
                    $(this).closest('.form-group.row').remove();
                }
            });
            update_sku();
        });

        // Colors active toggle
        $('input[name="colors_active"]').on('change', function () {
            $('#colors').prop('disabled', !$(this).is(':checked'));
            AIZ.plugins.bootstrapSelect('refresh');
            update_sku();
        });

        $(document).on("change", ".attribute_choice, #colors", function () {
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
            }).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY HH:mm') + ' to ' + picker
                    .endDate.format('DD-MM-YYYY HH:mm'));
            }).on('cancel.daterangepicker', function () {
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
        $(document).on('keyup', '#category-search', function () {
            var value = $(this).val().toLowerCase();
            $("#treeview li").filter(function () {
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
        window.addEventListener('scroll', function () {
            const header = document.querySelector('.sticky-action-container');
            if (header) {
                if (window.pageYOffset > 50) {
                    header.classList.add('stuck');
                } else {
                    header.classList.remove('stuck');
                }
            }
        });

        bindProductAjaxSubmit();
        bindProductGlobalAjaxErrors();
    });

    function clearProductFormErrors() {
        $('#product-form-alert').addClass('d-none').removeClass('alert-danger alert-success').html('');
        $('.product-form-field-error').remove();
        $('.is-invalid-field').removeClass('is-invalid-field');
    }

    function fieldSelector(name) {
        return '[name="' + name.replace(/"/g, '\\"') + '"], [name="' + name.replace(/"/g, '\\"') + '[]"]';
    }

    function showProductFormAlert(type, messages) {
        let html = '';
        if ($.isArray(messages)) {
            html = '<ul class="mb-0 pl-3">' + messages.map(function (message) {
                return '<li>' + message + '</li>';
            }).join('') + '</ul>';
        } else {
            html = messages;
        }

        $('#product-form-alert')
            .removeClass('d-none alert-danger alert-success')
            .addClass('alert-' + type)
            .html(html);
    }

    function notifyProductForm(type, messages) {
        let list = $.isArray(messages) ? messages : [messages];

        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
            $.each(list, function (index, message) {
                if (message) {
                    AIZ.plugins.notify(type, message);
                }
            });
            return;
        }

        if (type === 'danger') {
            console.error(list.join('\n'));
        } else {
            console.log(list.join('\n'));
        }
    }

    function addFieldError(field, message) {
        let normalized = field.replace(/\.\d+/g, '[]').replace(/\./g, '[').replace(/\[/g, '[').replace(/\]/g, ']');
        let input = $(fieldSelector(field)).first();

        if (!input.length) {
            normalized = field.replace(/\.(\d+)\./g, '[$1][').replace(/\./g, '][') + (field.indexOf('.') > -1 ? ']' : '');
            input = $(fieldSelector(normalized)).first();
        }

        if (!input.length && field === 'category_ids') {
            input = $('input[name="category_ids[]"]').first();
        }

        if (!input.length) return;

        let error = $('<span class="product-form-field-error"></span>').text(message);
        let wrapper = input.closest('.form-group, .input-group, .bootstrap-select, .aiz-file-box-wrap');

        input.addClass('is-invalid-field');
        if (input.hasClass('aiz-selectpicker')) {
            input.closest('.bootstrap-select').addClass('is-invalid-field');
        }

        if (wrapper.length) {
            wrapper.after(error);
        } else {
            input.after(error);
        }
    }

    function bindProductAjaxSubmit() {
        $('#choice_form[data-ajax-submit="true"]').on('submit', function (e) {
            e.preventDefault();

            let form = $(this);
            let submitButton = $(document.activeElement).is('[type="submit"]')
                ? $(document.activeElement)
                : form.find('[type="submit"]').first();
            let formData = new FormData(this);

            if (submitButton.attr('name')) {
                formData.set(submitButton.attr('name'), submitButton.val());
            }

            clearProductFormErrors();
            submitButton.prop('disabled', true).data('original-text', submitButton.html());
            submitButton.html('Saving...');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method') || 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    let message = response.message || 'Product saved successfully.';
                    showProductFormAlert('success', message);
                    notifyProductForm('success', message);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                },
                error: function (xhr) {
                    xhr.sellerToastHandled = true;
                    if (xhr.status === 422) {
                        let response = xhr.responseJSON || {};
                        let errors = response.errors || {};
                        let messages = [];

                        $.each(errors, function (field, fieldMessages) {
                            let firstMessage = $.isArray(fieldMessages) ? fieldMessages[0] : fieldMessages;
                            messages.push(firstMessage);
                            addFieldError(field, firstMessage);
                        });

                        if (!messages.length && response.message) {
                            messages = $.isArray(response.message) ? response.message : [response.message];
                        }

                        showProductFormAlert('danger', messages.length ? messages : ['Please check the highlighted fields.']);
                        notifyProductForm('danger', messages.length ? messages : ['Please check the highlighted fields.']);
                    } else {
                        let message = 'Something went wrong while saving the product. Please check the form and try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showProductFormAlert('danger', message);
                        notifyProductForm('danger', message);
                    }

                    $('html, body').animate({
                        scrollTop: $('#product-form-alert').offset().top - 90
                    }, 250);
                },
                complete: function () {
                    submitButton.prop('disabled', false);
                    submitButton.html(submitButton.data('original-text'));
                }
            });
        });
    }

    function bindProductGlobalAjaxErrors() {
        $(document).ajaxError(function (event, xhr, settings) {
            if (!$('#choice_form').length || !settings || !settings.url) return;
            if (settings.url === $('#choice_form').attr('action')) return;
            if (xhr.status === 0 || xhr.status === 422) return;
            xhr.sellerToastHandled = true;

            let message = 'Something went wrong. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            notifyProductForm('danger', message);
        });
    }

    // Global functions
    window.add_more_customer_choice_option = function (i, name) {
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
            success: function (data) {
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
    window.update_sku = function () {
        const formData = $('#product-form-data').data();
        $.ajax({
            type: "POST",
            url: formData.skuCombinationRoute,
            data: $('#choice_form').serialize(),
            success: function (data) {
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
    window.delete_row = function (em) {
        $(em).closest('.form-group').remove();
        update_sku();
    };
    window.delete_variant = function (em) {
        $(em).closest('.variant').remove();
    };
    window.toggleDiscount = function () {
        let boxes = $('.discount-box');
        let discountInput = $('#discountInput');
        let dateRange = $('#date_range');
        let enabled = $('#discountToggleBtn').is(':checked');
        if (enabled) {
            dateRange.prop('disabled', false);
            discountInput.prop('disabled', false);
            boxes.show();
        } else {
            dateRange.prop('disabled', true);
            discountInput.prop('disabled', true);
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
        success: function (response) {

            $('#checkout-services-wrapper').html(response);

        },
        error: function (xhr) {

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

function loadShippingCharges(categoryIds = []) {

    let route = $('#product-form-data').data('shipping-charges-route');

    if (!route) {
        return;
    }

    $.ajax({
        url: route,
        type: "GET",
        data: {
            category_ids: categoryIds
        },
        success: function (response) {

            $('#shipping-charges-wrapper').html(response);

        },
        error: function (xhr) {

            console.log(xhr.responseText);

            $('#shipping-charges-wrapper').html(`
                <div class="col-12">
                    <div class="alert alert-danger mb-0">
                        Unable to load shipping charges.
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
        loadShippingCharges(initialCategories);
    }

    // CATEGORY CHANGE
    $(document).on('change', '#treeview input[type="checkbox"]', function () {

        let categoryIds = getSelectedCategoryIds();

        loadCheckoutServices(categoryIds);
        loadShippingCharges(categoryIds);
    });

});
