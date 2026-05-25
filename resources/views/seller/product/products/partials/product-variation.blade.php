@php
    $attribute_values = old(
        'choice_attributes',
        isset($product) && $product->attributes != null && $product->attributes != '[]'
            ? json_decode($product->attributes, true)
            : [],
    );

    $selected_choice_no = old('choice_no');
    if (!$selected_choice_no && isset($product) && $product->attributes != '[]' && $product->attributes != null) {
        $selected_choice_no = json_decode($product->attributes);
    }

    $selectedCategories = $selectedCategories ?? [];
@endphp

<div class="card productvariation shadow-sm mb-4 mt-4">
    <div class="card-header bg-light border-bottom-0 pb-2 ">
        <h5 class="mb-0 h6 text-black">{{ translate('Product Variation') }}</h5>
    </div>

    <div class="card-body pb-3">
        <div class="row gutters-16">
            <div class="col-lg-12">
                <div class="form-group row align-items-center mb-4">
                    <label class="col-md-3 col-form-label font-weight-bold">
                        {{ translate('Colors') }}
                    </label>
                    <div class="col-md-8">
                        <select class="form-control aiz-selectpicker rounded-pill" data-live-search="true"
                            name="colors[]" data-selected-text-format="count" id="colors" multiple
                            {{ old('colors_active', $product->colors_active ?? '') ? '' : 'disabled' }}>
                            @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                                <option value="{{ $color->code }}"
                                    data-content="<span class='mr-2 border rounded-circle d-inline-block align-middle' style='background:{{ $color->code }};width:18px;height:18px;'></span><span>{{ $color->name }}</span>"
                                    {{ in_array($color->code, old('colors', [])) ? 'selected' : '' }}>
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 text-center">
                        <div class="custom-control custom-switch">
                            <input value="1" type="checkbox" class="custom-control-input" id="colors_active"
                                name="colors_active"
                                {{ old('colors_active', $product->colors_active ?? '') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="colors_active"></label>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mb-3">
                    <i class="las la-info-circle mr-2"></i>
                    {{ translate('Choose the attributes of this product and then input values of each attribute') }}
                    <br>
                    <small>{{ translate('Double-click an attribute name or variant option value to edit it, then press Enter or click outside to save.') }}</small>
                </div>

                <div class="form-group row align-items-center mb-4">
                    <label class="col-md-3 col-form-label font-weight-bold">
                        {{ translate('Attributes') }}
                    </label>
                    <div class="col-md-8 seller-variation-select-col">
                        <select name="choice_attributes[]" id="choice_attributes"
                            class="form-control aiz-selectpicker rounded-pill" data-live-search="true"
                            data-selected-text-format="count" multiple
                            data-placeholder="{{ translate('Choose Attributes') }}" data-container="body">
                            @foreach (\App\Models\Attribute::whereIn('id', (array) $attribute_values)->get() as $key => $attribute)
                                <option value="{{ $attribute->id }}" selected>
                                    {{ $attribute->getTranslation('name') }}
                                </option>
                            @endforeach
                        </select>
                        <small class="seller-select-help">
                            {{ translate('Search attributes. If there is no match, add it from the dropdown.') }}
                        </small>
                    </div>
                    <div class="col-md-1 text-center">
                        <div class="custom-control custom-switch">
                            <input id="attributes_enable_toggle" type="checkbox" class="custom-control-input"
                                value="1" checked disabled>
                            <label class="custom-control-label" for="attributes_enable_toggle"></label>
                        </div>
                    </div>
                </div>

                <div id="attributes-container" class="c-scrollbar-light seller-variation-options">
                    <div class="customer_choice_options p-2" id="customer_choice_options">
                        @if (empty($selected_choice_no) || count($selected_choice_no) == 0)
                            <div class="text-center mt-2 mb-2 text-muted" id="variant-table-prompt">
                                <i class="las la-info-circle"></i>
                                {{ translate('Select attributes above to add variant options for the product.') }}
                            </div>
                        @else
                            @foreach ($selected_choice_no as $key => $choice_no)
                                <div class="form-group row align-items-center mb-3">
                                    <div class="col-lg-3">
                                        <input type="hidden" name="choice_no[]" value="{{ $choice_no }}">
                                        @php $opt_att = \App\Models\Attribute::find($choice_no) @endphp
                                        @if (!empty($opt_att))
                                            <div class="seller-attribute-title-cell">
                                                <input type="text" class="form-control-plaintext font-weight-bold"
                                                    name="choice[]" value="{{ $opt_att->getTranslation('name') }}"
                                                    placeholder="{{ translate('Choice Title') }}" readonly>
                                                <button type="button"
                                                    class="btn btn-soft-primary btn-icon btn-circle btn-sm rename-attribute-btn"
                                                    data-attribute-id="{{ $choice_no }}"
                                                    data-attribute-name="{{ $opt_att->getTranslation('name') }}">
                                                    <i class="las la-pen"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-lg-8 seller-variation-select-col">
                                        @php
                                            $old_options = old('choice_options_' . $choice_no);
                                            if (
                                                !$old_options &&
                                                isset($product) &&
                                                isset($product->choice_options) &&
                                                $product->choice_options != '[]'
                                            ) {
                                                $decoded_options = json_decode($product->choice_options);
                                                if (is_array($decoded_options)) {
                                                    foreach ($decoded_options as $choice_option) {
                                                        if (
                                                            isset($choice_option->attribute_id) &&
                                                            $choice_option->attribute_id == $choice_no
                                                        ) {
                                                            $old_options = $choice_option->values;
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                            if (!$old_options) {
                                                $old_options = [];
                                            }
                                        @endphp
                                        <select class="form-control aiz-selectpicker attribute_choice rounded-pill"
                                            data-live-search="true" name="choice_options_{{ $choice_no }}[]"
                                            multiple data-container="body"
                                            {{ !old('attribute_choice_active_' . $choice_no, 1) ? 'disabled' : '' }}>
                                            @foreach (\App\Models\AttributeValue::where('attribute_id', $choice_no)->get() as $row)
                                                <option value="{{ $row->value }}"
                                                    @if (in_array($row->value, $old_options)) selected @endif>
                                                    {{ $row->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="seller-select-help">
                                            {{ translate('Search options. If there is no match, add it from the dropdown.') }}
                                        </small>
                                    </div>
                                    <div class="col-lg-1 text-center">
                                        <div class="custom-control custom-switch">
                                            <input value="1" type="checkbox"
                                                class="custom-control-input attribute_choice_toggle"
                                                id="attribute_choice_active_{{ $choice_no }}"
                                                name="attribute_choice_active_{{ $choice_no }}"
                                                {{ old('attribute_choice_active_' . $choice_no, 1) ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="attribute_choice_active_{{ $choice_no }}"></label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="sku_combination mt-3" id="sku_combination"></div>
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
    });
</script>

<style>
    #choice_form .productvariation {
        margin-top: 16px;
    }

    #choice_form .productvariation .card-body {
        padding: 16px;
    }

    #choice_form .productvariation .alert-info {
        margin: 12px 0;
        padding: 10px 12px;
        border: 1px solid rgba(197, 146, 89, 0.22);
        border-radius: 6px;
        background: #fbf7f2;
        color: #5f4a35;
        font-size: 13px;
        font-weight: 600;
    }

    #choice_form .productvariation .form-group.row.align-items-center {
        display: grid;
        grid-template-columns: 140px minmax(0, 1fr) 46px;
        gap: 10px 14px;
        align-items: center;
        margin: 0;
        padding: 12px 0;
        border-bottom: 1px solid #edf0f2;
    }

    #choice_form .productvariation .form-group.row.align-items-center>[class*="col-"] {
        width: 100%;
        max-width: none;
        padding: 0;
        flex: none;
    }

    #choice_form .productvariation .form-group.row.align-items-center>label {
        margin: 0;
        color: #202223;
        font-size: 13px;
        font-weight: 700;
    }

    #choice_form .productvariation .btn-link {
        color: #c59259 !important;
        font-size: 12px;
        font-weight: 700;
    }

    #choice_form .productvariation .bootstrap-select .dropdown-toggle {
        border-radius: 6px !important;
    }

    .seller-variation-options {
        max-height: none;
        overflow: visible;
        /* border: 1px solid #d9dee3; */
        border-radius: 8px;
        background: #f8fafb;
        padding: 10px;
    }

    #choice_form .seller-variation-options #customer_choice_options {
        display: grid;
        gap: 10px;
        padding: 0 !important;
    }

    #choice_form .seller-variation-options #customer_choice_options>.form-group.row {
        display: grid;
        grid-template-columns: 160px minmax(0, 1fr) 48px;
        gap: 10px 14px;
        align-items: start;
        margin: 0 !important;
        padding: 12px;
        border: 1px solid #d9dee3;
        border-radius: 8px;
        border-left: 3px solid #c59259;
        background: #fff;
    }

    #choice_form .seller-variation-options #customer_choice_options>.form-group.row>[class*="col-"] {
        width: 100%;
        max-width: none;
        padding: 0;
        flex: none;
    }

    #choice_form .seller-variation-options .form-control-plaintext {
        color: #202223;
        font-size: 13px;
        font-weight: 700;
        padding: 0;
        min-height: 40px;
        display: flex;
        align-items: center;
    }

    #choice_form .seller-variation-options #customer_choice_options>.form-group.row .col-lg-8::before {
        content: "Values";
        display: block;
        margin-bottom: 6px;
        color: #6d7175;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    #choice_form .productvariation .seller-variation-select-col {
        min-width: 0;
    }

    #choice_form .productvariation .seller-select-help {
        display: block;
        margin-top: 6px;
        color: #6d7175;
        font-size: 12px;
        line-height: 1.35;
    }

    #choice_form .seller-variation-options .custom-control,
    #choice_form .productvariation .custom-control {
        min-height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    #choice_form .productvariation #attributes_enable_toggle:disabled+.custom-control-label,
    #choice_form .seller-variation-options .attribute_choice_toggle:disabled+.custom-control-label {
        cursor: default;
        opacity: 1;
    }

    .seller-attribute-builder {
        border: 1px solid #edf0f2;
        border-radius: 8px;
        padding: 14px;
        background: #fcfcfd;
        height: 100%;
    }

    .seller-attribute-draft {
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        padding: 14px;
        margin-bottom: 12px;
        background: #fff;
    }

    .seller-attribute-values {
        display: grid;
        gap: 10px;
    }

    .seller-attribute-value-row {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .seller-attribute-value-row .form-control {
        flex: 1;
    }

    .seller-attribute-title-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
    }

    .seller-attribute-title-cell input {
        min-width: 0;
    }

    .seller-selected-values-editor {
        display: grid;
        gap: 8px;
        margin-top: 10px;
    }

    .seller-selected-values-title {
        color: #6d7175;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .seller-selected-value-row {
        max-width: 360px;
    }

    .seller-selected-value-input {
        height: 38px;
        border-radius: 20px;
        font-weight: 600;
    }

    .seller-attribute-icon-btn {
        width: 38px;
        height: 38px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
    }

    @media (max-width: 991px) {

        #choice_form .productvariation .form-group.row.align-items-center,
        #choice_form .seller-variation-options #customer_choice_options>.form-group.row {
            grid-template-columns: 1fr;
        }
    }
</style>
