@php
    $children = $category->childrenCategories;
    $hasChildren = $children->count() > 0;
@endphp

<div class="d-flex align-items-center mb-2 shipping-category-row" style="padding-left: {{ $level * 24 }}px;">
    @if($hasChildren)
        <button
            type="button"
            class="btn btn-link p-0 mr-2 shipping-category-toggle"
            data-category-id="{{ $category->id }}"
            aria-expanded="false"
            style="width: 22px; line-height: 1; font-size: 22px; text-decoration: none; color: #222;"
        >+</button>
    @else
        <span class="mr-2" style="width: 22px; display: inline-block;"></span>
    @endif
    <input
        type="checkbox"
        id="shipping_cat_{{ $category->id }}"
        name="categories[]"
        value="{{ $category->id }}"
        class="mr-2 shipping-category-checkbox"
        data-category-id="{{ $category->id }}"
        data-parent-id="{{ $category->parent_id }}"
        {{ in_array($category->id, $selectedCats) ? 'checked' : '' }}
    />
    <label for="shipping_cat_{{ $category->id }}" class="mb-0">
        {{ $category->getTranslation('name') }}
    </label>
</div>

@if($hasChildren)
    <div class="shipping-category-children" data-parent-id="{{ $category->id }}" style="display: none;">
        @foreach($children as $childCategory)
            @include('backend.shippingCharges.partials.category-checkbox', [
                'category' => $childCategory,
                'selectedCats' => $selectedCats,
                'level' => $level + 1
            ])
        @endforeach
    </div>
@endif
