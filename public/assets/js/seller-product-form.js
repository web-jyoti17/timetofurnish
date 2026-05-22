
(function ($) {
    "use strict";
    $(document).ready(function () {
        const treeviewAvailable = $.fn && $.fn.hummingbird;

        if (!treeviewAvailable) {
            console.warn(
                'hummingbird-treeview plugin is not available. Check script load order: jQuery must load before hummingbird-treeview.js, and avoid including jQuery twice.'
            );
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
        if (treeviewAvailable && $("#treeview").length) {
            $("#treeview").hummingbird();
        }
        // Restore categories
        var selected_ids = $('input[name="old_categories_string"]').val() || '';
        if (selected_ids != '') {
            const myArray = selected_ids.split(",");
            myArray.forEach(element => {
                if (treeviewAvailable) {
                    $('#treeview input:checkbox#' + element).prop('checked', true);
                    $('#treeview input:checkbox#' + element).parents("ul").css("display", "block");
                    $('#treeview input:checkbox#' + element).parents("li").children('.las')
                        .removeClass("la-plus").addClass('la-minus');
                }
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
                            let sizeAttributeId = String($('#choice_attributes').data('size-attribute-id') || '');
                            if (sizeAttributeId && String(attribute.id) === sizeAttributeId) {
                                isSelected = true;
                            }
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
                        $('#choice_attributes').prop('disabled', false);
                        ensureRequiredSizeAttribute();
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
            ensureRequiredSizeAttribute();
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
                let sizeAttributeId = String($('#choice_attributes').data('size-attribute-id') || '');
                if (sizeAttributeId && String(val) === sizeAttributeId) {
                    isSelected = true;
                }
                $("#choice_attributes option:selected").each(function () {
                    if ($(this).val() == val) isSelected = true;
                });
                if (!isSelected) {
                    $(this).closest('.form-group.row').remove();
                }
            });
            update_sku();
        });

        bindSellerAttributeBuilder(formData);

        // Colors active toggle
        $('input[name="colors_active"]').on('change', function () {
            $('#colors').prop('disabled', !$(this).is(':checked'));
            AIZ.plugins.bootstrapSelect('refresh');
            update_sku();
        });

        $(document).on("change", ".attribute_choice, #colors", function () {
            update_sku();
        });

        $(document).on('change', '.attribute_choice_toggle', function () {
            let attrId = this.id.replace('attribute_choice_active_', '');
            let sizeAttributeId = String($('#choice_attributes').data('size-attribute-id') || '');
            if (!$(this).is(':checked') && (!sizeAttributeId || String(attrId) !== sizeAttributeId)) {
                $('#choice_attributes option[value="' + attrId + '"]').prop('selected', false);
                $(this).closest('.form-group.row').remove();
                refreshProductSelects($('#choice_attributes'));
                update_sku();
                return;
            }

            let select = $('select[name="choice_options_' + attrId + '[]"]');
            select.prop('disabled', !$(this).is(':checked'));
            refreshProductSelects(select);
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

    function refreshProductSelects(target) {
        if (window.AIZ && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
            AIZ.plugins.bootstrapSelect('refresh');
        } else if ($.fn && $.fn.selectpicker) {
            (target ? $(target) : $('.aiz-selectpicker')).selectpicker('refresh');
        }
    }

    function selectedCategoryIds() {
        let categoryIds = [];

        $('input[name="category_ids[]"]:checked').each(function () {
            categoryIds.push($(this).val());
        });

        return categoryIds;
    }

    function sellerAttributeError(xhr, fallback) {
        if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) {
                let messages = [];
                $.each(xhr.responseJSON.errors, function (field, fieldMessages) {
                    messages.push($.isArray(fieldMessages) ? fieldMessages[0] : fieldMessages);
                });
                if (messages.length) return messages;
            }

            if (xhr.responseJSON.message) {
                return xhr.responseJSON.message;
            }
        }

        if (xhr.responseText) {
            return xhr.responseText.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim().slice(0, 220);
        }

        return fallback;
    }

    function addOrUpdateVariationAttribute(attribute, values) {
        if (!attribute || !attribute.id) return;

        let attributeSelect = $('#choice_attributes');
        let option = attributeSelect.find('option[value="' + attribute.id + '"]');

        if (!option.length) {
            attributeSelect.append($('<option></option>').val(attribute.id).text(attribute.name));
        }

        attributeSelect.prop('disabled', false);
        attributeSelect.find('option[value="' + attribute.id + '"]').prop('selected', true);
        $('#attributes_enable_toggle').prop('checked', true);
        $('#attributes-container').show();
        refreshProductSelects(attributeSelect);

        let hasRow = $('input[name="choice_no[]"][value="' + attribute.id + '"]').length > 0;

        if (!hasRow) {
            add_more_customer_choice_option(attribute.id, attribute.name, values || [], true);
            return;
        }

        let select = $('select[name="choice_options_' + attribute.id + '[]"]');
        (values || []).forEach(function (value) {
            let existing = select.find('option').filter(function () {
                return String($(this).val()).toLowerCase() === String(value).toLowerCase();
            });

            if (existing.length) {
                existing.prop('selected', true);
            } else {
                select.append($('<option></option>').val(value).text(value).prop('selected', true));
            }
        });

        refreshProductSelects(select);
        update_sku();
    }

    function ensureRequiredSizeAttribute() {
        let attributeSelect = $('#choice_attributes');
        let sizeAttributeId = String(attributeSelect.data('size-attribute-id') || '');
        if (!sizeAttributeId) return;

        let sizeOption = attributeSelect.find('option[value="' + sizeAttributeId + '"]');
        if (!sizeOption.length) {
            let sizeName = attributeSelect.data('size-attribute-name') || 'Size';
            attributeSelect.append($('<option></option>').val(sizeAttributeId).text(sizeName));
            sizeOption = attributeSelect.find('option[value="' + sizeAttributeId + '"]');
        }

        attributeSelect.prop('disabled', false);
        sizeOption.prop('selected', true);
        $('#attributes_enable_toggle').prop('checked', true);
        refreshProductSelects(attributeSelect);

        if (!$('input[name="choice_no[]"][value="' + sizeAttributeId + '"]').length) {
            add_more_customer_choice_option(sizeAttributeId, $.trim(sizeOption.text()), [], false);
        }
    }

    function sellerAttributeDraftTemplate(index) {
        return `
            <div class="seller-attribute-draft" data-index="${index}">
                <div class="form-group mb-3">
                    <label>Attribute Name</label>
                    <input type="text" class="form-control seller-attribute-name" placeholder="Example: Size">
                </div>
                <label>Values</label>
                <div class="seller-attribute-values">
                    ${sellerAttributeValueRow()}
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="button" class="btn btn-soft-primary btn-sm add-seller-attribute-value">
                        <i class="las la-plus"></i>
                        Add Value
                    </button>
                    <div>
                        <button type="button" class="btn btn-soft-danger btn-sm remove-seller-attribute-group">
                            <i class="las la-trash"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm save-seller-attribute">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function sellerAttributeValueRow() {
        return `
            <div class="seller-attribute-value-row">
                <input type="text" class="form-control seller-attribute-value" placeholder="Value">
                <button type="button" class="btn btn-soft-danger seller-attribute-icon-btn remove-seller-attribute-value">
                    <i class="las la-times"></i>
                </button>
            </div>
        `;
    }

    function bindSellerAttributeBuilder(formData) {
        let wrapper = $('#seller-attribute-groups');
        if (!wrapper.length) return;

        $('#add-seller-attribute-group').on('click', function () {
            let index = wrapper.find('.seller-attribute-draft').length;
            wrapper.append(sellerAttributeDraftTemplate(index));
            wrapper.find('.seller-attribute-draft').last().find('.seller-attribute-name').focus();
        });

        if (!wrapper.find('.seller-attribute-draft').length) {
            $('#add-seller-attribute-group').trigger('click');
        }

        wrapper.on('click', '.add-seller-attribute-value', function () {
            $(this).closest('.seller-attribute-draft').find('.seller-attribute-values').append(sellerAttributeValueRow());
        });

        wrapper.on('click', '.remove-seller-attribute-value', function () {
            let rows = $(this).closest('.seller-attribute-values').find('.seller-attribute-value-row');
            if (rows.length > 1) {
                $(this).closest('.seller-attribute-value-row').remove();
            } else {
                $(this).closest('.seller-attribute-value-row').find('.seller-attribute-value').val('');
            }
        });

        wrapper.on('click', '.remove-seller-attribute-group', function () {
            $(this).closest('.seller-attribute-draft').remove();
            if (!wrapper.find('.seller-attribute-draft').length) {
                $('#add-seller-attribute-group').trigger('click');
            }
        });

        wrapper.on('click', '.save-seller-attribute', function () {
            let button = $(this);
            let block = button.closest('.seller-attribute-draft');
            let name = $.trim(block.find('.seller-attribute-name').val());
            let values = [];

            block.find('.seller-attribute-value').each(function () {
                let value = $.trim($(this).val());
                if (value) values.push(value);
            });

            values = values.filter(function (value, index, list) {
                return list.map(function (item) {
                    return item.toLowerCase();
                }).indexOf(value.toLowerCase()) === index;
            });

            if (!name) {
                notifyProductForm('danger', 'Please enter an attribute name.');
                block.find('.seller-attribute-name').focus();
                return;
            }

            if (!values.length) {
                notifyProductForm('danger', 'Please add at least one attribute value.');
                block.find('.seller-attribute-value').first().focus();
                return;
            }

            button.prop('disabled', true).data('original-text', button.html()).html('Saving...');

            $.ajax({
                type: 'POST',
                url: formData.storeAttributeRoute,
                data: {
                    name: name,
                    values: values,
                    category_ids: selectedCategoryIds(),
                    _token: (typeof AIZ !== 'undefined' && AIZ.data && AIZ.data.csrf) ? AIZ.data.csrf : (formData.csrf || $('meta[name="csrf-token"]').attr('content'))
                },
                success: function (response) {
                    addOrUpdateVariationAttribute(response.attribute, response.values || values);
                    block.find('.seller-attribute-name').val('');
                    block.find('.seller-attribute-values').html(sellerAttributeValueRow());
                    notifyProductForm('success', response.message || 'Attribute saved.');
                },
                error: function (xhr) {
                    notifyProductForm('danger', sellerAttributeError(xhr, 'Unable to save attribute.'));
                },
                complete: function () {
                    button.prop('disabled', false).html(button.data('original-text'));
                }
            });
        });
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
    window.add_more_customer_choice_option = function (i, name, selectedValues, autoSelect) {
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
                let sizeAttributeId = String($('#choice_attributes').data('size-attribute-id') || '');
                let isSizeAttribute = sizeAttributeId && String(i) === sizeAttributeId;
                var selected = (selectedValues || []).map(function (value) {
                    return String(value).toLowerCase();
                });

                if (autoSelect && selected.length > 0) {
                    var optionHolder = $('<select multiple>' + obj + '</select>');
                    optionHolder.find('option').each(function () {
                        if (selected.includes(String($(this).val()).toLowerCase())) {
                            $(this).attr('selected', 'selected');
                        }
                    });
                    obj = optionHolder.html();
                } else if (isSizeAttribute) {
                    var sizeOptionHolder = $('<select multiple>' + obj + '</select>');
                    if (!sizeOptionHolder.find('option:selected').length) {
                        sizeOptionHolder.find('option').first().attr('selected', 'selected');
                    }
                    obj = sizeOptionHolder.html();
                }

                name = name.trim();
                $('#variant-table-prompt').remove();
                $('#customer_choice_options').append('\
                <div class="form-group row align-items-center mb-3">\
                    <div class="col-lg-3">\
                        <input type="hidden" name="choice_no[]" value="' + i + '">\
                        <input type="text" class="form-control-plaintext font-weight-bold" name="choice[]" value="' + name +
                    '" placeholder="Choice Title" readonly>\
                    </div>\
                    <div class="col-lg-8">\
                        <select class="form-control aiz-selectpicker attribute_choice rounded-pill" data-live-search="true" name="choice_options_' +
                    i +
                    '[]" multiple data-container="body">\                                ' +
                    obj + '\
                        </select>\
                    </div>\
                    <div class="col-lg-1 text-center">\
                        ' + (isSizeAttribute ? '<input type="hidden" name="attribute_choice_active_' + i + '" value="1">' : '') + '\
                        <div class="custom-control custom-switch">\
                            <input value="1" type="checkbox" class="custom-control-input attribute_choice_toggle" id="attribute_choice_active_' + i + '" name="attribute_choice_active_' + i + '" checked ' + (isSizeAttribute ? 'disabled' : '') + '>\
                            <label class="custom-control-label" for="attribute_choice_active_' + i + '"></label>\
                        </div>\
                    </div>\
                </div>');
                if (window.AIZ && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                    AIZ.plugins.bootstrapSelect('refresh');
                } else if ($.fn && $.fn.selectpicker) {
                    $('.aiz-selectpicker').selectpicker('refresh');
                }
                update_sku();
            },
            error: function (xhr) {
                let message = 'Unable to load attribute values.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                notifyProductForm('danger', message);
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
