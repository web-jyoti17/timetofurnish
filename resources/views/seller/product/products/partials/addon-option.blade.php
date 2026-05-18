@php
    $name = $option['name'] ?? '';
    $price = $option['price'] ?? '';
    $img = $option['img'] ?? '';

    $isOptChecked = false;
    if (old('addons') !== null) {
        $isOptChecked = old('addons.'.$groupIndex.'.options.'.$optIndex.'.id') !== null;
    } else {
        $isOptChecked = isset($option['id']);
    }
@endphp

<div class="row align-items-center mb-3 addon-option-row">

    <div class="col-md-auto">

        <label class="aiz-switch aiz-switch-success mb-0">

            <input type="checkbox"
                class="option-toggle"
                name="addons[{{ $groupIndex }}][options][{{ $optIndex }}][id]"
                value="{{ $option['id'] ?? 'new' }}"
                {{ $isOptChecked ? 'checked' : '' }}>

            <span></span>

        </label>

    </div>

    <div class="col-md-4">

        <input type="text"
               name="addons[{{ $groupIndex }}][options][{{ $optIndex }}][name]"
               class="form-control option-input"
               value="{{ old('addons.'.$groupIndex.'.options.'.$optIndex.'.name', $name) }}"
               placeholder="Option Name">

    </div>

    <div class="col-md-2">

        <input type="number"
               name="addons[{{ $groupIndex }}][options][{{ $optIndex }}][price]"
               class="form-control option-input"
               value="{{ old('addons.'.$groupIndex.'.options.'.$optIndex.'.price', $price) }}"
               placeholder="Price">

    </div>

    <div class="col-md-4">

        <input type="file"
               name="addons[{{ $groupIndex }}][options][{{ $optIndex }}][img]"
               class="form-control option-input">

        <input type="hidden"
               name="addons[{{ $groupIndex }}][options][{{ $optIndex }}][existing_img]"
               value="{{ $img }}">

    </div>

    <div class="col-md-auto">

        <button type="button"
                class="btn btn-soft-danger remove-option">

            <i class="las la-times"></i>

        </button>

    </div>

</div>
