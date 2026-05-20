<div class="d-flex align-items-center mb-2" style="padding-left: {{ $level * 24 }}px;">
    <input
        type="checkbox"
        id="cat_{{ $category->id }}"
        name="categories[]"
        value="{{ $category->id }}"
        class="mr-2"
        {{ in_array($category->id, $selectedCats) ? 'checked' : '' }}
    />
    <label for="cat_{{ $category->id }}" class="mb-0">
        {{ $category->getTranslation('name') }}
    </label>
</div>

@foreach($category->childrenCategories as $childCategory)
    @include('backend.productServices.partials.category-checkbox', [
        'category' => $childCategory,
        'selectedCats' => $selectedCats,
        'level' => $level + 1
    ])
@endforeach
