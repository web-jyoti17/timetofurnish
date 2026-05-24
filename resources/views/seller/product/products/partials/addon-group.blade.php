@php
    $groupName = $addon['name'] ?? '';
    $options = $addon['options'] ?? [];
    
    $isChecked = false;
    if (old('addons') !== null) {
        $isChecked = old('addons.'.$index.'.id') !== null;
    } else {
        $isChecked = isset($addon['id']);
    }
@endphp

<div class="addon-block card border mb-4 shadow-none {{ ($isGlobal ?? false) ? 'is-global-addon' : '' }}">

    <div class="card-header bg-light py-3 px-3 addon-collapse-header">
        <div class="d-flex align-items-center justify-content-between" style="width:100%;">

            <div class="d-flex align-items-center">

                <label class="aiz-switch aiz-switch-success mb-0 mr-3 p-0">

                    <input type="checkbox"
                           class="group-toggle"
                           name="addons[{{ $index }}][id]"
                           value="{{ $addon['id'] ?? 'new' }}"
                           {{ $isChecked ? 'checked' : '' }}>

                    <span></span>

                </label>

                <input type="text"
                       name="addons[{{ $index }}][name]"
                       value="{{ old('addons.'.$index.'.name', $groupName) }}"
                       class="form-control border-0 bg-transparent font-weight-bold group-name p-0"
                       placeholder="Addon Group Name">

                <i class="las la-angle-down addon-arrow ml-2"></i>

            </div>

            <div class="d-flex align-items-center">


                <button type="button"
                        class="btn btn-soft-success btn-sm mr-2 select-all-options">

                    Select All

                </button>

                <button type="button"
                        class="btn btn-soft-danger btn-icon btn-circle remove-group">

                    <i class="las la-trash"></i>

                </button>

            </div>

        </div>

    </div>

    <div class="card-body addon-body"  style="display:none;">

        <div class="seller-addon-option-head">
            <span></span>
            <span>Option name</span>
            <span>Price</span>
            <span>Quantity</span>
            <span>Image</span>
            <span></span>
        </div>

        <div class="addon-options">

            @forelse($options as $optIndex => $option)

                @include('seller.product.products.partials.addon-option', [
                    'option' => $option,
                    'groupIndex' => $index,
                    'optIndex' => $optIndex,
                ])

            @empty

                @include('seller.product.products.partials.addon-option', [
                    'option' => [],
                    'groupIndex' => $index,
                    'optIndex' => 0,
                ])

            @endforelse

        </div>

        <div class="text-center mt-3">

            <button type="button"
                    class="btn btn-soft-primary btn-sm add-option-btn">

                <i class="las la-plus"></i>
                Add Option

            </button>

        </div>

    </div>

</div>
