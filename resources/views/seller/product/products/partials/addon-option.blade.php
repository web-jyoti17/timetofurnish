@php
    $name = $option['name'] ?? '';
    $price = $option['price'] ?? '';
    $quantity = $option['quantity'] ?? '';
    $img = $option['img'] ?? '';

    $isOptChecked = false;
    if (old('addons') !== null) {
        $isOptChecked = old('addons.'.$groupIndex.'.options.'.$optIndex.'.id') !== null;
    } else {
        $isOptChecked = isset($option['id']);
    }
@endphp

<div class="addon-option-row">

    <div class="addon-option-toggle-cell" data-label="Use">

        <label class="aiz-switch aiz-switch-success mb-0">

            <input type="checkbox"
                class="option-toggle"
                name="addons[{{ $groupIndex }}][options][{{ $optIndex }}][id]"
                value="{{ $option['id'] ?? 'new' }}"
                {{ $isOptChecked ? 'checked' : '' }}>

            <span></span>

        </label>

    </div>

    <div data-label="Option name">

        <input type="text"
               name="addons[{{ $groupIndex }}][options][{{ $optIndex }}][name]"
               class="form-control option-input"
               value="{{ old('addons.'.$groupIndex.'.options.'.$optIndex.'.name', $name) }}"
               placeholder="Option Name">

    </div>

    <div data-label="Price">

        <input type="number"
               name="addons[{{ $groupIndex }}][options][{{ $optIndex }}][price]"
               class="form-control option-input"
               value="{{ old('addons.'.$groupIndex.'.options.'.$optIndex.'.price', $price) }}"
               placeholder="Price">

    </div>

    <div data-label="Quantity">

        <input type="number"
               name="addons[{{ $groupIndex }}][options][{{ $optIndex }}][quantity]"
               class="form-control option-input"
               value="{{ old('addons.'.$groupIndex.'.options.'.$optIndex.'.quantity', $quantity) }}"
               min="0"
               step="1"
               placeholder="Quantity">

    </div>

    <div data-label="Image">

        <input type="file"
               name="addons[{{ $groupIndex }}][options][{{ $optIndex }}][img]"
               class="form-control option-input">

        <input type="hidden"
               name="addons[{{ $groupIndex }}][options][{{ $optIndex }}][existing_img]"
               value="{{ $img }}">

    </div>

    <div data-label="Remove">

        <button type="button"
                class="btn btn-soft-danger remove-option">

            <i class="las la-times"></i>

        </button>

    </div>

</div>
