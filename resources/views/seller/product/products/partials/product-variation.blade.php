@php
    $choices = isset($product) ? get_product_stock_choices($product) : [];
    $selectedCategories = $selectedCategories ?? (old('category_ids', isset($product) ? $product->categories->pluck('id')->toArray() : []));

    // Admin-added attributes for this category should always show up automatically on the seller panel
    $category_attributes = collect([]);
    if (!empty($selectedCategories)) {
        $parentIds = getParentCategoryIds((array) $selectedCategories);
        $category_attributes = \App\Models\Attribute::where(function ($query) use ($parentIds) {
            $query->whereHas('categories', function ($categoryQuery) use ($parentIds) {
                $categoryQuery->whereIn('category_id', $parentIds);
            })
            ->orWhereRaw('LOWER(name) = ?', ['size']);
        })
        ->when(Schema::hasColumn('attributes', 'user_id'), function ($query) {
            $query->where(function ($innerQuery) {
                $innerQuery->whereNull('user_id')
                    ->orWhere('user_id', auth()->id());
            });
        })
        ->get()
        ->unique('id');
    }

    // Selected attribute IDs from old input or loaded choices
    $attribute_values = old('choice_attributes', collect($choices)->pluck('attribute_id')->toArray());
    
    // Always include category attributes in attribute_values ONLY if NOT in edit mode
    if (!isset($product)) {
        foreach ($category_attributes as $cat_attr) {
            if (!in_array($cat_attr->id, $attribute_values)) {
                $attribute_values[] = $cat_attr->id;
            }
        }
    }

    // Selected choice nos (which attribute inputs to render)
    $selected_choice_no = old('choice_no');
    if (!$selected_choice_no) {
        $selected_choice_no = collect($choices)->pluck('attribute_id')->toArray();
    }
    // Always include category attributes in selected_choice_no ONLY if NOT in edit mode
    if (!isset($product)) {
        foreach ($category_attributes as $cat_attr) {
            if (!in_array($cat_attr->id, $selected_choice_no)) {
                $selected_choice_no[] = $cat_attr->id;
            }
        }
    }

    // Format choices into options collection for easy mapping in the blade loop below
    $productChoiceOptions = collect($choices)->map(function ($choice) {
        return (object) [
            'attribute_id' => $choice->attribute_id,
            'name' => $choice->name,
            'values' => collect($choice->values)->map(function ($val) {
                return $val['value'] ?? $val;
            })->toArray()
        ];
    });

    $selected_custom_choice_no = [];
    $selected_admin_choice_no = [];
    foreach ((array) $selected_choice_no as $choice_no) {
        $opt_att = \App\Models\Attribute::find($choice_no);
        if ($opt_att && !is_null($opt_att->user_id)) {
            $selected_custom_choice_no[] = $choice_no;
        } else {
            $selected_admin_choice_no[] = $choice_no;
        }
    }
@endphp


