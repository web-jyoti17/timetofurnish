@php
    $colors_active = $colors_active ?? 0;
@endphp

<style>
    .premium-var-table-container {
        background: #fff;
        border: 1px solid #f0eeea;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .premium-var-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .premium-var-table th, 
    .premium-var-table td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f6f5f2;
        border-right: 1px solid #f6f5f2;
    }
    .premium-var-table th:last-child, 
    .premium-var-table td:last-child {
        border-right: none;
    }
    .premium-var-table tr:last-child td {
        border-bottom: none;
    }
    .premium-thead th {
        background: linear-gradient(135deg, rgba(197, 146, 89, 0.08) 0%, rgba(197, 146, 89, 0.03) 100%);
        color: #a27038 !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(197, 146, 89, 0.15) !important;
        text-align: center;
    }
    .premium-var-input {
        border-radius: 50rem !important;
        border: 1px solid #e2dfd8 !important;
        padding: 0.6rem 1rem !important;
        font-size: 0.9rem !important;
        color: #4a463e !important;
        background-color: #fff !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.01) !important;
    }
    .premium-var-input:focus {
        border-color: #c59259 !important;
        box-shadow: 0 0 0 3px rgba(197, 146, 89, 0.18) !important;
        background-color: #fff !important;
        outline: none !important;
    }
    .variant-option-cell {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .premium-badge {
        background: rgba(197, 146, 89, 0.1);
        color: #a27038;
        border: 1px solid rgba(197, 146, 89, 0.15);
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 50rem;
        transition: all 0.2s ease;
    }
    .premium-badge:hover {
        background: rgba(197, 146, 89, 0.15);
        transform: translateY(-1px);
    }
    .premium-btn-circle {
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: all 0.2s ease;
    }
    .premium-btn-edit {
        background: rgba(58, 126, 245, 0.08) !important;
        color: #3a7ef5 !important;
        border: 1px solid rgba(58, 126, 245, 0.12) !important;
    }
    .premium-btn-edit:hover {
        background: #3a7ef5 !important;
        color: #fff !important;
        box-shadow: 0 3px 10px rgba(58, 126, 245, 0.25) !important;
    }
    .premium-btn-delete {
        background: rgba(235, 87, 87, 0.08) !important;
        color: #eb5757 !important;
        border: 1px solid rgba(235, 87, 87, 0.12) !important;
    }
    .premium-btn-delete:hover {
        background: #eb5757 !important;
        color: #fff !important;
        box-shadow: 0 3px 10px rgba(235, 87, 87, 0.25) !important;
    }
    .premium-uploader-wrap {
        border-radius: 50rem !important;
        border: 1px solid #e2dfd8 !important;
        overflow: hidden;
        display: flex;
        align-items: center;
        background: #fff;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .premium-uploader-wrap:hover {
        border-color: #c59259 !important;
    }
    .premium-uploader-btn {
        background: #f8f6f2 !important;
        color: #7a756b !important;
        font-weight: 600 !important;
        padding: 0.6rem 1.2rem !important;
        border-right: 1px solid #e2dfd8 !important;
        font-size: 0.85rem;
        cursor: pointer;
        user-select: none;
    }
    .premium-uploader-file {
        padding: 0.6rem 1rem !important;
        color: #8c867a !important;
        font-size: 0.85rem;
        flex: 1;
    }
</style>

@if(count($combinations) > 0)
    <div class="premium-var-table-container">
        <table class="table premium-var-table">
            <thead class="premium-thead">
                <tr>
                    <th>{{translate('Variant Option')}}</th>
                    <th>{{translate('Variant Price')}} <span class="text-danger">*</span></th>
                    <th data-breakpoints="lg">{{translate('SKU')}}</th>
                    <th data-breakpoints="lg">{{translate('Quantity')}}</th>
                    <th data-breakpoints="lg">{{translate('Photo')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($combinations as $key => $combination)
                    @php
                        $variation_available = false;
                        $sku = '';
                        foreach (explode(' ', $product_name) as $key2 => $value) {
                            $sku .= substr($value, 0, 1);
                        }

                        $str = '';
                        $row_values = [];
                        foreach ($combination as $key2 => $item){
                            if($colors_active == 1) {
                                $color = \App\Models\Color::where('code', $item)->first();
                                if ($color) {
                                    $color_name = $color->name;
                                    $str .= $color_name;
                                    $sku .='-'.$color_name;
                                    $row_values[] = $color_name;
                                }
                                else {
                                    $str .= str_replace(' ', '', $item);
                                    $sku .='-'.str_replace(' ', '', $item);
                                    $row_values[] = $item;
                                }
                            }
                            else {
                                $str .= str_replace(' ', '', $item);
                                $sku .='-'.str_replace(' ', '', $item);
                                $row_values[] = $item;
                            }
                            $stock = $product->stocks->where('variant', $str)->first();
                        }
                    @endphp
                    @if(strlen($str) > 0)
                    <tr class="variant" data-variant-values='@json($combination)'>
                        <td class="text-center align-middle">
                            <div class="variant-option-cell">
                                @forelse ($row_values as $row_value)
                                    @php
                                        $isAdminValue = false;
                                        if (\App\Models\Color::where('name', $row_value)->exists()) {
                                            $isAdminValue = true;
                                        } else {
                                            $valModel = \App\Models\AttributeValue::where('value', $row_value)->first();
                                            if ($valModel && $valModel->attribute && is_null($valModel->attribute->user_id)) {
                                                $isAdminValue = true;
                                            }
                                        }
                                    @endphp
                                    <span class="variant-option-item d-inline-flex align-items-center gap-1">
                                        <span class="premium-badge variant-option-badge variant-option-edit"
                                            data-variant-value="{{ $row_value }}">
                                            {{ $row_value }}
                                        </span>
                                        @if(!$isAdminValue)
                                            <button type="button" class="btn premium-btn-circle premium-btn-edit variant-option-edit-btn" title="{{ translate('Edit this option name') }}">
                                                <i class="las la-pen"></i>
                                            </button>
                                        @endif
                                    </span>
                                @empty
                                    <span class="variant-option-item d-inline-flex align-items-center gap-1">
                                        <span class="premium-badge variant-option-badge variant-option-edit"
                                            data-variant-value="{{ $str }}">
                                            {{ $str }}
                                        </span>
                                    </span>
                                @endforelse
                                <button type="button" class="btn premium-btn-circle premium-btn-delete variant-value-remove" onclick="remove_variant_value(this)" title="{{ translate('Remove this option') }}">
                                    <i class="las la-times"></i>
                                </button>
                            </div>
                        </td>
                        <td> 
                            <input type="number" lang="en" name="price_{{ $str }}" value="{{ old('price_'.$str, $stock != null && $stock->price > 0 ? $stock->price : ($unit_price > 0 ? $unit_price : '')) }}" min="0.01" max="99999" step="0.01" required class="form-control var_price premium-var-input" oninput="
                                if (this.value.length > 1 && this.value.startsWith('0') && !this.value.startsWith('0.')) {
                                this.value = this.value.replace(/^0+/, '');
                                }
                                if (parseFloat(this.value) <= 0) {
                                this.value = '';
                                }
                                "> 
                        </td>
                        <td> 
                            <input type="text" name="sku_{{ $str }}" value="{{ old('sku_'.$str, ($stock != null ? $stock->sku : $str)) }}" class="form-control premium-var-input"> 
                        </td>
                        <td> 
                            <input type="number" lang="en" name="qty_{{ $str }}" value="{{ old('qty_'.$str, ($stock != null && $stock->qty > 0 ? $stock->qty : 1)) }}" min="1" step="1" max="9999" class="form-control var_qty premium-var-input" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,4); if (parseInt(this.value || 0, 10) <= 0) this.value = '';"> 
                        </td>
                        <td>
                            <div class="premium-uploader-wrap" data-toggle="aizuploader" data-type="image">
                                <div class="premium-uploader-btn">{{ translate('Browse') }}</div>
                                <div class="premium-uploader-file file-amount text-truncate">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="img_{{ $str }}" class="selected-files" value="@php
                                        if($stock != null){
                                            echo $stock->image;
                                        }
                                        else{
                                            echo null;
                                        }
                                       @endphp">
                            </div>
                            <div class="file-preview box sm mt-2"></div>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@endif
