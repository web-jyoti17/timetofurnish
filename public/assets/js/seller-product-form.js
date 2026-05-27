
(function ($) {
    "use strict";
    const sellerAttributeNameOverrides = {};

    $(document).ready(function () {
        const treeviewAvailable = $.fn && $.fn.hummingbird;

        if (!treeviewAvailable) {
            console.warn(
                'hummingbird-treeview plugin is not available. Check script load order: jQuery must load before hummingbird-treeview.js, and avoid including jQuery twice.'
            );
        }
        const formData = $('#product-form-data').data();
        $('input[name="choice_no[]"]').each(function () {
            let attributeId = $(this).val();
            let row = $(this).closest('.form-group.row');
            let name = $.trim(row.find('input[name="choice[]"]').first().val() || '');

            if (attributeId && name) {
                sellerAttributeNameOverrides[attributeId] = name;
            }
        });
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
        var main_id = $('#main_category_id').val() || 0;

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
            } else {
                $('#main_category_id').val('');
            }
        }

        // On load: keep existing main category if still selected, else pick first checked
        syncMainCategoryId(main_id);
        if (!$.trim($('#sku_combination').html())) {
            update_sku();
        } else if ($('#sku_combination').find('.variant').length > 0) {
            $('#show-hide-div').hide();
        }
        let initialCategoryIds = [];

        $('input[name="category_ids[]"]:checked').each(function () {

            initialCategoryIds.push($(this).val());

        });



        // UPDATE ATTRIBUTES
        function updateAttributes() {
            // Snapshot any user-typed prices BEFORE we rebuild the attribute rows.
            // This ensures prices survive the update_sku() re-render triggered below.
            captureFullVariantSnapshot();

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
                        const newCatAttrIds = response.map(attr => String(attr.id));

                        // 1. Loop through all existing options and set their selected status
                        $('#choice_attributes option').each(function () {
                            const value = String($(this).val());
                            if (value) {
                                if (newCatAttrIds.includes(value)) {
                                    $(this).prop('selected', true);
                                } else if (currentSelected.includes(value) || oldSelected.includes(value)) {
                                    $(this).prop('selected', true);
                                } else {
                                    $(this).prop('selected', false);
                                }
                            }
                        });

                        // 2. Append any category attributes that are NOT currently in the select options
                        $.each(response, function (index, attribute) {
                            const id = String(attribute.id);
                            if (!$('#choice_attributes option[value="' + id + '"]').length) {
                                let attributeName = sellerAttributeNameOverrides[attribute.id] || attribute.name;
                                $('#choice_attributes').append(
                                    $('<option></option>').val(id).text(attributeName).prop('selected', true)
                                );
                            }
                        });

                        if ($.fn && $.fn.selectpicker) {
                            $('#choice_attributes').selectpicker('refresh');
                        } else if (window.AIZ && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                            AIZ.plugins.bootstrapSelect('refresh');
                        }
                        $('#choice_attributes').prop('disabled', false);

                        // Snapshot again just before triggering change so prices
                        // entered between the two async calls are also captured.
                        captureFullVariantSnapshot();

                        // Trigger change on attributes select to automatically generate option selectors for the auto-selected category attributes
                        $('#choice_attributes').trigger('change');
                    }
                });
            } else {
                let attributeSelect = $('#choice_attributes');
                const currentSelected = (attributeSelect.val() || []).map(String);
                const oldSelected = (formData.choiceAttributesOld || []).map(String);

                // Deselect auto-selected attributes, preserving manual selections
                attributeSelect.find('option').each(function () {
                    const value = String($(this).val());
                    if (value) {
                        if (currentSelected.includes(value) || oldSelected.includes(value)) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    }
                });

                attributeSelect.prop('disabled', false);
                if ($.fn && $.fn.selectpicker) {
                    $('#choice_attributes').selectpicker('refresh');
                } else if (window.AIZ && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                    AIZ.plugins.bootstrapSelect('refresh');
                }
                
                $('#choice_attributes').trigger('change');
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

        // Initialize attributes on load (only if not in Edit mode to preserve db-prefilled ones)
        if (!formData.productId) {
            updateAttributes();
        }

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

        // bindSellerAttributeBuilder(formData);

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
            if (!$(this).is(':checked')) {
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

        bindProductAjaxSubmit();
        bindProductGlobalAjaxErrors();
        renderAllEditableAttributeValues();

        // Category selection state only controls the inline notice. All form sections stay visible.
        function updateSectionsVisibility() {
            var categoryIds = [];
            $('input[name="category_ids[]"]:checked').each(function () {
                categoryIds.push($(this).val());
            });
            var form = $('#choice_form');
            
            if (categoryIds.length > 0) {
                form.removeClass('seller-category-pending').addClass('seller-category-ready');
            } else {
                form.removeClass('seller-category-ready').addClass('seller-category-pending');
            }
        }

        // Trigger sections visibility check on any category checkbox change
        $(document).on('change', 'input[name="category_ids[]"]', function () {
            updateSectionsVisibility();
        });

        // Initialize visibility on load
        updateSectionsVisibility();

        function selectpickerInstanceFromSearch(searchInput) {
            let menu = searchInput.closest('.dropdown-menu');
            let instance = menu.data('this') || menu.data('selectpicker');

            if (!instance) {
                let picker = searchInput.closest('.bootstrap-select');
                instance = picker.data('this') || picker.data('selectpicker');
            }

            if (!instance) {
                let select = searchInput.closest('.bootstrap-select').children('select');
                if (!select.length) {
                    select = searchInput.closest('.bootstrap-select').prev('select');
                }
                if (select.length) {
                    instance = select.data('this') || select.data('selectpicker') || {
                        $element: select,
                        $menu: searchInput.closest('.dropdown-menu')
                    };
                }
            }

            if (!instance) {
                let openPicker = $('.bootstrap-select.show').first();
                let select = openPicker.children('select');
                if (!select.length) {
                    select = openPicker.prev('select');
                }
                if (select.length) {
                    instance = select.data('this') || select.data('selectpicker') || {
                        $element: select,
                        $menu: menu
                    };
                }
            }

            return instance || null;
        }

        function closeSelectpicker(select) {
            if ($.fn && $.fn.selectpicker && select && select.length) {
                select.selectpicker('toggle');
            }
        }

        function addSearchedAttribute(select, query) {
            $.ajax({
                type: 'POST',
                url: formData.storeAttributeRoute,
                data: {
                    name: query,
                    values: ['Default'],
                    category_ids: selectedCategoryIds(),
                    _token: (typeof AIZ !== 'undefined' && AIZ.data && AIZ.data.csrf) ? AIZ.data.csrf : (formData.csrf || $('meta[name="csrf-token"]').attr('content'))
                },
                success: function (response) {
                    var newAttr = response.attribute;
                    var optionExists = select.find('option[value="' + newAttr.id + '"]').length > 0;
                    if (!optionExists) {
                        select.append($('<option></option>').val(newAttr.id).text(newAttr.name).prop('selected', true));
                    } else {
                        select.find('option[value="' + newAttr.id + '"]').prop('selected', true);
                    }

                    select.prop('disabled', false);
                    refreshProductSelects(select);
                    select.trigger('change');
                    closeSelectpicker(select);
                    notifyProductForm('success', 'Attribute "' + query + '" added successfully.');
                },
                error: function (xhr) {
                    notifyProductForm('danger', sellerAttributeError(xhr, 'Unable to add custom attribute.'));
                }
            });
        }

        function addSearchedAttributeValue(select, query) {
            var choiceRow = select.closest('.form-group.row');
            var attributeId = choiceRow.find('input[name="choice_no[]"]').first().val();
            var attributeName = $.trim(choiceRow.find('input[name="choice[]"]').val());
            var previousValues = (select.val() || []).map(function (value) {
                return String(value);
            });

            if (!attributeId || !attributeName) {
                notifyProductForm('danger', 'Please choose an attribute before adding values.');
                return;
            }

            $.ajax({
                type: 'POST',
                url: formData.storeAttributeRoute,
                data: {
                    attribute_id: attributeId,
                    name: attributeName,
                    values: [query],
                    category_ids: selectedCategoryIds(),
                    _token: (typeof AIZ !== 'undefined' && AIZ.data && AIZ.data.csrf) ? AIZ.data.csrf : (formData.csrf || $('meta[name="csrf-token"]').attr('content'))
                },
                success: function (response) {
                    var newValue = response.values[0] || query;
                    var finalValues = previousValues.slice();
                    if (!finalValues.includes(String(newValue))) {
                        finalValues.push(String(newValue));
                    }
                    var optionExists = select.find('option[value="' + newValue + '"]').length > 0;
                    if (!optionExists) {
                        select.append($('<option></option>').val(newValue).text(newValue));
                    } else {
                        select.find('option[value="' + newValue + '"]').text(newValue);
                    }

                    select.val(finalValues);
                    refreshProductSelects(select);
                    select.trigger('change');
                    renderEditableAttributeValues(choiceRow);
                    closeSelectpicker(select);
                    notifyProductForm('success', 'Value "' + query + '" added successfully.');
                    update_sku();
                },
                error: function (xhr) {
                    notifyProductForm('danger', sellerAttributeError(xhr, 'Unable to add custom value.'));
                }
            });
        }

        function injectSearchedOptionAction(searchInput) {
            let query = $.trim(searchInput.val());
            let instance = selectpickerInstanceFromSearch(searchInput);
            if (!instance || !instance.$element) return;

            let menu = instance.$menu || searchInput.closest('.dropdown-menu');
            menu.find('.injected-add-custom-option-wrap').remove();
            if (!query) return;

            let originalSelect = instance.$element;
            let selectName = originalSelect.attr('name') || '';
            let isAttributeSelect = originalSelect.attr('id') === 'choice_attributes';
            let isAttributeValueSelect = selectName.indexOf('choice_options_') === 0;

            if (!isAttributeSelect && !isAttributeValueSelect) return;

            let exists = false;
            originalSelect.find('option').each(function () {
                if ($.trim($(this).text()).toLowerCase() === query.toLowerCase()) {
                    exists = true;
                }
            });

            if (exists) return;

            let inner = menu.find('.inner').first();
            if (!inner.length) return;

            let label = isAttributeSelect
                ? 'Add new attribute "' + query + '"'
                : 'Add new option "' + query + '"';
            let actionWrap = $('<div class="injected-add-custom-option-wrap"></div>');
            let action = $('<button type="button" class="injected-add-custom-option"><i class="las la-plus-circle"></i><span></span></button>');
            action.find('span').text(label);
            actionWrap.append(action);
            inner.after(actionWrap);

            action.on('click', function (ev) {
                ev.preventDefault();
                ev.stopPropagation();

                if (isAttributeSelect) {
                    addSearchedAttribute(originalSelect, query);
                } else {
                    addSearchedAttributeValue(originalSelect, query);
                }
            });
        }

        // Search & Add Custom Option / Attribute within the Bootstrap Selectpicker Search Input
        $(document).on('input keyup', '.bootstrap-select .bs-searchbox input, .bs-container .bs-searchbox input', function () {
            let searchInput = $(this);
            [0, 40, 120].forEach(function (delay) {
                setTimeout(function () {
                    injectSearchedOptionAction(searchInput);
                }, delay);
            });
        });

        $(document).on('shown.bs.select refreshed.bs.select', '.aiz-selectpicker', function () {
            let instance = $(this).data('this') || $(this).data('selectpicker');
            let menu = instance && instance.$menu;
            let searchInput = menu ? menu.find('.bs-searchbox input') : $();
            if (searchInput.length && $.trim(searchInput.val())) {
                injectSearchedOptionAction(searchInput);
            }
        });
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
    window.notifyProductForm = notifyProductForm;

    function refreshProductSelects(target) {
        if (window.AIZ && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
            AIZ.plugins.bootstrapSelect('refresh');
        } else if ($.fn && $.fn.selectpicker) {
            (target ? $(target) : $('.aiz-selectpicker')).selectpicker('refresh');
        }
    }
    window.refreshProductSelects = refreshProductSelects;

    function productFormData() {
        return $('#product-form-data').data() || {};
    }

    let pendingVariantInputRestores = [];

    // Full snapshot keyed by the price input's name attribute (e.g. "price_Small")
    // This ensures prices are preserved across ANY update_sku() re-render.
    let fullVariantPriceSnapshot = {};

    function captureFullVariantSnapshot() {
        $('#sku_combination .variant').each(function () {
            let row = $(this);
            let priceInput = row.find('.var_price').first();
            let priceName = priceInput.attr('name'); // e.g. "price_Small"
            if (!priceName) return;

            let price = priceInput.val();
            let qty   = row.find('.var_qty').first().val();
            let sku   = row.find('input[name^="sku_"]').first().val();
            let img   = row.find('input[name^="img_"]').first().val();

            // Only snapshot if there is actually a price entered (avoid overwriting
            // DB-loaded prices with empty strings from a race-condition render)
            if (price && parseFloat(price) > 0) {
                fullVariantPriceSnapshot[priceName] = { price: price, qty: qty, sku: sku, img: img };
            } else if (qty || sku || img) {
                // Preserve sku/qty/img even when price is blank
                if (!fullVariantPriceSnapshot[priceName]) {
                    fullVariantPriceSnapshot[priceName] = { price: price, qty: qty, sku: sku, img: img };
                }
            }
        });
    }

    function restoreFromFullVariantSnapshot() {
        if (!Object.keys(fullVariantPriceSnapshot).length) return;

        $('#sku_combination .variant').each(function () {
            let row = $(this);
            let priceInput = row.find('.var_price').first();
            let priceName  = priceInput.attr('name');
            if (!priceName) return;

            let saved = fullVariantPriceSnapshot[priceName];
            if (!saved) return;

            // Restore price only when the rendered cell is blank
            // (DB-loaded values from sku_combinations_edit take precedence)
            if (saved.price && parseFloat(saved.price) > 0 && (!priceInput.val() || parseFloat(priceInput.val()) <= 0)) {
                priceInput.val(saved.price);
            }

            let qtyInput = row.find('.var_qty').first();
            if (saved.qty && (!qtyInput.val() || parseInt(qtyInput.val()) <= 0)) {
                qtyInput.val(saved.qty);
            }

            let skuInput = row.find('input[name^="sku_"]').first();
            if (saved.sku && !skuInput.val()) {
                skuInput.val(saved.sku);
            }

            let imgInput = row.find('input[name^="img_"]').first();
            if (saved.img && !imgInput.val()) {
                imgInput.val(saved.img);
            }
        });
    }

    function variantValuesFromRow(row) {
        let values = row.data('variant-values') || [];

        if (!Array.isArray(values)) {
            values = [values];
        }

        return values.map(function (value) {
            return String(value);
        });
    }

    function sameVariantValues(first, second) {
        if (first.length !== second.length) return false;

        for (let i = 0; i < first.length; i++) {
            if (String(first[i]) !== String(second[i])) {
                return false;
            }
        }

        return true;
    }

    function snapshotVariantInputValues(oldValue, newValue) {
        $('#sku_combination .variant').each(function () {
            let row = $(this);
            let values = variantValuesFromRow(row);

            if (!values.some(function (value) {
                return value === oldValue;
            })) {
                return;
            }

            pendingVariantInputRestores.push({
                values: values.map(function (value) {
                    return value === oldValue ? newValue : value;
                }),
                price: row.find('.var_price').first().val(),
                sku: row.find('input[name^="sku_"]').first().val(),
                qty: row.find('.var_qty').first().val(),
                img: row.find('input[name^="img_"]').first().val()
            });
        });
    }

    function restorePendingVariantInputValues() {
        // 1. Restore from the full snapshot (covers prices typed before update_sku re-render)
        restoreFromFullVariantSnapshot();

        // 2. Restore from pending renames (covers inline option-value edits)
        if (!pendingVariantInputRestores.length) return;

        pendingVariantInputRestores.forEach(function (saved) {
            $('#sku_combination .variant').each(function () {
                let row = $(this);

                if (!sameVariantValues(variantValuesFromRow(row), saved.values)) {
                    return;
                }

                row.find('.var_price').first().val(saved.price);
                row.find('input[name^="sku_"]').first().val(saved.sku);
                row.find('.var_qty').first().val(saved.qty);
                row.find('input[name^="img_"]').first().val(saved.img);
            });
        });

        pendingVariantInputRestores = [];
    }

    function selectedCategoryIds() {
        let categoryIds = [];

        $('input[name="category_ids[]"]:checked').each(function () {
            categoryIds.push($(this).val());
        });

        return categoryIds;
    }
    window.selectedCategoryIds = selectedCategoryIds;

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
    window.sellerAttributeError = sellerAttributeError;

    function addOrUpdateVariationAttribute(attribute, values) {
        if (!attribute || !attribute.id) return;

        let attributeSelect = $('#choice_attributes');
        let option = attributeSelect.find('option[value="' + attribute.id + '"]');

        if (!option.length) {
            attributeSelect.append($('<option></option>').val(attribute.id).text(attribute.name));
        } else {
            option.text(attribute.name);
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

    function escapeHtml(value) {
        return $('<div>').text(String(value)).html();
    }

    function selectedValueEditorHtml(value) {
        return '<div class="seller-selected-value-row">' +
            '<input type="text" class="form-control seller-selected-value-input" value="' + escapeHtml(value) + '" data-original-value="' + escapeHtml(value) + '" title="Double-click to edit this value">' +
            '</div>';
    }

    function queueVariantValueRename(row, oldValue, newValue) {
        let attributeId = row.find('input[name="choice_no[]"]').first().val();
        if (!attributeId) return;

        let payload = JSON.stringify({
            old: oldValue,
            new: newValue
        });

        $('#choice_form input[name="variant_value_renames[' + attributeId + '][]"]').filter(function () {
            try {
                let existing = JSON.parse($(this).val());
                return existing && String(existing.old) === String(oldValue);
            } catch (e) {
                return false;
            }
        }).remove();

        $('<input>', {
            type: 'hidden',
            name: 'variant_value_renames[' + attributeId + '][]',
            value: payload
        }).appendTo('#choice_form');
    }

    function renderEditableAttributeValues(row) {
        let select = row.find('.attribute_choice').first();
        let values = select.val() || [];
        let holder = row.find('.seller-selected-values-editor');

        if (!holder.length) {
            holder = $('<div class="seller-selected-values-editor"></div>');
            row.find('.seller-select-help').first().after(holder);
        }

        holder.empty();

        if (!values.length) {
            holder.addClass('d-none');
            return;
        }

        holder.removeClass('d-none');
        holder.append('<div class="seller-selected-values-title">Edit selected values</div>');

        values.forEach(function (value) {
            holder.append(selectedValueEditorHtml(value));
        });
    }

    function renderAllEditableAttributeValues() {
        $('#customer_choice_options .form-group.row').each(function () {
            renderEditableAttributeValues($(this));
        });
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

    $(document).on('click', '.rename-attribute-btn', function () {
        let button = $(this);
        let isEditing = button.data('editing') === true;
        let row = button.closest('.form-group.row');
        let labelInput = row.find('input[name="choice[]"]').first();

        if (!isEditing) {
            button.data('editing', true);
            button.data('original-name', labelInput.val());
            labelInput.prop('readonly', false)
                .removeClass('form-control-plaintext')
                .addClass('form-control seller-attribute-inline-name');
            button.find('i').removeClass('la-pen').addClass('la-check');
            labelInput.trigger('focus').trigger('select');
            return;
        }

        saveInlineAttributeName(button);
    });

    $(document).on('dblclick', 'input[name="choice[]"]', function () {
        let row = $(this).closest('.form-group.row');
        let button = row.find('.rename-attribute-btn').first();

        if (button.length && button.data('editing') !== true) {
            button.trigger('click');
        }
    });

    $(document).on('keydown', '.seller-attribute-inline-name', function (event) {
        let input = $(this);
        let row = input.closest('.form-group.row');
        let button = row.find('.rename-attribute-btn').first();

        if (event.key === 'Enter') {
            event.preventDefault();
            saveInlineAttributeName(button);
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            input.val(button.data('original-name') || input.val());
            finishInlineAttributeEdit(button, input.val());
        }
    });

    $(document).on('blur', '.seller-attribute-inline-name', function () {
        let input = $(this);
        let row = input.closest('.form-group.row');
        let button = row.find('.rename-attribute-btn').first();

        setTimeout(function () {
            if (button.data('editing') === true) {
                saveInlineAttributeName(button);
            }
        }, 120);
    });

    function finishInlineAttributeEdit(button, finalName) {
        let row = button.closest('.form-group.row');
        let labelInput = row.find('input[name="choice[]"]').first();

        labelInput.val(finalName)
            .prop('readonly', true)
            .removeClass('form-control seller-attribute-inline-name')
            .addClass('form-control-plaintext');
        button.data('editing', false);
        button.data('attribute-name', finalName);
        button.find('i').removeClass('la-check').addClass('la-pen');
    }

    function saveInlineAttributeName(button) {
        let attributeId = button.data('attribute-id');
        let row = button.closest('.form-group.row');
        let labelInput = row.find('input[name="choice[]"]').first();
        let currentName = $.trim(button.data('attribute-name') || button.data('original-name') || labelInput.val() || '');
        let nextName = $.trim(labelInput.val());

        if (!nextName) {
            notifyProductForm('danger', 'Please enter an attribute name.');
            labelInput.trigger('focus');
            return;
        }

        if (nextName.toLowerCase() === currentName.toLowerCase()) {
            finishInlineAttributeEdit(button, currentName);
            return;
        }

        button.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: productFormData().storeAttributeRoute,
            data: {
                attribute_id: attributeId,
                name: nextName,
                values: [],
                category_ids: selectedCategoryIds(),
                _token: (typeof AIZ !== 'undefined' && AIZ.data && AIZ.data.csrf) ? AIZ.data.csrf : (productFormData().csrf || $('meta[name="csrf-token"]').attr('content'))
            },
            success: function (response) {
                let attribute = response.attribute || {};
                let finalName = attribute.name || nextName;

                sellerAttributeNameOverrides[attributeId] = finalName;
                finishInlineAttributeEdit(button, finalName);
                $('#choice_attributes option[value="' + attributeId + '"]').text(finalName);
                refreshProductSelects($('#choice_attributes'));
                update_sku();
                notifyProductForm('success', response.message || 'Attribute updated.');
            },
            error: function (xhr) {
                notifyProductForm('danger', sellerAttributeError(xhr, 'Unable to update attribute.'));
            },
            complete: function () {
                button.prop('disabled', false);
            }
        });
    }

    $(document).on('change', '.attribute_choice', function () {
        renderEditableAttributeValues($(this).closest('.form-group.row'));
    });

    $(document).on('keydown', '.seller-selected-value-input', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            $(this).trigger('change');
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            $(this).val($(this).data('original-value'));
            this.blur();
        }
    });

    $(document).on('dblclick', '.seller-selected-value-input', function () {
        $(this).trigger('select');
    });

    function saveSelectedAttributeValue(row, select, oldValue, newValue, input) {
        if (input && input.data('saving-value') === true) {
            return;
        }

        if (input) {
            input.data('saving-value', true);
        }

        snapshotVariantInputValues(oldValue, newValue);

        let selectedValues = (select.val() || []).map(function (value) {
            return String(value);
        });
        let duplicate = select.find('option').filter(function () {
            return String($(this).val()).toLowerCase() === newValue.toLowerCase() && String($(this).val()) !== oldValue;
        }).first();

        if (duplicate.length) {
            selectedValues = selectedValues.filter(function (value) {
                return value !== oldValue;
            });
            if (selectedValues.indexOf(String(duplicate.val())) === -1) {
                selectedValues.push(String(duplicate.val()));
            }
            select.find('option').filter(function () {
                return String($(this).val()) === oldValue;
            }).remove();
            select.val(selectedValues);
        } else {
            select.find('option').each(function () {
                if (String($(this).val()) === oldValue) {
                    $(this).val(newValue).text(newValue).prop('selected', true);
                }
            });
            selectedValues = selectedValues.map(function (value) {
                return value === oldValue ? newValue : value;
            });
            select.val(selectedValues);
        }

        refreshProductSelects(select);
        renderEditableAttributeValues(row);
        queueVariantValueRename(row, oldValue, newValue);
        update_sku();

        $.ajax({
            type: 'POST',
            url: productFormData().storeAttributeRoute,
            data: {
                attribute_id: row.find('input[name="choice_no[]"]').first().val(),
                name: $.trim(row.find('input[name="choice[]"]').first().val()),
                old_value: oldValue,
                values: [newValue],
                category_ids: selectedCategoryIds(),
                _token: (typeof AIZ !== 'undefined' && AIZ.data && AIZ.data.csrf) ? AIZ.data.csrf : (productFormData().csrf || $('meta[name="csrf-token"]').attr('content'))
            },
            success: function (response) {
                let finalValue = response.values && response.values.length ? String(response.values[0]) : newValue;

                if (finalValue !== newValue) {
                    snapshotVariantInputValues(newValue, finalValue);

                    let values = (select.val() || []).map(function (value) {
                        return String(value) === newValue ? finalValue : String(value);
                    });

                    select.find('option').each(function () {
                        if (String($(this).val()) === newValue) {
                            $(this).val(finalValue).text(finalValue).prop('selected', true);
                        }
                    });

                    select.val(values);
                    refreshProductSelects(select);
                    renderEditableAttributeValues(row);
                    update_sku();
                }

                notifyProductForm('success', response.message || 'Attribute value updated.');
            },
            error: function (xhr) {
                if (input && input.length) {
                    input.val(oldValue);
                }
                notifyProductForm('danger', sellerAttributeError(xhr, 'Unable to save attribute value.'));
            },
            complete: function () {
                if (input) {
                    input.data('saving-value', false);
                }
            }
        });
    }

    $(document).on('change blur', '.seller-selected-value-input', function () {
        let input = $(this);
        let row = input.closest('.form-group.row');
        let select = row.find('.attribute_choice').first();
        let oldValue = String(input.data('original-value') || '');
        let newValue = $.trim(input.val());

        if (!newValue) {
            input.val(oldValue);
            return;
        }

        if (newValue === oldValue) {
            return;
        }

        saveSelectedAttributeValue(row, select, oldValue, newValue, input);
    });

    function setVariantOptionEditButtonState(item, isEditing) {
        let button = $(item).find('.variant-option-edit-btn').first();

        button.data('editing', isEditing === true);
        button.find('i')
            .toggleClass('la-pen', isEditing !== true)
            .toggleClass('la-check', isEditing === true);
    }

    function openVariantOptionEditor(badge) {
        badge = $(badge);

        if (badge.find('.variant-option-inline-input').length) {
            return;
        }

        let oldValue = String(badge.data('variant-value') || badge.text()).trim();
        let input = $('<input type="text" class="variant-option-inline-input">').val(oldValue);
        let inputWidth = Math.min(180, Math.max(72, (oldValue.length * 9) + 32));

        badge.data('original-html', badge.html());
        badge.addClass('variant-option-editing');
        setVariantOptionEditButtonState(badge.closest('.variant-option-item'), true);
        badge.empty().append(input);
        input.css('width', inputWidth + 'px');
        input.trigger('focus');
        if (input[0] && input[0].setSelectionRange) {
            input[0].setSelectionRange(oldValue.length, oldValue.length);
        }
    }

    $(document).on('mousedown', '.variant-option-edit-btn', function (event) {
        if ($(this).data('editing') === true) {
            event.preventDefault();
        }
    });

    $(document).on('click', '.variant-option-edit-btn', function () {
        let button = $(this);
        let item = button.closest('.variant-option-item');
        let badge = item.find('.variant-option-edit').first();
        let input = badge.find('.variant-option-inline-input').first();

        if (button.data('editing') === true) {
            if (input.length) {
                input.trigger('change');
            } else {
                setVariantOptionEditButtonState(item, false);
            }
            return;
        }

        openVariantOptionEditor(badge);
    });

    $(document).on('keydown', '.variant-option-edit-btn', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            $(this).trigger('click');
        }
    });

    $(document).on('keydown', '.variant-option-inline-input', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            $(this).trigger('change');
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            let badge = $(this).closest('.variant-option-edit');
            $(this).data('variant-save-started', true);
            badge.removeClass('variant-option-editing');
            setVariantOptionEditButtonState(badge.closest('.variant-option-item'), false);
            badge.html(badge.data('original-html'));
        }
    });

    $(document).on('change blur', '.variant-option-inline-input', function () {
        let input = $(this);
        let badge = input.closest('.variant-option-edit');
        let oldValue = String(badge.data('variant-value') || '').trim();
        let newValue = $.trim(input.val());

        if (input.data('variant-save-started') === true) {
            return;
        }

        if (!newValue || newValue === oldValue) {
            input.data('variant-save-started', true);
            badge.removeClass('variant-option-editing');
            setVariantOptionEditButtonState(badge.closest('.variant-option-item'), false);
            badge.html(escapeHtml(oldValue));
            return;
        }

        input.data('variant-save-started', true);

        let matchedSelect = $();
        $('.attribute_choice').each(function () {
            let select = $(this);
            let match = select.find('option:selected').filter(function () {
                return String($(this).val()) === oldValue || $.trim($(this).text()) === oldValue;
            }).first();

            if (match.length) {
                matchedSelect = select;
                return false;
            }
        });

        if (!matchedSelect.length) {
            badge.removeClass('variant-option-editing');
            setVariantOptionEditButtonState(badge.closest('.variant-option-item'), false);
            badge.html(escapeHtml(oldValue));
            notifyProductForm('danger', 'Unable to find the selected attribute value.');
            return;
        }

        let row = matchedSelect.closest('.form-group.row');
        saveSelectedAttributeValue(row, matchedSelect, oldValue, newValue, input);
    });

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

    function validateProductVariations() {
        let messages = [];
        let attributeSelect = $('#choice_attributes');
        let selectedAttributes = (attributeSelect.val() || []).filter(function (value) {
            return String(value || '').length > 0;
        });

        $('#attributes_enable_toggle').prop('checked', true);
        $('#attributes-container').show();
        attributeSelect.prop('disabled', false);
        refreshProductSelects(attributeSelect);

        if (!selectedAttributes.length) {
            messages.push('Please choose at least one product attribute.');
            addFieldError('choice_attributes', 'Please choose at least one product attribute.');
        }

        selectedAttributes.forEach(function (attributeId) {
            let row = $('input[name="choice_no[]"][value="' + attributeId + '"]').closest('.form-group.row');
            let label = $.trim(row.find('input[name="choice[]"]').val()) || 'Attribute';
            let optionSelect = $('select[name="choice_options_' + attributeId + '[]"]');

            if (optionSelect.length) {
                optionSelect.prop('disabled', false);
                optionSelect.removeAttr('required');
                refreshProductSelects(optionSelect);
                let values = optionSelect.val() || [];
                if (!values.length) {
                    messages.push('Please choose at least one value for ' + label + '.');
                    addFieldError('choice_options_' + attributeId, 'Please choose at least one value for ' + label + '.');
                }
            }
        });

        if ($('#sku_combination').find('.variant').length > 0) {
            let visiblePrices = $('#sku_combination').find('.var_price:visible');
            let invalidPrices = [];

            visiblePrices.each(function () {
                let input = $(this);
                let value = parseFloat($.trim(input.val()));

                if (!value || value <= 0) {
                    invalidPrices.push(input);
                }
            });

            if (invalidPrices.length) {
                let label = 'Please enter a price greater than 0 for every variant.';

                messages.push(label);
                invalidPrices.forEach(function (priceInput) {
                    priceInput.addClass('is-invalid-field');
                    if (!priceInput.next('.product-form-field-error').length) {
                        priceInput.after($('<span class="product-form-field-error"></span>').text(label));
                    }
                });
            }
        }

        if (messages.length) {
            let uniqueMessages = messages.filter(function (message, index, list) {
                return list.indexOf(message) === index;
            });
            showProductFormAlert('danger', uniqueMessages);
            notifyProductForm('danger', uniqueMessages);
            $('html, body').animate({
                scrollTop: $('.productvariation').offset().top - 90
            }, 250);
            return false;
        }

        return true;
    }

    function bindProductAjaxSubmit() {
        $('#choice_form[data-ajax-submit="true"]').on('submit', function (e) {
            e.preventDefault();

            let form = $(this);
            clearProductFormErrors();
            if (!validateProductVariations()) {
                return;
            }

            if (this.checkValidity && !this.checkValidity()) {
                this.reportValidity();
                return;
            }

            let submitButton = $(document.activeElement).is('[type="submit"]')
                ? $(document.activeElement)
                : form.find('[type="submit"]').first();
            let formData = new FormData(this);

            if (submitButton.attr('name')) {
                formData.set(submitButton.attr('name'), submitButton.val());
            }

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
                attribute_id: i,
                attribute_name: name
            },
            success: function (data) {
                var obj = JSON.parse(data);
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
                }

                name = name.trim();
                $('#variant-table-prompt').remove();
                $('#customer_choice_options').append('\
                <div class="form-group row align-items-center mb-3 attribute-variation-row">\
                    <div class="col-lg-3">\
                        <input type="hidden" name="choice_no[]" value="' + i + '">\
                        <div class="seller-attribute-title-cell">\
                            <input type="text" class="form-control-plaintext font-weight-bold text-dark-title" name="choice[]" value="' + name +
                    '" placeholder="Choice Title" readonly>\
                            <button type="button" class="btn premium-btn-circle premium-btn-edit rename-attribute-btn premium-icon-btn" data-attribute-id="' + i + '" data-attribute-name="' + name + '">\
                                <i class="las la-pen"></i>\
                            </button>\
                        </div>\
                    </div>\
                    <div class="col-lg-8 seller-variation-select-col">\
                        <select class="form-control aiz-selectpicker attribute_choice rounded-pill premium-select" data-live-search="true" name="choice_options_' + i + '[]" multiple data-container="body">\
                            ' + obj + '\
                        </select>\
                        <small class="seller-select-help">Search options. If there is no match, add it from the dropdown.</small>\
                        <div class="seller-selected-values-editor mt-2 d-none" id="selected-values-editor-' + i + '">\
                            <div class="seller-selected-values-title">Set Option Values Sort Order</div>\
                            <div class="seller-selected-values-list d-flex flex-wrap gap-1 w-100"></div>\
                        </div>\
                    </div>\
                    <div class="col-lg-1 text-center d-flex align-items-center justify-content-center">\
                        <label class="premium-switch">\
                            <input value="1" type="checkbox" class="attribute_choice_toggle" id="attribute_choice_active_' + i + '" name="attribute_choice_active_' + i + '" checked>\
                            <span class="premium-slider"></span>\
                        </label>\
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

        // Capture all currently visible prices before the AJAX wipes the table
        captureFullVariantSnapshot();

        $.ajax({
            type: "POST",
            url: formData.skuCombinationRoute,
            data: $('#choice_form').serialize(),
            success: function (data) {
                $('#sku_combination').html(data);
                // Restore prices that were typed by the user (or loaded from DB) before this re-render
                restorePendingVariantInputValues();
                if (typeof AIZ !== 'undefined' && AIZ.uploader) AIZ.uploader.previewGenerate();
                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.fooTable) AIZ.plugins.fooTable();
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
    window.remove_variant_value = function (button) {
        let row = $(button).closest('.variant');
        let values = row.data('variant-values') || [];
        if (!Array.isArray(values)) {
            values = [values];
        }

        values.forEach(function (value) {
            $('.attribute_choice option:selected, #colors option:selected').each(function () {
                let option = $(this);
                if (String(option.val()) === String(value) || $.trim(option.text()) === String(value)) {
                    option.prop('selected', false);
                }
            });
        });

        refreshProductSelects($('.attribute_choice, #colors'));
        update_sku();
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

function loadProductAddons(categoryIds = []) {

    if (typeof window.loadProductAddonsByCategories === 'function') {
        window.loadProductAddonsByCategories(categoryIds);
    }
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
        loadProductAddons(initialCategories);
    }

    // CATEGORY CHANGE
    $(document).on('change', '#treeview input[type="checkbox"]', function () {

        let categoryIds = getSelectedCategoryIds();

        loadCheckoutServices(categoryIds);
        loadShippingCharges(categoryIds);
        loadProductAddons(categoryIds);
    });

});