<div class="card productvariation shadow-sm mb-4 mt-4">
    <div class="card-header border-bottom-0 pb-2 pt-3 seller-variation-header">
        <div class="d-flex align-items-center">
            <div class="seller-variation-header-icon mr-2">
                <i class="las la-tags"></i>
            </div>
            <h5 class="mb-0 h6 text-black font-weight-bold">{{ translate('Product Variation') }}</h5>
        </div>
    </div>

    <div class="card-body pb-3">
        <div class="row gutters-16">
            <div class="col-lg-12">
                <div class="form-group row align-items-center mb-4 premium-field-row">
                    <label class="col-md-3 col-form-label font-weight-bold text-muted-dark">
                        {{ translate('Colors') }}
                    </label>
                    <div class="col-md-8">
                        <select class="form-control aiz-selectpicker rounded-pill premium-select" data-live-search="true"
                            name="colors[]" data-selected-text-format="count" id="colors" multiple
                            {{ old('colors_active', $product->colors_active ?? '') ? '' : 'disabled' }}>
                            @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                                <option value="{{ $color->code }}"
                                    data-content="<span class='mr-2 border rounded-circle d-inline-block align-middle' style='background:{{ $color->code }};width:18px;height:18px;box-shadow: 0 2px 4px rgba(0,0,0,0.1);'></span><span>{{ $color->name }}</span>"
                                    {{ in_array($color->code, old('colors', [])) ? 'selected' : '' }}>
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 text-center d-flex align-items-center justify-content-center">
                        <label class="premium-switch">
                            <input value="1" type="checkbox" id="colors_active" name="colors_active"
                                {{ old('colors_active', $product->colors_active ?? '') ? 'checked' : '' }}>
                            <span class="premium-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="alert alert-info mb-4 premium-info-alert" style="background: linear-gradient(135deg, #fdfaf6 0%, #f7f1e5 100%); border-left: 5px solid #c59259; border-radius: 12px; box-shadow: 0 4px 15px rgba(197, 146, 89, 0.08); border-top: 1px solid rgba(197,146,89,0.1); border-right: 1px solid rgba(197,146,89,0.1); border-bottom: 1px solid rgba(197,146,89,0.1);">
                    <div class="d-flex align-items-start align-items-md-center flex-column flex-md-row">
                        <div class="alert-icon-wrap mr-3 mb-2 mb-md-0" style="background: rgba(197, 146, 89, 0.1); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(197, 146, 89, 0.2); flex-shrink: 0;">
                            <i class="las la-info-circle" style="color: #c59259; font-size: 24px;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <span class="font-weight-bold d-block mb-1 text-dark" style="font-size: 15px; letter-spacing: -0.2px;">{{ translate('Set Up Product Variations') }}</span>
                            <span style="color: #615a51; font-size: 13px; line-height: 1.5; display: block;">
                                {{ translate('Choose the attributes of this product and input values. Admin attributes are available by default; you can also use your own custom attributes.') }}
                            </span>
                            <div class="mt-2" style="font-size: 12px; color: #8e8376; display: flex; align-items: center; gap: 4px;">
                                <i class="las la-cog"></i> 
                                <span>
                                    {{ translate('To view, add, or edit your own attributes and options, visit your') }} 
                                    <a href="{{ route('seller.attributes.index') }}" target="_blank" class="font-weight-bold" style="color: #b57a45; text-decoration: underline;">
                                        {{ translate('Custom Attributes Page') }} <i class="las la-external-link-alt" style="font-size: 10px;"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row align-items-center mb-4 premium-field-row">
                    <label class="col-md-3 col-form-label font-weight-bold text-muted-dark">
                        {{ translate('Attributes') }}
                    </label>
                    <div class="col-md-8 seller-variation-select-col">
                        <select name="choice_attributes[]" id="choice_attributes"
                            class="form-control aiz-selectpicker rounded-pill premium-select" data-live-search="true"
                            data-selected-text-format="count" multiple
                            data-placeholder="{{ translate('Choose Attributes') }}" data-container="body">
                            @php
                                $all_attributes = \App\Models\Attribute::when(Schema::hasColumn('attributes', 'user_id'), function ($query) {
                                    $query->where(function ($innerQuery) {
                                        $innerQuery->whereNull('user_id')
                                            ->orWhere('user_id', auth()->id());
                                    });
                                })->get();
                                
                                $seller_attributes = $all_attributes->filter(function($attr) {
                                    return !is_null($attr->user_id);
                                });
                                
                                $admin_attributes = $all_attributes->filter(function($attr) {
                                    return is_null($attr->user_id);
                                });

                                $all_attr_ids = $all_attributes->pluck('id')->map(function($id) { return (string) $id; })->toArray();
                            @endphp

                            <optgroup label="{{ translate('My Custom Attributes') }}">
                                @foreach ($seller_attributes as $attribute)
                                    @php
                                        $isSelected = in_array((string) $attribute->id, array_map('strval', (array) $attribute_values));
                                        $attributeName = $attribute->getTranslation('name');
                                    @endphp
                                    <option value="{{ $attribute->id }}" {{ $isSelected ? 'selected' : '' }} data-user-id="{{ $attribute->user_id }}">
                                        {{ $attributeName }}
                                    </option>
                                @endforeach
                                @foreach ((array) $attribute_values as $attributeId)
                                    @if (!in_array((string) $attributeId, $all_attr_ids))
                                        @php
                                            $choiceOption = $productChoiceOptions->first(function ($choiceOption) use ($attributeId) {
                                                return isset($choiceOption->attribute_id) && (string) $choiceOption->attribute_id === (string) $attributeId;
                                            });
                                            $attributeName = $choiceOption->name ?? get_single_attribute_name($attributeId);
                                        @endphp
                                        <option value="{{ $attributeId }}" selected data-user-id="{{ auth()->id() }}">
                                            {{ $attributeName }}
                                        </option>
                                    @endif
                                @endforeach
                            </optgroup>

                            <optgroup label="{{ translate('Global Admin Attributes') }}">
                                @foreach ($admin_attributes as $attribute)
                                    @php
                                        $isSelected = in_array((string) $attribute->id, array_map('strval', (array) $attribute_values));
                                        $attributeName = $attribute->getTranslation('name');
                                    @endphp
                                    <option value="{{ $attribute->id }}" {{ $isSelected ? 'selected' : '' }} data-user-id="{{ $attribute->user_id }}">
                                        {{ $attributeName }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">
                            <small class="seller-select-help text-muted">
                                {{ translate('Search attributes. If there is no match, add it from the dropdown.') }}
                            </small>
                            <a href="{{ route('seller.attributes.index') }}" target="_blank" class="btn btn-link btn-sm p-0 font-weight-bold text-primary d-inline-flex align-items-center" style="font-size: 13px; color: #b57a45 !important;">
                                <i class="las la-plus-circle mr-1" style="font-size: 16px;"></i>{{ translate('Create Custom Attributes') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-1 text-center d-flex align-items-center justify-content-center">
                        <label class="premium-switch">
                            <input id="attributes_enable_toggle" type="checkbox" value="1" checked disabled>
                            <span class="premium-slider"></span>
                        </label>
                    </div>
                </div>

                <div id="attributes-container" class="c-scrollbar-light seller-variation-options">
                    <div class="text-center mt-3 mb-3 text-muted py-4 shadow-sm rounded-lg" id="variant-table-prompt" style="background:#fff; border: 1px dashed rgba(197,146,89,0.3); display: none;">
                        <i class="las la-info-circle fs-24 mb-2 text-primary"></i>
                        <div class="font-weight-bold">{{ translate('No Attributes Selected') }}</div>
                        <small class="text-muted-dark">{{ translate('Select attributes above to add variant options for the product.') }}</small>
                    </div>

                    <!-- My Custom Attributes Section -->
                    <div id="custom-attributes-section" class="mb-4">
                        <div class="seller-section-subtitle mb-3 font-weight-bold" style="color: #a27038; font-size: 13.5px; border-bottom: 2px solid rgba(197,146,89,0.12); padding-bottom: 8px; letter-spacing: -0.1px;">
                            <i class="las la-user-cog mr-1" style="font-size: 18px; vertical-align: middle;"></i> {{ translate('My Custom Attributes') }}
                        </div>
                        <div class="customer_choice_options p-2" id="customer_choice_options_custom">
                            @foreach ($selected_custom_choice_no as $key => $choice_no)
                                @php
                                    $productChoiceOption = $productChoiceOptions->first(function ($choiceOption) use ($choice_no) {
                                        return isset($choiceOption->attribute_id) && (string) $choiceOption->attribute_id === (string) $choice_no;
                                    });
                                    $opt_att = \App\Models\Attribute::find($choice_no);
                                    $choiceName = old(
                                        'choice.' . array_search($choice_no, $selected_choice_no),
                                        $productChoiceOption->name ?? ($opt_att ? $opt_att->getTranslation('name') : '')
                                    );
                                @endphp
                                <div class="form-group row align-items-center mb-3 attribute-variation-row" data-user-id="{{ $opt_att ? $opt_att->user_id : '' }}">
                                    <div class="col-lg-3">
                                        <input type="hidden" name="choice_no[]" value="{{ $choice_no }}">
                                        @if (!empty($choiceName))
                                            <div class="seller-attribute-title-cell">
                                                <input type="text" class="form-control-plaintext font-weight-bold text-dark-title"
                                                    name="choice[]" value="{{ $choiceName }}"
                                                    placeholder="{{ translate('Choice Title') }}" readonly>
                                                <button type="button"
                                                    class="btn premium-btn-circle premium-btn-edit rename-attribute-btn premium-icon-btn"
                                                    data-attribute-id="{{ $choice_no }}"
                                                    data-attribute-name="{{ $choiceName }}">
                                                    <i class="las la-pen"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-lg-8 seller-variation-select-col">
                                        @php
                                            $old_options = old('choice_options_' . $choice_no);
                                            $value_orders = [];
                                            if (
                                                !$old_options &&
                                                isset($productChoiceOption) &&
                                                isset($productChoiceOption->values)
                                            ) {
                                                $old_options = collect($productChoiceOption->values)
                                                    ->map(function ($value) use (&$value_orders) {
                                                        $val = \App\Utility\ProductUtility::choice_value($value);
                                                        $order = \App\Utility\ProductUtility::choice_value_sort_order($value, 0);
                                                        $value_orders[$val] = $order;
                                                        return $val;
                                                    })
                                                    ->filter()
                                                    ->values()
                                                    ->all();
                                            }
                                            if (!$old_options) {
                                                $old_options = [];
                                            }
                                            $knownValues = \App\Models\AttributeValue::where('attribute_id', $choice_no)
                                                ->get()
                                                ->pluck('value')
                                                ->merge($old_options)
                                                ->unique()
                                                ->values();
                                        @endphp
                                        <select class="form-control aiz-selectpicker attribute_choice rounded-pill premium-select"
                                            data-live-search="true" name="choice_options_{{ $choice_no }}[]"
                                            multiple data-container="body"
                                            {{ !old('attribute_choice_active_' . $choice_no, 1) ? 'disabled' : '' }}>
                                            @foreach ($knownValues as $value)
                                                <option value="{{ $value }}"
                                                    @if (in_array($value, $old_options)) selected @endif>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="seller-select-help">
                                            {{ translate('Search options. If there is no match, add it from the dropdown.') }}
                                        </small>

                                        <!-- Selected Values & Custom Sort Order Editor -->
                                        <!-- <div class="seller-selected-values-editor mt-2 @if(empty($old_options)) d-none @endif" id="selected-values-editor-{{ $choice_no }}">
                                            <div class="seller-selected-values-title">{{ translate('Set Option Values Sort Order') }}</div>
                                            <div class="seller-selected-values-list">
                                                @foreach((array) $old_options as $index => $val)
                                                    @php
                                                        $order = $value_orders[$val] ?? $index;
                                                    @endphp
                                                    <div class="seller-selected-value-row" data-value="{{ $val }}">
                                                        <span class="premium-badge">{{ $val }}</span>
                                                        <input type="number" class="seller-selected-value-sort-input" name="value_sort_order_{{ $choice_no }}[{{ $val }}]" value="{{ $order }}" data-value="{{ $val }}" title="{{ translate('Sort Order') }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="col-lg-1 text-center d-flex align-items-center justify-content-center">
                                        <label class="premium-switch">
                                            <input value="1" type="checkbox"
                                                class="attribute_choice_toggle"
                                                id="attribute_choice_active_{{ $choice_no }}"
                                                name="attribute_choice_active_{{ $choice_no }}"
                                                {{ old('attribute_choice_active_' . $choice_no, 1) ? 'checked' : '' }}>
                                            <span class="premium-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Global Admin Attributes Section -->
                    <div id="admin-attributes-section" class="mb-2">
                        <div class="seller-section-subtitle mb-3 font-weight-bold" style="color: #4b5259; font-size: 13.5px; border-bottom: 2px solid rgba(0,0,0,0.06); padding-bottom: 8px; letter-spacing: -0.1px;">
                            <i class="las la-globe mr-1" style="font-size: 18px; vertical-align: middle;"></i> {{ translate('Global Admin Attributes') }}
                        </div>
                        <div class="customer_choice_options p-2" id="customer_choice_options_admin">
                            @foreach ($selected_admin_choice_no as $key => $choice_no)
                                @php
                                    $productChoiceOption = $productChoiceOptions->first(function ($choiceOption) use ($choice_no) {
                                        return isset($choiceOption->attribute_id) && (string) $choiceOption->attribute_id === (string) $choice_no;
                                    });
                                    $opt_att = \App\Models\Attribute::find($choice_no);
                                    $choiceName = old(
                                        'choice.' . array_search($choice_no, $selected_choice_no),
                                        $productChoiceOption->name ?? ($opt_att ? $opt_att->getTranslation('name') : '')
                                    );
                                @endphp
                                <div class="form-group row align-items-center mb-3 attribute-variation-row" data-user-id="{{ $opt_att ? $opt_att->user_id : '' }}">
                                    <div class="col-lg-3">
                                        <input type="hidden" name="choice_no[]" value="{{ $choice_no }}">
                                        @if (!empty($choiceName))
                                            <div class="seller-attribute-title-cell">
                                                <input type="text" class="form-control-plaintext font-weight-bold text-dark-title"
                                                    name="choice[]" value="{{ $choiceName }}"
                                                    placeholder="{{ translate('Choice Title') }}" readonly>
                                                <!-- NO pencil icon rename button rendered for admin global attributes -->
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-lg-8 seller-variation-select-col">
                                        @php
                                            $old_options = old('choice_options_' . $choice_no);
                                            $value_orders = [];
                                            if (
                                                !$old_options &&
                                                isset($productChoiceOption) &&
                                                isset($productChoiceOption->values)
                                            ) {
                                                $old_options = collect($productChoiceOption->values)
                                                    ->map(function ($value) use (&$value_orders) {
                                                        $val = \App\Utility\ProductUtility::choice_value($value);
                                                        $order = \App\Utility\ProductUtility::choice_value_sort_order($value, 0);
                                                        $value_orders[$val] = $order;
                                                        return $val;
                                                    })
                                                    ->filter()
                                                    ->values()
                                                    ->all();
                                            }
                                            if (!$old_options) {
                                                $old_options = [];
                                            }
                                            $knownValues = \App\Models\AttributeValue::where('attribute_id', $choice_no)
                                                ->get()
                                                ->pluck('value')
                                                ->merge($old_options)
                                                ->unique()
                                                ->values();
                                        @endphp
                                        <select class="form-control aiz-selectpicker attribute_choice rounded-pill premium-select"
                                            data-live-search="true" name="choice_options_{{ $choice_no }}[]"
                                            multiple data-container="body"
                                            {{ !old('attribute_choice_active_' . $choice_no, 1) ? 'disabled' : '' }}>
                                            @foreach ($knownValues as $value)
                                                <option value="{{ $value }}"
                                                    @if (in_array($value, $old_options)) selected @endif>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="seller-select-help">
                                            {{ translate('Search options. If there is no match, add it from the dropdown.') }}
                                        </small>

                                        <!-- Selected Values & Custom Sort Order Editor -->
                                        <!-- <div class="seller-selected-values-editor mt-2 @if(empty($old_options)) d-none @endif" id="selected-values-editor-{{ $choice_no }}">
                                            <div class="seller-selected-values-title">{{ translate('Set Option Values Sort Order') }}</div>
                                            <div class="seller-selected-values-list">
                                                @foreach((array) $old_options as $index => $val)
                                                    @php
                                                        $order = $value_orders[$val] ?? $index;
                                                    @endphp
                                                    <div class="seller-selected-value-row" data-value="{{ $val }}">
                                                        <span class="premium-badge">{{ $val }}</span>
                                                        <input type="number" class="seller-selected-value-sort-input" name="value_sort_order_{{ $choice_no }}[{{ $val }}]" value="{{ $order }}" data-value="{{ $val }}" title="{{ translate('Sort Order') }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="col-lg-1 text-center d-flex align-items-center justify-content-center">
                                        <label class="premium-switch">
                                            <input value="1" type="checkbox"
                                                class="attribute_choice_toggle"
                                                id="attribute_choice_active_{{ $choice_no }}"
                                                name="attribute_choice_active_{{ $choice_no }}"
                                                {{ old('attribute_choice_active_' . $choice_no, 1) ? 'checked' : '' }}>
                                            <span class="premium-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                    </div>
                </div>

                <div class="sku_combination mt-3" id="sku_combination">
                    @php
                        $initialCombinations = [];
                        $initialOptions = [];
                        $initialColorsActive = old('colors_active', $product->colors_active ?? 0) ? 1 : 0;
                        $initialColors = old('colors', isset($product) ? json_decode($product->colors ?? '[]', true) : []);

                        if ($initialColorsActive && !empty($initialColors)) {
                            $initialOptions[] = $initialColors;
                        }

                        foreach ((array) $selected_choice_no as $choiceNo) {
                            $field = 'choice_options_' . $choiceNo;
                            $values = old($field);

                            if (!$values) {
                                $choiceOption = $productChoiceOptions->first(function ($item) use ($choiceNo) {
                                    return isset($item->attribute_id) && (string) $item->attribute_id === (string) $choiceNo;
                                });
                                $values = isset($choiceOption->values)
                                    ? collect($choiceOption->values)->map(function ($value) {
                                        return \App\Utility\ProductUtility::choice_value($value);
                                    })->filter()->values()->all()
                                    : [];
                            }

                            if (!empty($values)) {
                                $initialOptions[] = $values;
                            }
                        }

                        foreach ($initialOptions as $optionGroup) {
                            foreach ((array) $optionGroup as $optionValue) {
                                $initialCombinations[] = [$optionValue];
                            }
                        }
                    @endphp
                    @if (!empty($initialCombinations) && isset($product) && $product->id)
                        @include('backend.product.products.sku_combinations_edit', [
                            'combinations' => $initialCombinations,
                            'unit_price' => old('unit_price', $product->unit_price ?? 0),
                            'colors_active' => $initialColorsActive,
                            'product_name' => old('name', $product->name ?? ''),
                            'product' => $product,
                        ])
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var colorToggle = document.getElementById('colors_active');
        var colorSelect = document.getElementById('colors');
        if (colorToggle && colorSelect) {
            function updateColorsDropdown() {
                colorSelect.disabled = !colorToggle.checked;
                if (window.jQuery && window.jQuery.fn && window.jQuery.fn.selectpicker) {
                    window.jQuery(colorSelect).selectpicker('refresh');
                }
            }
            colorToggle.addEventListener('change', updateColorsDropdown);
            updateColorsDropdown();
        }

        document.querySelectorAll('.attribute_choice_toggle').forEach(function(toggleElem) {
            var attrId = toggleElem.id.replace('attribute_choice_active_', '');
            var selectElem = document.querySelector('select[name="choice_options_' + attrId + '[]"]');
            if (selectElem) {
                function updateAttrDropdown() {
                    selectElem.disabled = !toggleElem.checked;
                    if (window.jQuery && window.jQuery.fn && window.jQuery.fn.selectpicker) {
                        window.jQuery(selectElem).selectpicker('refresh');
                    }
                }
                toggleElem.addEventListener('change', updateAttrDropdown);
                updateAttrDropdown();
            }
        });

        // Event delegation for select choices change
        $(document).on('change', '.attribute_choice', handle_choice_change);

        // Event delegation for sort order input change
        $(document).on('input change', '.seller-selected-value-sort-input', function() {
            var row = $(this).closest('.seller-selected-values-editor');
            var attrId = row.attr('id').replace('selected-values-editor-', '');
            var selectElem = $('select[name="choice_options_' + attrId + '[]"]')[0];
            if (selectElem) {
                sort_select_options_by_custom_order(selectElem);
            }
        });
    });

    function handle_choice_change() {
        update_selected_values_order_ui(this);
    }

    function update_selected_values_order_ui(selectElem) {
        var select = $(selectElem);
        var attrIdMatch = select.attr('name').match(/(-?\d+)/);
        if (!attrIdMatch) return;
        var attrId = attrIdMatch[0];
        var container = $('#selected-values-editor-' + attrId);
        var list = container.find('.seller-selected-values-list');
        
        var selected = select.val() || [];
        
        if (selected.length === 0) {
            container.addClass('d-none');
            list.empty();
            return;
        }
        
        container.removeClass('d-none');
        
        // Keep a map of existing orders entered by user
        var existingOrders = {};
        list.find('.seller-selected-value-sort-input').each(function() {
            var val = $(this).attr('data-value');
            var order = parseInt($(this).val());
            if (!isNaN(order)) {
                existingOrders[val] = order;
            }
        });
        
        list.empty();
        
        selected.forEach(function(val, index) {
            var order = existingOrders[val] !== undefined ? existingOrders[val] : index;
            var pill = $('<div class="seller-selected-value-row" data-value="' + val + '">\
                <span class="premium-badge">' + val + '</span>\
                <input type="number" class="seller-selected-value-sort-input" name="value_sort_order_' + attrId + '[' + val + ']" value="' + order + '" data-value="' + val + '" title="Sort Order">\
            </div>');
            list.append(pill);
        });
        
        sort_select_options_by_custom_order(selectElem);
    }

    function sort_select_options_by_custom_order(selectElem) {
        var select = $(selectElem);
        var attrIdMatch = select.attr('name').match(/(-?\d+)/);
        if (!attrIdMatch) return;
        var attrId = attrIdMatch[0];
        var container = $('#selected-values-editor-' + attrId);
        
        var orders = {};
        container.find('.seller-selected-value-sort-input').each(function() {
            var val = $(this).attr('data-value');
            var order = parseInt($(this).val());
            orders[val] = isNaN(order) ? 99999 : order;
        });
        
        var options = select.find('option').get();
        options.sort(function(a, b) {
            var valA = $(a).val();
            var valB = $(b).val();
            var orderA = orders[valA] !== undefined ? orders[valA] : 99999;
            var orderB = orders[valB] !== undefined ? orders[valB] : 99999;
            
            if (orderA !== orderB) {
                return orderA - orderB;
            }
            return valA.localeCompare(valB);
        });
        
        $.each(options, function(i, opt) {
            select.append(opt);
        });
        
        if (window.jQuery && window.jQuery.fn && window.jQuery.fn.selectpicker) {
            select.off('change', handle_choice_change);
            select.selectpicker('refresh');
            select.on('change', handle_choice_change);
        }
        
        if (typeof update_sku === 'function') {
            update_sku();
        }
    }
</script>

<style>
    /* Product Variation Premium Stylesheet */
    
    #choice_form .productvariation {
        margin-top: 24px;
        border-radius: 12px;
        border: 1px solid rgba(197, 146, 89, 0.12);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.035) !important;
        background: #fff;
        overflow: hidden;
    }

    .seller-variation-header {
        background: linear-gradient(to right, rgba(197, 146, 89, 0.05) 0%, rgba(197, 146, 89, 0.01) 100%);
        border-bottom: 1px solid rgba(197, 146, 89, 0.08) !important;
    }

    .seller-variation-header-icon {
        color: #c59259;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #choice_form .productvariation .card-body {
        padding: 24px;
    }

    /* Info Alert Stylings */
    #choice_form .productvariation .premium-info-alert {
        margin: 16px 0 24px;
        padding: 14px 16px;
        border: 1px solid rgba(197, 146, 89, 0.2);
        border-radius: 8px;
        background: #fdfaf6;
        color: #5f4a35;
        font-size: 13px;
        box-shadow: 0 2px 6px rgba(197, 146, 89, 0.04);
    }

    #choice_form .productvariation .premium-info-alert .alert-icon-wrap {
        font-size: 22px;
        color: #c59259;
        line-height: 1;
    }

    /* Form Layout Grid & Rows */
    #choice_form .productvariation .premium-field-row {
        display: grid;
        grid-template-columns: 160px minmax(0, 1fr) 46px;
        gap: 10px 16px;
        align-items: center;
        margin: 0 0 16px;
        padding: 0 0 16px;
        border-bottom: 1px solid #edf0f2;
    }

    #choice_form .productvariation .premium-field-row>[class*="col-"] {
        width: 100%;
        max-width: none;
        padding: 0;
        flex: none;
    }

    #choice_form .productvariation .text-muted-dark {
        color: #4b5259;
        font-size: 13px;
        font-weight: 700;
    }

    /* Rounded Premium Select Inputs */
    #choice_form .productvariation .bootstrap-select .dropdown-toggle {
        border-radius: 8px !important;
        border-color: #d9dee3 !important;
        padding: 10px 16px !important;
        box-shadow: none !important;
        background: #fff !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #choice_form .productvariation .bootstrap-select.show .dropdown-toggle,
    #choice_form .productvariation .bootstrap-select .dropdown-toggle:focus {
        border-color: #c59259 !important;
        box-shadow: 0 0 0 3px rgba(197, 146, 89, 0.15) !important;
    }

    /* Premium Standalone Switch (iOS style, Bronze theme) */
    .premium-switch {
        position: relative;
        display: inline-flex;
        width: 46px;
        height: 24px;
        margin: 0;
        cursor: pointer;
        vertical-align: middle;
    }
    .premium-switch input {
        opacity: 0;
        width: 0;
        height: 0;
        position: absolute;
    }
    .premium-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #d9dee3;
        transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 24px;
    }
    .premium-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    .premium-switch input:checked + .premium-slider {
        background-color: #c59259;
    }
    .premium-switch input:checked + .premium-slider:before {
        transform: translateX(22px);
    }
    .premium-switch input:disabled + .premium-slider {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Attributes Selection Container Card */
    .seller-variation-options {
        max-height: none;
        overflow: visible;
        border-radius: 10px;
        background: #fafbfc;
        padding: 14px;
        border: 1px solid rgba(0, 0, 0, 0.035);
        box-shadow: inset 0 2px 8px rgba(0,0,0,0.02);
    }

    /* Visibility controls via :has() */
    #custom-attributes-section {
        display: none !important;
    }
    #custom-attributes-section:has(.attribute-variation-row) {
        display: block !important;
    }

    #admin-attributes-section {
        display: none !important;
    }
    #admin-attributes-section:has(.attribute-variation-row) {
        display: block !important;
    }

    #attributes-container:not(:has(.attribute-variation-row)) #variant-table-prompt {
        display: block !important;
    }

    #choice_form .seller-variation-options .customer_choice_options {
        display: grid;
        gap: 12px;
        padding: 0 !important;
    }

    /* Individual Attribute variation Blocks */
    #choice_form .seller-variation-options .customer_choice_options>.attribute-variation-row {
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr) 48px;
        gap: 10px 16px;
        align-items: center;
        margin: 0 !important;
        padding: 16px;
        border: 1px solid rgba(197, 146, 89, 0.09);
        border-radius: 10px;
        border-left: 4px solid #c59259;
        background: #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
    }

    #choice_form .seller-variation-options .customer_choice_options>.attribute-variation-row:hover {
        border-color: rgba(197, 146, 89, 0.3);
        box-shadow: 0 6px 20px rgba(197, 146, 89, 0.05);
    }

    #choice_form .seller-variation-options .customer_choice_options>.attribute-variation-row>[class*="col-"] {
        width: 100%;
        max-width: none;
        padding: 0;
        flex: none;
    }

    #choice_form .seller-variation-options .form-control-plaintext {
        color: #202223;
        font-size: 13.5px;
        font-weight: 700;
        padding: 0;
        min-height: 40px;
        display: flex;
        align-items: center;
        letter-spacing: -0.2px;
    }

    /* Option values header info */
    #choice_form .seller-variation-options .customer_choice_options>.attribute-variation-row .col-lg-8::before {
        content: "Option Values";
        display: block;
        margin-bottom: 6px;
        color: #8c959f;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    #choice_form .productvariation .seller-variation-select-col {
        min-width: 0;
    }

    #choice_form .productvariation .seller-select-help,
    #choice_form .seller-variation-options .seller-select-help {
        display: block;
        margin-top: 6px;
        color: #55616e !important;
        font-size: 12.5px;
        line-height: 1.4;
        font-weight: 500;
    }

    .seller-attribute-title-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
        padding-top: 0;
    }

    .seller-attribute-title-cell input {
        min-width: 0;
    }

    .premium-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 50% !important;
        background: #fdf8f4 !important;
        border: 1px solid rgba(197, 146, 89, 0.15) !important;
        color: #c59259 !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
    }

    .premium-icon-btn:hover {
        background: #c59259 !important;
        color: #fff !important;
        border-color: #c59259 !important;
        box-shadow: 0 4px 10px rgba(197, 146, 89, 0.25);
    }

    /* Selected Values Editor panel - Single Row Design */
    .seller-selected-values-editor {
        margin-top: 12px;
        padding-top: 8px;
        border-top: 1px dashed #edf0f2;
        display: flex !important;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px 12px;
    }

    .seller-selected-values-title {
        color: #c59259;
        font-size: 10.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0 !important;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .seller-selected-values-list {
        display: flex !important;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        flex: 1;
        min-width: 0;
        width: auto !important;
    }

    .seller-selected-value-row {
        background: linear-gradient(135deg, #ffffff 0%, #fdfbf7 100%);
        border-radius: 50rem;
        box-shadow: 0 2px 8px rgba(197, 146, 89, 0.05);
        border: 1px solid rgba(197, 146, 89, 0.22);
        display: inline-flex;
        align-items: center;
        overflow: hidden;
        height: 30px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .seller-selected-value-row:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(197, 146, 89, 0.12);
        border-color: rgba(197, 146, 89, 0.4);
    }

    .seller-selected-value-row .premium-badge {
        background: transparent !important;
        color: #a27038 !important;
        border: none !important;
        font-size: 11.5px !important;
        font-weight: 750 !important;
        padding: 0 10px 0 14px !important;
        margin: 0 !important;
        height: 100%;
        display: flex;
        align-items: center;
        letter-spacing: -0.1px;
    }

    .seller-selected-value-sort-input {
        width: 42px !important;
        height: 100% !important;
        border-radius: 0 !important;
        font-weight: 800 !important;
        font-size: 12px !important;
        text-align: center;
        border: none !important;
        border-left: 1px solid rgba(197, 146, 89, 0.22) !important;
        background: #faf6f0 !important;
        padding: 0 !important;
        color: #c59259 !important;
        outline: none !important;
        transition: all 0.25s ease;
    }

    .seller-selected-value-sort-input:focus {
        background: #ffffff !important;
        color: #a27038 !important;
        box-shadow: inset 0 0 0 1px rgba(197, 146, 89, 0.3) !important;
    }


    /* Premium Selectpicker Dropdown styling */
    #choice_form .productvariation .bootstrap-select .dropdown-toggle {
        min-height: 42px;
        padding: 8px 20px !important;
        border: 1px solid var(--seller-border-strong) !important;
        border-radius: 50rem !important; /* Rounded pill look */
        background: #fff !important;
        color: var(--seller-text) !important;
        font-weight: 600 !important;
        font-size: 13.5px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        transition: all 0.2s ease !important;
    }

    #choice_form .productvariation .bootstrap-select.show .dropdown-toggle,
    #choice_form .productvariation .bootstrap-select .dropdown-toggle:focus {
        border-color: #c59259 !important;
        box-shadow: 0 0 0 3px rgba(197, 146, 89, 0.15) !important;
    }

    #choice_form .productvariation .bootstrap-select .dropdown-toggle .filter-option {
        display: flex !important;
        align-items: center !important;
        color: var(--seller-text) !important;
    }

    /* Bootstrap selectpicker dropdown menu menu customisation */
    .productvariation .bootstrap-select .dropdown-menu {
        border: 1px solid rgba(197, 146, 89, 0.15) !important;
        border-radius: 14px !important;
        box-shadow: 0 10px 30px rgba(197, 146, 89, 0.08) !important;
        padding: 8px !important;
        margin-top: 6px !important;
        background: #fff !important;
        z-index: 9999 !important;
    }

    .productvariation .bootstrap-select .dropdown-menu .bs-searchbox {
        padding: 6px 6px 10px !important;
        border-bottom: 1px solid #f6f5f2 !important;
        margin-bottom: 6px !important;
    }

    .productvariation .bootstrap-select .dropdown-menu .bs-searchbox input {
        height: 38px !important;
        border-radius: 20px !important;
        border: 1px solid #d9dee3 !important;
        padding: 6px 16px !important;
        font-size: 13px !important;
        color: #4a463e !important;
        background: #fafbfc !important;
    }

    .productvariation .bootstrap-select .dropdown-menu .bs-searchbox input:focus {
        border-color: #c59259 !important;
        background: #fff !important;
        box-shadow: 0 0 0 3px rgba(197, 146, 89, 0.12) !important;
        outline: none !important;
    }

    .productvariation .bootstrap-select .dropdown-menu ul.dropdown-menu {
        border: 0 !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .productvariation .bootstrap-select .dropdown-menu li {
        margin: 2px 0 !important;
        padding: 0 !important;
    }

    .productvariation .bootstrap-select .dropdown-menu li a {
        border-radius: 8px !important;
        padding: 10px 14px !important;
        color: #4a463e !important;
        font-size: 13.5px !important;
        font-weight: 550 !important;
        transition: all 0.15s ease !important;
        display: flex !important;
        align-items: center !important;
    }

    .productvariation .bootstrap-select .dropdown-menu li a:hover,
    .productvariation .bootstrap-select .dropdown-menu li.active a {
        background: rgba(197, 146, 89, 0.08) !important;
        color: #a27038 !important;
    }

    .productvariation .bootstrap-select .dropdown-menu li.selected a {
        background: rgba(197, 146, 89, 0.15) !important;
        color: #a27038 !important;
        font-weight: 700 !important;
    }

    .productvariation .bootstrap-select .dropdown-menu li.selected a::after {
        content: "\f00c";
        font-family: "Line Awesome Free";
        font-weight: 900;
        margin-left: auto;
        color: #c59259;
        font-size: 12px;
    }

    @media (max-width: 991px) {
        #choice_form .productvariation .premium-field-row,
        #choice_form .seller-variation-options #customer_choice_options>.attribute-variation-row {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .seller-attribute-title-cell {
            padding-top: 0;
        }
    }
</style>
