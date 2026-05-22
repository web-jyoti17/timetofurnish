@php
    $sizeAttribute = \App\Models\Attribute::whereRaw('LOWER(name) = ?', ['size'])->first();
    $attribute_values = old(
        'choice_attributes',
        isset($product) && $product->attributes != null && $product->attributes != '[]'
            ? json_decode($product->attributes, true)
            : [],
    );

    if ($sizeAttribute && !in_array($sizeAttribute->id, (array) $attribute_values)) {
        $attribute_values[] = $sizeAttribute->id;
    }

    $selected_choice_no = old('choice_no');
    if (!$selected_choice_no && isset($product) && $product->attributes != '[]' && $product->attributes != null) {
        $selected_choice_no = json_decode($product->attributes);
    }

    if ($sizeAttribute && !in_array($sizeAttribute->id, (array) $selected_choice_no)) {
        $selected_choice_no = array_merge((array) $selected_choice_no, [$sizeAttribute->id]);
    }
@endphp

<div class="card productvariation shadow-sm mb-4 mt-4">
    <div class="card-header bg-light border-bottom-0 pb-2 ">
        <h5 class="mb-0 h6 text-black">{{ translate('Product Variation') }}</h5>
    </div>

    <div class="card-body pb-3">
        <div class="row gutters-16">
            <div class="col-lg-8">
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
                </div>

                <div class="form-group row align-items-center mb-4">
                    <label class="col-md-3 col-form-label font-weight-bold">
                        {{ translate('Attributes') }}
                    </label>
                    <div class="col-md-8">
                        <select name="choice_attributes[]" id="choice_attributes"
                            class="form-control aiz-selectpicker rounded-pill" data-live-search="true"
                            data-selected-text-format="count" multiple
                            data-placeholder="{{ translate('Choose Attributes') }}"
                            data-size-attribute-id="{{ $sizeAttribute->id ?? '' }}"
                            data-size-attribute-name="{{ $sizeAttribute ? $sizeAttribute->getTranslation('name') : '' }}"
                            {{ count($attribute_values) > 0 ? '' : 'disabled' }} data-container="body">
                            @foreach (\App\Models\Attribute::whereIn('id', (array) $attribute_values)->get() as $key => $attribute)
                                <option value="{{ $attribute->id }}" selected>
                                    {{ $attribute->getTranslation('name') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 text-center">
                        <div class="custom-control custom-switch">
                            <input id="attributes_enable_toggle" type="checkbox" class="custom-control-input"
                                value="1" {{ count($attribute_values) > 0 ? 'checked' : '' }}>
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
                                            <input type="text" class="form-control-plaintext font-weight-bold"
                                                name="choice[]" value="{{ $opt_att->getTranslation('name') }}"
                                                placeholder="{{ translate('Choice Title') }}" readonly>
                                        @endif
                                    </div>
                                    <div class="col-lg-8">
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
                                            $is_size_attribute =
                                                $sizeAttribute && (int) $choice_no === (int) $sizeAttribute->id;
                                            if ($is_size_attribute && empty($old_options)) {
                                                $first_size_value = \App\Models\AttributeValue::where(
                                                    'attribute_id',
                                                    $choice_no,
                                                )->first();
                                                if ($first_size_value) {
                                                    $old_options = [$first_size_value->value];
                                                }
                                            }
                                        @endphp
                                        <select class="form-control aiz-selectpicker attribute_choice rounded-pill"
                                            data-live-search="true" name="choice_options_{{ $choice_no }}[]"
                                            multiple data-container="body"
                                            {{ !$is_size_attribute && !old('attribute_choice_active_' . $choice_no, 1) ? 'disabled' : '' }}>
                                            @foreach (\App\Models\AttributeValue::where('attribute_id', $choice_no)->get() as $row)
                                                <option value="{{ $row->value }}"
                                                    @if (in_array($row->value, $old_options)) selected @endif>
                                                    {{ $row->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-1 text-center">
                                        @if ($is_size_attribute)
                                            <input type="hidden" name="attribute_choice_active_{{ $choice_no }}"
                                                value="1">
                                        @endif
                                        <div class="custom-control custom-switch">
                                            <input value="1" type="checkbox"
                                                class="custom-control-input attribute_choice_toggle"
                                                id="attribute_choice_active_{{ $choice_no }}"
                                                name="attribute_choice_active_{{ $choice_no }}"
                                                {{ $is_size_attribute || old('attribute_choice_active_' . $choice_no, 1) ? 'checked' : '' }}
                                                {{ $is_size_attribute ? 'disabled' : '' }}>
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

            <div class="col-lg-4">
                <div class="seller-attribute-builder">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="mb-1">{{ translate('Add New Attributes') }}</h6>
                            <small
                                class="text-muted">{{ translate('Saved attributes appear in the variation options immediately.') }}</small>
                        </div>
                        <button type="button" class="btn btn-soft-primary btn-sm seller-attribute-icon-btn"
                            id="add-seller-attribute-group" title="{{ translate('Add Attribute') }}">
                            <i class="las la-plus"></i>
                        </button>
                    </div>

                    <div id="seller-attribute-groups"></div>
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
                if (typeof $(colorSelect).selectpicker === 'function') {
                    $(colorSelect).selectpicker('refresh');
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
                    if (typeof $(selectElem).selectpicker === 'function') {
                        $(selectElem).selectpicker('refresh');
                    }
                }
                toggleElem.addEventListener('change', updateAttrDropdown);
                updateAttrDropdown();
            }
        });
    });
</script>

<style>
    .seller-variation-options {
        max-height: 350px;
        overflow-y: auto;
        overflow-x: hidden;
        border: 1px solid #f1f3f4;
        border-radius: 8px;
        background: #fcfcfc;
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

    .seller-attribute-icon-btn {
        width: 38px;
        height: 38px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
    }
</style>
