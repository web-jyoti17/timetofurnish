<div class="card seller-product-info-card">

    <div class="card-header bg-light border-bottom-0 pb-2">
        <h5 class="mb-0 h6 text-black">{{ translate('Product Information') }}</h5>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-from-label">{{ translate('Product Name') }} <span
                    class="text-danger">*</span></label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="name"
                    value="{{ old('name', $product->name ?? '') }}"
                    placeholder="{{ translate('Product Name') }}" onchange="update_sku()"
                    oninput="this.value = this.value.replace(/[^A-Za-z0-9\s\-\(\),\+\&\*]/g, '')" required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-from-label">{{ translate('Unit') }} <span
                    class="text-danger">*</span> <span class="position-relative main-category-info-icon">
                    <i class="las la-question-circle fs-18 " style="color:#685b4e;"></i>
                    <span class="p-2 border main-category-info bg-soft-info position-absolute d-none"
                        style="left:0;">{{ translate('Packed item no of boxes or Qty') }}</span>
                </span></label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="unit"
                    value="{{ old('unit', $product->unit ?? '') }}"
                    placeholder="{{ translate('Packed item no of boxes or Qty') }}"
                    oninput="
                            if (!/^(10|[1-9])$/.test(this.value)) {
                                this.value = '';
                            }
                        "
                    required>
            </div>
        </div>
        <div class="form-group row" style="display:none;">
            <label class="col-md-3 col-from-label">{{ translate('Weight') }}
                <small>({{ translate('In Kg') }})</small></label>
            <div class="col-md-8">
                <input type="number" class="form-control" name="weight"
                    value="{{ old('weight', $product->weight ?? '0.00') }}" step="0.01"
                    placeholder="0.00" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,4)">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-from-label">{{ translate('Minimum Purchase Qty') }} <span
                    class="text-danger">*</span></label>
            <div class="col-md-8">
                <input type="number" lang="en" class="form-control" name="min_qty"
                    value="{{ old('min_qty', $product->min_qty ?? 1) }}"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,5)" min="1"
                    required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-from-label">{{ translate('Tags') }}</label>
            <div class="col-md-8">
                @php
                    $tags = old('tags', $product->tags ?? []);
                    $tags_string = is_array($tags) ? implode(',', $tags) : $tags;
                @endphp
                <input type="text" class="form-control aiz-tag-input" name="tags[]"
                    value="{{ $tags_string }}"
                    placeholder="{{ translate('Type and hit enter to add a tag') }}">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-from-label">{{ translate('Dispatch Time') }}</label>
            <div class="col-md-8">
                @php
                    $deliveryTimes = [
                        'Dispatched in 24 Hrs',
                        'Next working day',
                        '3 working days',
                        '5 working days',
                        '14 working days',
                    ];
                @endphp
                <select name="dispatch_time" class="form-control">
                    <option value="">Nothing selected</option>
                    @foreach ($deliveryTimes as $option)
                        <option value="{{ $option }}"
                            {{ $option == old('dispatch_time', $product->dispatch_time ?? '') ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if (addon_is_activated('pos_system'))
            <div class="form-group row">
                <label class="col-md-3 col-from-label">{{ translate('Barcode') }}</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="barcode"
                        value="{{ old('barcode', $product->barcode ?? '') }}"
                        placeholder="{{ translate('Barcode') }}">
                </div>
            </div>
        @endif
        @if (addon_is_activated('refund_request'))
            <div class="form-group row">
                <label class="col-md-3 col-from-label">{{ translate('Refundable') }}</label>
                <div class="col-md-8">
                    <label class="mb-0 aiz-switch aiz-switch-success">
                        <input type="checkbox" name="refundable" checked value="1"
                            {{ old('refundable', $product->refundable ?? '') ? 'checked' : '' }}>
                        <span></span>
                    </label>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="card seller-product-details-card">
    <div class="card-header bg-light border-bottom-0 pb-2">
        <h5 class="mb-0 h6 text-black">
            {{ translate('Product Details') }}
        </h5>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs mb-3" id="productDetailsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description"
                    role="tab" aria-controls="description" aria-selected="true">
                    {{ translate('Product Description') }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="specification-tab" data-toggle="tab" href="#specification"
                    role="tab" aria-controls="specification" aria-selected="false">
                    {{ translate('Product Specification') }}
                </a>
            </li>
        </ul>
        <div class="tab-content" id="productDetailsTabsContent">
            <div class="tab-pane fade show active" id="description" role="tabpanel"
                aria-labelledby="description-tab">
                <div class="form-group row">
                    <div class="col-md-12">
                        <textarea class="aiz-text-editor" name="description">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="specification" role="tabpanel"
                aria-labelledby="specification-tab">
                <div class="form-group row">
                    <div class="col-md-12">
                        <textarea class="aiz-text-editor" name="specification">{{ old('specification', $product->specification ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card seller-product-images-card">
    <div class="card-header bg-light border-bottom-0 pb-2">
        <h5 class="mb-0 h6 text-black">{{ translate('Product Images') }}</h5>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Display Image') }}
                <small>(290x300)</small></label>
            <div class="col-md-8">
                <div class="input-group" data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                            {{ translate('Browse') }}
                        </div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="thumbnail_img" class="selected-files"
                        value="{{ old('thumbnail_img', $product->thumbnail_img ?? '') }}" required>
                </div>
                <div class="file-preview box sm">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label"
                for="signinSrEmail">{{ translate('Gallery Images') }}</label>
            <div class="col-md-8">
                <div class="input-group" data-toggle="aizuploader" data-type="image"
                    data-multiple="true">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                            {{ translate('Browse') }}
                        </div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="photos" class="selected-files"
                        value="{{ old('photos', $product->photos ?? '') }}" required>
                </div>
                <div class="file-preview box sm">
                </div>
            </div>
        </div>
    </div>
</div>
