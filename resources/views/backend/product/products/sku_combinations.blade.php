@php
    if(!empty($old_values)) $old_values = json_decode($old_values);
    $colors_active = $colors_active ?? 0;
@endphp
@if(count($combinations) > 0)
    <table class="table table-bordered aiz-table">
        <thead>
            <tr>
                <td class="text-center font-weight-bold" style="background: rgba(197, 146, 89, 0.05); color: #c59259; border: 1px solid #eae9e9;"> {{translate('Variant Option')}} </td>
                <td class="text-center font-weight-bold" style="background: rgba(197, 146, 89, 0.05); color: #c59259; border: 1px solid #eae9e9;"> {{translate('Variant Price')}} <span class="text-danger">*</span></td>
                <td class="text-center font-weight-bold" style="background: rgba(197, 146, 89, 0.05); color: #c59259; border: 1px solid #eae9e9;" data-breakpoints="lg"> {{translate('SKU')}} </td>
                <td class="text-center font-weight-bold" style="background: rgba(197, 146, 89, 0.05); color: #c59259; border: 1px solid #eae9e9;" data-breakpoints="lg"> {{translate('Quantity')}} </td>
                <td class="text-center font-weight-bold" style="background: rgba(197, 146, 89, 0.05); color: #c59259; border: 1px solid #eae9e9;" data-breakpoints="lg"> {{translate('Photo')}} </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($combinations as $key => $combination)
                @php
                    $str = '';
                    $sku = '';
                    foreach (explode(' ', $product_name) as $key2 => $value) {
                        $sku .= substr($value, 0, 1);
                    }
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
                    }
                @endphp
                @if(strlen($str) > 0)
                   <tr class="variant" data-variant-values='@json($combination)'>
                        <td class="text-center font-weight-bold align-middle" style="border: 1px solid #eae9e9;">
                            <div class="variant-option-cell">
                                @forelse ($row_values as $row_value)
                                    <span class="variant-option-item">
                                        <span class="badge badge-inline badge-md px-3 py-2 rounded-pill font-weight-bold variant-option-badge variant-option-edit"
                                            data-variant-value="{{ $row_value }}">
                                            {{ $row_value }}
                                        </span>
                                        <button type="button" class="btn btn-icon btn-soft-primary btn-sm variant-option-edit-btn" title="{{ translate('Edit this option name') }}">
                                            <i class="las la-pen"></i>
                                        </button>
                                    </span>
                                @empty
                                    <span class="variant-option-item">
                                        <span class="badge badge-inline badge-md px-3 py-2 rounded-pill font-weight-bold variant-option-badge variant-option-edit"
                                            data-variant-value="{{ $str }}">
                                            {{ $str }}
                                        </span>
                                        <button type="button" class="btn btn-icon btn-soft-primary btn-sm variant-option-edit-btn" title="{{ translate('Edit this option name') }}">
                                            <i class="las la-pen"></i>
                                        </button>
                                    </span>
                                @endforelse
                                <button type="button" class="btn btn-icon btn-soft-danger btn-sm variant-value-remove" onclick="remove_variant_value(this)" title="{{ translate('Remove this option') }}">
                                    <i class="las la-times"></i>
                                </button>
                            </div>
                        </td>
                        <td style="border: 1px solid #eae9e9;">
                            <input type="number" lang="en" name="price_{{ $str }}" value="{{ $old_values->{'price_'.$str} ?? '' }}" min="0.01" step="0.01" class="form-control var_price rounded-pill" oninput="
                            this.value = this.value.replace(/[^0-9.]/g, '').slice(0,5);
                            this.value = this.value.replace(/(\..*)\./g, '$1');
                            if (this.value.length > 1 && this.value.startsWith('0') && !this.value.startsWith('0.')) {
                            this.value = this.value.replace(/^0+/, '');
                            }
                            if (parseFloat(this.value) <= 0) {
                            this.value = '';
                            }
                            ">
                        </td>
                        <td style="border: 1px solid #eae9e9;">
                            <input type="text" name="sku_{{ $str }}" value="{{ $old_values->{'sku_'.$str} ?? $sku }}" class="form-control rounded-pill">
                        </td>
                        <td style="border: 1px solid #eae9e9;">
                            <input type="number" lang="en" name="qty_{{ $str }}" value="{{ $old_values->{'qty_'.$str} ?? 1 }}" min="1" step="1" class="form-control var_qty rounded-pill" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,4); if (parseInt(this.value || 0, 10) <= 0) this.value = '';">
                        </td>
                        <td style="border: 1px solid #eae9e9;">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium" style="border-top-left-radius: 20px; border-bottom-left-radius: 20px; border: 1px solid #e0e0e0; border-right: 0;">{{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount text-truncate" style="border-top-right-radius: 20px; border-bottom-right-radius: 20px; border: 1px solid #e0e0e0;">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="img_{{ $str }}" class="selected-files" value="{{ $old_values->{'img_'.$str} ?? '' }}">
                            </div>
                            <div class="file-preview box sm"></div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@endif
