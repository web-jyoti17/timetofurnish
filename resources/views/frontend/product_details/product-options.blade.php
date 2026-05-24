@if ($detailedProduct->auction_product != 1)
    <form id="option-choice-form">
        @csrf
        <input type="hidden" name="id" value="{{ $detailedProduct->id }}">
        @php
            $actual_base_price = $detailedProduct->unit_price;
            $discount_applicable = false;
            if ($detailedProduct->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $detailedProduct->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $detailedProduct->discount_end_date
            ) {
                $discount_applicable = true;
            }
            if ($discount_applicable) {
                if ($detailedProduct->discount_type == 'percent') {
                    $actual_base_price -= ($actual_base_price * $detailedProduct->discount) / 100;
                } elseif ($detailedProduct->discount_type == 'amount') {
                    $actual_base_price -= $detailedProduct->discount;
                }
            }
            $actual_base_price = max(0, $actual_base_price);
        @endphp
        <span class="d-none js-product-base-price"
            data-base-price="{{ home_discounted_base_price($detailedProduct, false) }}"
            data-actual-base-price="{{ $actual_base_price }}">
            {{ home_discounted_base_price($detailedProduct, false) }}
        </span>
        @php

            // dd($detailedProduct);
        @endphp
        @if ($detailedProduct->digital == 0)
            <!-- Choice Options -->
            @if ($detailedProduct->choice_options != null)

                @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)
                    <div class="mb-2 row no-gutters">

                        <div class="col-sm-12">
                            <h5 class="mb-2">
                               
                                {{ ucfirst(get_single_attribute_name($choice->attribute_id)) }}
                                <span style="color: red;">*</span>
                            </h5>
                        </div>



                        <div class="col-sm-12">

                            <select class="form-control custom-dropdown variant-dropdown"
                                name="attribute_id_{{ $choice->attribute_id }}"
                                data-attribute="{{ $choice->attribute_id }}"
                                onchange="getVariantPrice(); updateVariantOptionPrice(this);">

                                <option value="" selected>Choose Option</option>
                                @foreach ($choice->values as $key => $value)
                                    @php
                                        $optionDetails = get_product_option_display_details('attribute', $value, [
                                            'product' => $detailedProduct,
                                            'attribute_id' => $choice->attribute_id,
                                        ]);
                                    @endphp

                                    <option value="{{ $value }}" data-price="{{ $optionDetails['price'] }}"
                                        data-price-text="{{ $optionDetails['formatted_price'] }}"
                                        data-quantity="{{ $optionDetails['quantity'] }}"
                                        data-img="{{ $optionDetails['image_url'] }}"
                                        @if ($key == 0)  @endif>
                                        {{ $optionDetails['label'] }}
                                    </option>
                                @endforeach

                            </select>

                            <!-- PRICE INFO -->
                            {{-- <div class=" attribute-price-info" id="attribute-price-info-{{ $choice->attribute_id }}">
                            </div>
                            <div class="product-option-preview d-none"
                                id="attribute-preview-{{ $choice->attribute_id }}"></div> --}}

                        </div>

                    </div>
                @endforeach
            @endif
            <!----addon----------->

            <div class="product-addons">

                @php

                    // Get sort order using addon NAME
                    $globalAddonOrders = \App\Models\ProductAddonGlobal::pluck('sort_order', 'name');

                    // Sort addons according to global table
                    $sortedAddons = $detailedProduct->addons
                        ->sortBy(function ($addon) use ($globalAddonOrders) {
                            return $globalAddonOrders[$addon->name] ?? 9999;
                        })
                        ->values();

                @endphp
                @foreach ($sortedAddons as $addon)
                    @php
                        $isFabric = strtolower($addon->name) == 'fabric';

                        $fabricGroups = [];

                        if ($isFabric) {
                            foreach ($addon->options as $option) {
                                $words = explode(' ', trim($option->option_name));

                                if (count($words) >= 2) {
                                    $groupName = $words[0] . ' ' . $words[1];
                                } else {
                                    $groupName = $option->option_name;
                                }

                                if (!isset($fabricGroups[$groupName])) {
                                    $fabricGroups[$groupName] = [];
                                }

                                $fabricGroups[$groupName][] = $option;
                            }
                        }
                    @endphp

                    <div class="mb-3 addon-block">

                        <h5 class="mb-2">
                            {{ $addon->name }}
                            <span style="color: red;">*</span>
                        </h5>

                        @if ($isFabric)
                            {{-- FABRIC DESIGN --}}
                            <select class="form-select custom-dropdown fabric-dropdown"
                                name="addons_group[{{ $addon->id }}]" data-addonid="{{ $addon->id }}"
                                onchange="updateFabricPreview({{ $addon->id }}, this);">

                                <option value="">Choose Option</option>

                                @foreach ($fabricGroups as $groupName => $groupOptions)
                                    <option value="{{ $groupName }}" data-group="{{ $groupName }}">
                                        {{ $groupName }}
                                    </option>
                                @endforeach
                            </select>

                            <span id="addon-price-info-{{ $addon->id }}" class="addon-price-info d-none"></span>

                            <div class="flex-wrap mt-2 col-md-12 col-12 d-flex align-items-center"
                                id="fabric-preview-block-{{ $addon->id }}">

                                @foreach ($fabricGroups as $groupName => $groupOptions)
                                    <div class="fabric-preview-group d-none" data-group="{{ $groupName }}"
                                        id="fabric-preview-group-{{ $addon->id }}-{{ \Str::slug($groupName) }}">

                                        @foreach ($groupOptions as $option)
                                            @php
                                                $addonOptionDetails = get_product_option_display_details(
                                                    'addon',
                                                    $option,
                                                );
                                            @endphp
                                            <button type="button" class="p-0 fabric-color-box btn"
                                                data-addonid="{{ $addon->id }}" data-group="{{ $groupName }}"
                                                data-price="{{ $addonOptionDetails['price'] }}"
                                                data-price-text="{{ $addonOptionDetails['formatted_price'] }}"
                                                data-quantity="{{ $addonOptionDetails['quantity'] }}"
                                                data-img="{{ $addonOptionDetails['image_url'] }}"
                                                data-optionid="{{ $addonOptionDetails['id'] }}"
                                                data-fabricname="{{ $addonOptionDetails['value'] }}"
                                                aria-label="{{ $addonOptionDetails['value'] }}"
                                                onclick="selectFabricOption({{ $addon->id }}, this);">
                                                <div class="fabric-card-inner">
                                                    <div class="fabric-img-wrapper">
                                                        @if (!empty($addonOptionDetails['image_url']))
                                                            <img src="{{ $addonOptionDetails['image_url'] }}"
                                                                alt="{{ $addonOptionDetails['value'] }}"
                                                                class="fabric-img-tooltip"
                                                                data-box-img="{{ $addonOptionDetails['id'] }}">
                                                        @else
                                                            <span class="fabric-img-placeholder"></span>
                                                        @endif
                                                    </div>
                                                    <div class="fabric-price-wrapper">
                                                        @if ($addonOptionDetails['price'] > 0)
                                                            <span class="fabric-price-text">
                                                                +{{ $addonOptionDetails['formatted_price'] }}
                                                            </span>
                                                        @else
                                                            {{-- <span class="fabric-price-text free">
                                                                Free
                                                            </span> --}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </button>
                                        @endforeach

                                    </div>
                                @endforeach

                            </div>
                            {{-- <div class="product-option-preview d-none" id="addon-preview-{{ $addon->id }}"></div> --}}
                        @else
                            {{-- NORMAL DESIGN --}}
                            <select class="form-select custom-dropdown" name="addons[{{ $addon->id }}]"
                                data-addonid="{{ $addon->id }}">

                                <option value="">Choose Option</option>

                                @foreach ($addon->options as $option)
                                    @php
                                        $addonOptionDetails = get_product_option_display_details('addon', $option);
                                    @endphp
                                    <option value="{{ $addonOptionDetails['id'] }}"
                                        data-price="{{ $addonOptionDetails['price'] }}"
                                        data-price-text="{{ $addonOptionDetails['formatted_price'] }}"
                                        data-quantity="{{ $addonOptionDetails['quantity'] }}"
                                        data-img="{{ $addonOptionDetails['image_url'] }}"
                                        data-name="{{ $addonOptionDetails['value'] }}">

                                        {{ $addonOptionDetails['label'] }}

                                    </option>
                                @endforeach

                            </select>

                            {{-- <span id="addon-price-info-{{ $addon->id }}" class="addon-price-info d-none"></span> --}}
                            {{-- <div class="product-option-preview d-none" id="addon-preview-{{ $addon->id }}"></div> --}}
                        @endif

                    </div>
                @endforeach

                {{-- ✅ SIZE FIRST --}}

                <style>
                    .fabric-color-box {
                        display: inline-block !important;
                        background: #ffffff !important;
                        border: 2px solid #e5e5e5 !important;
                        border-radius: 10px !important;
                        padding: 6px !important;
                        margin: 8px 8px 8px 0 !important;
                        transition: all 0.2s ease-in-out !important;
                        cursor: pointer !important;
                        outline: none !important;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04) !important;
                        width: 76px !important;
                        text-align: center !important;
                        vertical-align: top !important;
                    }


                    .fabric-preview-group .fabric-color-box.selected,
                    .fabric-preview-group .fabric-color-box:hover {
                        border: 3px solid #1976d2 !important;
                        box-shadow: 0 0 12px rgba(25, 118, 210, 0.4) !important;
                        transform: scale(1.03) !important;
                        z-index: 2;
                    }

                    .fabric-card-inner {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        width: 100%;
                    }

                    .fabric-img-wrapper {
                        width: 60px;
                        height: 60px;
                        border-radius: 6px;
                        overflow: hidden;
                        border: 1px solid #eaeaea;
                        background: #fdfdfd;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-bottom: 4px;
                    }

                    .fabric-img-wrapper img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        display: block;
                        transition: transform 0.2s ease;
                    }

                    .fabric-color-box:hover .fabric-img-wrapper img {
                        transform: scale(1.08);
                    }

                    .fabric-img-placeholder {
                        width: 100%;
                        height: 100%;
                        background: #ececec;
                        display: block;
                        border-radius: 6px;
                    }

                    .fabric-price-wrapper {
                        width: 100%;
                        border-top: 1px solid #eaeaea;
                        padding-top: 4px;
                        margin-top: 2px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .fabric-price-text {
                        font-size: 11px !important;
                        font-weight: 600 !important;
                        color: #2e7d32 !important;
                        display: block;
                        white-space: nowrap;
                    }

                    .fabric-price-text.free {
                        color: #757575 !important;
                    }

                    .product-option-preview {
                        margin-top: 10px;
                        display: inline-flex;
                        align-items: center;
                        min-height: 34px;
                    }

                    .product-option-preview.d-none {
                        display: none !important;
                    }

                    .product-option-preview img {
                        width: 34px;
                        height: 34px;
                        object-fit: cover;
                        border-radius: 6px;
                        border: 1px solid #e5e5e5;
                        background: #f7f7f7;
                        cursor: zoom-in;
                    }
                </style>


            </div>



            <div class="py-3 row no-gutters">
                <div class="col-sm-2">
                    <div class="mt-2 text-secondary fs-15 fw-500" style="color:#333 !important">
                        {{ translate('Quantity') }}
                    </div>
                </div>
                <div class="col-sm-10">
                    <div class="py-1 product-quantity d-flex align-items-center">
                        <div class="mr-4 border rounded d-flex align-items-center"
                            style="width: 140px; overflow: hidden; background: #f5f5f5;">
                            <button
                                class="border-0 btn btn-icon btn-quantity d-flex align-items-center justify-content-center"
                                type="button" data-type="minus" data-field="quantity"
                                style="min-width:40px; min-height:40px; border-radius: 0; background: #f0eeea; color: #979797;"
                                disabled>
                                <span style="font-size: 22px; line-height: 1;">−</span>
                            </button>
                            <input type="number" name="quantity" id="quantity"
                                class="px-0 py-0 text-center border-0 form-control flex-grow-1 bg-divider quantity-input"
                                style="width:60px;height:40px;box-shadow:none;background: #ded3c3; font-size: 22px; color: #888; font-weight: 500; border-radius: 0;"
                                value="{{ $detailedProduct->min_qty }}" min="{{ $detailedProduct->min_qty }}"
                                max="10" placeholder="1" lang="en" autocomplete="off" onblur="change_qty()">
                            <button
                                class="border-0 btn btn-icon btn-quantity d-flex align-items-center justify-content-center"
                                type="button" data-type="plus" data-field="quantity"
                                style="min-width:40px; min-height:40px; border-radius: 0; background: #f0eeea; color: #979797;">
                                <span style="font-size: 22px; line-height: 1;">+</span>
                            </button>
                        </div>
                        @php
                            $qty = 0;
                            $qty1 = 0;
                            foreach ($detailedProduct->stocks as $key => $stock) {
                                if ($qty1 == 0) {
                                    $qty1 = $stock->qty;
                                }
                                $qty += $stock->qty;
                            }
                        @endphp
                        <input type="hidden" name="qty1" id="qty1" value="{{ $qty1 }}">
                        <div class="pl-3 ml-2 available-amount border-left" style="font-size: 14px; color: #888;">
                            @if ($detailedProduct->stock_visibility_state == 'quantity')
                                (<span id="available-quantity" style="font-weight:600;">{{ $qty }}</span>
                                {{ translate('available') }})
                            @elseif($detailedProduct->stock_visibility_state == 'text' && $qty >= 1)
                                (<span id="available-quantity"
                                    style="font-weight:600;">{{ translate('In Stock') }}</span>)
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        @else
            <!-- Quantity -->
            <input type="hidden" name="quantity" value="1">
        @endif

        <div class="mb-3 row no-gutters d-none" id="total-price-div">
            <div class="col-sm-2">
                <div class="text-black fs-18 fw-600" style="color: #333 !important;">
                    {{ translate('Total Price') }}
                </div>
            </div>
            <div class="col-sm-10">
                <div class="flex-wrap d-flex align-items-center">
                    <!-- Regular Price (with Addon total UI dynamic addition) -->
                    <strong class="fs-20 fw-600 text-primary js-product-total-price" id="total-pricing"
                        data-default-price-text="{{ home_discounted_base_price($detailedProduct) }}">
                        {{ home_discounted_base_price($detailedProduct) }}
                    </strong>

                </div>
            </div>

        </div>
        @php
            $dimensions_enabled = (int) old('dimensions_enabled', $detailedProduct->dimensions_enabled ?? 0) === 1;
        @endphp
        @if (
            !empty($detailedProduct->product_length) &&
                !empty($detailedProduct->product_breadth) &&
                !empty($detailedProduct->product_height) &&
                $dimensions_enabled == 1)
            <div class="pb-3 row no-gutters d-none" id="chosen_price_div">
                <div class="col-sm-3">
                    <div class="mt-1 text-secondary fs-15 fw-500" style="color:#333 !important">
                        {{ translate('Dimensions') }} (
                        {{ $detailedProduct->dimensions_unit == 'cm' ? 'CM' : 'IN' }})
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="pt-1 diamentions"> <strong class="ml-2 fs-14 fw-600 text-primary">
                            L:{{ $detailedProduct->product_length }} X W:{{ $detailedProduct->product_breadth }}
                            X
                            H:{{ $detailedProduct->product_height }}</strong>
                    </div>
                </div>
            </div>
        @endif
        @if (!empty($detailedProduct->dispatch_time))
            <div class="pb-3 row no-gutters d-none" id="chosen_price_div">
                <div class="col-sm-3">
                    <div class="mt-1 text-secondary fs-15 fw-500" style="color:#333 !important">
                        {{ translate('Dispatch
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            Time') }}
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="pt-1 "> <strong class="ml-2 fs-14 fw-600 text-primary">
                            {{ $detailedProduct->dispatch_time }}</strong>
                    </div>
                </div>
            </div>
        @endif
    </form>


    <script>
        // ---------------------------------
        // Helper: slugify for IDs
        // ---------------------------------
        function slugify(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }

        // ---------------------------------
        // Auto-selection of next/remaining options
        // ---------------------------------
        var isAutoSelecting = false;

        function autoSelectRemainingAddons() {
            return;
        }

        function focusNextSelect(currSelect) {
            if (!currSelect || currSelect.length === 0) return;
            
            var configSelects = $('.variant-dropdown, .addon-block select');
            var currentIndex = configSelects.index(currSelect);
            
            if (currentIndex !== -1) {
                // Find the next select that is NOT already selected
                for (var i = currentIndex + 1; i < configSelects.length; i++) {
                    var nextSelect = configSelects.eq(i);
                    var addonId = nextSelect.data('addonid');
                    
                    var isAlreadySelected = false;
                    
                    if (nextSelect.hasClass('fabric-dropdown')) {
                        // Check if fabric hidden input has value
                        var fabricVal = $(`input[type="hidden"][name="addons[${addonId}]"]`).val();
                        if (fabricVal && fabricVal !== '') {
                            isAlreadySelected = true;
                        }
                    } else {
                        var val = nextSelect.val();
                        if (val && val !== '') {
                            isAlreadySelected = true;
                        }
                    }
                    
                    if (!isAlreadySelected) {
                        setTimeout(function() {
                            nextSelect.select2('open');
                        }, 300);
                        break;
                    }
                }
            }
        }

        // ---------------------------------
        // Fabric Preview logic
        // ---------------------------------
        var isUpdatingFabric = false;

        function updateFabricPreview(addonId, selectElem) {
            if (isUpdatingFabric) return;
            isUpdatingFabric = true;

            try {
                var lastGroup = $(selectElem).data('last-group') || '';
                var currentGroup = $(selectElem).val() || '';

                if (lastGroup === currentGroup) {
                    return;
                }
                $(selectElem).data('last-group', currentGroup);

                var $previewBlock = $('#fabric-preview-block-' + addonId);

                // Hide all groups for this addon
                $previewBlock.find('.fabric-preview-group').addClass('d-none');

                // ALWAYS remove previous selections and reset hidden input when group changes
                $previewBlock.find('.fabric-color-box').removeClass('selected');
                $(`input[type=hidden][name="addons[${addonId}]"]`).val('');

                // Clear stored fabric selection name when changing group
                $(selectElem).data('selected-fabric', '');

                // Reset all options in the dropdown back to their original group name
                $(selectElem).find('option').each(function() {
                    var originalGroup = $(this).data('group');
                    if (originalGroup) {
                        $(this).text(originalGroup);
                    }
                });
                // Trigger select2 update to show the original group name
                $(selectElem).trigger('change.select2');

                var selectedOption = selectElem.selectedOptions ? selectElem.selectedOptions[0] : null;
                if (selectedOption && selectedOption.value) {
                    var groupName = selectedOption.value;
                    var groupSelector = '#fabric-preview-group-' + addonId + '-' + slugify(groupName);
                    var $groupDiv = $(groupSelector);
                    $groupDiv.removeClass('d-none');
                }

                var $priceInfo = $('#addon-price-info-' + addonId);
                $priceInfo.addClass('d-none').html('');

                if (typeof getVariantPrice === 'function') {
                    getVariantPrice();
                }
            } finally {
                isUpdatingFabric = false;
            }
        }

        function selectFabricOption(addonId, optionBtn) {
            var $previewBlock = $('#fabric-preview-block-' + addonId);

            // Remove selected class from all boxes in this addon
            $previewBlock.find('.fabric-color-box').removeClass('selected');

            // Add selected class to this box
            $(optionBtn).addClass('selected');

            // Set hidden input for form
            var name = `addons[${addonId}]`;
            var $input = $(`input[type=hidden][name="${name}"]`);

            if ($input.length === 0) {
                $input = $(`<input type="hidden" name="${name}" />`);
                $(`.fabric-dropdown[data-addonid="${addonId}"]`).after($input);
            }
            $input.val($(optionBtn).data('optionid'));
            $input.trigger('change');

            // Refresh pricing
            if (typeof getVariantPrice === 'function') {
                getVariantPrice();
            }

            // Update dropdown text with selected fabric name
            var fabricName = $(optionBtn).data('fabricname');
            var price = parseFloat($(optionBtn).data('price')) || 0;
            var priceText = $(optionBtn).data('price-text') || '';

            if (price > 0 && priceText) {
                fabricName = fabricName + ' (+' + priceText + ')';
            }

            var groupValue = $(optionBtn).data('group');
            var $groupDropdown = $(`.fabric-dropdown[data-addonid="${addonId}"]`);

            if ($groupDropdown.length) {
                // Store selected fabric full name as data on the select element
                $groupDropdown.data('selected-fabric', fabricName);

                // Reset all options to their original group name first
                $groupDropdown.find('option').each(function() {
                    var originalGroup = $(this).data('group');
                    if (originalGroup) {
                        $(this).text(originalGroup);
                    }
                });

                // Set the active option to show the full fabric name
                var $activeOption = $groupDropdown.find(`option[value="${groupValue}"]`);
                if ($activeOption.length) {
                    $activeOption.text(fabricName);
                }

                // Keep group value
                $groupDropdown.val(groupValue);

                // Force Select2 display text to be the full fabric name
                var select2Rendered = $groupDropdown.next('.select2').find('.select2-selection__rendered');
                if (select2Rendered.length) {
                    select2Rendered.contents().filter(function() {
                        return this.nodeType === 3;
                    }).remove();
                    select2Rendered.append(' ' + fabricName);
                }

                // Trigger Select2 to refresh its list view
                $groupDropdown.trigger('change.select2');
            }

            // Show price info next to select
            var $priceInfo = $('#addon-price-info-' + addonId);
            var price = parseFloat($(optionBtn).data('price')) || 0;
            var image = $(optionBtn).data('img') || '';

            $priceInfo.addClass('d-none').html('');

            renderProductOptionPreview('#addon-preview-' + addonId, {
                image: image,
                name: fabricName || ''
            });

            $previewBlock.find('.fabric-preview-group').addClass('d-none');

            if (typeof checkEnableDisableButtons === 'function') {
                checkEnableDisableButtons();
            }

            if (typeof focusNextSelect === 'function') {
                focusNextSelect($(`.fabric-dropdown[data-addonid="${addonId}"]`));
            }
        }

        function showFabricTooltip(e, imgSrc, fabricName) {
            $('#fabric-img-tooltip-floating').remove();
            var tooltip = $(`
            <div id="fabric-img-tooltip-floating" style="
                position: absolute;
                z-index: 10000;
                top: 0; left: 0;
                background: rgba(33, 33, 33, 0.95);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                color: #fff;
                border-radius: 10px;
                padding: 10px;
                min-width: 170px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25), 0 1px 3px rgba(0, 0, 0, 0.1);
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                text-align: center;
                pointer-events: none;
                border: 1px solid rgba(255, 255, 255, 0.08);
                transition: opacity 0.15s ease;
                ">
                <div style="position: relative;">
                    <img src="${imgSrc}" alt="${fabricName.replace(/"/g, '&quot;')}" style="width:150px;height:150px;object-fit:cover;border-radius:8px;display:block;margin:0 auto 8px;border: 1px solid rgba(255,255,255,0.1);">
                    <div style="font-size:13px;font-weight:600;color:#ffffff;line-height:1.4;padding:0 4px;">${fabricName.replace(/</g, '&lt;')}</div>
                </div>
                <span class="fabric-tooltip-arrow" style="position:absolute;top:100%;left:50%;transform:translateX(-50%);width:0;height:0;border-left:10px solid transparent;border-right:10px solid transparent;border-top:10px solid rgba(33, 33, 33, 0.95);"></span>
            </div>
        `);
            $('body').append(tooltip);

            var tooltipElem = $('#fabric-img-tooltip-floating');
            var box = $(e.currentTarget);
            var boxOffset = box.offset();
            var tooltipWidth = tooltipElem.outerWidth();
            var tooltipHeight = tooltipElem.outerHeight();
            var pageWidth = $(window).width();

            var desiredLeft = boxOffset.left + (box.outerWidth() / 2) - (tooltipWidth / 2);
            if (desiredLeft + tooltipWidth > pageWidth - 16) {
                desiredLeft = pageWidth - tooltipWidth - 16;
            }
            if (desiredLeft < 16) desiredLeft = 16;

            var desiredTop = boxOffset.top - tooltipHeight - 12;
            if (desiredTop < $(window).scrollTop() + 16) {
                desiredTop = boxOffset.top + box.outerHeight() + 12;
                tooltipElem.find('.fabric-tooltip-arrow').css({
                    top: 'auto',
                    bottom: '100%',
                    borderTop: 'none',
                    borderBottom: '10px solid rgba(33, 33, 33, 0.95)'
                });
            } else {
                tooltipElem.find('.fabric-tooltip-arrow').css({
                    top: '100%',
                    bottom: 'auto',
                    borderBottom: 'none',
                    borderTop: '10px solid rgba(33, 33, 33, 0.95)'
                });
            }

            tooltipElem.css({
                left: desiredLeft + "px",
                top: desiredTop + "px"
            });
        }

        function hideFabricTooltip() {
            $('#fabric-img-tooltip-floating').remove();
        }

        function renderProductOptionPreview(targetSelector, details) {
            var $target = $(targetSelector);
            if (!$target.length) return;

            var hasImage = details.image && details.image.length > 0;

            if (!hasImage) {
                $target.addClass('d-none').html('');
                return;
            }

            var html = '';
            // if (hasImage) {
            //     html += '<img class="product-option-preview-img" src="' + details.image + '" alt="' + (details.name ||
            //         'Selected option') + '" data-preview-img="' + details.image + '" data-preview-name="' + (details.name ||
            //         'Selected option') + '">';
            // }

            $target.removeClass('d-none').html(html);
        }

        // ---------------------------------
        // Attribute Price
        // ---------------------------------
        function updateVariantOptionPrice(selectElement) {
            let attributeId = $(selectElement).data('attribute');
            let selected = $(selectElement).find(':selected');
            let price = parseFloat(selected.data('price')) || 0;
            let image = selected.data('img') || '';
            let box = $('#attribute-price-info-' + attributeId);

            box.html('');

            renderProductOptionPreview('#attribute-preview-' + attributeId, {
                image: image,
                name: selected.val() || ''
            });
        }

        // ---------------------------------
        // Validation
        // ---------------------------------
        function validateRequiredSelections(showMessage = false) {
            let isValid = true;
            // remove old errors
            $('.selection-error').remove();
            $('.is-invalid-addon').removeClass('is-invalid-addon');

            // CHECK ATTRIBUTES
            $('.variant-dropdown').each(function() {
                let value = $(this).val();
                if (!value || value === '') {
                    isValid = false;
                    $(this).next('.select2').find('.select2-selection').addClass('is-invalid-addon');
                    if (showMessage) {
                        $(this).closest('.row').append(
                            '<small class="text-danger selection-error d-block mt-1">Please choose this attribute</small>'
                        );
                    }
                }
            });

            // CHECK ADDONS
            $('.addon-block select').each(function() {
                let addonId = $(this).data('addonid');
                let isFabric = $(this).hasClass('fabric-dropdown');

                if (isFabric) {
                    let fabricHidden = $(`input[type="hidden"][name="addons[${addonId}]"]`);
                    if (fabricHidden.length === 0 || !fabricHidden.val()) {
                        isValid = false;
                        $(this).next('.select2').find('.select2-selection').addClass('is-invalid-addon');
                        if (showMessage) {
                            $(this).closest('.addon-block').append(
                                '<small class="text-danger selection-error d-block mt-1">Please choose a fabric option</small>'
                            );
                        }
                    }
                } else {
                    let value = $(this).val();
                    if (!value || value === '') {
                        isValid = false;
                        $(this).next('.select2').find('.select2-selection').addClass('is-invalid-addon');
                        if (showMessage) {
                            $(this).closest('.addon-block').append(
                                '<small class="text-danger selection-error d-block mt-1">Please select this addon</small>'
                            );
                        }
                    }
                }
            });

            // GLOBAL MESSAGE
            if (!isValid && showMessage) {
                if ($('.global-addon-error').length === 0) {
                    $('.product-action-buttons').before(`
                    <div class="alert alert-danger global-addon-error mt-3">
                        Please select all required attributes and addons before continuing.
                    </div>
                `);
                }
                $('html, body').animate({
                    scrollTop: $('.global-addon-error').offset().top - 120
                }, 400);
            } else {
                $('.global-addon-error').remove();
            }
            return isValid;
        }

        function validatedAddToCart() {
            if (!validateRequiredSelections(true)) return;
            addToCart();
        }

        function validatedBuyNow() {
            if (!validateRequiredSelections(true)) return;
            buyNow();
        }

        // ---------------------------------
        // Dynamic Enable/Disable Action Buttons
        // ---------------------------------
        function checkEnableDisableButtons() {
            let totalAttributesAvailable = $('.variant-dropdown').length;
            let totalAddonsAvailable = $('.addon-block select').length;

            // Dynamic thresholds based on product specifications
            let requiredAttributes = totalAttributesAvailable;
            let requiredAddons = Math.min(3, totalAddonsAvailable);

            let selectedAttributesCount = 0;
            $('.variant-dropdown').each(function() {
                let val = $(this).val();
                if (val && val !== '') {
                    selectedAttributesCount++;
                }
            });

            let selectedAddonsCount = 0;
            $('.addon-block select').each(function() {
                let addonId = $(this).data('addonid');
                let isFabric = $(this).hasClass('fabric-dropdown');
                if (isFabric) {
                    let fabricVal = $(`input[type="hidden"][name="addons[${addonId}]"]`).val();
                    if (fabricVal && fabricVal !== '') {
                        selectedAddonsCount++;
                    }
                } else {
                    let val = $(this).val();
                    if (val && val !== '') {
                        selectedAddonsCount++;
                    }
                }
            });

            let isEligible = (selectedAddonsCount >= requiredAddons && selectedAttributesCount >= requiredAttributes);

            let $basketButtons = $('.add-to-cart');
            let $buyButtons = $('.buy-now');
            let $wishlistButtons = $('.wishlist-btn');

            if (isEligible) {
                $basketButtons.prop('disabled', false).removeClass('btn-disabled-custom');
                $buyButtons.prop('disabled', false).removeClass('btn-disabled-custom');
                $wishlistButtons.removeClass('disabled-wishlist');
            } else {
                $basketButtons.prop('disabled', true).addClass('btn-disabled-custom');
                $buyButtons.prop('disabled', true).addClass('btn-disabled-custom');
                $wishlistButtons.addClass('disabled-wishlist');
            }
        }

        // ---------------------------------
        // Initialization & Events
        // ---------------------------------
        $(document).ready(function() {
            // Initialize Select2
            $('.custom-dropdown').each(function() {
                let $select = $(this);
                let placeholder = $select.find('option:first').text();
                $select.select2({
                    placeholder: placeholder,
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: -1,
                    templateSelection: function(state) {
                        if (!state.id) {
                            return state.text;
                        }
                        var selectedFabric = $select.data('selected-fabric');
                        if (selectedFabric) {
                            return selectedFabric;
                        }
                        return state.text;
                    }
                });
            });

            // Handle select2:clearing to correctly reset states on cross and remove
            $('.custom-dropdown').on('select2:clearing', function(e) {
                let $select = $(this);
                let addonId = $select.data('addonid');
                let attributeId = $select.data('attribute');
                $select.data('is-clearing', true);

                if ($select.hasClass('fabric-dropdown')) {
                    // Clear selected fabric name
                    $select.data('selected-fabric', '');
                    $select.data('last-group', '');

                    // Reset option texts to original group name
                    $select.find('option').each(function() {
                        var originalGroup = $(this).data('group');
                        if (originalGroup) {
                            $(this).text(originalGroup);
                        }
                    });

                    // Clear hidden input
                    $(`input[type="hidden"][name="addons[${addonId}]"]`).val('');

                    // Remove selected class from color boxes
                    var $previewBlock = $('#fabric-preview-block-' + addonId);
                    $previewBlock.find('.fabric-color-box').removeClass('selected');

                    // Hide all preview groups
                    $previewBlock.find('.fabric-preview-group').addClass('d-none');

                    // Hide price warning/info box
                    $('#addon-price-info-' + addonId).addClass('d-none').html('');
                    $('#addon-preview-' + addonId).addClass('d-none').html('');
                } else if (attributeId) {
                    // For attribute/variant dropdown
                    $('#attribute-price-info-' + attributeId).html('');
                    $('#attribute-preview-' + attributeId).addClass('d-none').html('');
                } else if (addonId) {
                    // For normal addon dropdown
                    $('#addon-price-info-' + addonId).addClass('d-none').html('');
                    $('#addon-preview-' + addonId).addClass('d-none').html('');
                }

                // Trigger price recalculation after clear completes
                setTimeout(function() {
                    $select.val(null).trigger('change.select2');
                    $select.data('is-clearing', false);
                    getVariantPrice();
                    validateRequiredSelections(false);
                    if (typeof checkEnableDisableButtons === 'function') {
                        checkEnableDisableButtons();
                    }
                }, 50);
            });

            // Initialize variant prices
            $('.variant-dropdown').each(function() {
                updateVariantOptionPrice(this);
            });

            // Initialize Fabric dropdown views
            $('.fabric-dropdown').each(function() {
                updateFabricPreview($(this).data('addonid'), this);
            });

            // Initial Price Refresh
            getVariantPrice();
            if (typeof checkEnableDisableButtons === 'function') {
                checkEnableDisableButtons();
            }

            // ---------------------------------
            // Event Listeners
            // ---------------------------------

            // Attribute change
            $(document).on('change', '.variant-dropdown', function() {
                updateVariantOptionPrice(this);
                getVariantPrice();
                if (typeof checkEnableDisableButtons === 'function') {
                    checkEnableDisableButtons();
                }
                $('.selection-error, .global-addon-error').remove();
                $(this).next('.select2').find('.select2-selection').removeClass('is-invalid-addon');
                if (typeof focusNextSelect === 'function') {
                    focusNextSelect($(this));
                }
            });

            // Addon dropdown change
            $(document).on('change', '.addon-block select', function() {
                // Note: fabric dropdowns have inline onchange="updateFabricPreview"
                // so we only need to trigger getVariantPrice here
                var addonId = $(this).data('addonid');
                if (!$(this).hasClass('fabric-dropdown')) {
                    var selected = $(this).find(':selected');
                    $('#addon-price-info-' + addonId).addClass('d-none').html('');

                    renderProductOptionPreview('#addon-preview-' + addonId, {
                        image: selected.data('img') || '',
                        name: selected.data('name') || selected.text() || ''
                    });
                }
                getVariantPrice();
                if (typeof checkEnableDisableButtons === 'function') {
                    checkEnableDisableButtons();
                }
                $('.selection-error, .global-addon-error').remove();
                $(this).next('.select2').find('.select2-selection').removeClass('is-invalid-addon');
                if (!$(this).hasClass('fabric-dropdown') && typeof focusNextSelect === 'function') {
                    focusNextSelect($(this));
                }
            });

            // Fabric color box clicks
            $(document).on('click', '.fabric-color-box', function(e) {
                var addonId = $(this).data('addonid');
                selectFabricOption(addonId, this);
                if (typeof checkEnableDisableButtons === 'function') {
                    checkEnableDisableButtons();
                }
                $('.selection-error, .global-addon-error').remove();
                $(`.fabric-dropdown[data-addonid="${addonId}"]`).next('.select2').find('.select2-selection')
                    .removeClass('is-invalid-addon');
            });

            // Qty change
            $(document).on('keyup change', '#quantity', function() {
                getVariantPrice();
            });

            // Fabric Tooltips
            $(document).on('mouseenter', '.fabric-color-box', function(e) {
                var imgSrc = $(this).data('img');
                var fabricName = $(this).data('fabricname') || '';
                if (imgSrc) {
                    showFabricTooltip(e, imgSrc, fabricName);
                }
            });
            $(document).on('mouseleave', '.fabric-color-box', function(e) {
                hideFabricTooltip();
            });
            $(document).on('mouseenter', '.product-option-preview-img', function(e) {
                var imgSrc = $(this).data('preview-img');
                var previewName = $(this).data('preview-name') || '';
                if (imgSrc) {
                    showFabricTooltip(e, imgSrc, previewName);
                }
            });
            $(document).on('mouseleave', '.product-option-preview-img', function(e) {
                hideFabricTooltip();
            });
            $(window).on('scroll resize', function() {
                hideFabricTooltip();
            });

            // Touch devices tooltips
            $(document).on('touchend', '.fabric-color-box', function(e) {
                var btn = $(this);
                var addonId = btn.data('addonid');
                selectFabricOption(addonId, btn[0]);
                var imgSrc = btn.data('img');
                var fabricName = btn.data('fabricname') || '';
                if (imgSrc) {
                    showFabricTooltip(e, imgSrc, fabricName);
                    setTimeout(hideFabricTooltip, 2100);
                }
            });

            // Remove error formatting on select2 focus
            $(document).on('select2:open', '.custom-dropdown', function(e) {
                var $select = $(this);
                $select.next('.select2').find('.select2-selection').removeClass('is-invalid-addon');

                if ($select.hasClass('fabric-dropdown') && $select.val()) {
                    var addonId = $select.data('addonid');
                    var groupSelector = '#fabric-preview-group-' + addonId + '-' + slugify($select.val());
                    $('#fabric-preview-block-' + addonId).find('.fabric-preview-group').addClass('d-none');
                    $(groupSelector).removeClass('d-none');
                }
            });
        });
    </script>
@endif
