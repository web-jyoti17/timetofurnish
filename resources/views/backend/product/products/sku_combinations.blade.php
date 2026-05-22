@php if(!empty($old_values)) $old_values = json_decode($old_values); @endphp
@if(count($combinations) > 0)
    <table class="table table-bordered aiz-table">
        <thead>
            <tr>
                <td class="text-center"> {{translate('Variant')}} </td>
                <td class="text-center"> {{translate('Variant Price')}} </td>
                <td class="text-center" data-breakpoints="lg"> {{translate('SKU')}} </td>
                <td class="text-center" data-breakpoints="lg"> {{translate('Quantity')}} </td>
                <td class="text-center" data-breakpoints="lg"> {{translate('Photo')}} </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($combinations as $key => $combination)
                @php
                    $str = '';
                    $sku = '';
                    foreach (explode(' ', $product_name) as $key => $value) {
                        $sku .= substr($value, 0, 1);
                    }
                    foreach ($combination as $key => $item){
                        if($key > 0 ){
                            $str .= '-'.str_replace(' ', '', $item);
                            $sku .='-'.str_replace(' ', '', $item);
                        }
                        else {
                            if($colors_active == 1) {
                                $color_name = \App\Models\Color::where('code', $item)->first()->name;
                                $str .= $color_name;
                                $sku .='-'.$color_name;
                            }
                            else {
                                $str .= str_replace(' ', '', $item);
                                $sku .='-'.str_replace(' ', '', $item);
                            }
                        }
                    }
                @endphp
                @if(strlen($str) > 0)
                   <tr class="variant">
                        <td>
                            <label for="" class="control-label">{{ $str }} </label>
                        </td>
                       <td>
                            <input type="number" lang="en" name="price_{{ $str }}" value="{{ $old_values->{'price_'.$str} ?? '' }}" min="0.01" step="0.01" class="form-control var_price" oninput="
                        // allow only numbers and dot
                        this.value = this.value.replace(/[^0-9.]/g, '').slice(0,5);
                        // allow only one dot
                        this.value = this.value.replace(/(\..*)\./g, '$1');
                        // block leading zero like 01, 00 (except 0.)
                        if (this.value.length > 1 && this.value.startsWith('0') && !this.value.startsWith('0.')) {
                        this.value = this.value.replace(/^0+/, '');
                        }
                        // block 0 or 0.00
                        if (parseFloat(this.value) <= 0) {
                        this.value = '';
                        }
                        "  required>
                        </td>
                        <td>
                            <input type="text" name="sku_{{ $str }}" value="{{ $old_values->{'sku_'.$str} ?? $sku }}" class="form-control">
                        </td>
                        <td>
                            <input type="number" lang="en" name="qty_{{ $str }}" value="{{ $old_values->{'qty_'.$str} ?? 1 }}" min="1" step="1"  class="form-control var_qty" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,4); if (parseInt(this.value || 0, 10) <= 0) this.value = '';" required>
                        </td>
                        <td>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount text-truncate">{{ translate('Choose File') }}</div>
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
