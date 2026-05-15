<div class="card productvariation shadow-sm mb-4">
    <div class="card-header bg-light border-bottom-0 pb-2">
        <h5 class="mb-0 h6 text-black">{{ translate('Product Variation') }}</h5>
    </div>
    <div class="card-body pb-3">
        {{-- Colors --}}
        <div class="form-group row align-items-center mb-4">
            <label class="col-md-3 col-form-label  font-weight-bold">
                {{ translate('Colors') }}
            </label>
            <div class="col-md-8">
                <select class="form-control aiz-selectpicker rounded-pill"
                        data-live-search="true"
                        name="colors[]"
                        data-selected-text-format="count"
                        id="colors"
                        multiple
                        {{ (old('colors_active', $product->colors_active ?? '') ? '' : 'disabled') }}>
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

        {{-- Attributes Notice --}}
        <div class="alert alert-info mb-2">
            <i class="las la-info-circle mr-2"></i>
            {{ translate('Attributes will load based on selected categories') }}
        </div>

        {{-- Attributes --}}
        <div class="form-group row align-items-center mb-4">
            <label class="col-md-3 col-form-label  font-weight-bold">
                {{ translate('Attributes') }}
            </label>
            <div class="col-md-8">
                @php
                    $attribute_values = old(
                        'choice_attributes',
                        isset($product) && $product->attributes != null && $product->attributes != '[]'
                            ? json_decode($product->attributes, true)
                            : [],
                    );
                @endphp
                <select name="choice_attributes[]"
                        id="choice_attributes"
                        class="form-control aiz-selectpicker rounded-pill"
                        data-live-search="true"
                        data-selected-text-format="count"
                        multiple
                        data-placeholder="{{ translate('Choose Attributes') }}"
                        {{ (count($attribute_values) > 0 ? '' : 'disabled') }}
                        data-container="body">
                    @foreach (\App\Models\Attribute::whereIn('id', (array) $attribute_values)->get() as $key => $attribute)
                        <option value="{{ $attribute->id }}" selected>
                            {{ $attribute->getTranslation('name') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 text-center">
                <div class="custom-control custom-switch">
                    {{-- We want to enable/disable the attribute select based on this toggle --}}
                    <input id="attributes_enable_toggle" type="checkbox" class="custom-control-input"
                           value="1" {{ count($attribute_values) > 0 ? 'checked' : '' }}>
                    <label class="custom-control-label" for="attributes_enable_toggle"></label>
                </div>
            </div>
        </div>

        {{-- Attribute instructions --}}
        <div class="alert alert-info mb-2">
            <i class="las la-info-circle mr-2"></i>
            {{ translate('Choose the attributes of this product and then input values of each attribute') }}
        </div>

        {{-- Choices --}}
        <div id="attributes-container" class="c-scrollbar-light"
             style="max-height: 350px; overflow-y: auto; overflow-x: hidden; border:1px solid #f1f3f4; border-radius: 0.5rem; background: #fcfcfc;">
            <div class="customer_choice_options p-2" id="customer_choice_options">
                @php
                    $selected_choice_no = old('choice_no');
                    if (
                        !$selected_choice_no &&
                        isset($product) &&
                        $product->attributes != '[]' &&
                        $product->attributes != null
                    ) {
                        $selected_choice_no = json_decode($product->attributes);
                    }
                @endphp
                {{-- WHEN NO ATTRIBUTES SELECTED: Show a prompt --}}
                @if (empty($selected_choice_no) || count($selected_choice_no) == 0)
                    <div class="text-center mt-2 mb-2 text-muted" id="variant-table-prompt">
                        <i class="las la-info-circle"></i>
                        {{ translate("Select attributes above to add variant options for the product.") }}
                    </div>
                @else
                    @foreach ($selected_choice_no as $key => $choice_no)
                        <div class="form-group row align-items-center mb-3">
                            <div class="col-lg-3">
                                <input type="hidden" name="choice_no[]" value="{{ $choice_no }}">
                                @php $opt_att = \App\Models\Attribute::find($choice_no) @endphp
                                @if (!empty($opt_att))
                                    <input type="text" class="form-control-plaintext font-weight-bold" name="choice[]"
                                           value="{{ $opt_att->getTranslation('name') }}"
                                           placeholder="{{ translate('Choice Title') }}" readonly>
                                @endif
                            </div>
                            <div class="col-lg-8">
                                @php
                                    $old_options = old('choice_options_' . $choice_no);
                                    $choice_toggle = old('attribute_choice_active_' . $choice_no, 1); // fallback enabled
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
                                        data-live-search="true"
                                        name="choice_options_{{ $choice_no }}[]"
                                        multiple data-container="body"
                                        {{ (old('attribute_choice_active_' . $choice_no, 1) ? '' : 'disabled') }}>
                                    @foreach (\App\Models\AttributeValue::where('attribute_id', $choice_no)->get() as $row)
                                        <option value="{{ $row->value }}"
                                            @if (in_array($row->value, $old_options)) selected @endif>
                                            {{ $row->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-1 text-center">
                                <div class="custom-control custom-switch">
                                    <input value="1" type="checkbox" class="custom-control-input attribute_choice_toggle"
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
        <div class="sku_combination mt-3" id="sku_combination">
        </div>
    </div>
</div>
<script>
    // Enable/disable the Colors select depending on the colors_active toggle
    document.addEventListener('DOMContentLoaded', function () {
        var colorToggle = document.getElementById('colors_active');
        var colorSelect = document.getElementById('colors');
        if (colorToggle && colorSelect) {
            function updateColorsDropdown() {
                colorSelect.disabled = !colorToggle.checked;
                // force refresh if selectpicker (if using BS select)
                if (typeof $(colorSelect).selectpicker === 'function') {
                    $(colorSelect).selectpicker('refresh');
                }
            }
            colorToggle.addEventListener('change', updateColorsDropdown);
            updateColorsDropdown();
        }

        // For each attribute choice, enable/disable accordingly
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
        // If no attributes selected, show the "Select attributes above..." prompt, hide variant table if needed.
        function showVariantPromptIfNeeded() {
            var choiceOptionsContainers = document.querySelector('#customer_choice_options');
            var children = choiceOptionsContainers ? choiceOptionsContainers.children : [];
            var hasAttributeRows = false;
            for (var i=0; i < children.length; i++) {
                if (children[i].classList && children[i].classList.contains('form-group')) {
                    hasAttributeRows = true;
                    break;
                }
            }
            var prompt = document.querySelector('#variant-table-prompt');
            if (prompt) {
                prompt.style.display = hasAttributeRows ? 'none' : '';
            }
        }
        // Call on page load
        showVariantPromptIfNeeded();
    });
</script>
