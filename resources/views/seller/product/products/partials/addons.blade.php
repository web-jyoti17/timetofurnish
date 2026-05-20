<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Product Add-ons</h5>

        <button type="button"
                class="btn btn-soft-primary btn-sm"
                id="add-addon-group">
            <i class="las la-plus"></i>
            Add New Addon
        </button>
    </div>

    <div class="card-body">

        <div id="addon-wrapper">

            @php
                $addons = !empty($addons) ? $addons : ($oldAddonsJson ?? []);
            @endphp

            @forelse($addons as $index => $addon)

                @include('seller.product.products.partials.addon-group', [
                    'addon' => $addon,
                    'index' => $index
                ])

            @empty

                @include('seller.product.products.partials.addon-group', [
                    'addon' => null,
                    'index' => 0
                ])

            @endforelse

        </div>

    </div>

</div>

<template id="addon-group-template">
    @include('seller.product.products.partials.addon-group', [
        'addon' => null,
        'index' => '__GROUP_INDEX__'
    ])
</template>

<template id="addon-option-template">
    @include('seller.product.products.partials.addon-option', [
        'option' => [],
        'groupIndex' => '__GROUP_INDEX__',
        'optIndex' => '__OPTION_INDEX__',
    ])
</template>
